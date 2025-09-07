/**
 * GREENZIO ENHANCED SEARCH
 * Minimal, optimized search with auto-complete and filters
 */

(function() {
  'use strict';
  
  // Configuration
  const CONFIG = {
    minSearchLength: 2,
    debounceDelay: 300,
    maxSuggestions: 8,
    cacheExpiry: 300000 // 5 minutes
  };
  
  // Cache and state
  let searchCache = new Map();
  let searchInput, suggestionsContainer;
  let debounceTimer;
  let isInitialized = false;
  
  /**
   * Initialize search functionality
   */
  function init() {
    if (isInitialized) return;
    
    // Cache DOM elements
    searchInput = document.querySelector('#searchInput') || document.querySelector('.mobile-search-input');
    suggestionsContainer = document.querySelector('#searchSuggestions');
    
    if (!searchInput) return;
    
    // Create suggestions container if it doesn't exist
    if (!suggestionsContainer) {
      suggestionsContainer = createSuggestionsContainer();
    }
    
    bindEvents();
    isInitialized = true;
  }
  
  /**
   * Create suggestions container
   */
  function createSuggestionsContainer() {
    const container = document.createElement('div');
    container.id = 'searchSuggestions';
    container.className = 'search-suggestions';
    
    // Insert after search input
    const wrapper = searchInput.closest('.search-wrapper') || searchInput.closest('.mobile-search-form');
    if (wrapper) {
      wrapper.appendChild(container);
    }
    
    return container;
  }
  
  /**
   * Bind event listeners
   */
  function bindEvents() {
    // Search input events
    searchInput.addEventListener('input', handleSearchInput);
    searchInput.addEventListener('focus', handleSearchFocus);
    searchInput.addEventListener('blur', handleSearchBlur);
    searchInput.addEventListener('keydown', handleKeyNavigation);
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.search-wrapper') && !e.target.closest('.mobile-search-form')) {
        hideSuggestions();
      }
    });
  }
  
  /**
   * Handle search input with debouncing
   */
  function handleSearchInput(e) {
    const query = e.target.value.trim();
    
    clearTimeout(debounceTimer);
    
    if (query.length < CONFIG.minSearchLength) {
      hideSuggestions();
      return;
    }
    
    debounceTimer = setTimeout(() => {
      searchProducts(query);
    }, CONFIG.debounceDelay);
  }
  
  /**
   * Handle search input focus
   */
  function handleSearchFocus(e) {
    const query = e.target.value.trim();
    if (query.length >= CONFIG.minSearchLength) {
      searchProducts(query);
    }
  }
  
  /**
   * Handle search input blur with delay
   */
  function handleSearchBlur() {
    setTimeout(hideSuggestions, 150);
  }
  
  /**
   * Handle keyboard navigation in suggestions
   */
  function handleKeyNavigation(e) {
    const suggestions = suggestionsContainer.querySelectorAll('.suggestion-item');
    if (suggestions.length === 0) return;
    
    const activeItem = suggestionsContainer.querySelector('.suggestion-item.active');
    let nextItem;
    
    switch (e.key) {
      case 'ArrowDown':
        e.preventDefault();
        nextItem = activeItem ? activeItem.nextElementSibling : suggestions[0];
        setActiveSuggestion(nextItem, activeItem);
        break;
        
      case 'ArrowUp':
        e.preventDefault();
        nextItem = activeItem ? activeItem.previousElementSibling : suggestions[suggestions.length - 1];
        setActiveSuggestion(nextItem, activeItem);
        break;
        
      case 'Enter':
        if (activeItem) {
          e.preventDefault();
          selectSuggestion(activeItem);
        }
        break;
        
      case 'Escape':
        hideSuggestions();
        searchInput.blur();
        break;
    }
  }
  
  /**
   * Set active suggestion for keyboard navigation
   */
  function setActiveSuggestion(nextItem, currentItem) {
    if (currentItem) currentItem.classList.remove('active');
    if (nextItem) {
      nextItem.classList.add('active');
      nextItem.scrollIntoView({ block: 'nearest' });
    }
  }
  
  /**
   * Search products with caching
   */
  function searchProducts(query) {
    const cacheKey = query.toLowerCase();
    const cached = searchCache.get(cacheKey);
    
    // Return cached results if available and not expired
    if (cached && (Date.now() - cached.timestamp) < CONFIG.cacheExpiry) {
      displaySuggestions(cached.data, query);
      return;
    }
    
    // Show loading state
    showLoadingState();
    
    // Make API request
    const baseUrl = window.location.origin + '/Greenzio';
    fetch(`${baseUrl}/product/searchSuggestions?q=${encodeURIComponent(query)}`)
      .then(response => response.json())
      .then(data => {
        // Cache results
        searchCache.set(cacheKey, {
          data: data,
          timestamp: Date.now()
        });
        
        displaySuggestions(data, query);
      })
      .catch(error => {
        console.warn('Search failed:', error);
        hideSuggestions();
      });
  }
  
  /**
   * Display search suggestions
   */
  function displaySuggestions(data, query) {
    if (!data || data.length === 0) {
      showNoResults(query);
      return;
    }
    
    const html = data
      .slice(0, CONFIG.maxSuggestions)
      .map(item => createSuggestionHTML(item, query))
      .join('');
    
    suggestionsContainer.innerHTML = html;
    suggestionsContainer.style.display = 'block';
    
    // Bind click events to suggestions
    bindSuggestionEvents();
  }
  
  /**
   * Create HTML for a suggestion item
   */
  function createSuggestionHTML(item, query) {
    const icon = getIconForType(item.type);
    const highlightedText = highlightSearchTerm(item.suggestion, query);
    
    return `
      <div class="suggestion-item" data-value="${escapeHtml(item.suggestion)}" data-type="${item.type}">
        ${icon}
        <span class="suggestion-text">${highlightedText}</span>
        <span class="suggestion-type">${item.type}</span>
      </div>
    `;
  }
  
  /**
   * Get icon for suggestion type
   */
  function getIconForType(type) {
    const icons = {
      product: '<i class="fas fa-cube"></i>',
      category: '<i class="fas fa-list"></i>',
      brand: '<i class="fas fa-tag"></i>'
    };
    return icons[type] || '<i class="fas fa-search"></i>';
  }
  
  /**
   * Highlight search term in suggestion
   */
  function highlightSearchTerm(text, query) {
    const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
    return text.replace(regex, '<mark>$1</mark>');
  }
  
  /**
   * Show loading state
   */
  function showLoadingState() {
    suggestionsContainer.innerHTML = `
      <div class="suggestion-loading">
        <i class="fas fa-spinner fa-spin"></i>
        <span>Searching...</span>
      </div>
    `;
    suggestionsContainer.style.display = 'block';
  }
  
  /**
   * Show no results message
   */
  function showNoResults(query) {
    suggestionsContainer.innerHTML = `
      <div class="suggestion-no-results">
        <i class="fas fa-search"></i>
        <span>No results for "${escapeHtml(query)}"</span>
      </div>
    `;
    suggestionsContainer.style.display = 'block';
  }
  
  /**
   * Hide suggestions
   */
  function hideSuggestions() {
    suggestionsContainer.style.display = 'none';
  }
  
  /**
   * Bind click events to suggestion items
   */
  function bindSuggestionEvents() {
    const suggestions = suggestionsContainer.querySelectorAll('.suggestion-item');
    suggestions.forEach(item => {
      item.addEventListener('click', () => selectSuggestion(item));
    });
  }
  
  /**
   * Select a suggestion
   */
  function selectSuggestion(item) {
    const value = item.getAttribute('data-value');
    const type = item.getAttribute('data-type');
    
    searchInput.value = value;
    hideSuggestions();
    
    // Update category filter if applicable
    if (type === 'category') {
      const categorySelect = document.querySelector('#searchCategory');
      if (categorySelect) {
        categorySelect.value = value;
      }
    }
    
    // Trigger search
    const form = searchInput.closest('form');
    if (form) {
      form.submit();
    }
  }
  
  /**
   * Utility functions
   */
  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
  
  function escapeRegex(text) {
    return text.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }
  
  /**
   * Clear search cache (for memory management)
   */
  function clearCache() {
    searchCache.clear();
  }
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
  
  // Clear cache periodically
  setInterval(clearCache, CONFIG.cacheExpiry);
  
  // Export for external use
  window.EnhancedSearch = {
    init: init,
    clearCache: clearCache
  };
  
})();
