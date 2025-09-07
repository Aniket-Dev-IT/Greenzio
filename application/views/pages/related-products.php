<?php if (!empty($relatedProducts)): ?>
<section class="mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="workFont text-center mb-4">Related Products</h3>
            </div>
        </div>
        
        <div class="row">
            <?php foreach (array_slice($relatedProducts, 0, 4) as $relatedProduct): ?>
            <?php if ($relatedProduct['pid'] != $productDetail['productDetail'][0]['pid']): // Don't show the current product ?>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-img-top position-relative">
                        <a href="<?php echo base_url() . 'product/index/' . $relatedProduct['pid']; ?>">
                            <img src="<?php echo base_url() . 'assets/img/' . $relatedProduct['pimage']; ?>" 
                                 class="img-fluid" alt="<?php echo $relatedProduct['pname']; ?>">
                        </a>
                        
                        <!-- Discount badge -->
                        <?php if ($relatedProduct['discount'] > 0): ?>
                        <span class="badge badge-success position-absolute" style="top: 10px; right: 10px;">
                            <?php echo $relatedProduct['discount']; ?>% OFF
                        </span>
                        <?php endif; ?>
                        
                        <!-- Stock status -->
                        <?php if ($relatedProduct['stock_quantity'] <= 0): ?>
                        <span class="badge badge-danger position-absolute" style="top: 10px; left: 10px;">
                            Out of Stock
                        </span>
                        <?php elseif ($relatedProduct['stock_quantity'] < 10): ?>
                        <span class="badge badge-warning position-absolute" style="top: 10px; left: 10px;">
                            Low Stock
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <!-- Brand -->
                        <?php if (!empty($relatedProduct['brand'])): ?>
                        <small class="text-muted mb-1"><?php echo $relatedProduct['brand']; ?></small>
                        <?php endif; ?>
                        
                        <!-- Product Name -->
                        <h6 class="card-title">
                            <a href="<?php echo base_url() . 'product/index/' . $relatedProduct['pid']; ?>" 
                               class="text-dark text-decoration-none">
                                <?php echo $relatedProduct['pname']; ?>
                            </a>
                        </h6>
                        
                        <!-- Weight/Unit -->
                        <small class="text-info mb-2"><?php echo $relatedProduct['weight'] . ' ' . $relatedProduct['unit']; ?></small>
                        
                        <!-- Price -->
                        <div class="mt-auto">
                            <?php 
                            $originalPrice = $relatedProduct['price'];
                            $finalPrice = $relatedProduct['discount'] > 0 ? 
                                $originalPrice * (1 - $relatedProduct['discount'] / 100) : $originalPrice;
                            ?>
                            
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="h6 text-primary">₹<?php echo number_format($finalPrice, 2); ?></span>
                                    <?php if ($relatedProduct['discount'] > 0): ?>
                                    <br><small class="text-muted"><s>₹<?php echo number_format($originalPrice, 2); ?></s></small>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Quick Add to Cart Button -->
                                <?php if ($relatedProduct['stock_quantity'] > 0): ?>
                                <button class="btn btn-sm btn-outline-primary quick-add-btn" 
                                        data-pid="<?php echo $relatedProduct['pid']; ?>"
                                        data-price="<?php echo $finalPrice; ?>"
                                        title="Quick Add to Cart">
                                    <i class="fa fa-cart-plus"></i>
                                </button>
                                <?php else: ?>
                                <button class="btn btn-sm btn-outline-secondary" disabled title="Out of Stock">
                                    <i class="fa fa-times"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        
        <!-- View More Button -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="<?php echo base_url() . 'product/category/' . urlencode($productDetail['productDetail'][0]['category']); ?>" 
                   class="btn btn-outline-primary">
                    View More from <?php echo ucfirst($productDetail['productDetail'][0]['category']); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<script>
// Quick add to cart functionality for related products
$(document).on('click', '.quick-add-btn', function() {
    const btn = $(this);
    const pid = btn.data('pid');
    const price = btn.data('price');
    
    // Show loading state
    const originalHtml = btn.html();
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    
    $.ajax({
        url: '<?php echo base_url("shopping/checkCart"); ?>',
        type: 'POST',
        data: {
            pid: pid,
            price: price,
            quantity: 1
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                btn.html('<i class="fa fa-check"></i>').removeClass('btn-outline-primary').addClass('btn-success');
                updateCartCount();
                
                // Reset button after 2 seconds
                setTimeout(function() {
                    btn.html(originalHtml).removeClass('btn-success').addClass('btn-outline-primary');
                }, 2000);
            } else {
                alert(response.message || 'Failed to add to cart');
            }
        },
        error: function() {
            alert('Error adding product to cart');
        },
        complete: function() {
            btn.prop('disabled', false);
        }
    });
});
</script>

<?php else: ?>
<!-- If no related products found -->
<section class="mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <p class="text-muted">No related products found.</p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
