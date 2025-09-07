/**
 * Lazy Loading Utility for Greenzio
 * Optimizes image loading for better performance
 */

class LazyLoader {
    constructor(options = {}) {
        this.options = {
            root: null,
            rootMargin: '50px 0px',
            threshold: 0.01,
            placeholder: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 200"%3E%3Crect fill="%23f8f9fa" width="300" height="200"/%3E%3Ctext fill="%236c757d" x="50%" y="50%" text-anchor="middle" dy=".3em" font-family="Arial" font-size="14"%3ELoading...%3C/text%3E%3C/svg%3E',
            errorPlaceholder: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 200"%3E%3Crect fill="%23f8f9fa" width="300" height="200"/%3E%3Ctext fill="%23dc3545" x="50%" y="50%" text-anchor="middle" dy=".3em" font-family="Arial" font-size="14"%3EImage not found%3C/text%3E%3C/svg%3E',
            fadeInDuration: 300,
            retryAttempts: 2,
            ...options
        };
        
        this.imageCache = new Set();
        this.retryCount = new Map();
        this.init();
    }
    
    init() {
        if ('IntersectionObserver' in window) {
            this.observer = new IntersectionObserver(
                this.handleIntersection.bind(this),
                {
                    root: this.options.root,
                    rootMargin: this.options.rootMargin,
                    threshold: this.options.threshold
                }
            );
            
            this.observeImages();
        } else {
            // Fallback for older browsers
            this.loadAllImages();
        }
        
        // Re-observe new images when DOM changes
        this.observeNewImages();
    }
    
    observeImages() {
        const lazyImages = document.querySelectorAll('img[data-src]:not([data-lazy-loaded])');
        lazyImages.forEach(img => {
            this.setupImage(img);
            this.observer.observe(img);
        });
    }
    
    observeNewImages() {
        // Use MutationObserver to watch for new images
        if ('MutationObserver' in window) {
            const mutationObserver = new MutationObserver(mutations => {
                mutations.forEach(mutation => {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1) { // Element node
                            const newImages = node.querySelectorAll ? 
                                node.querySelectorAll('img[data-src]:not([data-lazy-loaded])') : 
                                [];
                            
                            if (node.tagName === 'IMG' && node.hasAttribute('data-src') && !node.hasAttribute('data-lazy-loaded')) {
                                this.setupImage(node);
                                this.observer.observe(node);
                            }
                            
                            newImages.forEach(img => {
                                this.setupImage(img);
                                this.observer.observe(img);
                            });
                        }
                    });
                });
            });
            
            mutationObserver.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }
    
    setupImage(img) {
        // Set placeholder if not already set
        if (!img.src || img.src === '') {
            img.src = this.options.placeholder;
        }
        
        // Add loading class
        img.classList.add('lazy-loading');
        
        // Set up error handling
        img.addEventListener('error', () => this.handleImageError(img));
    }
    
    handleIntersection(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                this.loadImage(entry.target);
                this.observer.unobserve(entry.target);
            }
        });
    }
    
    loadImage(img) {
        const src = img.getAttribute('data-src');
        if (!src) return;
        
        // Check if image is already cached
        if (this.imageCache.has(src)) {
            this.displayImage(img, src);
            return;
        }
        
        // Create new image to preload
        const newImg = new Image();
        
        newImg.onload = () => {
            this.imageCache.add(src);
            this.displayImage(img, src);
        };
        
        newImg.onerror = () => {
            this.handleImageError(img);
        };
        
        newImg.src = src;
    }
    
    displayImage(img, src) {
        img.src = src;
        img.removeAttribute('data-src');
        img.setAttribute('data-lazy-loaded', 'true');
        img.classList.remove('lazy-loading');
        img.classList.add('lazy-loaded');
        
        // Fade in effect
        if (this.options.fadeInDuration > 0) {
            img.style.opacity = '0';
            img.style.transition = `opacity ${this.options.fadeInDuration}ms ease-in-out`;
            
            setTimeout(() => {
                img.style.opacity = '1';
            }, 10);
        }
        
        // Trigger custom event
        img.dispatchEvent(new CustomEvent('lazyloaded', {
            detail: { src: src }
        }));
    }
    
    handleImageError(img) {
        const src = img.getAttribute('data-src') || img.src;
        const retryCount = this.retryCount.get(src) || 0;
        
        if (retryCount < this.options.retryAttempts) {
            this.retryCount.set(src, retryCount + 1);
            
            // Retry after a delay
            setTimeout(() => {
                if (img.getAttribute('data-src')) {
                    this.loadImage(img);
                }
            }, 1000 * (retryCount + 1)); // Exponential backoff
        } else {
            // Show error placeholder
            img.src = this.options.errorPlaceholder;
            img.removeAttribute('data-src');
            img.setAttribute('data-lazy-loaded', 'true');
            img.classList.remove('lazy-loading');
            img.classList.add('lazy-error');
            
            // Trigger error event
            img.dispatchEvent(new CustomEvent('lazyerror', {
                detail: { src: src, retryCount: retryCount }
            }));
        }
    }
    
    loadAllImages() {
        // Fallback: load all images immediately for older browsers
        const lazyImages = document.querySelectorAll('img[data-src]:not([data-lazy-loaded])');
        lazyImages.forEach(img => this.loadImage(img));
    }
    
    // Public methods
    refresh() {
        this.observeImages();
    }
    
    loadAll() {
        const lazyImages = document.querySelectorAll('img[data-src]:not([data-lazy-loaded])');
        lazyImages.forEach(img => {
            this.observer.unobserve(img);
            this.loadImage(img);
        });
    }
    
    destroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize lazy loading
    window.lazyLoader = new LazyLoader({
        rootMargin: '100px 0px', // Start loading when image is 100px away from viewport
        threshold: 0.01,
        fadeInDuration: 400
    });
    
    // Add CSS for lazy loading effects
    if (!document.getElementById('lazy-loading-styles')) {
        const style = document.createElement('style');
        style.id = 'lazy-loading-styles';
        style.textContent = `
            .lazy-loading {
                background: #f8f9fa url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="20" fill="none" stroke="%23007bff" stroke-width="2" stroke-dasharray="31.416" stroke-dashoffset="31.416"><animate attributeName="stroke-dasharray" dur="2s" values="0 31.416;15.708 15.708;0 31.416" repeatCount="indefinite"/><animate attributeName="stroke-dashoffset" dur="2s" values="0;-15.708;-31.416" repeatCount="indefinite"/></circle></svg>') center center no-repeat;
                background-size: 30px 30px;
                min-height: 150px;
                transition: all 0.3s ease;
            }
            
            .lazy-loaded {
                background: none;
            }
            
            .lazy-error {
                background: #f8f9fa;
                border: 2px dashed #dc3545;
                opacity: 0.7;
            }
            
            .product-image .lazy-loading {
                border-radius: 4px;
            }
            
            .product-image .lazy-loaded {
                transform: scale(1);
            }
            
            .product-image .lazy-error {
                border-radius: 4px;
            }
        `;
        document.head.appendChild(style);
    }
});

// Utility function to convert images to lazy loading
window.convertToLazyLoading = function(container) {
    const images = container ? container.querySelectorAll('img:not([data-src])') : document.querySelectorAll('img:not([data-src])');
    
    images.forEach(img => {
        if (img.src && img.src !== '') {
            img.setAttribute('data-src', img.src);
            img.removeAttribute('src');
            img.removeAttribute('data-lazy-loaded');
        }
    });
    
    if (window.lazyLoader) {
        window.lazyLoader.refresh();
    }
};

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LazyLoader;
}
