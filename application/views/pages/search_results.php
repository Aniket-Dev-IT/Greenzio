<?php
// Initialize variables safely
$i = 1;
$priceArray = [];
$total = 0;
$maximumPrice = 10000; // Default max price
$minimumPrice = 0;     // Default min price

if (!empty($products)) {
    foreach ($products as $list) {
        $total = $i++;
        if (isset($list['price']) && is_numeric($list['price'])) {
            $priceArray[] = $list['price']; 
        }
    }
    
    if (!empty($priceArray)) {
        $maximumPrice = max($priceArray);
        $minimumPrice = min($priceArray);
    }
}

// Get current search parameters
$currentSearch = isset($searchTerm) ? $searchTerm : '';
$currentCategory = $this->input->get('category');
$currentBrand = $this->input->get('brand');
$currentMinPrice = $this->input->get('min_price');
$currentMaxPrice = $this->input->get('max_price');
$currentSort = $this->input->get('sort');
?>

<section class="mt-8 mb-5">
    <div class="container">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item active">Search Results</li>
        </ol>
    </div>

    <div class="text-center">
        <h1 class="display-4 font-weight-bold letter-spacing-5">Search Results</h1>
        <?php if (!empty($currentSearch)): ?>
            <p class="lead">Showing results for "<strong><?php echo htmlspecialchars($currentSearch); ?></strong>"</p>
        <?php endif; ?>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <!-- Enhanced Filters Sidebar -->
            <div class="col-xl-3">
                <div class="filters-wrapper">
                    <h3 class='h4 my-3 p-3 text-uppercase letter-spacing-5 bg-light'>Advanced Filters</h3>
                    
                    <!-- Search Within Results -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="h6 mb-0">Refine Search</h4>
                        </div>
                        <div class="card-body">
                            <input type="text" id="refineSearch" class="form-control" placeholder="Search within results..." value="<?php echo htmlspecialchars($currentSearch); ?>">
                        </div>
                    </div>
                    
                    <div class="accordion" id="searchFilters">
                        <!-- Category Filter -->
                        <div class="card">
                            <div class="card-header" id="headingCategory" data-toggle="collapse" data-target="#collapseCategory" aria-expanded="true" aria-controls="collapseCategory">
                                <h2 class="mb-0 h6">
                                    Category
                                    <i class="fas fa-angle-down" style="float:right;"></i>
                                </h2>
                            </div>
                            <div id="collapseCategory" class="collapse show" aria-labelledby="headingCategory">
                                <div class="card-body category-list">
                                    <ul class="list-unstyled">
                                        <?php if (isset($categories) && !empty($categories)): ?>
                                            <?php foreach ($categories as $category): ?>
                                                <li>
                                                    <input class="styled-checkbox search-category-filter" 
                                                           id="cat_<?php echo md5($category['category']); ?>" 
                                                           type="checkbox" 
                                                           value="<?php echo $category['category']; ?>"
                                                           <?php echo ($currentCategory == $category['category']) ? 'checked' : ''; ?>>
                                                    <label for="cat_<?php echo md5($category['category']); ?>">
                                                        <?php echo $category['category']; ?>
                                                    </label>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li>
                                                <input class="styled-checkbox search-category-filter" id="fruits-veg" type="checkbox" value="Fruits & Vegetables">
                                                <label for="fruits-veg">Fruits & Vegetables</label>
                                            </li>
                                            <li>
                                                <input class="styled-checkbox search-category-filter" id="dairy" type="checkbox" value="Dairy Products">
                                                <label for="dairy">Dairy Products</label>
                                            </li>
                                            <li>
                                                <input class="styled-checkbox search-category-filter" id="grains" type="checkbox" value="Grains & Pulses">
                                                <label for="grains">Grains & Pulses</label>
                                            </li>
                                            <li>
                                                <input class="styled-checkbox search-category-filter" id="spices" type="checkbox" value="Spices & Condiments">
                                                <label for="spices">Spices & Condiments</label>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="card">
                            <div class="card-header" id="headingBrand" data-toggle="collapse" data-target="#collapseBrand" aria-expanded="false" aria-controls="collapseBrand">
                                <h2 class="mb-0 h6">
                                    Brand
                                    <i class="fas fa-angle-down" style="float:right;"></i>
                                </h2>
                            </div>
                            <div id="collapseBrand" class="collapse show" aria-labelledby="headingBrand">
                                <div class="card-body category-list">
                                    <div class="brand-search mb-2">
                                        <input type="text" id="brandSearch" class="form-control form-control-sm" placeholder="Search brands...">
                                    </div>
                                    <ul class="list-unstyled brand-list" style="max-height: 200px; overflow-y: auto;">
                                        <?php if (isset($brands) && !empty($brands)): ?>
                                            <?php foreach ($brands as $brand): ?>
                                                <li class="brand-item">
                                                    <input class="styled-checkbox search-brand-filter" 
                                                           id="brand_<?php echo md5($brand['brand']); ?>" 
                                                           type="checkbox" 
                                                           value="<?php echo $brand['brand']; ?>"
                                                           <?php echo ($currentBrand == $brand['brand']) ? 'checked' : ''; ?>>
                                                    <label for="brand_<?php echo md5($brand['brand']); ?>">
                                                        <?php echo $brand['brand']; ?>
                                                    </label>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="brand-item">
                                                <input class="styled-checkbox search-brand-filter" id="organic-farms" type="checkbox" value="Organic Farms">
                                                <label for="organic-farms">Organic Farms</label>
                                            </li>
                                            <li class="brand-item">
                                                <input class="styled-checkbox search-brand-filter" id="fresh-valley" type="checkbox" value="Fresh Valley">
                                                <label for="fresh-valley">Fresh Valley</label>
                                            </li>
                                            <li class="brand-item">
                                                <input class="styled-checkbox search-brand-filter" id="nature-fresh" type="checkbox" value="Nature Fresh">
                                                <label for="nature-fresh">Nature Fresh</label>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="card">
                            <div class="card-header">
                                <h2 class="mb-0 h6">Price Range</h2>
                            </div>
                            <div class="card-body category-list mt-3">
                                <div id="searchPriceFilterMenu">
                                    <div id="search-price-slider" class="mt-4 mt-lg-0"></div>
                                    <div class="nouislider-values">
                                        <div class="min">From <i class="fas fa-rupee-sign"></i>&nbsp;<span id="search-price-lower"></span></div>
                                        <div class="max">To <i class="fas fa-rupee-sign"></i>&nbsp;<span id="search-price-upper"></span></div>
                                        <input type="hidden" name="search_price_from" id="search-price-input-lower" value="<?php echo $currentMinPrice ?: $minimumPrice; ?>" class="search-price-input">
                                        <input type="hidden" name="search_price_to" id="search-price-input-upper" value="<?php echo $currentMaxPrice ?: $maximumPrice; ?>" class="search-price-input">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Weight/Unit Filter -->
                        <div class="card">
                            <div class="card-header" id="headingWeight" data-toggle="collapse" data-target="#collapseWeight" aria-expanded="false" aria-controls="collapseWeight">
                                <h2 class="mb-0 h6">
                                    Unit/Weight
                                    <i class="fas fa-angle-down" style="float:right;"></i>
                                </h2>
                            </div>
                            <div id="collapseWeight" class="collapse show" aria-labelledby="headingWeight">
                                <div class="card-body category-list">
                                    <ul class="list-unstyled">
                                        <li>
                                            <input class="styled-checkbox search-unit-filter" id="unit-kg" type="checkbox" value="kg">
                                            <label for="unit-kg">Per KG</label>
                                        </li>
                                        <li>
                                            <input class="styled-checkbox search-unit-filter" id="unit-grams" type="checkbox" value="grams">
                                            <label for="unit-grams">Per 500g/1kg</label>
                                        </li>
                                        <li>
                                            <input class="styled-checkbox search-unit-filter" id="unit-litre" type="checkbox" value="litre">
                                            <label for="unit-litre">Per Litre</label>
                                        </li>
                                        <li>
                                            <input class="styled-checkbox search-unit-filter" id="unit-piece" type="checkbox" value="piece">
                                            <label for="unit-piece">Per Piece</label>
                                        </li>
                                        <li>
                                            <input class="styled-checkbox search-unit-filter" id="unit-dozen" type="checkbox" value="dozen">
                                            <label for="unit-dozen">Per Dozen</label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Availability Filter -->
                        <div class="card">
                            <div class="card-header" id="headingAvailability" data-toggle="collapse" data-target="#collapseAvailability" aria-expanded="false" aria-controls="collapseAvailability">
                                <h2 class="mb-0 h6">
                                    Availability
                                    <i class="fas fa-angle-down" style="float:right;"></i>
                                </h2>
                            </div>
                            <div id="collapseAvailability" class="collapse show" aria-labelledby="headingAvailability">
                                <div class="card-body category-list">
                                    <ul class="list-unstyled">
                                        <li>
                                            <input class="styled-checkbox search-stock-filter" id="in-stock" type="checkbox" value="in_stock" checked>
                                            <label for="in-stock">In Stock</label>
                                        </li>
                                        <li>
                                            <input class="styled-checkbox search-stock-filter" id="low-stock" type="checkbox" value="low_stock">
                                            <label for="low-stock">Low Stock (Under 10)</label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Clear Filters -->
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <button type="button" id="clearAllFilters" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times"></i> Clear All Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Results -->
            <div class="col-xl-9">
                <!-- Results Header with Sorting -->
                <div class="col-12 mt-3 pt-3">
                    <div class="d-flex flex-row justify-content-between align-items-center flex-wrap">
                        <div class="p-2">
                            <?php if (!empty($products)): ?>
                                Showing <strong>1 - </strong><strong id="searchNumRows"><?php echo $total; ?></strong>&nbsp;products
                            <?php else: ?>
                                Showing <strong id="searchNumRows">0</strong>&nbsp;products
                            <?php endif; ?>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="mr-2">Sort by:</span>
                            <select class="custom-select w-auto border-0" id="searchSortOptions">
                                <option value="relevance">Relevance</option>
                                <option value="price_low" <?php echo ($currentSort == 'price_low') ? 'selected' : ''; ?>>Price: Low to High</option>
                                <option value="price_high" <?php echo ($currentSort == 'price_high') ? 'selected' : ''; ?>>Price: High to Low</option>
                                <option value="name_asc" <?php echo ($currentSort == 'name_asc') ? 'selected' : ''; ?>>Name: A to Z</option>
                                <option value="name_desc" <?php echo ($currentSort == 'name_desc') ? 'selected' : ''; ?>>Name: Z to A</option>
                                <option value="newest" <?php echo ($currentSort == 'newest') ? 'selected' : ''; ?>>Newest First</option>
                                <option value="stock_high" <?php echo ($currentSort == 'stock_high') ? 'selected' : ''; ?>>Stock: High to Low</option>
                                <option value="expiry_soon" <?php echo ($currentSort == 'expiry_soon') ? 'selected' : ''; ?>>Expiry: Soonest First</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Search Results Grid -->
                <div class="row mt-3 py-3 mb-5" id="searchResultsData">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <?php $id = $product['pid']; ?>
                            <div class="col-lg-4 my-4 product-item" 
                                 data-price="<?php echo $product['price']; ?>" 
                                 data-name="<?php echo strtolower($product['pname']); ?>"
                                 data-category="<?php echo $product['category']; ?>"
                                 data-brand="<?php echo $product['brand'] ?? ''; ?>"
                                 data-stock="<?php echo $product['stock_quantity'] ?? 0; ?>"
                                 data-unit="<?php echo $product['unit'] ?? ''; ?>">
                                <div class="product-image">
                                    <img src="<?php echo base_url() . $product['pimage']; ?>" 
                                         class="pimage img-fluid" 
                                         alt="<?php echo htmlspecialchars($product['pname']); ?>" 
                                         loading="lazy">
                                    
                                    <!-- Stock Badge -->
                                    <?php if (isset($product['stock_quantity']) && $product['stock_quantity'] > 0): ?>
                                        <?php if ($product['stock_quantity'] <= 5): ?>
                                            <span class="stock-badge low-stock">Only <?php echo $product['stock_quantity']; ?> left</span>
                                        <?php elseif ($product['stock_quantity'] <= 10): ?>
                                            <span class="stock-badge medium-stock">Limited Stock</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="stock-badge out-of-stock">Out of Stock</span>
                                    <?php endif; ?>
                                    
                                    <div class="product-hover-overlay">
                                        <a href="<?php echo base_url() . 'product/index/' . $id; ?>" class="product-hover-overlay-link"></a>
                                        <div class="product-hover-overlay-buttons">
                                            <a href="<?php echo base_url() . 'product/index/' . $id; ?>" class="btn btn-outline-dark btn-buy">
                                                <i class="fa-search fa"></i>
                                                <span>View</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-2">
                                    <p class="text-muted text-sm mb-1">
                                        <?php echo htmlspecialchars($product['category'] ?? ''); ?>
                                        <?php if (!empty($product['brand'])): ?>
                                            • <?php echo htmlspecialchars($product['brand']); ?>
                                        <?php endif; ?>
                                    </p>
                                    <h3 class="h6 text-uppercase mb-1">
                                        <a href="<?php echo base_url() . 'product/index/' . $id; ?>" class="text-dark">
                                            <?php echo htmlspecialchars($product['pname']); ?>
                                        </a>
                                    </h3>
                                    <div class="product-price">
                                        <span class="text-dark font-weight-bold">
                                            <i class="fas fa-rupee-sign"></i>
                                            <?php echo number_format($product['price'], 2); ?>
                                        </span>
                                        <?php if (!empty($product['weight']) && !empty($product['unit'])): ?>
                                            <small class="text-muted ml-2">
                                                per <?php echo $product['weight'] . ' ' . $product['unit']; ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <div class="no-results-found">
                                <i class="fas fa-search fa-5x text-muted mb-4"></i>
                                <h3 class="h4 text-muted mb-3">No products found</h3>
                                <p class="text-muted mb-4">
                                    <?php if (!empty($currentSearch)): ?>
                                        We couldn't find any products matching "<strong><?php echo htmlspecialchars($currentSearch); ?></strong>".
                                    <?php else: ?>
                                        No products found matching your criteria.
                                    <?php endif; ?>
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
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination (if needed) -->
                <?php if (!empty($products) && count($products) > 12): ?>
                <div class="row">
                    <div class="col-12">
                        <nav aria-label="Search results pagination">
                            <ul class="pagination justify-content-center">
                                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Initialize price range slider for search results
    if (document.getElementById('search-price-slider')) {
        var searchPriceSlider = document.getElementById('search-price-slider');
        
        noUiSlider.create(searchPriceSlider, {
            start: [<?php echo $currentMinPrice ?: $minimumPrice; ?>, <?php echo $currentMaxPrice ?: $maximumPrice; ?>],
            connect: true,
            range: {
                'min': <?php echo $minimumPrice; ?>,
                'max': <?php echo $maximumPrice; ?>
            },
            step: 10,
            format: {
                to: function(value) {
                    return parseInt(value);
                },
                from: function(value) {
                    return parseInt(value);
                }
            }
        });

        // Update display values when slider changes
        searchPriceSlider.noUiSlider.on('update', function(values, handle) {
            document.getElementById('search-price-lower').textContent = values[0];
            document.getElementById('search-price-upper').textContent = values[1];
            document.getElementById('search-price-input-lower').value = values[0];
            document.getElementById('search-price-input-upper').value = values[1];
        });

        // Apply filters when slider changes
        searchPriceSlider.noUiSlider.on('change', function() {
            applySearchFilters();
        });
    }

    // Apply filters when checkboxes change
    $('.styled-checkbox').on('change', function() {
        applySearchFilters();
    });

    // Apply filters when sort option changes
    $('#searchSortOptions').on('change', function() {
        applySearchFilters();
    });

    // Brand search functionality
    $('#brandSearch').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('.brand-item').each(function() {
            var brandName = $(this).find('label').text().toLowerCase();
            if (brandName.indexOf(searchTerm) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    // Refine search functionality
    $('#refineSearch').on('keyup debounce', function() {
        setTimeout(function() {
            applySearchFilters();
        }, 500);
    });

    // Clear all filters
    $('#clearAllFilters').on('click', function() {
        $('.styled-checkbox').prop('checked', false);
        $('#refineSearch').val('');
        $('#searchSortOptions').val('relevance');
        
        // Reset price slider
        if (document.getElementById('search-price-slider')) {
            document.getElementById('search-price-slider').noUiSlider.set([<?php echo $minimumPrice; ?>, <?php echo $maximumPrice; ?>]);
        }
        
        applySearchFilters();
    });

    function applySearchFilters() {
        var searchTerm = $('#refineSearch').val();
        var categories = getFilterValues('search-category-filter');
        var brands = getFilterValues('search-brand-filter');
        var units = getFilterValues('search-unit-filter');
        var stockFilters = getFilterValues('search-stock-filter');
        var sortBy = $('#searchSortOptions').val();
        var minPrice = $('#search-price-input-lower').val();
        var maxPrice = $('#search-price-input-upper').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>product/searchFilter',
            dataType: "JSON",
            data: {
                search_term: searchTerm,
                categories: categories,
                brands: brands,
                units: units,
                stock_filters: stockFilters,
                sort_by: sortBy,
                min_price: minPrice,
                max_price: maxPrice
            },
            success: function(data) {
                $('#searchNumRows').html(data.row);
                $('#searchResultsData').html(data.products);
            },
            error: function(jqXhr, textStatus, errorMessage) {
                console.log("Error: ", errorMessage);
            }
        });
    }

    function getFilterValues(className) {
        var filters = [];
        $('.' + className + ':checked').each(function() {
            filters.push($(this).val());
        });
        return filters;
    }
});
</script>

<style>
.search-container {
    min-width: 300px;
}

.search-form {
    position: relative;
}

.search-category-select {
    max-width: 150px;
    font-size: 0.9rem;
}

.search-input {
    border-left: 0;
    border-right: 0;
}

.search-btn {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.search-btn:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: 0;
    border-radius: 0 0 4px 4px;
    z-index: 1000;
    display: none;
    max-height: 300px;
    overflow-y: auto;
}

.stock-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: bold;
    z-index: 10;
}

.stock-badge.low-stock {
    background-color: #dc3545;
    color: white;
}

.stock-badge.medium-stock {
    background-color: #ffc107;
    color: black;
}

.stock-badge.out-of-stock {
    background-color: #6c757d;
    color: white;
}

.filters-wrapper {
    position: sticky;
    top: 100px;
    max-height: calc(100vh - 120px);
    overflow-y: auto;
}

.brand-search input {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px 8px;
}

.no-results-found {
    padding: 3rem 1rem;
}

@media (max-width: 768px) {
    .search-container {
        min-width: 100%;
        margin-bottom: 1rem;
    }
    
    .search-category-select {
        max-width: 120px;
        font-size: 0.8rem;
    }
    
    .filters-wrapper {
        position: relative;
        top: auto;
        max-height: none;
    }
}
</style>
