/**
 * GREENZIO MOBILE NAVIGATION
 * Minimal, optimized, and reusable mobile navigation handler
 */

(function() {
  'use strict';
  
  // Cache DOM elements for performance
  let mobileToggle, mobileOverlay, mobileMenu, mobileClose;
  let isInitialized = false;
  
  /**
   * Initialize mobile navigation when DOM is ready
   */
  function init() {
    if (isInitialized) return;
    
    // Cache elements
    mobileToggle = document.querySelector('.mobile-nav-toggle');
    mobileOverlay = document.querySelector('.mobile-nav-overlay');
    mobileMenu = document.querySelector('.mobile-nav-menu');
    mobileClose = document.querySelector('.mobile-nav-close');
    
    // Only initialize if elements exist
    if (!mobileToggle || !mobileMenu) return;
    
    // Bind events
    bindEvents();
    isInitialized = true;
  }
  
  /**
   * Bind event listeners with minimal code
   */
  function bindEvents() {
    // Toggle menu
    mobileToggle.addEventListener('click', toggleMenu);
    
    // Close menu handlers
    if (mobileClose) mobileClose.addEventListener('click', closeMenu);
    if (mobileOverlay) mobileOverlay.addEventListener('click', closeMenu);
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeMenu();
    });
    
    // Close menu when clicking nav links
    const navLinks = mobileMenu.querySelectorAll('a');
    navLinks.forEach(link => {
      link.addEventListener('click', closeMenu);
    });
  }
  
  /**
   * Toggle mobile menu state
   */
  function toggleMenu() {
    const isOpen = mobileMenu.classList.contains('active');
    isOpen ? closeMenu() : openMenu();
  }
  
  /**
   * Open mobile menu
   */
  function openMenu() {
    mobileMenu.classList.add('active');
    if (mobileOverlay) {
      mobileOverlay.style.display = 'block';
      // Trigger reflow for animation
      mobileOverlay.offsetHeight;
      mobileOverlay.style.opacity = '1';
    }
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
  }
  
  /**
   * Close mobile menu
   */
  function closeMenu() {
    mobileMenu.classList.remove('active');
    if (mobileOverlay) {
      mobileOverlay.style.opacity = '0';
      setTimeout(() => {
        mobileOverlay.style.display = 'none';
      }, 300);
    }
    
    // Restore body scroll
    document.body.style.overflow = '';
  }
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
  
  // Export for external use if needed
  window.MobileNav = {
    open: openMenu,
    close: closeMenu,
    toggle: toggleMenu
  };
  
})();
