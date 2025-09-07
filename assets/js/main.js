/**
 * GREENZIO - MAIN JAVASCRIPT
 * Consolidated and optimized functionality
 */

(function() {
    'use strict';

    // UTILITY FUNCTIONS
    const Utils = {
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = function() {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        throttle: function(func, limit) {
            let lastFunc;
            let lastRan;
            return function() {
                const context = this;
                const args = arguments;
                if (!lastRan) {
                    func.apply(context, args);
                    lastRan = Date.now();
                } else {
                    clearTimeout(lastFunc);
                    lastFunc = setTimeout(function() {
                        if ((Date.now() - lastRan) >= limit) {
                            func.apply(context, args);
                            lastRan = Date.now();
                        }
                    }, limit - (Date.now() - lastRan));
                }
            }
        },

        createElement: function(tag, className, innerHTML) {
            const element = document.createElement(tag);
            if (className) element.className = className;
            if (innerHTML) element.innerHTML = innerHTML;
            return element;
        }
    };

    // VIEWPORT OPTIMIZER
    const ViewportOptimizer = {
        init: function() {
            this.setViewport();
            this.fixOversizedContent();
            this.setupContentObserver();
            this.handleResize();
            
            window.addEventListener('resize', Utils.debounce(this.handleResize.bind(this), 250));
            window.addEventListener('load', () => {
                setTimeout(this.fixOversizedContent.bind(this), 500);
            });
        },

        setViewport: function() {
            let viewport = document.querySelector('meta[name="viewport"]');
            if (viewport) {
                viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
            }
        },

        fixOversizedContent: function() {
            const containers = document.querySelectorAll('.container, .container-fluid');
            containers.forEach(container => {
                container.style.maxWidth = '100%';
                container.style.overflowX = 'hidden';
            });

            // Fix images and media
            const media = document.querySelectorAll('img, video, iframe');
            media.forEach(item => {
                item.style.maxWidth = '100%';
                item.style.height = 'auto';
            });
        },

        handleResize: function() {
            this.fixOversizedContent();
            document.documentElement.style.setProperty('--vw', (window.innerWidth * 0.01) + 'px');
            document.documentElement.style.setProperty('--vh', (window.innerHeight * 0.01) + 'px');
        },

        setupContentObserver: function() {
            if (typeof MutationObserver !== 'undefined') {
                const observer = new MutationObserver(mutations => {
                    mutations.forEach(mutation => {
                        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                            setTimeout(this.fixOversizedContent.bind(this), 100);
                        }
                    });
                });
                
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }
        }
    };


    // SEARCH FUNCTIONALITY
    const Search = {
        init: function() {
            this.setupSearchSuggestions();
            this.setupFormSubmission();
        },

        setupSearchSuggestions: function() {
            const searchInput = document.getElementById('searchInput');
            const suggestionsContainer = document.getElementById('searchSuggestions');

            if (!searchInput || !suggestionsContainer) return;

            searchInput.addEventListener('input', Utils.debounce((e) => {
                const query = e.target.value.trim();
                
                if (query.length >= 2) {
                    this.fetchSuggestions(query);
                } else {
                    this.hideSuggestions();
                }
            }, 300));

            // Handle suggestion clicks
            document.addEventListener('click', (e) => {
                if (e.target.matches('.suggestion-item')) {
                    const value = e.target.dataset.value;
                    const type = e.target.dataset.type;
                    
                    searchInput.value = value;
                    
                    if (type === 'category') {
                        const categorySelect = document.getElementById('searchCategory');
                        if (categorySelect) categorySelect.value = value;
                    }
                    
                    this.hideSuggestions();
                }
                
                // Hide suggestions when clicking outside
                if (!e.target.closest('.search-wrapper')) {
                    this.hideSuggestions();
                }
            });
        },

        fetchSuggestions: function(query) {
            fetch(`${window.location.origin}/Greenzio/product/searchSuggestions?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    this.displaySuggestions(data);
                })
                .catch(() => {
                    this.hideSuggestions();
                });
        },

        displaySuggestions: function(suggestions) {
            const container = document.getElementById('searchSuggestions');
            if (!container) return;

            if (suggestions.length === 0) {
                this.hideSuggestions();
                return;
            }

            let html = '';
            suggestions.forEach(item => {
                let icon = '';
                switch(item.type) {
                    case 'product': icon = '<i class="fas fa-cube mr-2"></i>'; break;
                    case 'brand': icon = '<i class="fas fa-tag mr-2"></i>'; break;
                    case 'category': icon = '<i class="fas fa-list mr-2"></i>'; break;
                }
                
                html += `<div class="suggestion-item" data-value="${item.suggestion}" data-type="${item.type}">
                    ${icon}${item.suggestion}
                </div>`;
            });
            
            container.innerHTML = html;
            container.style.display = 'block';
        },

        hideSuggestions: function() {
            const container = document.getElementById('searchSuggestions');
            if (container) {
                container.style.display = 'none';
            }
        },

        setupFormSubmission: function() {
            const searchForm = document.getElementById('globalSearchForm');
            const mobileSearchForm = document.querySelector('.mobile-search-form');
            
            if (searchForm) {
                searchForm.addEventListener('submit', (e) => {
                    const input = searchForm.querySelector('input[name="q"]');
                    if (!input || !input.value.trim()) {
                        e.preventDefault();
                        input.focus();
                    }
                });
            }

            if (mobileSearchForm) {
                mobileSearchForm.addEventListener('submit', (e) => {
                    const input = mobileSearchForm.querySelector('input[name="q"]');
                    if (!input || !input.value.trim()) {
                        e.preventDefault();
                        input.focus();
                    }
                });
            }

            // Category change auto-search
            const categorySelect = document.getElementById('searchCategory');
            if (categorySelect) {
                categorySelect.addEventListener('change', () => {
                    const searchInput = document.getElementById('searchInput');
                    if (searchInput && searchInput.value.length > 0) {
                        setTimeout(() => {
                            searchForm.submit();
                        }, 300);
                    }
                });
            }
        }
    };

    // CART FUNCTIONALITY
    const Cart = {
        init: function() {
            this.updateCartCount();
            this.setupCartUpdates();
        },

        updateCartCount: function() {
            fetch(`${window.location.origin}/Greenzio/shopping/getCartItemCount`)
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('cart-count-badge');
                    if (badge) {
                        if (data.count && data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'inline-block';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.warn('Cart count update failed:', error);
                });
        },

        setupCartUpdates: function() {
            // Update cart count every 30 seconds
            setInterval(() => {
                this.updateCartCount();
            }, 30000);

            // Expose global function for manual updates
            window.refreshCartCount = () => {
                this.updateCartCount();
            };
        },

        addToCart: function(productId, quantity = 1) {
            return fetch(`${window.location.origin}/Greenzio/shopping/addToCart`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof window.showNotification === 'function') {
                        window.showNotification(data.message, 'success');
                    }
                    this.updateCartCount();
                } else {
                    if (typeof window.showNotification === 'function') {
                        window.showNotification(data.message, 'error');
                    }
                }
                return data;
            })
            .catch(error => {
                console.error('Add to cart error:', error);
                if (typeof window.showNotification === 'function') {
                    window.showNotification('An error occurred while adding to cart. Please try again.', 'error');
                }
                throw error;
            });
        }
    };

    // FORM HANDLING
    const Forms = {
        init: function() {
            this.setupLoginForm();
            this.setupRegisterForm();
            this.setupPasswordVisibility();
        },

        setupLoginForm: function() {
            const loginForm = document.getElementById('loginForm');
            if (!loginForm) return;

            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                const formData = new FormData(loginForm);
                const errorContainer = document.getElementById('loginError');
                
                if (errorContainer) {
                    errorContainer.innerHTML = 'Logging in...';
                    errorContainer.style.color = '#007bff';
                }

                fetch(`${window.location.origin}/Greenzio/user/login`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        if (errorContainer) {
                            errorContainer.innerHTML = data.error;
                            errorContainer.style.color = '#dc3545';
                        }
                    } else {
                        if (errorContainer) {
                            errorContainer.innerHTML = 'Login successful! Redirecting...';
                            errorContainer.style.color = '#28a745';
                        }
                        setTimeout(() => {
                            window.location.href = data.url || window.location.origin + '/Greenzio/';
                        }, 1000);
                    }
                })
                .catch(error => {
                    if (errorContainer) {
                        errorContainer.innerHTML = 'Login failed. Please try again.';
                        errorContainer.style.color = '#dc3545';
                    }
                    console.error('Login error:', error);
                });
            });
        },

        setupRegisterForm: function() {
            const registerForm = document.getElementById('registerForm');
            if (!registerForm) return;

            registerForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                const formData = new FormData(registerForm);
                const errorContainer = document.getElementById('registerError');
                
                if (errorContainer) {
                    errorContainer.innerHTML = 'Creating account...';
                    errorContainer.style.color = '#007bff';
                }

                fetch(`${window.location.origin}/Greenzio/user/register`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        if (errorContainer) {
                            errorContainer.innerHTML = data.error;
                            errorContainer.style.color = '#dc3545';
                        }
                    } else {
                        if (errorContainer) {
                            errorContainer.innerHTML = 'Registration successful! Redirecting...';
                            errorContainer.style.color = '#28a745';
                        }
                        setTimeout(() => {
                            window.location.href = data.url || window.location.origin + '/Greenzio/';
                        }, 1000);
                    }
                })
                .catch(error => {
                    if (errorContainer) {
                        errorContainer.innerHTML = 'Registration failed. Please try again.';
                        errorContainer.style.color = '#dc3545';
                    }
                    console.error('Registration error:', error);
                });
            });
        },

        setupPasswordVisibility: function() {
            document.addEventListener('click', (e) => {
                if (e.target.matches('.togglePassword')) {
                    const passwordField = document.querySelector(e.target.getAttribute('toggle'));
                    if (passwordField) {
                        const type = passwordField.type === 'password' ? 'text' : 'password';
                        passwordField.type = type;
                        
                        e.target.classList.toggle('fa-eye');
                        e.target.classList.toggle('fa-eye-slash');
                    }
                }
            });
        }
    };

    // MODAL HANDLING
    const Modals = {
        init: function() {
            this.setupModalSwitching();
            this.setupAutoShowLogin();
        },

        setupModalSwitching: function() {
            // Switch to register modal
            document.addEventListener('click', (e) => {
                if (e.target.matches('#registerButton')) {
                    e.preventDefault();
                    const loginModal = document.getElementById('login');
                    const registerModal = document.getElementById('register');
                    
                    if (loginModal) loginModal.classList.add('d-none');
                    if (registerModal) registerModal.classList.remove('d-none');
                }
                
                // Switch to login modal
                if (e.target.matches('#loginButton')) {
                    e.preventDefault();
                    const loginModal = document.getElementById('login');
                    const registerModal = document.getElementById('register');
                    
                    if (registerModal) registerModal.classList.add('d-none');
                    if (loginModal) loginModal.classList.remove('d-none');
                }
            });
        },

        setupAutoShowLogin: function() {
            // Auto-show login modal if login is required
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('login_required')) {
                const modal = document.getElementById('logModal');
                const errorContainer = document.getElementById('loginError');
                
                if (modal && typeof bootstrap !== 'undefined') {
                    new bootstrap.Modal(modal).show();
                }
                
                if (errorContainer) {
                    errorContainer.innerHTML = 'Please login to proceed with checkout.';
                    errorContainer.style.color = '#007bff';
                }
                
                // Clean up URL
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            }
        }
    };

    // LAZY LOADING
    const LazyLoading = {
        init: function() {
            if ('IntersectionObserver' in window) {
                this.setupObserver();
            } else {
                this.fallbackLoading();
            }
        },

        setupObserver: function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadImage(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '50px' });

            document.querySelectorAll('img[data-src]').forEach(img => {
                observer.observe(img);
            });
        },

        loadImage: function(img) {
            img.src = img.dataset.src;
            img.classList.add('fade-in');
            img.addEventListener('load', () => {
                img.removeAttribute('data-src');
            });
        },

        fallbackLoading: function() {
            document.querySelectorAll('img[data-src]').forEach(img => {
                this.loadImage(img);
            });
        }
    };

    // ERROR HANDLING
    const ErrorHandler = {
        init: function() {
            window.addEventListener('error', this.handleError);
            window.addEventListener('unhandledrejection', this.handlePromiseRejection);
        },

        handleError: function(event) {
            console.error('JavaScript Error:', {
                message: event.message,
                filename: event.filename,
                lineno: event.lineno,
                colno: event.colno,
                error: event.error
            });
            
            // Don't show errors to users in production
            if (window.location.hostname !== 'localhost') {
                event.preventDefault();
            }
        },

        handlePromiseRejection: function(event) {
            console.error('Unhandled Promise Rejection:', event.reason);
            event.preventDefault();
        }
    };

    // INITIALIZATION
    const App = {
        init: function() {
            // Initialize all modules
            ViewportOptimizer.init();
            Search.init();
            Cart.init();
            Forms.init();
            Modals.init();
            LazyLoading.init();
            ErrorHandler.init();
            
            console.log('Greenzio app initialized successfully');
        }
    };

    // Start the application
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', App.init);
    } else {
        App.init();
    }

    // Expose useful functions globally
    window.Greenzio = {
        updateCartCount: Cart.updateCartCount.bind(Cart),
        addToCart: Cart.addToCart.bind(Cart),
        showModal: function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal && typeof bootstrap !== 'undefined') {
                new bootstrap.Modal(modal).show();
            }
        }
    };

})();
