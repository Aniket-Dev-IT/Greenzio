<?php
// Get cart data and calculate totals
$total = 0;  
$itemCount = 0;
$hasPerishableItems = false;
$hasFragileItems = false;
$fragileItems = ['eggs', 'bread', 'cake', 'pastry', 'chips', 'biscuit'];
$perishableItems = ['milk', 'yogurt', 'cheese', 'meat', 'fish', 'vegetable', 'fruit', 'dairy'];

$data = $this->session->userdata();

if(!empty($productDetail)){
    foreach($productDetail as $product) {
        $itemCount++;
        
        // Check for fragile and perishable items
        foreach($fragileItems as $fragileItem) {
            if(stripos($product['pname'], $fragileItem) !== false || 
               stripos($product['category'], $fragileItem) !== false) {
                $hasFragileItems = true;
                break;
            }
        }
        
        foreach($perishableItems as $perishableItem) {
            if(stripos($product['pname'], $perishableItem) !== false || 
               stripos($product['category'], $perishableItem) !== false) {
                $hasPerishableItems = true;
                break;
            }
        }
    }
}

// Calculate subtotal, savings, and totals
$subtotal = 0;
$totalSavings = 0;
$minimumOrderAmount = 200;

if(!empty($productDetail)) {
    foreach($productDetail as $product) {
        $itemTotal = $product['price'];
        $subtotal += $itemTotal;
        
        // Calculate potential savings based on bulk discounts
        try {
            $originalPrice = $this->cart->calculateWeightBasedPrice($product['pid'], $product['quantity']);
            $actualPrice = $product['price'];
            if($originalPrice && $originalPrice > $actualPrice) {
                $totalSavings += ($originalPrice - $actualPrice);
            }
        } catch (Exception $e) {
            // Skip savings calculation if there's an error
            log_message('error', 'Cart savings calculation error: ' . $e->getMessage());
        }
    }
}

$isMinimumMet = $subtotal >= $minimumOrderAmount;
?>

<section class="mt-8 mb-5">
    <div class="container">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item active">Shopping Cart</li>
        </ol>
    </div>

    <div class="text-center">
        <h1 class="display-4 letter-spacing-5 text-uppercase font-weight-bold">Shopping Cart</h1>
    </div>
</section>

<!-- Cart Status and Alerts -->
<?php if ($this->session->flashdata('cart_success')): ?>
    <div class="container">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('cart_success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('cart_error')): ?>
    <div class="container">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('cart_error'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
<?php endif; ?>

<div class="container">
    <div class="row">
        <div class="col-xl-8 offset-xl-2 text-center mb-5">
            <p class="lead text-muted">
                You have <strong><?php echo $itemCount; ?></strong> items in your shopping cart
            </p>
        </div>
    </div>
</div>

<section>
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8">
                <div class="cart">
                    <div class="cart-header text-center bg-light p-3 rounded">
                        <div class="row font-weight-bold">
                            <div class="col-md-5">Item Details</div>
                            <div class="col-md-7 d-md-block d-none">
                                <div class="row">
                                    <div class="col-md-2">Price/Unit</div>
                                    <div class="col-md-3">Quantity</div>
                                    <div class="col-md-2">Weight</div>
                                    <div class="col-md-3">Total</div>
                                    <div class="col-md-2">Action</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Body - Products -->
                    <?php if(!empty($productDetail)): ?>
                        <?php foreach ($productDetail as $product): 
                            // Check if item is fragile
                            $isFragile = false;
                            foreach($fragileItems as $fragileItem) {
                                if(stripos($product['pname'], $fragileItem) !== false || 
                                   stripos($product['category'], $fragileItem) !== false) {
                                    $isFragile = true;
                                    break;
                                }
                            }
                            
                            // Check if item is perishable
                            $isPerishable = false;
                            foreach($perishableItems as $perishableItem) {
                                if(stripos($product['pname'], $perishableItem) !== false || 
                                   stripos($product['category'], $perishableItem) !== false) {
                                    $isPerishable = true;
                                    break;
                                }
                            }
                            
                            // Calculate per unit price
                            $perUnitPrice = $product['price'] / $product['quantity'];
                        ?>
                        <div class="cart-body border-bottom">
                            <div class="cart-item py-3">
                                <div class="row d-flex align-items-center text-left text-md-center">
                                    <div class="col-12 col-md-5">
                                        <a href="<?php echo base_url().'shopping/deleteItem?id='.$product['pid'];?>" 
                                           class="cart-remove close mt-3 d-md-none" 
                                           onclick="return confirm('Remove this item from cart?')">
                                            <i class="fa fa-times"></i>
                                        </a>
                                        <div class="d-flex align-items-center">
                                            <a href="<?php echo base_url().'product/index/'.$product['pid'];?>">
                                                <img src="<?php echo base_url() . $product['pimage']; ?>" 
                                                     class="cart-item-img" 
                                                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; background-color: #f8f9fa;" 
                                                     alt="<?php echo htmlspecialchars($product['pname']); ?>" 
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="cart-item-placeholder" style="width: 80px; height: 80px; background-color: #e9ecef; border-radius: 4px; display: none; align-items: center; justify-content: center; color: #6c757d; font-size: 12px; text-align: center; flex-direction: column;">
                                                    <i class="fas fa-image mb-1"></i>
                                                    <span>No Image</span>
                                                </div>
                                            </a>
                                            <div class="cart-title text-left ml-3">
                                                <a href="<?php echo base_url().'product/index/'.$product['pid'];?>" 
                                                   class="text-uppercase text-dark">
                                                    <strong><?php echo ucfirst($product['category']) . " " . $product['pname']; ?></strong>
                                                </a>
                                                <br>
                                                <span class="text-muted text-sm">
                                                    <?php echo $product['weight'] . ' ' . ucfirst($product['unit']); ?>
                                                </span>
                                                <?php if (!empty($product['brand'])): ?>
                                                    <br><span class="text-muted text-sm">Brand: <?php echo $product['brand']; ?></span>
                                                <?php endif; ?>
                                                
                                                <!-- Special handling notes for fragile items -->
                                                <?php if($isFragile): ?>
                                                    <br><span class="badge badge-warning text-xs mt-1">
                                                        <i class="fas fa-exclamation-triangle"></i> Fragile - Handle with Care
                                                    </span>
                                                <?php endif; ?>
                                                
                                                <?php if($isPerishable): ?>
                                                    <br><span class="badge badge-info text-xs mt-1">
                                                        <i class="fas fa-leaf"></i> Perishable Item
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-7 mt-4 mt-md-0">
                                        <div class="row align-items-center">
                                            <!-- Price per unit -->
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-6 d-md-none text-muted">Price per unit</div>
                                                    <div class="col-6 col-md-12 text-right text-md-center">
                                                        <small class="text-muted">Per <?php echo $product['unit']; ?>:</small><br>
                                                        <strong>₹<?php echo number_format($perUnitPrice, 2); ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Quantity -->
                                            <div class="col-md-3">
                                                <div class="row align-items-center">
                                                    <div class="d-md-none col-7 col-sm-9 text-muted">Quantity</div>
                                                    <div class="col-5 col-sm-3 col-md-12">
                                                        <div class="d-flex align-items-center justify-content-center mb-1">
                                                            <button class="btn btn-sm btn-outline-secondary px-2 py-1" 
                                                                    style="min-width: 30px; font-size: 14px;" 
                                                                    onclick="updateQuantity(<?php echo $product['pid']; ?>, -1)">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" 
                                                                   value="<?php echo $product['quantity']; ?>" 
                                                                   min="1" 
                                                                   max="<?php echo $product['stock_quantity']; ?>" 
                                                                   class="form-control text-center mx-1" 
                                                                   style="width: 60px; height: 32px; font-size: 14px; padding: 4px;" 
                                                                   id="quantity_<?php echo $product['pid']; ?>" 
                                                                   onchange="updateQuantityDirect(<?php echo $product['pid']; ?>, this.value)">
                                                            <button class="btn btn-sm btn-outline-secondary px-2 py-1" 
                                                                    style="min-width: 30px; font-size: 14px;" 
                                                                    onclick="updateQuantity(<?php echo $product['pid']; ?>, 1)">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <?php if(isset($product['stock_quantity']) && $product['stock_quantity'] <= 5): ?>
                                                            <small class="text-warning d-block text-center">Only <?php echo $product['stock_quantity']; ?> left!</small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Weight -->
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <div class="col-6 d-md-none text-muted">Total Weight</div>
                                                    <div class="col-6 col-md-12 text-right text-md-center">
                                                        <strong>
                                                            <?php 
                                                            $totalWeight = $product['weight'] * $product['quantity'];
                                                            echo $totalWeight . ' ' . $product['unit']; 
                                                            ?>
                                                        </strong>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Total Price -->
                                            <div class="col-md-3">
                                                <div class="row">
                                                    <div class="col-6 d-md-none text-muted">Total price</div>
                                                    <div class="col-6 col-md-12 text-right text-md-center">
                                                        <strong class="text-success">₹<?php echo number_format($product['price'], 2); ?></strong>
                                                        <?php 
                                                        // Show savings if applicable
                                                        try {
                                                            $originalPrice = $this->cart->calculateWeightBasedPrice($product['pid'], $product['quantity']);
                                                            if($originalPrice && $originalPrice > $product['price']): 
                                                                $savings = $originalPrice - $product['price'];
                                                        ?>
                                                                <br><small class="text-success">Save ₹<?php echo number_format($savings, 2); ?></small>
                                                        <?php 
                                                            endif;
                                                        } catch (Exception $e) {
                                                            // Skip showing savings if calculation fails
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Remove Button -->
                                            <div class="col-md-2 d-none d-md-block text-center">
                                                <a href="<?php echo base_url().'shopping/deleteItem?id='.$product['pid'];?>" 
                                                   class="cart-remove btn btn-sm btn-outline-danger" 
                                                   onclick="return confirm('Remove this item from cart?')">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-5 text-center">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h3 class="text-center workFont">Your cart is empty!</h3>
                            <p class="text-muted">Let's add some fresh groceries to your cart.</p>
                            <a href="<?php echo base_url(); ?>" class="btn btn-primary mt-3">
                                <i class="fas fa-arrow-left"></i> Start Shopping
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Special Instructions for Fragile Items -->
                <?php if($hasFragileItems): ?>
                    <div class="alert alert-warning mt-4">
                        <h6><i class="fas fa-exclamation-triangle"></i> Special Handling Required</h6>
                        <p class="mb-0">Your cart contains fragile items (eggs, bread, etc.). Our delivery team will handle these with extra care to ensure they reach you in perfect condition.</p>
                    </div>
                <?php endif; ?>

                <!-- Delivery Time Slot Selection for Perishable Items -->
                <?php if($hasPerishableItems): ?>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-leaf"></i> Perishable Items - Select Delivery Preference</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">Your cart contains perishable items. Please select your preferred delivery time to ensure freshness.</p>
                            <div class="form-group">
                                <label for="preferred_delivery_time">Preferred Delivery Time:</label>
                                <select class="form-control" id="preferred_delivery_time" name="preferred_delivery_time">
                                    <option value="morning">Morning (9 AM - 12 PM) - Best for perishables</option>
                                    <option value="afternoon">Afternoon (12 PM - 4 PM)</option>
                                    <option value="evening">Evening (4 PM - 8 PM)</option>
                                </select>
                            </div>
                            <small class="text-info">
                                <i class="fas fa-info-circle"></i> Morning delivery is recommended for maximum freshness of dairy and produce items.
                            </small>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Minimum Order Warning -->
                <?php if(!$isMinimumMet && $subtotal > 0): ?>
                    <div class="alert alert-warning mt-4">
                        <h6><i class="fas fa-shopping-basket"></i> Minimum Order Amount</h6>
                        <p class="mb-0">
                            Add <strong>₹<?php echo number_format($minimumOrderAmount - $subtotal, 2); ?></strong> more to reach the minimum order amount of ₹<?php echo $minimumOrderAmount; ?> for delivery.
                        </p>
                        <div class="progress mt-2">
                            <div class="progress-bar" style="width: <?php echo ($subtotal/$minimumOrderAmount)*100; ?>%"></div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Cart Actions -->
                <?php if(!empty($productDetail)): ?>
                    <div class="my-5 d-flex justify-content-between flex-column flex-lg-row">
                        <a href="<?php echo base_url();?>" class="btn btn-link text-muted mb-2 mb-lg-0">
                            <i class="fas fa-arrow-left"></i> Continue Shopping
                        </a>
                        <div>
                            <button type="button" class="btn btn-outline-secondary mr-2" onclick="clearCart()">
                                <i class="fas fa-trash"></i> Clear Cart
                            </button>
                            <a href="#" 
                               class="btn btn-outline-dark"
                               <?php if(isset($data['userID'])): ?>
                                   onclick="return proceedToCheckout()"
                               <?php else: ?>
                                   data-toggle="modal" data-target="#logModal"
                               <?php endif; ?>
                               id="proceedToCheckOut">
                                Proceed to Checkout <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="block mb-5">
                    <div class="block-header">
                        <h6 class="text-uppercase mb-0">Order Summary</h6>
                    </div>
                    <div class="block-body bg-light pt-1">
                        <p class="text-sm">Shipping and additional costs are calculated based on your location and order value.</p>
                        
                        <ul class="order-summary mb-0 list-unstyled">
                            <li class="order-summary-item d-flex justify-content-between">
                                <span>Subtotal (<?php echo $itemCount; ?> items)</span>
                                <span>₹<?php echo number_format($subtotal, 2); ?></span>
                            </li>
                            
                            <?php if($totalSavings > 0): ?>
                                <li class="order-summary-item d-flex justify-content-between text-success">
                                    <span><i class="fas fa-tag"></i> Your Savings</span>
                                    <span>-₹<?php echo number_format($totalSavings, 2); ?></span>
                                </li>
                            <?php endif; ?>
                            
                            <li class="order-summary-item d-flex justify-content-between">
                                <span>Delivery Charges</span>
                                <span>
                                    <?php 
                                    $deliveryCharges = ($subtotal > 0 && $subtotal < 500) ? 50 : 0;
                                    if($deliveryCharges > 0) {
                                        echo '₹' . number_format($deliveryCharges, 2);
                                    } else {
                                        echo '<span class="text-success">FREE</span>';
                                    }
                                    ?>
                                </span>
                            </li>
                            
                            <li class="order-summary-item d-flex justify-content-between">
                                <span>Tax</span>
                                <span>₹0.00</span>
                            </li>
                            
                            <li class="order-summary-item border-0 d-flex justify-content-between">
                                <span class="font-weight-bold">Total</span>
                                <strong class="order-summary-total">₹<?php echo number_format($subtotal + $deliveryCharges, 2); ?></strong>
                            </li>
                        </ul>
                        
                        <?php if (!$isMinimumMet && $subtotal > 0): ?>
                            <div class="alert alert-warning mt-3">
                                <small>
                                    <i class="fas fa-info-circle"></i> 
                                    Add ₹<?php echo number_format($minimumOrderAmount - $subtotal, 2); ?> more for delivery
                                </small>
                            </div>
                        <?php elseif ($isMinimumMet && $subtotal > 0): ?>
                            <div class="alert alert-success mt-3">
                                <small>
                                    <i class="fas fa-check-circle"></i> 
                                    Great! Free delivery qualified
                                </small>
                            </div>
                        <?php endif; ?>

                        <!-- Benefits Information -->
                        <div class="mt-3 p-3 bg-white rounded">
                            <h6 class="text-uppercase text-xs font-weight-bold mb-2">Why Shop With Us?</h6>
                            <ul class="list-unstyled mb-0 text-xs">
                                <li class="mb-1"><i class="fas fa-check text-success"></i> Fresh guarantee on all produce</li>
                                <li class="mb-1"><i class="fas fa-check text-success"></i> Same day delivery available</li>
                                <li class="mb-1"><i class="fas fa-check text-success"></i> Easy returns & replacements</li>
                                <li class="mb-1"><i class="fas fa-check text-success"></i> Special handling for fragile items</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function updateQuantity(pid, change) {
    const quantityInput = document.getElementById('quantity_' + pid);
    let currentQuantity = parseInt(quantityInput.value);
    let newQuantity = currentQuantity + change;
    
    if (newQuantity < 1) {
        newQuantity = 1;
    }
    
    if (newQuantity > parseInt(quantityInput.max)) {
        alert('Cannot exceed available stock of ' + quantityInput.max + ' units');
        return;
    }
    
    quantityInput.value = newQuantity;
    updateQuantityDirect(pid, newQuantity);
}

function updateQuantityDirect(pid, quantity) {
    if (quantity < 1) {
        alert('Quantity must be at least 1');
        document.getElementById('quantity_' + pid).value = 1;
        return;
    }
    
    const formData = new FormData();
    formData.append('pid', pid);
    formData.append('quantity', quantity);
    
    fetch('<?php echo base_url('shopping/updateQuantity'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload to update all calculations
        } else {
            alert(data.message);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating quantity. Please try again.');
    });
}

function proceedToCheckout() {
    // Validate minimum order amount
    const subtotal = <?php echo $subtotal; ?>;
    const minimumOrderAmount = <?php echo $minimumOrderAmount; ?>;
    
    if (subtotal < minimumOrderAmount) {
        alert('Minimum order amount is ₹' + minimumOrderAmount + '. Please add ₹' + (minimumOrderAmount - subtotal) + ' more to proceed.');
        return false;
    }
    
    // Store delivery preferences if perishable items exist
    <?php if($hasPerishableItems): ?>
        const deliveryTime = document.getElementById('preferred_delivery_time').value;
        sessionStorage.setItem('preferred_delivery_time', deliveryTime);
    <?php endif; ?>
    
    // Store cart requirements for delivery suggestions
    const cartRequirements = {
        hasFragileItems: <?php echo $hasFragileItems ? 'true' : 'false'; ?>,
        hasPerishableItems: <?php echo $hasPerishableItems ? 'true' : 'false'; ?>,
        itemCount: <?php echo $itemCount; ?>,
        subtotal: <?php echo $subtotal; ?>
    };
    sessionStorage.setItem('cart_requirements', JSON.stringify(cartRequirements));
    
    // Proceed to checkout
    window.location.href = '<?php echo base_url('shopping/getAddress'); ?>';
    return false;
}

function clearCart() {
    if (confirm('Are you sure you want to clear your entire cart?')) {
        // You can implement a clear cart method in the controller
        window.location.href = '<?php echo base_url('shopping/clearCart'); ?>';
    }
}
</script>
