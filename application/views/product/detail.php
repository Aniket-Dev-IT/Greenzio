<?php
/**
 * Product Detail View - Enhanced Grocery Display
 * Shows detailed product information with grocery-specific features
 */

// Ensure product data exists
if (empty($productDetail['productDetail'])) {
    redirect('/');
    return;
}

$product = $productDetail['productDetail'][0];

// Extract grocery-specific data
$weight = $product['weight'] ?? '';
$unit = $product['unit'] ?? '';
$brand = $product['brand'] ?? '';
$expiryDate = $product['expiry_date'] ?? '';
$stockQuantity = $product['stock_quantity'] ?? 0;
$discount = $product['discount'] ?? 0;
$description = $product['description'] ?? '';
$category = $product['category'] ?? '';
$subcategory = $product['subcategory'] ?? '';

// Calculate pricing
$originalPrice = $product['price'];
$finalPrice = $discount > 0 ? $originalPrice * (1 - $discount / 100) : $originalPrice;

// Get helper data
$stockDetails = $productDetail['stockDetails'] ?? null;
$expiryDetails = $productDetail['expiryDetails'] ?? null;
$nutritionalInfo = $productDetail['nutritionalInfo'] ?? [];
$storageInstructions = $productDetail['storageInstructions'] ?? 'Store in a cool, dry place away from direct sunlight.';
$averageRating = $productDetail['averageRating'] ?? 4.2;
$totalReviews = $productDetail['totalReviews'] ?? 127;
$reviews = $productDetail['reviews'] ?? [];
$relatedProducts = $productDetail['relatedProducts'] ?? [];
?>

<!-- Breadcrumbs -->
<section class="breadcrumbs-section py-3 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item">
                    <a href="<?php echo base_url('product/category/' . urlencode($category)); ?>">
                        <?php echo ucfirst($category); ?>
                    </a>
                </li>
                <?php if (!empty($subcategory)): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo base_url('product/category/' . urlencode($category) . '/' . urlencode($subcategory)); ?>">
                        <?php echo ucfirst($subcategory); ?>
                    </a>
                </li>
                <?php endif; ?>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['pname']); ?></li>
            </ol>
        </nav>
    </div>
</section>

<!-- Product Detail Section -->
<section class="product-detail-section py-4">
    <div class="container">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="product-images-container">
                    <!-- Main Image -->
                    <div class="main-image-container position-relative mb-3">
                        <?php
                        // Handle image path - check if it starts with 'Images/' and format accordingly
                        $image_src = $product['pimage'];
                        if (!empty($product['pimage'])) {
                            // If image path doesn't start with http/https and doesn't have base_url, add it
                            if (!preg_match('/^https?:\/\//', $product['pimage']) && !preg_match('/^' . preg_quote(base_url(), '/') . '/', $product['pimage'])) {
                                $image_src = base_url($product['pimage']);
                            }
                        } else {
                            $image_src = function_exists('get_image_placeholder') ? get_image_placeholder() : 'https://via.placeholder.com/500x500/cccccc/666666?text=No+Image';
                        }
                        ?>
                        <img src="<?php echo $image_src; ?>" 
                             class="img-fluid main-product-image" 
                             id="mainProductImage"
                             alt="<?php echo htmlspecialchars($product['pname']); ?>"
                             onerror="this.src='<?php echo function_exists('get_image_placeholder') ? get_image_placeholder() : 'https://via.placeholder.com/500x500/cccccc/666666?text=No+Image'; ?>'">
                        
                        <!-- Image Zoom Overlay -->
                        <div class="image-zoom-overlay" id="imageZoomOverlay">
                            <div class="zoom-lens" id="zoomLens"></div>
                        </div>
                        
                        <!-- Product Badges -->
                        <div class="product-badges">
                            <?php if ($discount > 0): ?>
                            <span class="badge badge-success discount-badge"><?php echo $discount; ?>% OFF</span>
                            <?php endif; ?>
                            
                            <?php if ($stockDetails && in_array($stockDetails['status'], ['very_low_stock', 'low_stock'])): ?>
                            <span class="badge <?php echo $stockDetails['badge_class']; ?> stock-badge">
                                <?php echo $stockDetails['message']; ?>
                            </span>
                            <?php endif; ?>
                            
                            <?php if ($expiryDetails && in_array($expiryDetails['status'], ['expiring_urgent', 'expiring_soon'])): ?>
                            <span class="badge <?php echo $expiryDetails['badge_class']; ?> expiry-badge">
                                Expires <?php echo $expiryDetails['days_to_expiry']; ?> days
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Thumbnail Images -->
                    <div class="thumbnail-images d-flex">
                        <div class="thumbnail-item active mr-2">
                            <img src="<?php echo $image_src; ?>" 
                                 class="img-thumbnail thumbnail-img" 
                                 data-large="<?php echo $image_src; ?>">
                        </div>
                        <!-- Additional thumbnails would go here -->
                    </div>
                    
                    <!-- Magnified Image Container (for zoom) -->
                    <div class="magnified-image-container d-none d-lg-block" id="magnifiedContainer">
                        <img src="<?php echo $image_src; ?>" 
                             class="magnified-image" id="magnifiedImage">
                    </div>
                </div>
            </div>
            
            <!-- Product Information -->
            <div class="col-lg-6 col-md-6">
                <div class="product-info">
                    <!-- Product Title and Brand -->
                    <div class="product-header mb-3">
                        <h1 class="product-title h3 mb-2"><?php echo htmlspecialchars($product['pname']); ?></h1>
                        <?php if (!empty($brand)): ?>
                        <p class="brand-name text-muted mb-1">
                            <strong>Brand:</strong> <a href="<?php echo base_url('product/brand/' . urlencode($brand)); ?>" class="text-primary"><?php echo $brand; ?></a>
                        </p>
                        <?php endif; ?>
                        
                        <!-- Weight/Unit -->
                        <?php if (!empty($weight) && !empty($unit)): ?>
                        <p class="product-weight text-info font-weight-bold mb-2">
                            <i class="fas fa-weight"></i> <?php echo $weight . ' ' . $unit; ?>
                        </p>
                        <?php endif; ?>
                        
                        <!-- Rating -->
                        <div class="product-rating d-flex align-items-center mb-3">
                            <div class="stars mr-2">
                                <?php 
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= floor($averageRating)) {
                                        echo '<i class="fas fa-star text-warning"></i>';
                                    } elseif ($i == ceil($averageRating) && $averageRating > floor($averageRating)) {
                                        echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                    } else {
                                        echo '<i class="far fa-star text-muted"></i>';
                                    }
                                }
                                ?>
                            </div>
                            <span class="rating-text"><?php echo number_format($averageRating, 1); ?></span>
                            <span class="text-muted ml-2">(<?php echo $totalReviews; ?> reviews)</span>
                        </div>
                    </div>
                    
                    <!-- Product Status Cards -->
                    <div class="product-status-cards mb-4">
                        <div class="row">
                            <!-- Stock Status -->
                            <div class="col-6 mb-2">
                                <div class="status-card card border-0 bg-light">
                                    <div class="card-body p-2 text-center">
                                        <small class="text-muted d-block">Availability</small>
                                        <?php if ($stockDetails): ?>
                                        <div class="<?php echo $stockDetails['css_class']; ?> font-weight-bold">
                                            <?php echo $stockDetails['message']; ?>
                                        </div>
                                        <?php else: ?>
                                        <div class="<?php echo $stockQuantity > 0 ? 'text-success' : 'text-danger'; ?> font-weight-bold">
                                            <?php echo $stockQuantity > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Expiry Date -->
                            <?php if (!empty($expiryDate)): ?>
                            <div class="col-6 mb-2">
                                <div class="status-card card border-0 bg-light">
                                    <div class="card-body p-2 text-center">
                                        <small class="text-muted d-block">Best Before</small>
                                        <?php if ($expiryDetails): ?>
                                        <div class="<?php echo $expiryDetails['css_class']; ?> font-weight-bold">
                                            <?php echo $expiryDetails['formatted_date']; ?>
                                        </div>
                                        <?php else: ?>
                                        <div class="text-info font-weight-bold">
                                            <?php echo date('d M Y', strtotime($expiryDate)); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Urgent Alerts -->
                    <?php if ($stockDetails && in_array($stockDetails['status'], ['very_low_stock', 'low_stock'])): ?>
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Limited Stock!</strong> Only <?php echo $stockQuantity; ?> units left. Order now!
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($expiryDetails && in_array($expiryDetails['status'], ['expiring_urgent', 'expiring_soon'])): ?>
                    <div class="alert <?php echo $expiryDetails['status'] === 'expiring_urgent' ? 'alert-danger' : 'alert-warning'; ?>" role="alert">
                        <i class="fas fa-clock"></i>
                        <strong>Expires Soon!</strong> This product expires in <?php echo $expiryDetails['days_to_expiry']; ?> days.
                    </div>
                    <?php endif; ?>
                    
                    <!-- Pricing Section -->
                    <div class="pricing-section mb-4">
                        <div class="price-display d-flex align-items-center mb-2">
                            <h2 class="current-price text-primary font-weight-bold mb-0">
                                ₹<?php echo number_format($finalPrice, 2); ?>
                            </h2>
                            <?php if ($discount > 0): ?>
                            <span class="original-price text-muted ml-3">
                                <s>₹<?php echo number_format($originalPrice, 2); ?></s>
                            </span>
                            <span class="discount-percent badge badge-success ml-2">
                                <?php echo $discount; ?>% OFF
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Price per unit -->
                        <?php if (!empty($weight) && !empty($unit) && $weight > 1): ?>
                        <div class="price-per-unit">
                            <small class="text-muted">
                                ₹<?php echo number_format($finalPrice / $weight, 2); ?> per <?php echo $unit; ?>
                            </small>
                        </div>
                        <?php endif; ?>
                        
                        <small class="text-muted">Inclusive of all taxes | FREE delivery</small>
                    </div>
                    
                    <!-- Add to Cart Section -->
                    <?php if ($stockQuantity > 0): ?>
                    <div class="add-to-cart-section">
                        <form action="<?php echo base_url('shopping/checkCart/'); ?>" method="POST" id="addToCartForm" class="mb-4">
                            <!-- Quantity Selector -->
                            <div class="quantity-selector mb-3">
                                <label class="font-weight-bold mb-2">Quantity:</label>
                                <div class="input-group" style="width: 150px;">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-outline-secondary" id="decreaseQty">-</button>
                                    </div>
                                    <input type="number" name="quantity" id="quantity" value="1" 
                                           min="1" max="<?php echo $stockQuantity; ?>" 
                                           class="form-control text-center">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="increaseQty">+</button>
                                    </div>
                                </div>
                                <small class="text-muted">Max available: <?php echo $stockQuantity; ?></small>
                            </div>
                            
                            <input type="hidden" name="pid" value="<?php echo $product['pid']; ?>">
                            <input type="hidden" name="price" value="<?php echo $finalPrice; ?>">
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                            </button>
                        </form>
                        
                        <!-- Pincode Check -->
                        <div class="pincode-check">
                            <h6 class="font-weight-bold mb-2">Check Delivery</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Enter Pincode" 
                                       id="pincode" maxlength="6" pattern="[0-9]{6}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="checkPincode">Check</button>
                                </div>
                            </div>
                            <div id="pincodeResult" class="mt-2"></div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="out-of-stock-section text-center py-4">
                        <button class="btn btn-secondary btn-lg w-100" disabled>
                            <i class="fas fa-times mr-2"></i>Out of Stock
                        </button>
                        <p class="text-muted mt-2">This product is currently unavailable</p>
                        <button class="btn btn-outline-primary btn-sm" id="notifyWhenAvailable">
                            <i class="fas fa-bell mr-2"></i>Notify When Available
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Details Tabs -->
<section class="product-tabs-section py-4 bg-light">
    <div class="container">
        <nav>
            <div class="nav nav-tabs justify-content-center" id="product-tabs" role="tablist">
                <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab">
                    <i class="fas fa-info-circle mr-2"></i>Description
                </a>
                <a class="nav-link" id="nutrition-tab" data-toggle="tab" href="#nutrition" role="tab">
                    <i class="fas fa-apple-alt mr-2"></i>Nutrition
                </a>
                <a class="nav-link" id="storage-tab" data-toggle="tab" href="#storage" role="tab">
                    <i class="fas fa-box mr-2"></i>Storage
                </a>
                <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab">
                    <i class="fas fa-star mr-2"></i>Reviews (<?php echo $totalReviews; ?>)
                </a>
            </div>
        </nav>
        
        <div class="tab-content bg-white border-left border-right border-bottom" id="product-tab-content">
            <!-- Description Tab -->
            <div class="tab-pane fade show active" id="description" role="tabpanel">
                <div class="p-4">
                    <h5 class="mb-3">Product Description</h5>
                    <p class="text-muted mb-4">
                        <?php echo !empty($description) ? nl2br(htmlspecialchars($description)) : 'Premium quality ' . htmlspecialchars($product['pname']) . ' sourced from the finest producers. Perfect for your daily needs with guaranteed freshness and quality.'; ?>
                    </p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Product Specifications</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td><?php echo ucfirst($category); ?></td>
                                </tr>
                                <?php if (!empty($subcategory)): ?>
                                <tr>
                                    <td><strong>Subcategory:</strong></td>
                                    <td><?php echo ucfirst($subcategory); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($brand)): ?>
                                <tr>
                                    <td><strong>Brand:</strong></td>
                                    <td><?php echo $brand; ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($weight) && !empty($unit)): ?>
                                <tr>
                                    <td><strong>Net Weight:</strong></td>
                                    <td><?php echo $weight . ' ' . $unit; ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($expiryDate)): ?>
                                <tr>
                                    <td><strong>Best Before:</strong></td>
                                    <td><?php echo date('M d, Y', strtotime($expiryDate)); ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Quality Assurance</h6>
                            <div class="quality-badges">
                                <span class="badge badge-success mr-2 mb-2"><i class="fas fa-check"></i> Fresh</span>
                                <span class="badge badge-primary mr-2 mb-2"><i class="fas fa-award"></i> Premium Quality</span>
                                <span class="badge badge-info mr-2 mb-2"><i class="fas fa-truck"></i> Fast Delivery</span>
                                <span class="badge badge-warning mr-2 mb-2"><i class="fas fa-undo"></i> Easy Returns</span>
                                <span class="badge badge-secondary mr-2 mb-2"><i class="fas fa-shield-alt"></i> Quality Assured</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Nutrition Tab -->
            <div class="tab-pane fade" id="nutrition" role="tabpanel">
                <div class="p-4">
                    <h5 class="mb-3">Nutrition Facts</h5>
                    <?php if (!empty($nutritionalInfo)): ?>
                    <div class="nutrition-facts-label bg-white border p-3">
                        <div class="nutrition-header text-center border-bottom pb-2 mb-3">
                            <h6 class="font-weight-bold mb-1">Nutrition Facts</h6>
                            <small class="text-muted">Per <?php echo $weight . ' ' . $unit; ?> serving</small>
                        </div>
                        
                        <div class="nutrition-content">
                            <?php if (isset($nutritionalInfo['calories'])): ?>
                            <div class="nutrition-row d-flex justify-content-between border-bottom py-2">
                                <strong>Calories</strong>
                                <span><?php echo $nutritionalInfo['calories']; ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="nutrition-macros mt-3">
                                <?php if (isset($nutritionalInfo['fat'])): ?>
                                <div class="nutrition-row d-flex justify-content-between py-1">
                                    <span>Total Fat</span>
                                    <span><?php echo $nutritionalInfo['fat']; ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($nutritionalInfo['protein'])): ?>
                                <div class="nutrition-row d-flex justify-content-between py-1">
                                    <span>Protein</span>
                                    <span><?php echo $nutritionalInfo['protein']; ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($nutritionalInfo['carbs'])): ?>
                                <div class="nutrition-row d-flex justify-content-between py-1">
                                    <span>Total Carbohydrates</span>
                                    <span><?php echo $nutritionalInfo['carbs']; ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($nutritionalInfo['fiber'])): ?>
                                <div class="nutrition-row d-flex justify-content-between py-1 ml-3">
                                    <span>Dietary Fiber</span>
                                    <span><?php echo $nutritionalInfo['fiber']; ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($nutritionalInfo['sodium'])): ?>
                                <div class="nutrition-row d-flex justify-content-between py-1">
                                    <span>Sodium</span>
                                    <span><?php echo $nutritionalInfo['sodium']; ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="nutrition-footer mt-3 pt-2 border-top">
                            <small class="text-muted">*Percent Daily Values are based on a 2,000 calorie diet.</small>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <p>Nutritional information not available for this product.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Storage Tab -->
            <div class="tab-pane fade" id="storage" role="tabpanel">
                <div class="p-4">
                    <h5 class="mb-3">Storage Instructions</h5>
                    
                    <div class="storage-info">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <?php echo $storageInstructions; ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="storage-card card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6><i class="fas fa-thermometer-half text-primary mr-2"></i>Temperature</h6>
                                        <p class="text-muted mb-0">
                                            <?php 
                                            $isPerishable = in_array(strtolower($category), ['fruits-vegetables', 'dairy-products']);
                                            echo $isPerishable ? 'Store in refrigerator (2-8°C)' : 'Store at room temperature (15-25°C)'; 
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="storage-card card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6><i class="fas fa-clock text-info mr-2"></i>Shelf Life</h6>
                                        <p class="text-muted mb-0">
                                            <?php if (!empty($expiryDate)): ?>
                                            Best before <?php echo date('M d, Y', strtotime($expiryDate)); ?>
                                            <?php else: ?>
                                            Check product packaging
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="storage-tips">
                            <h6>Storage Tips</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success mr-2"></i> Keep in original packaging</li>
                                <li><i class="fas fa-check text-success mr-2"></i> Avoid direct sunlight</li>
                                <li><i class="fas fa-check text-success mr-2"></i> Keep away from strong odors</li>
                                <?php if ($isPerishable): ?>
                                <li><i class="fas fa-check text-success mr-2"></i> Consume within recommended period</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <div class="p-4">
                    <!-- Reviews Summary -->
                    <div class="reviews-summary mb-4">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="rating-display">
                                    <h2 class="rating-score text-warning mb-2">
                                        <?php echo number_format($averageRating, 1); ?>
                                        <i class="fas fa-star"></i>
                                    </h2>
                                    <div class="stars mb-2">
                                        <?php 
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= floor($averageRating)) {
                                                echo '<i class="fas fa-star text-warning"></i>';
                                            } elseif ($i == ceil($averageRating) && $averageRating > floor($averageRating)) {
                                                echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                            } else {
                                                echo '<i class="far fa-star text-muted"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <small class="text-muted"><?php echo $totalReviews; ?> reviews</small>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <!-- Rating distribution would go here -->
                                <div class="rating-distribution">
                                    <?php 
                                    $ratingDist = [5 => 68, 4 => 24, 3 => 5, 2 => 2, 1 => 1];
                                    foreach ($ratingDist as $stars => $percentage): 
                                    ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="mr-2"><?php echo $stars; ?></span>
                                        <i class="fas fa-star text-warning mr-2"></i>
                                        <div class="progress flex-grow-1 mr-2" style="height: 8px;">
                                            <div class="progress-bar bg-warning" style="width: <?php echo $percentage; ?>%"></div>
                                        </div>
                                        <small><?php echo $percentage; ?>%</small>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Individual Reviews -->
                    <div class="reviews-list">
                        <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $review): ?>
                        <div class="review-item border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1"><?php echo htmlspecialchars($review['user_name']); ?></h6>
                                    <div class="review-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $review['rating']): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-muted"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <small class="text-muted"><?php echo date('M d, Y', strtotime($review['date'])); ?></small>
                            </div>
                            <h6 class="review-title"><?php echo htmlspecialchars($review['title']); ?></h6>
                            <p class="review-comment text-muted"><?php echo htmlspecialchars($review['comment']); ?></p>
                            <div class="review-actions">
                                <small class="text-muted mr-3">
                                    <i class="fas fa-thumbs-up mr-1"></i> Helpful (<?php echo $review['helpful_count']; ?>)
                                </small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <!-- Write Review Button -->
                        <div class="text-center mt-4">
                            <button class="btn btn-outline-primary" data-toggle="modal" data-target="#writeReviewModal">
                                <i class="fas fa-edit mr-2"></i>Write a Review
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<?php if (!empty($relatedProducts)): ?>
<section class="related-products-section py-5">
    <div class="container">
        <h3 class="text-center mb-5">Related Products</h3>
        <div class="row">
            <?php 
            $similarProducts = array_slice($relatedProducts, 0, 4);
            foreach ($similarProducts as $similar): 
                if ($similar['pid'] != $product['pid']): 
            ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <?php $this->load->view('partials/product_card', [
                    'product' => $similar,
                    'show_stock_badge' => true,
                    'card_size' => 'sm'
                ]); ?>
            </div>
            <?php 
                endif;
            endforeach; 
            ?>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
.product-badges {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 1;
}

.product-badges .badge {
    display: block;
    margin-bottom: 5px;
}

.main-product-image {
    max-height: 500px;
    object-fit: cover;
    cursor: zoom-in;
}

.thumbnail-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    cursor: pointer;
}

.thumbnail-item.active .thumbnail-img {
    border-color: #007bff;
}

.magnified-image-container {
    position: absolute;
    top: 0;
    left: 105%;
    width: 400px;
    height: 400px;
    border: 1px solid #ddd;
    background: white;
    overflow: hidden;
    z-index: 1000;
    display: none;
}

.magnified-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-zoom-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: none;
}

.zoom-lens {
    position: absolute;
    width: 100px;
    height: 100px;
    border: 2px solid #007bff;
    background: rgba(0, 123, 255, 0.1);
    cursor: none;
}

.status-card {
    transition: transform 0.2s;
}

.status-card:hover {
    transform: translateY(-2px);
}

.nutrition-facts-label {
    max-width: 400px;
    font-family: Arial, sans-serif;
}

.nutrition-row {
    border-bottom: 1px solid #eee;
}

.nutrition-row:last-child {
    border-bottom: none;
}

@media (max-width: 768px) {
    .magnified-image-container {
        display: none !important;
    }
    
    .product-info {
        margin-top: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity controls
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const maxStock = <?php echo $stockQuantity; ?>;
    
    if (decreaseBtn && increaseBtn) {
        decreaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
        
        increaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < maxStock) {
                quantityInput.value = currentValue + 1;
            }
        });
    }
    
    // Pincode check
    const pincodeInput = document.getElementById('pincode');
    const checkPincodeBtn = document.getElementById('checkPincode');
    const pincodeResult = document.getElementById('pincodeResult');
    
    if (checkPincodeBtn) {
        checkPincodeBtn.addEventListener('click', function() {
            const pincode = pincodeInput.value.trim();
            if (pincode.length !== 6 || !/^\d{6}$/.test(pincode)) {
                pincodeResult.innerHTML = '<small class="text-danger">Please enter a valid 6-digit pincode</small>';
                return;
            }
            
            // Simulate pincode check - in real implementation, call AJAX endpoint
            pincodeResult.innerHTML = '<div class="spinner-border spinner-border-sm mr-2"></div><small>Checking...</small>';
            
            setTimeout(() => {
                pincodeResult.innerHTML = '<small class="text-success"><i class="fas fa-check mr-1"></i>Delivery available | Expected by tomorrow</small>';
            }, 1000);
        });
    }
    
    // Image zoom functionality
    const mainImage = document.getElementById('mainProductImage');
    const zoomOverlay = document.getElementById('imageZoomOverlay');
    const zoomLens = document.getElementById('zoomLens');
    const magnifiedContainer = document.getElementById('magnifiedContainer');
    const magnifiedImage = document.getElementById('magnifiedImage');
    
    if (mainImage && window.innerWidth >= 992) { // Only on desktop
        let isZooming = false;
        
        mainImage.addEventListener('mouseenter', function() {
            isZooming = true;
            zoomOverlay.style.display = 'block';
            magnifiedContainer.style.display = 'block';
        });
        
        mainImage.addEventListener('mouseleave', function() {
            isZooming = false;
            zoomOverlay.style.display = 'none';
            magnifiedContainer.style.display = 'none';
        });
        
        mainImage.addEventListener('mousemove', function(e) {
            if (!isZooming) return;
            
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            // Position zoom lens
            const lensSize = 100;
            const lensX = Math.max(0, Math.min(x - lensSize/2, rect.width - lensSize));
            const lensY = Math.max(0, Math.min(y - lensSize/2, rect.height - lensSize));
            
            zoomLens.style.left = lensX + 'px';
            zoomLens.style.top = lensY + 'px';
            
            // Calculate magnified image position
            const zoomLevel = 2;
            const magnifiedX = -(x * zoomLevel - 200);
            const magnifiedY = -(y * zoomLevel - 200);
            
            magnifiedImage.style.transform = `scale(${zoomLevel}) translate(${magnifiedX/zoomLevel}px, ${magnifiedY/zoomLevel}px)`;
        });
    }
    
    // Add to cart form enhancement
    const addToCartForm = document.getElementById('addToCartForm');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            const formData = new FormData(this);
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
            
            // AJAX request to add to cart
            fetch('<?php echo base_url("shopping/checkCart"); ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Added to Cart!';
                    submitBtn.style.background = '#28a745';
                    
                    // Update cart count
                    if (typeof window.refreshCartCount === 'function') {
                        window.refreshCartCount();
                    }
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                        submitBtn.style.background = '';
                    }, 2000);
                } else {
                    submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Error';
                    submitBtn.style.background = '#dc3545';
                    alert(data.message || 'Failed to add product to cart. Please try again.');
                    
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                        submitBtn.style.background = '';
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Network Error';
                submitBtn.style.background = '#dc3545';
                alert('Network error. Please check your connection and try again.');
                
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    submitBtn.style.background = '';
                }, 2000);
            });
        });
    }
    
    // Notify when available
    const notifyBtn = document.getElementById('notifyWhenAvailable');
    if (notifyBtn) {
        notifyBtn.addEventListener('click', function() {
            // In real implementation, open modal to collect email
            alert('We\'ll notify you when this product is back in stock!');
        });
    }
});
</script>
