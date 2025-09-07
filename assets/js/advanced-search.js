/**
 * GREENZIO ADVANCED SEARCH
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
  let searchInput, suggestionsContainer, filtersContainer;
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
    filtersContainer = document.querySelector('.search-filters');
    
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
    document.addEventListener('click', function(e) {\n      if (!e.target.closest('.search-wrapper') && !e.target.closest('.mobile-search-form')) {\n        hideSuggestions();\n      }\n    });\n  }\n  \n  /**\n   * Handle search input with debouncing\n   */\n  function handleSearchInput(e) {\n    const query = e.target.value.trim();\n    \n    clearTimeout(debounceTimer);\n    \n    if (query.length < CONFIG.minSearchLength) {\n      hideSuggestions();\n      return;\n    }\n    \n    debounceTimer = setTimeout(() => {\n      searchProducts(query);\n    }, CONFIG.debounceDelay);\n  }\n  \n  /**\n   * Handle search input focus\n   */\n  function handleSearchFocus(e) {\n    const query = e.target.value.trim();\n    if (query.length >= CONFIG.minSearchLength) {\n      searchProducts(query);\n    }\n  }\n  \n  /**\n   * Handle search input blur with delay\n   */\n  function handleSearchBlur() {\n    setTimeout(hideSuggestions, 150);\n  }\n  \n  /**\n   * Handle keyboard navigation in suggestions\n   */\n  function handleKeyNavigation(e) {\n    const suggestions = suggestionsContainer.querySelectorAll('.suggestion-item');\n    if (suggestions.length === 0) return;\n    \n    const activeItem = suggestionsContainer.querySelector('.suggestion-item.active');\n    let nextItem;\n    \n    switch (e.key) {\n      case 'ArrowDown':\n        e.preventDefault();\n        nextItem = activeItem ? activeItem.nextElementSibling : suggestions[0];\n        setActiveSuggestion(nextItem, activeItem);\n        break;\n        \n      case 'ArrowUp':\n        e.preventDefault();\n        nextItem = activeItem ? activeItem.previousElementSibling : suggestions[suggestions.length - 1];\n        setActiveSuggestion(nextItem, activeItem);\n        break;\n        \n      case 'Enter':\n        if (activeItem) {\n          e.preventDefault();\n          selectSuggestion(activeItem);\n        }\n        break;\n        \n      case 'Escape':\n        hideSuggestions();\n        searchInput.blur();\n        break;\n    }\n  }\n  \n  /**\n   * Set active suggestion for keyboard navigation\n   */\n  function setActiveSuggestion(nextItem, currentItem) {\n    if (currentItem) currentItem.classList.remove('active');\n    if (nextItem) {\n      nextItem.classList.add('active');\n      nextItem.scrollIntoView({ block: 'nearest' });\n    }\n  }\n  \n  /**\n   * Search products with caching\n   */\n  function searchProducts(query) {\n    const cacheKey = query.toLowerCase();\n    const cached = searchCache.get(cacheKey);\n    \n    // Return cached results if available and not expired\n    if (cached && (Date.now() - cached.timestamp) < CONFIG.cacheExpiry) {\n      displaySuggestions(cached.data, query);\n      return;\n    }\n    \n    // Show loading state\n    showLoadingState();\n    \n    // Make API request\n    fetch(`${window.location.origin}/Greenzio/product/searchSuggestions?q=${encodeURIComponent(query)}`)\n      .then(response => response.json())\n      .then(data => {\n        // Cache results\n        searchCache.set(cacheKey, {\n          data: data,\n          timestamp: Date.now()\n        });\n        \n        displaySuggestions(data, query);\n      })\n      .catch(error => {\n        console.warn('Search failed:', error);\n        hideSuggestions();\n      });\n  }\n  \n  /**\n   * Display search suggestions\n   */\n  function displaySuggestions(data, query) {\n    if (!data || data.length === 0) {\n      showNoResults(query);\n      return;\n    }\n    \n    const html = data\n      .slice(0, CONFIG.maxSuggestions)\n      .map(item => createSuggestionHTML(item, query))\n      .join('');\n    \n    suggestionsContainer.innerHTML = html;\n    suggestionsContainer.style.display = 'block';\n    \n    // Bind click events to suggestions\n    bindSuggestionEvents();\n  }\n  \n  /**\n   * Create HTML for a suggestion item\n   */\n  function createSuggestionHTML(item, query) {\n    const icon = getIconForType(item.type);\n    const highlightedText = highlightSearchTerm(item.suggestion, query);\n    \n    return `\n      <div class=\"suggestion-item\" data-value=\"${escapeHtml(item.suggestion)}\" data-type=\"${item.type}\">\n        ${icon}\n        <span class=\"suggestion-text\">${highlightedText}</span>\n        <span class=\"suggestion-type\">${item.type}</span>\n      </div>\n    `;\n  }\n  \n  /**\n   * Get icon for suggestion type\n   */\n  function getIconForType(type) {\n    const icons = {\n      product: '<i class=\"fas fa-cube\"></i>',\n      category: '<i class=\"fas fa-list\"></i>',\n      brand: '<i class=\"fas fa-tag\"></i>'\n    };\n    return icons[type] || '<i class=\"fas fa-search\"></i>';\n  }\n  \n  /**\n   * Highlight search term in suggestion\n   */\n  function highlightSearchTerm(text, query) {\n    const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');\n    return text.replace(regex, '<mark>$1</mark>');\n  }\n  \n  /**\n   * Show loading state\n   */\n  function showLoadingState() {\n    suggestionsContainer.innerHTML = '\n      <div class=\"suggestion-loading\">\n        <i class=\"fas fa-spinner fa-spin\"></i>\n        <span>Searching...</span>\n      </div>\n    ';\n    suggestionsContainer.style.display = 'block';\n  }\n  \n  /**\n   * Show no results message\n   */\n  function showNoResults(query) {\n    suggestionsContainer.innerHTML = `\n      <div class=\"suggestion-no-results\">\n        <i class=\"fas fa-search\"></i>\n        <span>No results for \"${escapeHtml(query)}\"</span>\n      </div>\n    `;\n    suggestionsContainer.style.display = 'block';\n  }\n  \n  /**\n   * Hide suggestions\n   */\n  function hideSuggestions() {\n    suggestionsContainer.style.display = 'none';\n  }\n  \n  /**\n   * Bind click events to suggestion items\n   */\n  function bindSuggestionEvents() {\n    const suggestions = suggestionsContainer.querySelectorAll('.suggestion-item');\n    suggestions.forEach(item => {\n      item.addEventListener('click', () => selectSuggestion(item));\n    });\n  }\n  \n  /**\n   * Select a suggestion\n   */\n  function selectSuggestion(item) {\n    const value = item.getAttribute('data-value');\n    const type = item.getAttribute('data-type');\n    \n    searchInput.value = value;\n    hideSuggestions();\n    \n    // Update category filter if applicable\n    if (type === 'category') {\n      const categorySelect = document.querySelector('#searchCategory');\n      if (categorySelect) {\n        categorySelect.value = value;\n      }\n    }\n    \n    // Trigger search\n    const form = searchInput.closest('form');\n    if (form) {\n      form.submit();\n    }\n  }\n  \n  /**\n   * Utility functions\n   */\n  function escapeHtml(text) {\n    const div = document.createElement('div');\n    div.textContent = text;\n    return div.innerHTML;\n  }\n  \n  function escapeRegex(text) {\n    return text.replace(/[.*+?^${}()|[\\]\\\\]/g, '\\\\$&');\n  }\n  \n  /**\n   * Clear search cache (for memory management)\n   */\n  function clearCache() {\n    searchCache.clear();\n  }\n  \n  // Initialize when DOM is ready\n  if (document.readyState === 'loading') {\n    document.addEventListener('DOMContentLoaded', init);\n  } else {\n    init();\n  }\n  \n  // Clear cache periodically\n  setInterval(clearCache, CONFIG.cacheExpiry);\n  \n  // Export for external use\n  window.AdvancedSearch = {\n    init: init,\n    clearCache: clearCache\n  };\n  \n})();
