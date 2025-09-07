<style>
.category-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
    margin-bottom: 50px;
}

.category-title {
    font-size: 3rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.category-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-top: 15px;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

.product-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.product-image {
    width: 100%;
    height: 200px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-info {
    padding: 20px;
}

.product-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3436;
    margin-bottom: 8px;
    line-height: 1.3;
}

.product-brand {
    color: #636e72;
    font-size: 0.9rem;
    margin-bottom: 10px;
}

.product-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: #00b894;
    margin-bottom: 15px;
}

.product-price .original-price {
    color: #b2bec3;
    text-decoration: line-through;
    font-size: 0.9rem;
    margin-left: 8px;
}

.product-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    font-size: 0.9rem;
    color: #636e72;
}

.stock-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.stock-in { background: #d1f2eb; color: #00a085; }
.stock-low { background: #fdf2e9; color: #e17055; }
.stock-out { background: #ffeaa7; color: #fdcb6e; }

.add-to-cart-btn {
    width: 100%;
    background: linear-gradient(45deg, #00b894, #00a085);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.add-to-cart-btn:hover {
    background: linear-gradient(45deg, #00a085, #00b894);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,184,148,0.3);
}

.add-to-cart-btn:disabled {
    background: #ddd;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.filters-section {
    background: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: center;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-label {
    font-weight: 600;
    color: #2d3436;
    margin-bottom: 8px;
    display: block;
}

.filter-select {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: border-color 0.3s ease;
}

.filter-select:focus {
    outline: none;
    border-color: #00b894;
}

.no-products {
    text-align: center;
    padding: 80px 20px;
    color: #636e72;
}

.no-products i {
    font-size: 4rem;
    margin-bottom: 20px;
    color: #ddd;
}

.breadcrumb-custom {
    background: transparent;
    padding: 20px 0;
    margin-bottom: 0;
}

.breadcrumb-custom .breadcrumb-item a {
    color: #6c5ce7;
    text-decoration: none;
}

.breadcrumb-custom .breadcrumb-item.active {
    color: #2d3436;
    font-weight: 600;
}

@media (max-width: 768px) {
    .category-title {
        font-size: 2rem;
    }
    
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .filter-row {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-group {
        min-width: auto;
    }
}
</style>

<!-- Breadcrumb -->
<nav class="breadcrumb-custom">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
            <li class="breadcrumb-item active"><?= $category ?></li>
        </ol>
    </div>
</nav>

<!-- Category Header -->
<section class="category-header">
    <div class="container text-center">
        <h1 class="category-title"><?= $category ?></h1>
        <p class="category-subtitle">Fresh, quality products delivered to your doorstep</p>
        <div class="mt-4">
            <span class="badge badge-light px-3 py-2">
                <?= count($products) ?> Products Available
            </span>
        </div>
    </div>
</section>

<div class="container">
    
    <!-- Filters Section -->
    <?php if (!empty($subcategories)): ?>
    <section class="filters-section">
        <div class="filter-row">
            <div class="filter-group">
                <label class="filter-label">Filter by Subcategory:</label>
                <select class="filter-select" id="subcategoryFilter" onchange="filterBySubcategory()">
                    <option value="">All Subcategories</option>
                    <?php foreach ($subcategories as $subcat): ?>
                        <option value="<?= htmlspecialchars($subcat['subcategory']) ?>"><?= htmlspecialchars($subcat['subcategory']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Sort by:</label>
                <select class="filter-select" id="sortFilter" onchange="sortProducts()">
                    <option value="name">Product Name</option>
                    <option value="price_low">Price: Low to High</option>
                    <option value="price_high">Price: High to Low</option>
                    <option value="stock">Stock Level</option>
                </select>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Products Grid -->
    <?php if (!empty($products)): ?>
        <div class="product-grid" id="productsGrid">
            <?php foreach ($products as $product): ?>
                <?php
                $stockClass = 'stock-in';
                $stockText = 'In Stock';
                if ($product['stock_quantity'] <= 0) {
                    $stockClass = 'stock-out';
                    $stockText = 'Out of Stock';
                } elseif ($product['stock_quantity'] <= 5) {
                    $stockClass = 'stock-low';
                    $stockText = 'Low Stock';
                }
                
                $originalPrice = $product['price'];
                $discountedPrice = $product['price'];
                if ($product['discount'] > 0) {
                    $discountedPrice = $product['price'] * (1 - $product['discount'] / 100);
                }
                ?>
                
                <div class="product-card" 
                     data-name="<?= strtolower($product['pname']) ?>"
                     data-price="<?= $product['price'] ?>"
                     data-subcategory="<?= $product['subcategory'] ?>"
                     data-stock="<?= $product['stock_quantity'] ?>">
                    
                    <div class="product-image">
                        <?php if (!empty($product['pimage']) && file_exists($product['pimage'])): ?>
                            <img src="<?= base_url($product['pimage']) ?>" alt="<?= htmlspecialchars($product['pname']) ?>" loading="lazy">
                        <?php else: ?>
                            <div class="placeholder-image">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($product['discount'] > 0): ?>
                            <span class="discount-badge"><?= $product['discount'] ?>% OFF</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-info">
                        <h3 class="product-name"><?= htmlspecialchars($product['pname']) ?></h3>
                        
                        <?php if (!empty($product['brand'])): ?>
                            <div class="product-brand"><?= htmlspecialchars($product['brand']) ?></div>
                        <?php endif; ?>
                        
                        <div class="product-price">
                            ₹<?= number_format($discountedPrice, 2) ?>
                            <?php if ($product['discount'] > 0): ?>
                                <span class="original-price">₹<?= number_format($originalPrice, 2) ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-details">
                            <span>
                                <?php if (!empty($product['weight']) && !empty($product['unit'])): ?>
                                    <?= $product['weight'] ?> <?= $product['unit'] ?>
                                <?php endif; ?>
                            </span>
                            <span class="stock-badge <?= $stockClass ?>"><?= $stockText ?></span>
                        </div>
                        
                        <?php if ($product['stock_quantity'] > 0): ?>
                            <button class="add-to-cart-btn" onclick="addToCart(<?= $product['pid'] ?>, '<?= htmlspecialchars($product['pname']) ?>')">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        <?php else: ?>
                            <button class="add-to-cart-btn" disabled>
                                <i class="fas fa-ban"></i> Out of Stock
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-products">
            <i class="fas fa-box-open"></i>
            <h3>No Products Found</h3>
            <p>We're working to add more products to this category. Please check back soon!</p>
            <a href="<?= base_url() ?>" class="btn btn-primary mt-3">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
    <?php endif; ?>
    
</div>

<script>
// Filter by subcategory
function filterBySubcategory() {
    const filter = document.getElementById('subcategoryFilter').value.toLowerCase();
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        const subcategory = product.dataset.subcategory ? product.dataset.subcategory.toLowerCase() : '';
        
        if (filter === '' || subcategory.includes(filter)) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

// Sort products
function sortProducts() {
    const sortBy = document.getElementById('sortFilter').value;
    const grid = document.getElementById('productsGrid');
    const products = Array.from(grid.children);
    
    products.sort((a, b) => {
        switch (sortBy) {
            case 'name':
                return a.dataset.name.localeCompare(b.dataset.name);
            case 'price_low':
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            case 'price_high':
                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            case 'stock':
                return parseInt(b.dataset.stock) - parseInt(a.dataset.stock);
            default:
                return 0;
        }
    });
    
    // Re-append sorted products
    products.forEach(product => grid.appendChild(product));
}

// Add to cart function
function addToCart(productId, productName) {
    // Get the button that was clicked
    const button = event ? event.target : document.querySelector(`button[onclick*="${productId}"]`);
    
    if (!button) {
        console.error('Could not find add to cart button');
        showToast('❌ Error: Could not process request', 'error');
        return;
    }
    
    // Show loading state
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    button.disabled = true;
    
    console.log('Adding product to cart:', { productId, productName });
    
    fetch('<?= base_url("shopping/checkCart") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `pid=${productId}&quantity=1&price=0`
    })
    .then(response => {
        console.log('Response status:', response.status);
        
        // Check if response is ok
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response is not JSON');
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Server response:', data);
        
        if (data.success) {
            // Success feedback
            button.innerHTML = '<i class="fas fa-check"></i> Added!';
            button.style.background = '#00a085';
            
            // Update cart count if function exists
            if (typeof window.refreshCartCount === 'function') {
                window.refreshCartCount();
            }
            
            // Reset button after 2 seconds
            setTimeout(() => {
                button.innerHTML = originalContent;
                button.disabled = false;
                button.style.background = '';
            }, 2000);
            
            // Show success message
            showToast('✅ ' + productName + ' added to cart!', 'success');
        } else {
            // Error feedback
            button.innerHTML = '<i class="fas fa-exclamation"></i> Error';
            button.style.background = '#d63031';
            
            setTimeout(() => {
                button.innerHTML = originalContent;
                button.disabled = false;
                button.style.background = '';
            }, 2000);
            
            showToast('❌ ' + (data.message || 'Failed to add product'), 'error');
            
            // Log debug info if available
            if (data.debug && console.log) {
                console.log('Debug info:', data.debug);
            }
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        
        // Reset button state
        button.innerHTML = '<i class="fas fa-exclamation"></i> Error';
        button.style.background = '#d63031';
        
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.disabled = false;
            button.style.background = '';
        }, 2000);
        
        // Show appropriate error message
        if (error.message.includes('JSON')) {
            showToast('❌ Server response error. Please try again.', 'error');
        } else {
            showToast('❌ Network error. Please try again.', 'error');
        }
    });
}

// Simple toast notification
function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = message;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#00b894' : '#d63031'};
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        z-index: 1000;
        font-weight: 600;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    .discount-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #e17055;
        color: white;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .placeholder-image {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        background: #f8f9fa;
    }
`;
document.head.appendChild(style);
</script>
