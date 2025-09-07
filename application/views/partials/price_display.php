<?php
/**
 * Price Display Partial
 * Reusable price display component
 * 
 * Required variables:
 * - $price: Original price
 * - $discount: Discount percentage (optional)
 * - $weight: Product weight (optional)
 * - $unit: Product unit (optional)
 * - $size: Display size - 'sm', 'md', 'lg' (optional)
 */

// Set defaults
$discount = isset($discount) ? (float)$discount : 0;
$weight = isset($weight) ? (float)$weight : 0;
$unit = isset($unit) ? $unit : '';
$size = isset($size) ? $size : 'md';

// Helper functions (ensure they're loaded)
$this->load->helper(['common']);

// Calculate pricing
$price_info = calculate_discount_price($price, $discount);

// Size-specific classes
$size_classes = [
    'sm' => ['price' => 'h6', 'original' => 'small', 'savings' => 'small'],
    'md' => ['price' => 'h5', 'original' => '', 'savings' => ''],
    'lg' => ['price' => 'h4', 'original' => 'h6', 'savings' => 'h6']
];

$classes = $size_classes[$size] ?? $size_classes['md'];
?>

<div class="price-display">
    <!-- Main Price -->
    <div class="current-price-container d-flex align-items-center mb-1">
        <span class="current-price text-primary font-weight-bold <?php echo $classes['price']; ?> mb-0">
            <?php echo format_price($price_info['final_price']); ?>
        </span>
        
        <?php if ($price_info['has_discount']): ?>
        <span class="original-price text-muted ml-2 <?php echo $classes['original']; ?>">
            <s><?php echo format_price($price_info['original_price']); ?></s>
        </span>
        <?php endif; ?>
    </div>
    
    <!-- Discount Information -->
    <?php if ($price_info['has_discount']): ?>
    <div class="discount-info mb-2">
        <div class="d-flex flex-wrap gap-2">
            <span class="badge badge-success">
                <?php echo $discount; ?>% OFF
            </span>
            <small class="text-success font-weight-bold <?php echo $classes['savings']; ?>">
                You Save <?php echo format_price($price_info['discount_amount']); ?>
            </small>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Price Per Unit -->
    <?php if ($weight > 0 && !empty($unit)): ?>
    <div class="price-per-unit">
        <small class="text-muted">
            <?php echo format_price($price_info['final_price'] / $weight); ?> per <?php echo $unit; ?>
            <?php if ($weight > 1): ?>
            • Total: <?php echo format_weight_unit($weight, $unit); ?>
            <?php endif; ?>
        </small>
    </div>
    <?php endif; ?>
    
    <!-- Tax Information -->
    <div class="tax-info mt-1">
        <small class="text-muted">
            <i class="fas fa-info-circle"></i> Inclusive of all taxes
        </small>
    </div>
    
    <!-- Price Breakdown (for larger sizes) -->
    <?php if ($size === 'lg' && $price_info['has_discount']): ?>
    <div class="price-breakdown mt-3 p-2 bg-light rounded">
        <small class="text-muted">
            <div class="d-flex justify-content-between">
                <span>MRP:</span>
                <span><?php echo format_price($price_info['original_price']); ?></span>
            </div>
            <div class="d-flex justify-content-between text-success">
                <span>Discount (<?php echo $discount; ?>%):</span>
                <span>-<?php echo format_price($price_info['discount_amount']); ?></span>
            </div>
            <div class="d-flex justify-content-between font-weight-bold border-top pt-1">
                <span>Final Price:</span>
                <span><?php echo format_price($price_info['final_price']); ?></span>
            </div>
        </small>
    </div>
    <?php endif; ?>
</div>

<style>
.price-display {
    line-height: 1.2;
}

.current-price {
    color: #28a745 !important;
}

.original-price {
    text-decoration: line-through;
    opacity: 0.7;
}

.discount-info .badge {
    font-size: 0.8em;
}

.price-breakdown {
    font-size: 0.9em;
}

.gap-2 > * + * {
    margin-left: 0.5rem;
}

@media (max-width: 576px) {
    .price-display .h4 {
        font-size: 1.25rem;
    }
    
    .price-display .h5 {
        font-size: 1.1rem;
    }
    
    .current-price-container {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .original-price {
        margin-left: 0 !important;
        margin-top: 0.25rem;
    }
}
</style>
