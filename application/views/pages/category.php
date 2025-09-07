<?php

// Initialize variables safely
$i = 1;
$priceArray = [];
$total = 0;
$maximumPrice = 1000; // Default max price
$minimumPrice = 0;    // Default min price

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
?>

<section class="mt-8 mb-5">
    <div class="container">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item active"><?php echo isset($category) ? $category : 'Products'; ?></li>
        </ol>
    </div>

    <div class="text-center">
        <h1 class="display-4 font-weight-bold letter-spacing-5 text-capitalize"><?php echo isset($category) ? $category : 'Products'; ?></h1>
    </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-xl-3">
                <h3 class='h4 my-3 p-3 text-uppercase letter-spacing-5'>Filters</h3>
                <div class="accordion" id="filters">
                    <div class="card">
                        <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h2 class="mb-0 h6">
                                Category
                                <i class="fas fa-angle-down" style="float:right;"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne">
                            <div class="card-body category-list categoryFilter">
                                <ul class="list-unstyled">
                                    <?php
                                    if (isset($categories) && !empty($categories)):
                                        foreach ($categories as $category_item) {
                                            //  print_r($category_item);
                                            ?>
                                            <li>
                                                <input class="styled-checkbox sub-category" id="<?php echo htmlspecialchars($category_item['subcategory']); ?>" type="checkbox" value="<?php echo htmlspecialchars($category_item['subcategory']); ?>">
                                                <label for="<?php echo htmlspecialchars($category_item['subcategory']); ?>">
                                                    <?php echo htmlspecialchars($category_item['subcategory']); ?></label>
                                            </li>
                                        <?php
                                        }
                                    else:
                                        ?>
                                        <li>
                                            <p class="text-muted">No subcategories available</p>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h2 class="mb-0 h6">
                                Brand
                                <i class="fas fa-angle-down" style="float:right;"></i>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo">
                            <div class="card-body category-list">
                                <ul class="list-unstyled">
                                    <?php if(isset($brands) && !empty($brands)): ?>
                                        <?php foreach($brands as $brand): ?>
                                        <li>
                                            <input class="styled-checkbox check-brand" id="<?php echo $brand['brand']; ?>" type="checkbox" value="<?php echo $brand['brand']; ?>">
                                            <label for="<?php echo $brand['brand']; ?>"><?php echo $brand['brand']; ?></label>
                                        </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li>
                                            <input class="styled-checkbox check-brand" id="organic-farms" type="checkbox" value="Organic Farms">
                                            <label for="organic-farms">Organic Farms</label>
                                        </li>
                                        <li>
                                            <input class="styled-checkbox check-brand" id="fresh-valley" type="checkbox" value="Fresh Valley">
                                            <label for="fresh-valley">Fresh Valley</label>
                                        </li>
                                        <li>
                                            <input class="styled-checkbox check-brand" id="nature-fresh" type="checkbox" value="Nature Fresh">
                                            <label for="nature-fresh">Nature Fresh</label>
                                        </li>
                                        <li>
                                            <input class="styled-checkbox check-brand" id="green-harvest" type="checkbox" value="Green Harvest">
                                            <label for="green-harvest">Green Harvest</label>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Brand Filter Ends -->

                    <div class="card">
                        <div class="card-header">
                            <h2 class="mb-0 h6">
                                Price
                            </h2>
                        </div>
                        <div class="card-body category-list mt-3">

                            <div id="priceFilterMenu">
                                <div id="slider-snap" class="mt-4 mt-lg-0"> </div>
                                <div class="nouislider-values">
                                    <div class="min">From <i class="fas fa-rupee-sign"></i>&nbsp;<span id="slider-snap-value-lower"></span></div>
                                    <div class="max">To <i class="fas fa-rupee-sign"></i>&nbsp;<span id="slider-snap-value-upper"></span></div>
                                    <input type="hidden" name="pricefrom" id="slider-snap-input-lower" value="100" class="slider-snap-input">
                                    <input type="hidden" name="priceto" id="slider-snap-input-upper" value="10000" class="slider-snap-input">
                                </div>
                            </div>

                        </div>
                    </div> <!-- Price Slider Ends -->

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
                                        <input class="styled-checkbox unit-filter" id="unit-kg" type="checkbox" value="kg">
                                        <label for="unit-kg">Per KG</label>
                                    </li>
                                    <li>
                                        <input class="styled-checkbox unit-filter" id="unit-grams" type="checkbox" value="grams">
                                        <label for="unit-grams">Per 500g/1kg</label>
                                    </li>
                                    <li>
                                        <input class="styled-checkbox unit-filter" id="unit-litre" type="checkbox" value="litre">
                                        <label for="unit-litre">Per Litre</label>
                                    </li>
                                    <li>
                                        <input class="styled-checkbox unit-filter" id="unit-ml" type="checkbox" value="ml">
                                        <label for="unit-ml">Per ML</label>
                                    </li>
                                    <li>
                                        <input class="styled-checkbox unit-filter" id="unit-piece" type="checkbox" value="piece">
                                        <label for="unit-piece">Per Piece</label>
                                    </li>
                                    <li>
                                        <input class="styled-checkbox unit-filter" id="unit-dozen" type="checkbox" value="dozen">
                                        <label for="unit-dozen">Per Dozen</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> <!-- Weight div ends -->

                    <!-- Availability Filter -->
                    <div class="card">
                        <div class="card-header" id="headingStock" data-toggle="collapse" data-target="#collapseStock" aria-expanded="false" aria-controls="collapseStock">
                            <h2 class="mb-0 h6">
                                Availability
                                <i class="fas fa-angle-down" style="float:right;"></i>
                            </h2>
                        </div>
                        <div id="collapseStock" class="collapse show" aria-labelledby="headingStock">
                            <div class="card-body category-list">
                                <ul class="list-unstyled">
                                    <li>
                                        <input class="styled-checkbox stock-filter" id="in-stock" type="checkbox" value="in_stock" checked>
                                        <label for="in-stock">In Stock</label>
                                    </li>
                                    <li>
                                        <input class="styled-checkbox stock-filter" id="low-stock" type="checkbox" value="low_stock">
                                        <label for="low-stock">Low Stock (Under 10)</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> <!-- Availability div ends -->

                    <div class="card">
                        <div class="card-header" id="headingOrganic" data-toggle="collapse" data-target="#collapseOrganic" aria-expanded="false" aria-controls="collapseOrganic">
                            <h2 class="mb-0 h6">
                                Organic/Natural
                                <i class="fas fa-angle-down" style="float:right;"></i>
                            </h2>
                        </div>
                        <div id="collapseOrganic" class="collapse show" aria-labelledby="headingOrganic">
                            <div class="card-body colorFilter">
                                <ul class="list-inline colours-wrapper">

                                    <?php
                                    if (isset($color) && !empty($color)):
                                        foreach ($color as $colorList) {
                                            ?>
                                            <li class="list-inline-item">
                                                <label for="<?php echo htmlspecialchars($colorList['color']); ?>" class="btn-colour" data-allow-multiple style="background-color:<?php echo htmlspecialchars($colorList['color']); ?>"></label>
                                                <input id="<?php echo htmlspecialchars($colorList['color']); ?>" type="checkbox" value="<?php echo htmlspecialchars($colorList['color']); ?>" class="input-invisible color styled-checkbox">
                                            </li>
                                        <?php
                                        }
                                    else:
                                        ?>
                                        <li class="list-inline-item">
                                            <p class="text-muted">No color filters available</p>
                                        </li>
                                    <?php endif; ?>

                                </ul>
                            </div>
                        </div>
                    </div> <!-- Color div ends -->

                    <div class="card">
                        <div class="card-header" id="headingDiscount" data-toggle="collapse" data-target="#collapseDiscount" aria-expanded="false" aria-controls="collapseDiscount">
                            <h2 class="mb-0 h6">
                                Discount
                                <i class="fas fa-angle-down" style="float:right;"></i>
                            </h2>
                        </div>
                        <div id="collapseDiscount" class="collapse show" aria-labelledby="headingDiscount">
                            <div class="card-body category-list">

                                <ul class="list-unstyled">
                                    <li>
                                        <input class="styled-checkbox discount" id="10" type="checkbox" value="10">
                                        <label for="10">10% or above</label>
                                    </li>
                                    <li>
                                        <input class="styled-checkbox discount" id="20" type="checkbox" value="20">
                                        <label for="20">20% or above</label>
                                    </li>
                                    <li>
                                        <input class="styled-checkbox discount" id="30" type="checkbox" value="30">
                                        <label for="30">Upto 30%</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> <!-- Discount div ends -->
                </div> <!-- Accordian Ends ends -->

            </div> <!-- Col -3 ends -->

            <div class="col-xl-9">

                <div class="col-12 mt-3 pt-3">
                    <div class="d-flex flex-row justify-content-between">
                        <div class="p-2">
                            <?php if (!empty($products)): ?>
                                Showing <strong>1 - </strong><strong id="numRows"><?php echo $total; ?></strong>&nbsp;products
                            <?php else: ?>
                                Showing <strong id="numRows">0</strong>&nbsp;products
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="mr-1">Sort by</span>
                            <select class="custom-select w-auto border-0" id="sortOptions">
                                <option value="orderby_0">Default</option>
                                <option value="orderby_1">Price: Low to High</option>
                                <option value="orderby_2">Price: High to Low</option>
                                <option value="orderby_3">Newest first</option>
                                <option value="orderby_4">Popularity</option>
                                <option value="orderby_5">Rating</option>
                                <option value="orderby_6">Expiry Date</option>
                                <option value="orderby_7">Price per Unit</option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="row mt-3 py-3 mb-5" id="ajaxData">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $lists): ?>
                            <?php $id = $lists['pid']; ?>
                            <div class="col-lg-4 my-4">
                                <div class="product-image">
                                    <img data-src="<?php echo base_url() . $lists['pimage']; ?>" 
                                         class="pimage img-fluid" 
                                         alt="<?php echo htmlspecialchars($lists['pname']); ?>"
                                         loading="lazy">
                                    <div class="product-hover-overlay">
                                        <a href="<?php echo base_url() . 'product/index/' . $id; ?>" class="product-hover-overlay-link"></a>
                                        <div class="product-hover-overlay-buttons">
                                            <a href="<?php echo base_url() . 'product/index/' . $id; ?>" class="btn btn-outline-dark btn-buy">
                                                <i class="fa-search fa"></i>
                                                <span>View</span>
                                            </a>
                                            <button class="btn btn-success btn-add-to-cart" 
                                                    data-product-id="<?php echo $id; ?>"
                                                    data-product-name="<?php echo htmlspecialchars($lists['pname']); ?>"
                                                    data-product-price="<?php echo $lists['price']; ?>"
                                                    onclick="addToCartFromCategory(<?php echo $id; ?>)">
                                                <i class="fas fa-cart-plus"></i>
                                                <span>Add to Cart</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-2">
                                    <p class="text-muted text-sm mb-1">
                                        <?php echo htmlspecialchars($lists['subcategory']); ?>
                                    </p>
                                    <h3 class="h6 text-uppercase mb-1">
                                        <a href="<?php echo base_url() . 'product/index/' . $id; ?>" class="text-dark">
                                            <?php echo htmlspecialchars($lists['pname']); ?>
                                        </a>
                                    </h3>
                                    <span class="text-dark">
                                        <i class="fas fa-rupee-sign"></i>
                                        <?php echo number_format($lists['price'], 2); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <div class="no-products-found">
                                <i class="fas fa-search fa-5x text-muted mb-4"></i>
                                <h3 class="h4 text-muted mb-3">No products found</h3>
                                <p class="text-muted mb-4">
                                    We couldn't find any products in the "<?php echo htmlspecialchars($category ?? 'this category'); ?>" category.
                                </p>
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <p class="text-muted mb-3">Try:</p>
                                        <ul class="list-unstyled text-muted">
                                            <li>• Checking other categories</li>
                                            <li>• Adjusting your filters</li>
                                            <li>• Searching for specific products</li>
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
            </div>
        </div>
    </div>

</section>
<script>
    // --------------------------------------------------------------------------------------------
    // -----* .color : for color filters
    // -----* .sub-category : for categories filter 
    // -----* .check-size: for size filters
    // -----* .discount: for discout check-boxes
    // ------------------------------------------------------------------------------------------>
    const values = document.querySelectorAll('.styled-checkbox');


    // const category = [];
    // const size = [];
    // const color = [];
    // const discount = [];        
    $(values).click(function() {
        //   console.log('checkbox changed');
        filter();
    });

    // function uniqueArray(arr){
    //     var result = [...new Set(arr)];
    //     return result;
    // }


    var segment = window.location.href.split('/');
    console.log(segment);
    var categorySlug = segment[5]; // This is now the category slug like 'fruits-vegetables'
    //console.log(categorySlug);

    function filter() {
        var size = [];
        var category = [];
        var color = [];
        var discount = [];
        var brand = [];
        size = filterData('check-size');
        category = filterData('sub-category');
        color = filterData('color');
        discount = filterData('discount');
        brand = filterData('check-brand');

        var minimumPrice = document.querySelector('#slider-snap-input-lower').value;
        var maximumPrice = document.querySelector('#slider-snap-input-upper').value;       
        //    console.group('Size');
        //     console.log(size);
        //     console.log(category);
        //     console.log(color);
        //     console.log(discount);
        //     console.groupEnd();    

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>filter/',
            dataType: "JSON",
            data: {
                category: category,
                size: size,
                color: color,
                discount: discount,
                brand: brand,
                minimumPrice: minimumPrice,
                maximumPrice: maximumPrice,
                gender: categorySlug // This passes the category slug to filter
            },

            success: function(data) {
             JSON.stringify(data);
                //console.log(data);
                //console.log(data.row);
                $('#numRows').html(data.row);
                $('#ajaxData').html(data.products);
                //    
            },
            error: function(jqXhr, textStatus, errorMessage) {
                console.log("Error: ", errorMessage);
            }
        });
    }

  function filterData(className) {
        var filter = [];
        $('.' + className + ':checked').each(function() {
            filter.push($(this).val());
        });
        return filter;
    }

    //filter(); //if u want to fire on load (usefull when n items are checked by default...)
    
    // Initialize price slider
    $(document).ready(function() {
        if (typeof noUiSlider !== 'undefined' && document.getElementById('slider-snap')) {
            var priceSlider = document.getElementById('slider-snap');
            
            noUiSlider.create(priceSlider, {
                start: [<?php echo $minimumPrice; ?>, <?php echo $maximumPrice; ?>],
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
            priceSlider.noUiSlider.on('update', function(values, handle) {
                document.getElementById('slider-snap-value-lower').textContent = values[0];
                document.getElementById('slider-snap-value-upper').textContent = values[1];
                document.getElementById('slider-snap-input-lower').value = values[0];
                document.getElementById('slider-snap-input-upper').value = values[1];
            });
            
            // Apply filters when slider changes (with debounce)
            var filterTimeout;
            priceSlider.noUiSlider.on('change', function() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(function() {
                    filter();
                }, 300);
            });
        } else {
            console.warn('NoUiSlider not loaded or element not found');
        }
    });
    
    // Add to cart functionality
    function addToCartFromCategory(productId) {
        // Get product data from the button
        const button = document.querySelector(`button[data-product-id="${productId}"]`);
        const productName = button.getAttribute('data-product-name');
        const productPrice = button.getAttribute('data-product-price');
        
        // Disable button and show loading state
        const originalContent = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        
        // Prepare form data
        const formData = new FormData();
        formData.append('pid', productId);
        formData.append('quantity', 1); // Default quantity
        formData.append('price', productPrice);
        
        // Send AJAX request
        fetch('<?php echo base_url(); ?>shopping/checkCart', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Add to cart response status:', response.status);
            
            // Handle non-200 responses
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            // Try to parse as JSON, but handle non-JSON responses
            return response.text().then(text => {
                console.log('Raw response text:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('JSON parse error:', e);
                    throw new Error('Server returned invalid JSON response: ' + text.substring(0, 100));
                }
            });
        })
        .then(data => {
            console.log('Parsed cart data:', data);
            
            // Re-enable button
            button.disabled = false;
            button.innerHTML = originalContent;
            
            if (data && data.success) {
                // Show success message
                showNotification('success', `${productName} added to cart successfully!`);
                
                // Update cart count in header immediately
                if (typeof window.refreshCartCount === 'function') {
                    console.log('Updating cart count...');
                    window.refreshCartCount();
                } else {
                    console.warn('refreshCartCount function not found');
                    // Fallback: try to update manually
                    updateCartCountFallback();
                }
                
                // Temporarily change button appearance
                button.classList.remove('btn-success');
                button.classList.add('btn-primary');
                button.innerHTML = '<i class="fas fa-check"></i> Added!';
                
                setTimeout(() => {
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');
                    button.innerHTML = originalContent;
                }, 2000);
            } else {
                // Show error message
                const errorMsg = (data && data.message) ? data.message : 'Failed to add item to cart';
                showNotification('error', errorMsg);
            }
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
            button.disabled = false;
            button.innerHTML = originalContent;
            
            // Show user-friendly error based on error type
            let errorMessage = 'An error occurred. Please try again.';
            if (error.message.includes('HTTP 500')) {
                errorMessage = 'Server error. Please refresh the page and try again.';
            } else if (error.message.includes('HTTP 404')) {
                errorMessage = 'Service not found. Please contact support.';
            } else if (error.message.includes('Failed to fetch')) {
                errorMessage = 'Network error. Please check your connection.';
            }
            
            showNotification('error', errorMessage);
        });
    }
    
    // Fallback cart count update function
    function updateCartCountFallback() {
        fetch('<?php echo base_url(); ?>shopping/getCartItemCount')
            .then(response => response.json())
            .then(data => {
                console.log('Cart count data:', data);
                const badge = document.getElementById('cart-count-badge');
                if (badge) {
                    if (data.count && data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                } else {
                    console.warn('Cart count badge element not found');
                }
            })
            .catch(error => {
                console.error('Error updating cart count:', error);
            });
    }
    
    // Simple notification function
    function showNotification(type, message) {
        // Remove existing notifications
        const existingNotification = document.querySelector('.cart-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show cart-notification`;
        notification.style.cssText = 'position: fixed; top: 120px; right: 20px; z-index: 9999; max-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification && notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
</script>
