<?php
/**
 * Product Index View - Enhanced Grocery Display
 * Shows product listing with grocery-specific filters and information
 */

// Set default values if not provided
$products = $products ?? [];
$categories = $categories ?? [];
$brands = $brands ?? [];
$filters = $filters ?? [];
$search_term = $search_term ?? '';
$total_products = count($products);

// Grocery-specific filter options
$grocery_types = [
    'organic' => 'Organic',
    'fresh' => 'Fresh',
    'frozen' => 'Frozen',
    'diet' => 'Diet/Low Fat',
    'gluten_free' => 'Gluten Free',
    'vegan' => 'Vegan',
    'sugar_free' => 'Sugar Free'
];

$stock_filters = [
    'in_stock' => 'In Stock',
    'low_stock' => 'Limited Stock',
    'expiring_soon' => 'Expiring Soon'
];

$sort_options = [
    'price_low' => 'Price: Low to High',
    'price_high' => 'Price: High to Low',
    'name_asc' => 'Name: A to Z',
    'name_desc' => 'Name: Z to A',
    'newest' => 'Newest First',
    'expiry_date' => 'Expiry Date',
    'stock_quantity' => 'Stock Quantity'
];
?>

<section class="product-listing-header py-4 bg-light">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-2">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <?php if (!empty($category)): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo base_url('product/category/' . urlencode($category)); ?>">
                        <?php echo ucfirst($category); ?>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (!empty($subcategory)): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo base_url('product/category/' . urlencode($category) . '/' . urlencode($subcategory)); ?>">
                        <?php echo ucfirst($subcategory); ?>
                    </a>
                </li>
                <?php endif; ?>
                <li class="breadcrumb-item active">Products</li>
            </ol>
        </nav>

        <!-- Page Title and Results Count -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">
                    <?php if (!empty($search_term)): ?>
                        Search Results for "<?php echo htmlspecialchars($search_term); ?>"
                    <?php elseif (!empty($category)): ?>
                        <?php echo ucfirst($category); ?>
                        <?php if (!empty($subcategory)): ?>
                            - <?php echo ucfirst($subcategory); ?>
                        <?php endif; ?>
                    <?php else: ?>
                        All Products
                    <?php endif; ?>
                </h1>
                <p class="text-muted mb-0"><?php echo $total_products; ?> products found</p>
            </div>
            
            <!-- View Toggle -->
            <div class="view-toggle btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm active" data-view="grid">
                    <i class="fas fa-th-large"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-view="list">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<section class="product-listing-content py-4">
    <div class="container">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="filters-sidebar">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>Filters</h5>
                        </div>
                        <div class="card-body p-0">
                            <!-- Search Filter -->
                            <div class="filter-section p-3 border-bottom">
                                <h6 class="filter-title mb-3">Search Products</h6>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search..." 
                                           id="productSearch" value="<?php echo htmlspecialchars($search_term); ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Category Filter -->
                            <?php if (!empty($categories)): ?>
                            <div class="filter-section p-3 border-bottom">
                                <h6 class="filter-title mb-3">Categories</h6>
                                <div class="filter-options max-height-150">
                                    <?php foreach ($categories as $cat): ?>
                                    <div class="form-check">
                                        <input class="form-check-input category-filter" type="checkbox" 
                                               value="<?php echo $cat; ?>" id="cat_<?php echo str_replace('-', '_', $cat); ?>">
                                        <label class="form-check-label" for="cat_<?php echo str_replace('-', '_', $cat); ?>">
                                            <?php echo ucfirst(str_replace('-', ' ', $cat)); ?>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Brand Filter -->
                            <?php if (!empty($brands)): ?>
                            <div class="filter-section p-3 border-bottom">
                                <h6 class="filter-title mb-3">Brands</h6>
                                <div class="filter-options max-height-150">
                                    <?php foreach ($brands as $brand): ?>
                                    <div class="form-check">
                                        <input class="form-check-input brand-filter" type="checkbox" 
                                               value="<?php echo $brand; ?>" id="brand_<?php echo str_replace(' ', '_', $brand); ?>">
                                        <label class="form-check-label" for="brand_<?php echo str_replace(' ', '_', $brand); ?>">
                                            <?php echo $brand; ?>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Grocery Type Filter -->
                            <div class="filter-section p-3 border-bottom">
                                <h6 class="filter-title mb-3">Grocery Type</h6>
                                <div class="filter-options">
                                    <?php foreach ($grocery_types as $type => $label): ?>
                                    <div class="form-check">
                                        <input class="form-check-input grocery-type-filter" type="checkbox" 
                                               value="<?php echo $type; ?>" id="type_<?php echo $type; ?>">
                                        <label class="form-check-label" for="type_<?php echo $type; ?>">
                                            <?php echo $label; ?>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Stock Filter -->
                            <div class="filter-section p-3 border-bottom">
                                <h6 class="filter-title mb-3">Stock Status</h6>
                                <div class="filter-options">
                                    <?php foreach ($stock_filters as $status => $label): ?>
                                    <div class="form-check">
                                        <input class="form-check-input stock-filter" type="checkbox" 
                                               value="<?php echo $status; ?>" id="stock_<?php echo $status; ?>">
                                        <label class="form-check-label" for="stock_<?php echo $status; ?>">
                                            <?php echo $label; ?>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Price Range Filter -->
                            <div class="filter-section p-3 border-bottom">
                                <h6 class="filter-title mb-3">Price Range</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" class="form-control form-control-sm" 
                                               placeholder="Min" id="minPrice" step="0.01">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control form-control-sm" 
                                               placeholder="Max" id="maxPrice" step="0.01">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100" 
                                        id="applyPriceFilter">Apply</button>
                            </div>

                            <!-- Clear Filters -->
                            <div class="filter-section p-3">
                                <button type="button" class="btn btn-outline-danger btn-sm w-100" 
                                        id="clearFilters">
                                    <i class="fas fa-times mr-2"></i>Clear All Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Content -->
            <div class="col-lg-9 col-md-8">
                <!-- Sort and Display Options -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="products-count">
                        <span class="text-muted">Showing <span id="showingCount"><?php echo $total_products; ?></span> of <?php echo $total_products; ?> products</span>
                    </div>
                    <div class="sort-options">
                        <select class="form-control form-control-sm" id="sortBy" style="width: auto; display: inline-block;">
                            <option value="">Sort by</option>
                            <?php foreach ($sort_options as $value => $label): ?>
                            <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Products Grid/List -->
                <div id="productsContainer">
                    <?php if (!empty($products)): ?>
                    <div class="row products-grid" id="productsGrid">
                        <?php foreach ($products as $product): ?>
                            <?php 
                            // Set product variables for the partial
                            $show_stock_badge = true;
                            $card_size = 'md';
                            ?>
                            <?php $this->load->view('partials/product_card', [
                                'product' => $product,
                                'show_stock_badge' => $show_stock_badge,
                                'card_size' => $card_size
                            ]); ?>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="no-products-found text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-shopping-basket fa-5x text-muted"></i>
                        </div>
                        <h3 class="h4 text-muted mb-3">No products found</h3>
                        <p class="text-muted mb-4">
                            We couldn't find any products matching your criteria.
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <p class="text-muted mb-3">Try:</p>
                                <ul class="list-unstyled text-muted">
                                    <li>• Using different keywords</li>
                                    <li>• Checking the spelling</li>
                                    <li>• Using more general terms</li>
                                    <li>• Removing some filters</li>
                                </ul>
                            </div>
                        </div>
                        <a href="<?php echo base_url(); ?>" class="btn btn-primary mt-3">
                            <i class="fas fa-home mr-2"></i>Back to Home
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if (!empty($pagination)): ?>
                <div class="pagination-wrapper mt-5">
                    <?php echo $pagination; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay" style="display: none;">
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p class="mt-2">Loading products...</p>
    </div>
</div>

<style>
.filters-sidebar .max-height-150 {
    max-height: 150px;
    overflow-y: auto;
}

.filters-sidebar .filter-section:last-child {
    border-bottom: none !important;
}

.product-listing-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.view-toggle .btn {
    border: 1px solid #dee2e6;
}

.view-toggle .btn.active {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.products-list .product-item {
    display: flex;
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
}

.products-list .product-image {
    flex: 0 0 150px;
    margin-right: 20px;
}

.products-list .product-details {
    flex: 1;
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

@media (max-width: 768px) {
    .filters-sidebar {
        margin-bottom: 20px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const viewToggleButtons = document.querySelectorAll('.view-toggle .btn');
    const productsGrid = document.getElementById('productsGrid');
    
    viewToggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Update active button
            viewToggleButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Update grid/list view
            if (view === 'list') {
                productsGrid.classList.remove('products-grid');
                productsGrid.classList.add('products-list');
            } else {
                productsGrid.classList.remove('products-list');
                productsGrid.classList.add('products-grid');
            }
        });
    });
    
    // Filter functionality
    const searchInput = document.getElementById('productSearch');
    const searchBtn = document.getElementById('searchBtn');
    const categoryFilters = document.querySelectorAll('.category-filter');
    const brandFilters = document.querySelectorAll('.brand-filter');
    const groceryTypeFilters = document.querySelectorAll('.grocery-type-filter');
    const stockFilters = document.querySelectorAll('.stock-filter');
    const sortSelect = document.getElementById('sortBy');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const applyPriceBtn = document.getElementById('applyPriceFilter');
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');
    
    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Apply filters function
    function applyFilters() {
        const filters = {
            search_term: searchInput.value.trim(),
            categories: Array.from(categoryFilters)
                .filter(cb => cb.checked)
                .map(cb => cb.value),
            brands: Array.from(brandFilters)
                .filter(cb => cb.checked)
                .map(cb => cb.value),
            grocery_types: Array.from(groceryTypeFilters)
                .filter(cb => cb.checked)
                .map(cb => cb.value),
            stock_filters: Array.from(stockFilters)
                .filter(cb => cb.checked)
                .map(cb => cb.value),
            sort_by: sortSelect.value,
            min_price: minPriceInput.value,
            max_price: maxPriceInput.value
        };
        
        // Show loading
        document.getElementById('loadingOverlay').style.display = 'flex';
        
        // Make AJAX request to filter products
        fetch('<?php echo base_url("product/searchFilter"); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(filters)
        })
        .then(response => response.json())
        .then(data => {
            // Update products grid
            document.getElementById('productsGrid').innerHTML = data.products;
            document.getElementById('showingCount').textContent = data.row;
            
            // Hide loading
            document.getElementById('loadingOverlay').style.display = 'none';
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loadingOverlay').style.display = 'none';
        });
    }
    
    // Debounced search
    const debouncedFilter = debounce(applyFilters, 300);
    
    // Event listeners
    searchInput.addEventListener('input', debouncedFilter);
    searchBtn.addEventListener('click', applyFilters);
    
    categoryFilters.forEach(filter => filter.addEventListener('change', applyFilters));
    brandFilters.forEach(filter => filter.addEventListener('change', applyFilters));
    groceryTypeFilters.forEach(filter => filter.addEventListener('change', applyFilters));
    stockFilters.forEach(filter => filter.addEventListener('change', applyFilters));
    sortSelect.addEventListener('change', applyFilters);
    
    // Price filter
    applyPriceBtn.addEventListener('click', applyFilters);
    
    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        categoryFilters.forEach(cb => cb.checked = false);
        brandFilters.forEach(cb => cb.checked = false);
        groceryTypeFilters.forEach(cb => cb.checked = false);
        stockFilters.forEach(cb => cb.checked = false);
        sortSelect.value = '';
        minPriceInput.value = '';
        maxPriceInput.value = '';
        applyFilters();
    });
    
    // Initialize
    if (searchInput.value.trim()) {
        applyFilters();
    }
});
</script>
