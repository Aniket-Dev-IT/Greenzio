/**
 * Frontend Error Handler for Greenzio
 * Handles JavaScript errors, AJAX failures, and user feedback
 */

class GreenzioErrorHandler {
    constructor() {
        this.errorQueue = [];
        this.maxErrors = 10;
        this.isProduction = window.location.hostname !== 'localhost';
        this.init();
    }

    init() {
        // Global error handler
        window.addEventListener('error', (event) => {
            this.handleJavaScriptError(event);
        });

        // Unhandled promise rejection handler
        window.addEventListener('unhandledrejection', (event) => {
            this.handlePromiseRejection(event);
        });

        // Setup AJAX error handlers
        this.setupAjaxErrorHandlers();

        // Setup form validation helpers
        this.setupFormValidation();
    }

    /**
     * Handle JavaScript errors
     */
    handleJavaScriptError(event) {
        const error = {
            type: 'javascript',
            message: event.message,
            filename: event.filename,
            line: event.lineno,
            column: event.colno,
            stack: event.error ? event.error.stack : null,
            timestamp: new Date().toISOString(),
            url: window.location.href,
            userAgent: navigator.userAgent
        };

        this.logError(error);

        // Show user-friendly message for critical errors
        if (this.isCriticalError(error)) {
            this.showUserError('Something went wrong. Please refresh the page and try again.');
        }

        // Prevent default browser error handling in production
        if (this.isProduction) {
            event.preventDefault();
        }
    }

    /**
     * Handle promise rejections
     */
    handlePromiseRejection(event) {
        const error = {
            type: 'promise_rejection',
            message: event.reason ? event.reason.toString() : 'Unhandled promise rejection',
            stack: event.reason && event.reason.stack ? event.reason.stack : null,
            timestamp: new Date().toISOString(),
            url: window.location.href
        };

        this.logError(error);

        // Show user feedback for AJAX-related promise rejections
        if (this.isAjaxRelatedError(error)) {
            this.showUserError('Failed to load data. Please check your connection and try again.');
        }

        event.preventDefault();
    }

    /**
     * Setup AJAX error handlers for jQuery and fetch
     */
    setupAjaxErrorHandlers() {
        // jQuery AJAX error handler
        if (typeof $ !== 'undefined') {
            $(document).ajaxError((event, jqXHR, ajaxSettings, thrownError) => {
                const error = {
                    type: 'ajax',
                    status: jqXHR.status,
                    statusText: jqXHR.statusText,
                    responseText: jqXHR.responseText,
                    url: ajaxSettings.url,
                    method: ajaxSettings.type,
                    timestamp: new Date().toISOString()
                };

                this.logError(error);
                this.handleAjaxError(error);
            });
        }

        // Override fetch to add error handling
        const originalFetch = window.fetch;
        window.fetch = async (...args) => {
            try {
                const response = await originalFetch(...args);
                
                if (!response.ok) {
                    const error = {
                        type: 'fetch',
                        status: response.status,
                        statusText: response.statusText,
                        url: args[0],
                        timestamp: new Date().toISOString()
                    };

                    this.logError(error);
                    this.handleAjaxError(error);
                }

                return response;
            } catch (networkError) {
                const error = {
                    type: 'fetch_network',
                    message: networkError.message,
                    url: args[0],
                    timestamp: new Date().toISOString()
                };

                this.logError(error);
                this.handleAjaxError(error);
                throw networkError;
            }
        };
    }

    /**
     * Handle AJAX errors with user-friendly messages
     */
    handleAjaxError(error) {
        let userMessage = 'Something went wrong. Please try again.';

        switch (error.status) {
            case 0:
                userMessage = 'Connection failed. Please check your internet connection.';
                break;
            case 400:
                userMessage = 'Invalid request. Please check your input.';
                break;
            case 401:
                userMessage = 'Please log in to continue.';
                this.redirectToLogin();
                break;
            case 403:
                userMessage = 'You don\'t have permission to perform this action.';
                break;
            case 404:
                userMessage = 'The requested resource could not be found.';
                break;
            case 422:
                userMessage = 'Please check your input and try again.';
                break;
            case 429:
                userMessage = 'Too many requests. Please wait a moment and try again.';
                break;
            case 500:
                userMessage = 'Server error. Our team has been notified.';
                break;
            case 502:
            case 503:
            case 504:
                userMessage = 'Service temporarily unavailable. Please try again later.';
                break;
        }

        this.showUserError(userMessage);
    }

    /**
     * Setup form validation helpers
     */
    setupFormValidation() {
        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (form.tagName === 'FORM' && form.hasAttribute('data-validate')) {
                if (!this.validateForm(form)) {
                    event.preventDefault();
                }
            }
        });
    }

    /**
     * Validate form before submission
     */
    validateForm(form) {
        const errors = [];
        const formData = new FormData(form);

        // Required field validation
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                errors.push(`${this.getFieldLabel(field)} is required.`);
                this.highlightField(field, 'error');
            } else {
                this.highlightField(field, 'success');
            }
        });

        // Email validation
        const emailFields = form.querySelectorAll('input[type="email"]');
        emailFields.forEach(field => {
            if (field.value && !this.isValidEmail(field.value)) {
                errors.push('Please enter a valid email address.');
                this.highlightField(field, 'error');
            }
        });

        // Phone validation
        const phoneFields = form.querySelectorAll('input[type="tel"], input[data-type="phone"]');
        phoneFields.forEach(field => {
            if (field.value && !this.isValidPhone(field.value)) {
                errors.push('Please enter a valid phone number.');
                this.highlightField(field, 'error');
            }
        });

        // Password validation
        const passwordFields = form.querySelectorAll('input[type="password"][data-validate="strength"]');
        passwordFields.forEach(field => {
            if (field.value) {
                const validation = this.validatePasswordStrength(field.value);
                if (!validation.valid) {
                    errors.push(...validation.errors);
                    this.highlightField(field, 'error');
                }
            }
        });

        // Show errors if any
        if (errors.length > 0) {
            this.showValidationErrors(errors, form);
            return false;
        }

        return true;
    }

    /**
     * Show validation errors on form
     */
    showValidationErrors(errors, form) {
        // Remove existing error messages
        const existingErrors = form.querySelectorAll('.form-validation-errors');
        existingErrors.forEach(el => el.remove());

        // Create error container
        const errorContainer = document.createElement('div');
        errorContainer.className = 'alert alert-danger form-validation-errors';
        errorContainer.innerHTML = `
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                ${errors.map(error => `<li>${error}</li>`).join('')}
            </ul>
        `;

        // Insert at top of form
        form.insertBefore(errorContainer, form.firstChild);

        // Scroll to errors
        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    /**
     * Highlight form field with success or error styling
     */
    highlightField(field, type) {
        field.classList.remove('is-valid', 'is-invalid');
        
        if (type === 'error') {
            field.classList.add('is-invalid');
        } else if (type === 'success') {
            field.classList.add('is-valid');
        }
    }

    /**
     * Get user-friendly field label
     */
    getFieldLabel(field) {
        const label = field.closest('form').querySelector(`label[for="${field.id}"]`);
        if (label) {
            return label.textContent.replace('*', '').trim();
        }
        
        return field.getAttribute('data-label') || 
               field.getAttribute('placeholder') || 
               field.name || 
               'This field';
    }

    /**
     * Show user-friendly error message
     */
    showUserError(message, type = 'error') {
        // Remove existing notifications
        const existing = document.querySelectorAll('.greenzio-notification');
        existing.forEach(el => el.remove());

        // Create notification
        const notification = document.createElement('div');
        notification.className = `greenzio-notification alert alert-${type === 'error' ? 'danger' : 'warning'} alert-dismissible fade show`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        notification.innerHTML = `
            <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        // Auto-remove after 8 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 8000);
    }

    /**
     * Log error (send to server in production)
     */
    logError(error) {
        // Add to queue
        this.errorQueue.push(error);
        
        // Keep queue size manageable
        if (this.errorQueue.length > this.maxErrors) {
            this.errorQueue.shift();
        }

        // Console log in development
        if (!this.isProduction) {
            console.error('Greenzio Error:', error);
        }

        // Send to server (implement based on your logging system)
        if (this.isProduction) {
            this.sendErrorToServer(error);
        }
    }

    /**
     * Send error to server for logging
     */
    sendErrorToServer(error) {
        try {
            const baseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/') + '/';
            const logUrl = baseUrl + 'errorLogger/logJavaScriptError';
            
            fetch(logUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(error)
            }).catch(() => {
                // Silently fail if logging fails
            });
        } catch (e) {
            // Silently fail if logging fails
        }
    }

    /**
     * Utility functions
     */
    isCriticalError(error) {
        const criticalPatterns = [
            /script error/i,
            /network error/i,
            /failed to load/i
        ];
        
        return criticalPatterns.some(pattern => 
            pattern.test(error.message) || 
            (error.filename && pattern.test(error.filename))
        );
    }

    isAjaxRelatedError(error) {
        const ajaxPatterns = [
            /fetch/i,
            /ajax/i,
            /xhr/i,
            /network/i,
            /load/i
        ];
        
        return ajaxPatterns.some(pattern => pattern.test(error.message));
    }

    redirectToLogin() {
        setTimeout(() => {
            window.location.href = '/user/login';
        }, 3000);
    }

    isValidEmail(email) {
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return pattern.test(email);
    }

    isValidPhone(phone) {
        // Indian phone number pattern
        const pattern = /^[6-9][0-9]{9}$/;
        const cleaned = phone.replace(/\D/g, '');
        return pattern.test(cleaned);
    }

    validatePasswordStrength(password) {
        const errors = [];

        if (password.length < 8) {
            errors.push('Password must be at least 8 characters long.');
        }

        if (!/[A-Z]/.test(password)) {
            errors.push('Password must contain at least one uppercase letter.');
        }

        if (!/[a-z]/.test(password)) {
            errors.push('Password must contain at least one lowercase letter.');
        }

        if (!/[0-9]/.test(password)) {
            errors.push('Password must contain at least one number.');
        }

        if (!/[^A-Za-z0-9]/.test(password)) {
            errors.push('Password must contain at least one special character.');
        }

        return {
            valid: errors.length === 0,
            errors: errors
        };
    }

    /**
     * Public methods
     */
    showError(message) {
        this.showUserError(message, 'error');
    }

    showWarning(message) {
        this.showUserError(message, 'warning');
    }

    // Generic notification (success/info)
    showNotification(message, type = 'success') {
        // Map to bootstrap alert types
        const alertType = type === 'success' ? 'success' : (type === 'info' ? 'info' : 'secondary');
        // Reuse showUserError pipeline but adapt styling
        const prevShowUserError = this.showUserError.bind(this);
        // Temporary wrapper to leverage existing rendering
        const notification = document.createElement('div');
        notification.className = `greenzio-notification alert alert-${alertType} alert-dismissible fade show`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(notification);
        setTimeout(() => {
            if (notification.parentNode) notification.remove();
        }, 6000);
    }

    clearErrors() {
        const notifications = document.querySelectorAll('.greenzio-notification');
        notifications.forEach(el => el.remove());
    }
}

// Initialize error handler when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.greenzioErrorHandler = new GreenzioErrorHandler();
    
    // Add CSS for error handling
    if (!document.getElementById('error-handler-styles')) {
        const style = document.createElement('style');
        style.id = 'error-handler-styles';
        style.textContent = `
            .greenzio-notification {
                animation: slideInRight 0.3s ease;
            }
            
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            .form-validation-errors {
                border-left: 4px solid #dc3545;
                animation: fadeIn 0.3s ease;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .is-invalid {
                border-color: #dc3545 !important;
                animation: shake 0.5s ease-in-out;
            }
            
            .is-valid {
                border-color: #28a745 !important;
            }
            
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
        `;
        document.head.appendChild(style);
    }
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = GreenzioErrorHandler;
}
