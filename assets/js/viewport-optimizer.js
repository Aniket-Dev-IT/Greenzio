/**
 * Viewport Optimizer for Greenzio
 * Ensures proper content scaling at 100% browser zoom
 */

(function() {
    'use strict';
    
    // Set proper viewport meta tag if not already set
    function setViewport() {
        let viewport = document.querySelector('meta[name="viewport"]');
        if (viewport) {
            viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
        }
    }
    
    // Detect and fix oversized content
    function fixOversizedContent() {
        // Find all elements that might be oversized
        const potentialOverflowElements = document.querySelectorAll('*[style*="width"]');
        
        potentialOverflowElements.forEach(function(element) {
            const computedStyle = window.getComputedStyle(element);
            const elementWidth = parseFloat(computedStyle.width);
            const viewportWidth = window.innerWidth;
            
            // If element is wider than viewport, fix it
            if (elementWidth > viewportWidth) {
                element.style.maxWidth = '100%';
                element.style.width = '100%';
                element.style.boxSizing = 'border-box';
            }
        });
        
        // Fix any fixed-width containers
        const containers = document.querySelectorAll('.container, .container-fluid');
        containers.forEach(function(container) {
            container.style.maxWidth = '100%';
            container.style.overflowX = 'hidden';
        });
    }
    
    // Add responsive utility classes
    function addResponsiveUtilities() {
        // Add a style element with utility classes
        if (!document.getElementById('viewport-optimizer-styles')) {
            const style = document.createElement('style');
            style.id = 'viewport-optimizer-styles';
            style.textContent = `
                /* Viewport Optimizer Utilities */
                .vp-no-overflow { overflow-x: hidden !important; }
                .vp-full-width { width: 100% !important; max-width: 100% !important; }
                .vp-responsive-text { font-size: clamp(0.8rem, 2vw, 1rem) !important; }
                .vp-responsive-padding { padding: clamp(0.5rem, 2vw, 1rem) !important; }
                .vp-responsive-margin { margin: clamp(0.25rem, 1vw, 0.5rem) !important; }
                
                /* Prevent horizontal scroll */
                html, body { overflow-x: hidden !important; max-width: 100vw !important; }
                
                /* Fix common Bootstrap issues */
                .row { margin-left: -10px !important; margin-right: -10px !important; }
                [class*="col-"] { padding-left: 10px !important; padding-right: 10px !important; }
                
                /* Ensure all content fits */
                * { box-sizing: border-box !important; }
                img, video, iframe, object, embed { max-width: 100% !important; height: auto !important; }
            `;
            document.head.appendChild(style);
        }
    }
    
    // Monitor for dynamic content changes
    function setupContentObserver() {
        if (typeof MutationObserver !== 'undefined') {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        // Small delay to ensure content is rendered
                        setTimeout(fixOversizedContent, 100);
                    }
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }
    
    // Handle window resize
    function handleResize() {
        fixOversizedContent();
        
        // Update viewport dimensions for CSS calculations
        document.documentElement.style.setProperty('--vw', (window.innerWidth * 0.01) + 'px');
        document.documentElement.style.setProperty('--vh', (window.innerHeight * 0.01) + 'px');
    }
    
    // Debounce function for resize events
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = function() {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Initialize when DOM is ready
    function initialize() {
        setViewport();
        addResponsiveUtilities();
        fixOversizedContent();
        setupContentObserver();
        
        // Set initial viewport dimensions
        handleResize();
        
        // Listen for window resize with debouncing
        window.addEventListener('resize', debounce(handleResize, 250));
        
        // Also fix content after images load
        window.addEventListener('load', function() {
            setTimeout(fixOversizedContent, 500);
        });
        
        console.log('Viewport Optimizer: Content optimized for 100% browser zoom');
    }
    
    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
    } else {
        initialize();
    }
    
    // Expose utilities globally for debugging
    window.ViewportOptimizer = {
        fixContent: fixOversizedContent,
        setViewport: setViewport,
        handleResize: handleResize
    };
    
})();
