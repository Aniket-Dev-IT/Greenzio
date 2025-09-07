<?php
/**
 * Enhanced Product Card Partial - Grocery Display
 * Reusable product display component with grocery-specific features
 * 
 * Required variables:
 * - $product: Product data array
 * - $show_stock_badge: Boolean (optional)
 * - $card_size: String (optional) - 'sm', 'md', 'lg'
 */

// Set defaults
$show_stock_badge = isset($show_stock_badge) ? $show_stock_badge : true;
$card_size = isset($card_size) ? $card_size : 'md';

// Helper functions (ensure they're loaded)
if (function_exists('get_instance')) {
    $CI = get_instance();
    $CI->load->helper(['common', 'validation']);
}

// Extract product data with enhanced grocery fields
$product_id = $product['pid'];
$product_name = $product['pname'];
$product_price = $product['price'];
$product_image = $product['pimage'];
$product_category = $product['category'] ?? '';
$product_brand = $product['brand'] ?? '';
$product_weight = $product['weight'] ?? '';
$product_unit = $product['unit'] ?? '';
$stock_quantity = $product['stock_quantity'] ?? 0;
$discount = $product['discount'] ?? 0;
$expiry_date = $product['expiry_date'] ?? '';
$nutritional_info = $product['nutritional_info'] ?? '';
$is_organic = $product['is_organic'] ?? false;
$is_fresh = $product['is_fresh'] ?? false;
$storage_type = $product['storage_type'] ?? 'dry'; // dry, refrigerated, frozen

// Calculate pricing
if (function_exists('calculate_discount_price')) {
    $price_info = calculate_discount_price($product_price, $discount);
} else {
    $final_price = $discount > 0 ? $product_price * (1 - $discount / 100) : $product_price;
    $price_info = [
        'original_price' => $product_price,
        'final_price' => $final_price,
        'discount_amount' => $product_price - $final_price,
        'has_discount' => $discount > 0
    ];
}

// Get stock status
if (function_exists('get_stock_status')) {
    $stock_status = get_stock_status($stock_quantity);
} else {
    $stock_status = [
        'available' => $stock_quantity > 0,
        'message' => $stock_quantity > 0 ? 'In Stock' : 'Out of Stock',
        'css_class' => $stock_quantity > 0 ? 'text-success' : 'text-danger',
        'badge_class' => $stock_quantity > 0 ? 'badge-success' : 'badge-danger'
    ];
}

// Get expiry status
if (function_exists('get_expiry_status')) {
    $expiry_status = get_expiry_status($expiry_date);
} else {
    $expiry_status = null;
    if (!empty($expiry_date)) {
        $days_to_expiry = floor((strtotime($expiry_date) - time()) / 86400);
        if ($days_to_expiry <= 3) {
            $expiry_status = [
                'status' => 'expiring_urgent',
                'message' => "Expires in {$days_to_expiry} days",
                'badge_class' => 'badge-danger'
            ];
        } elseif ($days_to_expiry <= 7) {
            $expiry_status = [
                'status' => 'expiring_soon',
                'message' => "Expires in {$days_to_expiry} days",
                'badge_class' => 'badge-warning'
            ];
        }
    }
}

// Generate URLs
if (function_exists('generate_product_url')) {
    $product_url = generate_product_url($product_id, $product_name);
} else {
    $product_url = base_url('product/index/' . $product_id);
}

if (function_exists('generate_category_url')) {
    $category_url = generate_category_url($product_category);
} else {
    $category_url = base_url('product/category/' . urlencode($product_category));
}

// Card size classes
$size_classes = [
    'sm' => 'col-lg-3 col-md-4 col-sm-6',
    'md' => 'col-lg-4 col-md-6 col-sm-6',
    'lg' => 'col-lg-6 col-md-8 col-sm-12'
];

$card_class = $size_classes[$card_size] ?? $size_classes['md'];

// Format price function
if (!function_exists('format_price_local')) {
    function format_price_local($price) {
        return '₹' . number_format($price, 2);
    }
} else {
    function format_price_local($price) {
        return format_price($price);
    }
}
?>

<div class="<?php echo $card_class; ?> my-3 product-item" 
     data-price="<?php echo $product_price; ?>" 
     data-name="<?php echo strtolower($product_name); ?>"
     data-category="<?php echo $product_category; ?>"
     data-brand="<?php echo $product_brand; ?>"
     data-stock="<?php echo $stock_quantity; ?>"
     data-unit="<?php echo $product_unit; ?>"
     data-weight="<?php echo $product_weight; ?>"
     data-expiry="<?php echo $expiry_date; ?>"
     data-organic="<?php echo $is_organic ? '1' : '0'; ?>"
     data-fresh="<?php echo $is_fresh ? '1' : '0'; ?>"
     data-storage="<?php echo $storage_type; ?>">
     
    <div class="card h-100 product-card grocery-card">
        <div class="product-image-container position-relative">
            <!-- Product Image -->
            <a href="<?php echo $product_url; ?>">
                <?php
                // Handle image path - check if it starts with 'Images/' and format accordingly
                $image_src = $product_image;
                if (!empty($product_image)) {
                    // If image path doesn't start with http/https and doesn't have base_url, add it
                    if (!preg_match('/^https?:\/\//', $product_image) && !preg_match('/^' . preg_quote(base_url(), '/') . '/', $product_image)) {
                        $image_src = base_url($product_image);
                    }
                } else {
                    $image_src = function_exists('get_image_placeholder') ? get_image_placeholder() : 'https://via.placeholder.com/300x300/cccccc/666666?text=No+Image';
                }
                ?>
                <img src="<?php echo $image_src; ?>" 
                     class="card-img-top product-image" 
                     alt="<?php echo htmlspecialchars($product_name); ?>" 
                     loading="lazy"
                     onerror="this.src='<?php echo function_exists('get_image_placeholder') ? get_image_placeholder() : 'https://via.placeholder.com/300x300/cccccc/666666?text=No+Image'; ?>'">
            </a>
            
            <!-- Enhanced Badge System -->
            <div class="product-badges">
                <!-- Discount Badge -->
                <?php if ($price_info['has_discount']): ?>
                <span class="badge badge-success discount-badge">
                    <?php echo $discount; ?>% OFF
                </span>
                <?php endif; ?>
                
                <!-- Stock Badge -->
                <?php if ($show_stock_badge && !$stock_status['available']): ?>
                <span class="badge badge-danger stock-badge">
                    Out of Stock
                </span>
                <?php elseif ($show_stock_badge && isset($stock_status['status']) && $stock_status['status'] === 'low_stock'): ?>
                <span class="badge badge-warning stock-badge">
                    Limited Stock
                </span>
                <?php endif; ?>
                
                <!-- Expiry Badge -->
                <?php if ($expiry_status && in_array($expiry_status['status'], ['expiring_urgent', 'expiring_soon'])): ?>
                <span class="badge <?php echo $expiry_status['badge_class']; ?> expiry-badge">
                    <?php echo $expiry_status['message']; ?>
                </span>
                <?php endif; ?>
                
                <!-- Organic Badge -->
                <?php if ($is_organic): ?>
                <span class="badge badge-success organic-badge">
                    <i class="fas fa-leaf"></i> Organic
                </span>
                <?php endif; ?>
                
                <!-- Fresh Badge -->
                <?php if ($is_fresh): ?>
                <span class="badge badge-info fresh-badge">
                    <i class="fas fa-snowflake"></i> Fresh
                </span>
                <?php endif; ?>
            </div>
            
            <!-- Enhanced Product Hover Overlay -->
            <div class="product-hover-overlay">
                <div class="product-hover-buttons">
                    <a href="<?php echo $product_url; ?>" class="btn btn-outline-light btn-sm mb-2">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                    <?php if ($stock_status['available']): ?>
                    <button type="button" class="btn btn-primary btn-sm add-to-cart-btn" 
                            data-product-id="<?php echo $product_id; ?>"
                            data-product-name="<?php echo htmlspecialchars($product_name); ?>"
                            data-product-price="<?php echo $price_info['final_price']; ?>">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                    <?php else: ?>
                    <button type="button" class="btn btn-secondary btn-sm" disabled>
                        <i class="fas fa-times"></i> Out of Stock
                    </button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-outline-light btn-sm mt-2 add-to-wishlist" 
                            data-product-id="<?php echo $product_id; ?>">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Enhanced Product Details -->
        <div class="card-body d-flex flex-column">
            <!-- Category and Brand -->
            <?php if (!empty($product_category)): ?>
            <div class="product-meta mb-2">
                <small class="text-muted d-flex align-items-center">
                    <a href="<?php echo $category_url; ?>" class="text-muted text-decoration-none">
                        <?php echo ucfirst(str_replace('-', ' ', $product_category)); ?>
                    </a>
                    <?php if (!empty($product_brand)): ?>
                    <span class="mx-1">•</span>
                    <a href="<?php echo base_url('product/brand/' . urlencode($product_brand)); ?>" class="text-muted text-decoration-none">
                        <?php echo htmlspecialchars($product_brand); ?>
                    </a>
                    <?php endif; ?>
                </small>
            </div>
            <?php endif; ?>
            
            <!-- Product Name -->
            <h5 class="card-title product-name mb-2">
                <a href="<?php echo $product_url; ?>" class="text-dark text-decoration-none product-name-link">
                    <?php echo htmlspecialchars(truncate_text($product_name, 50)); ?>
                </a>
            </h5>
            
            <!-- Weight/Unit -->
            <?php if (!empty($product_weight) && !empty($product_unit)): ?>
            <div class="product-weight-info mb-2">
                <span class="badge badge-light weight-badge">
                    <i class="fas fa-weight mr-1"></i>
                    <?php echo $product_weight . ' ' . $product_unit; ?>
                </span>
                <!-- Storage type indicator -->
                <?php if (!empty($storage_type)): ?>
                <span class="badge badge-outline-secondary storage-badge ml-1">
                    <?php 
                    switch($storage_type) {
                        case 'refrigerated':
                            echo '<i class="fas fa-snowflake"></i> Refrigerated';
                            break;
                        case 'frozen':
                            echo '<i class="fas fa-icicles"></i> Frozen';
                            break;
                        default:
                            echo '<i class="fas fa-archive"></i> Dry Storage';
                    }
                    ?>
                </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Enhanced Pricing Section -->
            <div class="product-pricing mb-3">
                <?php if ($price_info['has_discount']): ?>
                <div class="price-with-discount">
                    <div class="d-flex align-items-center mb-1">
                        <span class="current-price text-primary font-weight-bold h5 mb-0">
                            <?php echo format_price_local($price_info['final_price']); ?>
                        </span>
                        <span class="original-price text-muted ml-2">
                            <s><?php echo format_price_local($price_info['original_price']); ?></s>
                        </span>
                    </div>
                    <small class="text-success font-weight-bold d-block">
                        <i class="fas fa-tag mr-1"></i>Save <?php echo format_price_local($price_info['discount_amount']); ?>
                    </small>
                </div>
                <?php else: ?>
                <span class="current-price text-primary font-weight-bold h5 mb-0">
                    <?php echo format_price_local($product_price); ?>
                </span>
                <?php endif; ?>
                
                <!-- Price per unit calculation -->
                <?php if (!empty($product_weight) && !empty($product_unit) && $product_weight > 0): ?>
                <div class="price-per-unit mt-1">
                    <small class="text-muted d-block">
                        <i class="fas fa-calculator mr-1"></i>
                        <?php echo format_price_local($price_info['final_price'] / $product_weight); ?> per <?php echo $product_unit; ?>
                    </small>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Enhanced Stock Status -->
            <div class="stock-status mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="<?php echo $stock_status['css_class']; ?> font-weight-bold">
                        <i class="fas fa-circle mr-1"></i> <?php echo $stock_status['message']; ?>
                    </small>
                    <?php if (!empty($expiry_date) && $stock_status['available']): ?>
                    <small class="text-muted">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        <?php echo date('M j', strtotime($expiry_date)); ?>
                    </small>
                    <?php endif; ?>
                </div>
                
                <!-- Stock quantity indicator -->
                <?php if ($stock_status['available'] && $stock_quantity <= 10): ?>
                <div class="stock-bar mt-1">
                    <div class="progress" style="height: 3px;">
                        <div class="progress-bar <?php echo $stock_quantity <= 3 ? 'bg-danger' : 'bg-warning'; ?>" 
                             style="width: <?php echo min(($stock_quantity / 10) * 100, 100); ?>%"></div>
                    </div>
                    <small class="text-muted">Only <?php echo $stock_quantity; ?> left</small>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Enhanced Action Buttons -->
            <div class="card-actions mt-auto">
                <?php if ($stock_status['available']): ?>
                <div class="action-buttons">
                    <!-- Quick quantity selector -->
                    <div class="quantity-selector mb-2">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary quantity-decrease" 
                                        type="button" data-target="quantity-<?php echo $product_id; ?>">-</button>
                            </div>
                            <input type="number" class="form-control text-center quantity-input" 
                                   value="1" min="1" max="<?php echo $stock_quantity; ?>"
                                   id="quantity-<?php echo $product_id; ?>"
                                   data-product-id="<?php echo $product_id; ?>">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary quantity-increase" 
                                        type="button" data-target="quantity-<?php echo $product_id; ?>">+</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add to cart button -->
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm flex-fill add-to-cart-btn" 
                                data-product-id="<?php echo $product_id; ?>"
                                data-product-name="<?php echo htmlspecialchars($product_name); ?>"
                                data-product-price="<?php echo $price_info['final_price']; ?>">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                        
                        <!-- Quick buy option -->
                        <button type="button" class="btn btn-success btn-sm quick-buy-btn" 
                                data-product-id="<?php echo $product_id; ?>"
                                title="Quick Buy">
                            <i class="fas fa-bolt"></i>
                        </button>
                    </div>
                </div>
                <?php else: ?>
                <div class="out-of-stock-actions">
                    <button type="button" class="btn btn-outline-secondary btn-sm w-100 mb-2" disabled>
                        <i class="fas fa-times"></i> Currently Unavailable
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm w-100 notify-when-available" 
                            data-product-id="<?php echo $product_id; ?>"
                            data-product-name="<?php echo htmlspecialchars($product_name); ?>">
                        <i class="fas fa-bell"></i> Notify When Available
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Grocery Product Card Styles */
.grocery-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
}

.grocery-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    border-color: #007bff;
}

.product-image-container {
    position: relative;
    overflow: hidden;
    border-radius: 12px 12px 0 0;
}

.product-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.grocery-card:hover .product-image {
    transform: scale(1.08);
}

/* Enhanced Badge System */
.product-badges {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.product-badges .badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.discount-badge {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.organic-badge {
    background: linear-gradient(135deg, #28a745, #34ce57);
    color: white;
}

.fresh-badge {
    background: linear-gradient(135deg, #17a2b8, #20c997);
    color: white;
}

.stock-badge {
    font-size: 0.7rem;
}

.expiry-badge {
    font-size: 0.7rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

/* Enhanced Hover Overlay */
.product-hover-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0,123,255,0.9), rgba(40,167,69,0.9));
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    backdrop-filter: blur(2px);
}

.grocery-card:hover .product-hover-overlay {
    opacity: 1;
}

.product-hover-buttons {
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: center;
}

.product-hover-buttons .btn {
    min-width: 140px;
    font-weight: 600;
    border-radius: 25px;
    transition: all 0.2s ease;
}

.product-hover-buttons .btn:hover {
    transform: scale(1.05);
}

/* Enhanced Card Body */
.grocery-card .card-body {
    padding: 1.25rem;
}

.product-name-link {
    transition: color 0.2s ease;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
}

.product-name-link:hover {
    color: #007bff !important;
}

/* Weight and Storage Badges */
.weight-badge {
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid #dee2e6;
    color: #495057;
}

.storage-badge {
    font-size: 0.7rem;
    border: 1px solid #6c757d;
    color: #6c757d;
    background: transparent;
}

/* Enhanced Pricing */
.price-with-discount {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 8px;
    padding: 8px;
    margin: -4px -4px 8px -4px;
}

.current-price {
    font-size: 1.25rem;
    font-weight: 700;
}

.original-price {
    font-size: 0.95rem;
}

.price-per-unit {
    background: #f8f9fa;
    border-radius: 4px;
    padding: 2px 6px;
    display: inline-block;
}

/* Stock Status Enhancements */
.stock-status {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 8px;
}

.stock-bar .progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.stock-bar .progress-bar {
    border-radius: 10px;
    transition: width 0.3s ease;
}

/* Enhanced Action Buttons */
.card-actions {
    background: #f8f9fa;
    margin: -1.25rem -1.25rem -1.25rem;
    padding: 1rem 1.25rem;
    border-radius: 0 0 12px 12px;
}

.quantity-selector .input-group {
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.quantity-selector .btn {
    border: none;
    background: #e9ecef;
    color: #495057;
    font-weight: 700;
    transition: all 0.2s ease;
}

.quantity-selector .btn:hover {
    background: #dee2e6;
    transform: scale(1.1);
}

.quantity-selector .form-control {
    border: none;
    background: white;
    font-weight: 600;
}

.add-to-cart-btn {
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.2s ease;
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
}

.add-to-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.3);
}

.quick-buy-btn {
    border-radius: 50%;
    width: 38px;
    height: 38px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    transition: all 0.2s ease;
}

.quick-buy-btn:hover {
    transform: rotate(15deg) scale(1.1);
    box-shadow: 0 4px 8px rgba(40,167,69,0.3);
}

.notify-when-available {
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.notify-when-available:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,123,255,0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
    .product-image {
        height: 180px;
    }
    
    .grocery-card .card-body {
        padding: 1rem;
    }
    
    .card-actions {
        margin: -1rem -1rem -1rem;
        padding: 0.75rem 1rem;
    }
    
    .product-hover-buttons .btn {
        min-width: 120px;
        font-size: 0.85rem;
    }
}

@media (max-width: 576px) {
    .product-image {
        height: 160px;
    }
    
    .current-price {
        font-size: 1.1rem;
    }
    
    .quick-buy-btn {
        width: 32px;
        height: 32px;
    }
}

/* Animation for loading states */
.btn.loading {
    position: relative;
    color: transparent;
}

.btn.loading::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>

<script>
// Enhanced Product Card JavaScript Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Quantity selector functionality
    document.querySelectorAll('.quantity-decrease').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            const currentValue = parseInt(target.value);
            if (currentValue > 1) {
                target.value = currentValue - 1;
                target.dispatchEvent(new Event('change'));
            }
        });
    });
    
    document.querySelectorAll('.quantity-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            const currentValue = parseInt(target.value);
            const maxValue = parseInt(target.getAttribute('max'));
            if (currentValue < maxValue) {
                target.value = currentValue + 1;
                target.dispatchEvent(new Event('change'));
            }
        });
    });
    
    // Enhanced Add to Cart functionality
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const quantityInput = document.getElementById('quantity-' + productId);
            const quantity = quantityInput ? quantityInput.value : 1;
            
            // Add loading state
            this.classList.add('loading');
            this.disabled = true;
            
            // Simulate add to cart (replace with actual AJAX call)
            setTimeout(() => {
                this.classList.remove('loading');
                this.disabled = false;
                
                // Show success feedback
                this.innerHTML = '<i class="fas fa-check"></i> Added!';
                this.classList.remove('btn-primary');
                this.classList.add('btn-success');
                
                // Reset after 2 seconds
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-cart-plus"></i> Add to Cart';
                    this.classList.remove('btn-success');
                    this.classList.add('btn-primary');
                }, 2000);
                
                // You would typically make an AJAX call here
                console.log(`Added product ${productId} (${productName}) with quantity ${quantity} to cart`);
            }, 800);
        });
    });
    
    // Quick Buy functionality
    document.querySelectorAll('.quick-buy-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            // Redirect to quick checkout or open modal
            console.log(`Quick buy for product ${productId}`);
            // window.location.href = `<?php echo base_url('checkout/quick/'); ?>${productId}`;
        });
    });
    
    // Notify when available functionality
    document.querySelectorAll('.notify-when-available').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            
            // In a real implementation, you'd open a modal to collect email
            const email = prompt(`Enter your email to be notified when "${productName}" is back in stock:`);
            if (email) {
                // Make AJAX call to subscribe
                console.log(`Subscribed ${email} for notifications about product ${productId}`);
                alert('Thank you! We\'ll notify you when this product is available.');
            }
        });
    });
    
    // Add to wishlist functionality
    document.querySelectorAll('.add-to-wishlist').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const icon = this.querySelector('i');
            
            // Toggle wishlist state
            if (icon.classList.contains('fas')) {
                icon.classList.remove('fas');
                icon.classList.add('far');
                console.log(`Removed product ${productId} from wishlist`);
            } else {
                icon.classList.remove('far');
                icon.classList.add('fas');
                console.log(`Added product ${productId} to wishlist`);
            }
        });
    });
});
</script>
