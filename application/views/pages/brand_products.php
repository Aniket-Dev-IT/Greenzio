<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
.brand-header {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 3rem 0;
    margin-bottom: 2rem;
    text-align: center;
}

.brand-logo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    font-weight: 700;
    margin: 0 auto 1rem;
    border: 3px solid rgba(255,255,255,0.3);
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.product-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.product-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.product-card:hover .product-image img {
    transform: scale(1.1);
}

.product-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #28a745;
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.product-info {
    padding: 1.5rem;
}

.product-category {
    color: #28a745;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.current-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: #28a745;
}

.original-price {
    font-size: 1rem;
    color: #999;
    text-decoration: line-through;
}

.discount-badge {
    background: #dc3545;
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 600;
}

.product-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-add-cart {
    flex: 1;
    background: #28a745;
    color: white;
    border: none;
    padding: 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    cursor: pointer;
}

.btn-add-cart:hover {
    background: #218838;
    transform: translateY(-1px);
}

.btn-view {
    background: #f8f9fa;
    color: #333;
    border: 2px solid #e9ecef;
    padding: 0.75rem;
    border-radius: 8px;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.btn-view:hover {
    background: #e9ecef;
    color: #333;
    text-decoration: none;
}

.no-products {
    text-align: center;
    padding: 4rem 0;
    color: #666;
}

.filters-bar {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.filter-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sort-select {
    min-width: 200px;
}

@media (max-width: 768px) {
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .filter-row {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-group {
        width: 100%;
    }
    
    .sort-select {
        min-width: 100%;
    }
}
</style>

<main class="brand-products-page">
    <div class="container">
        <!-- Brand Header -->
        <div class="brand-header">
            <div class="brand-logo">
                <?php echo strtoupper(substr($brand ?? 'B', 0, 1)); ?>
            </div>
            <h1 class="h2 mb-2"><?php echo htmlspecialchars($brand ?? 'Brand'); ?> Products</h1>
            <p class="mb-0">
                <?php 
                $productCount = is_array($products) ? count($products) : 0;
                echo $productCount; 
                ?> products available
            </p>
        </div>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo base_url('product/listing'); ?>">Products</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($brand ?? 'Brand'); ?></li>
            </ol>
        </nav>

        <!-- Filters Bar -->
        <div class="filters-bar">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="categoryFilter">Category:</label>
                    <select class="form-control" id="categoryFilter" onchange="filterProducts()">
                        <option value="">All Categories</option>
                        <option value="Fruits & Vegetables">Fruits & Vegetables</option>
                        <option value="Dairy & Bakery">Dairy & Bakery</option>
                        <option value="Grains & Rice">Grains & Rice</option>
                        <option value="Spices & Seasonings">Spices & Seasonings</option>
                        <option value="Snacks & Instant Food">Snacks & Instant Food</option>
                        <option value="Beverages">Beverages</option>
                        <option value="Personal Care">Personal Care</option>
                        <option value="Household Items">Household Items</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="priceFilter">Price Range:</label>
                    <select class="form-control" id="priceFilter" onchange="filterProducts()">
                        <option value="">Any Price</option>
                        <option value="0-100">Under ₹100</option>
                        <option value="100-500">₹100 - ₹500</option>
                        <option value="500-1000">₹500 - ₹1000</option>
                        <option value="1000+">Above ₹1000</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="sortBy">Sort By:</label>
                    <select class="form-control sort-select" id="sortBy" onchange="sortProducts()">
                        <option value="name-asc">Name (A-Z)</option>
                        <option value="name-desc">Name (Z-A)</option>
                        <option value="price-asc">Price (Low to High)</option>
                        <option value="price-desc">Price (High to Low)</option>
                        <option value="newest">Newest First</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div id="productsContainer">
            <?php if (!empty($products) && is_array($products)): ?>
                <div class="product-grid" id="productGrid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card" 
                             data-category="<?php echo htmlspecialchars($product['category'] ?? ''); ?>"
                             data-price="<?php echo $product['price'] ?? 0; ?>"
                             data-name="<?php echo htmlspecialchars($product['pname'] ?? ''); ?>"
                             data-id="<?php echo $product['pid'] ?? 0; ?>">
                            <div class="product-image">
                                <img src="<?php echo base_url($product['pimage'] ?? 'assets/img/placeholder.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($product['pname'] ?? ''); ?>"
                                     loading="lazy">
                                
                                <?php if (!empty($product['discount']) && $product['discount'] > 0): ?>
                                    <div class="product-badge">
                                        <?php echo $product['discount']; ?>% OFF
                                    </div>
                                <?php elseif (!empty($product['is_new'])): ?>
                                    <div class="product-badge" style="background: #17a2b8;">
                                        NEW
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-info">
                                <div class="product-category">
                                    <?php echo htmlspecialchars($product['category'] ?? ''); ?>
                                </div>
                                
                                <h3 class="product-title">
                                    <?php echo htmlspecialchars($product['pname'] ?? ''); ?>
                                </h3>
                                
                                <div class="product-price">
                                    <span class="current-price">
                                        <i class="fas fa-rupee-sign"></i><?php echo number_format($product['price'] ?? 0, 2); ?>
                                    </span>
                                    
                                    <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): ?>
                                        <span class="original-price">
                                            <i class="fas fa-rupee-sign"></i><?php echo number_format($product['original_price'], 2); ?>
                                        </span>
                                        <span class="discount-badge">
                                            <?php echo round((($product['original_price'] - $product['price']) / $product['original_price']) * 100); ?>% OFF
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($product['weight']) && !empty($product['unit'])): ?>
                                    <div class="product-weight text-muted small mb-2">
                                        <?php echo $product['weight'] . ' ' . $product['unit']; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="product-actions">
                                    <button class="btn-add-cart" onclick="addToCart(<?php echo $product['pid'] ?? 0; ?>)">
                                        <i class="fas fa-cart-plus"></i>
                                        Add to Cart
                                    </button>
                                    
                                    <a href="<?php echo base_url('product/index/' . ($product['pid'] ?? 0)); ?>" 
                                       class="btn-view">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Load More Button -->
                <div class="text-center mt-4" id="loadMoreContainer">
                    <button class="btn btn-outline-primary" onclick="loadMoreProducts()">
                        <i class="fas fa-plus-circle"></i>
                        Load More Products
                    </button>
                </div>
                
            <?php else: ?>
                <div class="no-products">
                    <i class="fas fa-search fa-5x mb-4 text-muted"></i>
                    <h3 class="h4 text-muted mb-3">No Products Found</h3>
                    <p class="text-muted mb-4">
                        No products found for brand "<?php echo htmlspecialchars($brand ?? ''); ?>".
                    </p>
                    <a href="<?php echo base_url(); ?>" class="btn btn-primary">
                        <i class="fas fa-home"></i>
                        Continue Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
let allProducts = [];
let filteredProducts = [];
let currentPage = 1;
const productsPerPage = 12;

document.addEventListener('DOMContentLoaded', function() {
    // Store all products for filtering
    allProducts = Array.from(document.querySelectorAll('.product-card')).map(card => ({
        element: card,
        category: card.dataset.category,
        price: parseFloat(card.dataset.price),
        name: card.dataset.name,
        id: card.dataset.id
    }));
    
    filteredProducts = [...allProducts];
    updateDisplay();
});

function filterProducts() {
    const categoryFilter = document.getElementById('categoryFilter').value;
    const priceFilter = document.getElementById('priceFilter').value;
    
    filteredProducts = allProducts.filter(product => {
        // Category filter
        if (categoryFilter && product.category !== categoryFilter) {
            return false;
        }
        
        // Price filter
        if (priceFilter) {
            const price = product.price;
            if (priceFilter === '0-100' && price > 100) return false;
            if (priceFilter === '100-500' && (price < 100 || price > 500)) return false;
            if (priceFilter === '500-1000' && (price < 500 || price > 1000)) return false;
            if (priceFilter === '1000+' && price < 1000) return false;
        }
        
        return true;
    });
    
    currentPage = 1;
    updateDisplay();
}

function sortProducts() {
    const sortBy = document.getElementById('sortBy').value;
    
    filteredProducts.sort((a, b) => {
        switch (sortBy) {
            case 'name-asc':
                return a.name.localeCompare(b.name);
            case 'name-desc':
                return b.name.localeCompare(a.name);
            case 'price-asc':
                return a.price - b.price;
            case 'price-desc':
                return b.price - a.price;
            case 'newest':
                // Assuming newer products have higher IDs
                return b.id - a.id;
            default:
                return 0;
        }
    });
    
    updateDisplay();
}

function updateDisplay() {
    const container = document.getElementById('productGrid');
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    
    if (filteredProducts.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="no-products">
                    <i class="fas fa-search fa-4x mb-3 text-muted"></i>
                    <h4 class="text-muted mb-3">No products match your filters</h4>
                    <p class="text-muted">Try adjusting your filters or search terms.</p>
                    <button class="btn btn-primary" onclick="clearFilters()">
                        <i class="fas fa-refresh"></i>
                        Clear Filters
                    </button>
                </div>
            </div>
        `;
        loadMoreContainer.style.display = 'none';
        return;
    }
    
    // Hide all products first
    allProducts.forEach(product => {
        product.element.style.display = 'none';
    });
    
    // Show filtered products up to current page
    const endIndex = currentPage * productsPerPage;
    const productsToShow = filteredProducts.slice(0, endIndex);
    
    productsToShow.forEach(product => {
        product.element.style.display = 'block';
    });
    
    // Show/hide load more button
    if (endIndex >= filteredProducts.length) {
        loadMoreContainer.style.display = 'none';
    } else {
        loadMoreContainer.style.display = 'block';
    }
}

function loadMoreProducts() {
    currentPage++;
    updateDisplay();
}

function clearFilters() {
    document.getElementById('categoryFilter').value = '';
    document.getElementById('priceFilter').value = '';
    document.getElementById('sortBy').value = 'name-asc';
    
    filteredProducts = [...allProducts];
    currentPage = 1;
    updateDisplay();
}

function addToCart(productId) {
    if (!productId) return;
    
    fetch('<?php echo base_url(); ?>shopping/addToCart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `pid=${productId}&quantity=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            if (window.greenzioErrorHandler) {
                window.greenzioErrorHandler.showNotification('Product added to cart!', 'success');
            } else {
                alert('Product added to cart!');
            }
            
            // Update cart count
            if (window.refreshCartCount) {
                window.refreshCartCount();
            }
        } else {
            // Show error message
            if (window.greenzioErrorHandler) {
                window.greenzioErrorHandler.showError(data.message || 'Failed to add product to cart');
            } else {
                alert(data.message || 'Failed to add product to cart');
            }
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        if (window.greenzioErrorHandler) {
            window.greenzioErrorHandler.showError('Failed to add product to cart');
        } else {
            alert('Failed to add product to cart');
        }
    });
}
</script>
