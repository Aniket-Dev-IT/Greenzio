<?php 
// Calculate batch update metrics
$total_products = 0;
$selected_products = 0;
$pending_updates = 0;

if (isset($productDetails)) {
    $total_products = count($productDetails);
}
?>

<div class="content">
    <div class="container-fluid">
        
        <!-- Batch Update Overview -->
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Total Products</h4>
                    </div>
                    <div class="content text-center">
                        <h1 class="text-info"><?php echo $total_products; ?></h1>
                        <p>Available for update</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Selected Products</h4>
                    </div>
                    <div class="content text-center">
                        <h1 class="text-success" id="selectedCount">0</h1>
                        <p>Ready for batch update</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Low Stock Items</h4>
                    </div>
                    <div class="content text-center">
                        <?php 
                        $low_stock_count = 0;
                        if (isset($productDetails)) {
                            foreach ($productDetails as $product) {
                                if (isset($product['stock_quantity']) && $product['stock_quantity'] <= 10) {
                                    $low_stock_count++;
                                }
                            }
                        }
                        ?>
                        <h1 class="text-warning"><?php echo $low_stock_count; ?></h1>
                        <p>Need attention</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Quick Actions</h4>
                    </div>
                    <div class="content text-center">
                        <button class="btn btn-success btn-block" onclick="selectAllProducts()">
                            <i class="fa fa-check-square"></i> Select All
                        </button>
                        <button class="btn btn-warning btn-block" onclick="selectLowStock()">
                            <i class="fa fa-exclamation-triangle"></i> Select Low Stock
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Batch Update Forms -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Batch Price Update</h4>
                        <p class="category">Update prices for multiple products</p>
                    </div>
                    <div class="content">
                        <form method="post" action="<?php echo base_url('admin/batchUpdatePrices'); ?>" id="priceUpdateForm">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Update Method</label>
                                        <select class="form-control" name="price_method" id="priceMethod" onchange="togglePriceFields()">
                                            <option value="percentage_increase">Percentage Increase</option>
                                            <option value="percentage_decrease">Percentage Decrease</option>
                                            <option value="fixed_increase">Fixed Amount Increase</option>
                                            <option value="fixed_decrease">Fixed Amount Decrease</option>
                                            <option value="set_price">Set Specific Price</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6" id="percentageField">
                                    <div class="form-group">
                                        <label>Percentage (%)</label>
                                        <input type="number" class="form-control" name="percentage" min="0" max="100" step="0.01">
                                    </div>
                                </div>
                                
                                <div class="col-md-6" id="amountField" style="display:none;">
                                    <div class="form-group">
                                        <label>Amount (₹)</label>
                                        <input type="number" class="form-control" name="amount" min="0" step="0.01">
                                    </div>
                                </div>
                                
                                <div class="col-md-6" id="priceField" style="display:none;">
                                    <div class="form-group">
                                        <label>New Price (₹)</label>
                                        <input type="number" class="form-control" name="new_price" min="0" step="0.01">
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Category Filter (Optional)</label>
                                        <select class="form-control" name="category_filter" id="priceCategoryFilter">
                                            <option value="">All Categories</option>
                                            <option value="Fruits & Vegetables">Fruits & Vegetables</option>
                                            <option value="Dairy & Bakery">Dairy & Bakery</option>
                                            <option value="Grains & Rice">Grains & Rice</option>
                                            <option value="Pulses & Beans">Pulses & Beans</option>
                                            <option value="Spices & Condiments">Spices & Condiments</option>
                                            <option value="Oil & Ghee">Oil & Ghee</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> 
                                        <span id="selectedProductsForPrice">0</span> products selected for price update
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success btn-fill btn-block" onclick="return confirmPriceUpdate()">
                                        <i class="fa fa-tag"></i> Update Prices
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Batch Stock Update</h4>
                        <p class="category">Update stock quantities for multiple products</p>
                    </div>
                    <div class="content">
                        <form method="post" action="<?php echo base_url('admin/batchUpdateStock'); ?>" id="stockUpdateForm">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Update Method</label>
                                        <select class="form-control" name="stock_method" id="stockMethod">
                                            <option value="add">Add to Current Stock</option>
                                            <option value="subtract">Subtract from Current Stock</option>
                                            <option value="set">Set Exact Stock Quantity</option>
                                            <option value="reorder">Set to Reorder Level</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input type="number" class="form-control" name="stock_quantity" min="0" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Stock Filter</label>
                                        <select class="form-control" name="stock_filter" id="stockFilter">
                                            <option value="all">All Products</option>
                                            <option value="low_stock">Low Stock Only (≤10)</option>
                                            <option value="out_of_stock">Out of Stock Only</option>
                                            <option value="in_stock">In Stock Only</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Category Filter (Optional)</label>
                                        <select class="form-control" name="category_filter" id="stockCategoryFilter">
                                            <option value="">All Categories</option>
                                            <option value="Fruits & Vegetables">Fruits & Vegetables</option>
                                            <option value="Dairy & Bakery">Dairy & Bakery</option>
                                            <option value="Grains & Rice">Grains & Rice</option>
                                            <option value="Pulses & Beans">Pulses & Beans</option>
                                            <option value="Spices & Condiments">Spices & Condiments</option>
                                            <option value="Oil & Ghee">Oil & Ghee</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> 
                                        <span id="selectedProductsForStock">0</span> products selected for stock update
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-warning btn-fill btn-block" onclick="return confirmStockUpdate()">
                                        <i class="fa fa-cubes"></i> Update Stock
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Selection Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Select Products for Batch Update</h4>
                        <p class="category">Choose products to apply batch updates</p>
                        <div class="pull-right">
                            <button class="btn btn-sm btn-info" onclick="refreshProductData()">
                                <i class="fa fa-refresh"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="content table-responsive">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" id="searchProduct" class="form-control" placeholder="Search products..." onkeyup="filterProducts()">
                            </div>
                            <div class="col-md-3">
                                <select id="categoryFilter" class="form-control" onchange="filterProducts()">
                                    <option value="all">All Categories</option>
                                    <option value="Fruits & Vegetables">Fruits & Vegetables</option>
                                    <option value="Dairy & Bakery">Dairy & Bakery</option>
                                    <option value="Grains & Rice">Grains & Rice</option>
                                    <option value="Pulses & Beans">Pulses & Beans</option>
                                    <option value="Spices & Condiments">Spices & Condiments</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="stockStatusFilter" class="form-control" onchange="filterProducts()">
                                    <option value="all">All Stock Levels</option>
                                    <option value="in_stock">In Stock</option>
                                    <option value="low_stock">Low Stock</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="btn-group btn-block">
                                    <button class="btn btn-info" onclick="selectAllVisible()"><i class="fa fa-check-square"></i> All</button>
                                    <button class="btn btn-warning" onclick="clearSelection()"><i class="fa fa-square-o"></i> Clear</button>
                                </div>
                            </div>
                        </div>
                        
                        <table class="table table-striped table-hover" id="productsTable">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox">
                                            <input id="selectAllCheckbox" type="checkbox" onchange="toggleAllProducts()">
                                            <label for="selectAllCheckbox"></label>
                                        </div>
                                    </th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Current Price</th>
                                    <th>Current Stock</th>
                                    <th>Stock Status</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($productDetails) && !empty($productDetails)): ?>
                                    <?php foreach ($productDetails as $index => $product): ?>
                                        <?php
                                        // Calculate stock status
                                        $stock_status = 'in_stock';
                                        $stock_class = 'text-success';
                                        $stock_badge = 'success';
                                        
                                        if ($product['stock_quantity'] <= 0) {
                                            $stock_status = 'out_of_stock';
                                            $stock_class = 'text-danger';
                                            $stock_badge = 'danger';
                                        } elseif ($product['stock_quantity'] <= 10) {
                                            $stock_status = 'low_stock';
                                            $stock_class = 'text-warning';
                                            $stock_badge = 'warning';
                                        }
                                        ?>
                                        <tr class="product-row" 
                                            data-category="<?php echo $product['category']; ?>"
                                            data-stock-status="<?php echo $stock_status; ?>"
                                            data-product-name="<?php echo strtolower($product['pname']); ?>">
                                            <td>
                                                <div class="checkbox">
                                                    <input id="product_<?php echo $product['pid']; ?>" 
                                                           type="checkbox" 
                                                           name="selected_products[]" 
                                                           value="<?php echo $product['pid']; ?>"
                                                           onchange="updateSelectedCount()">
                                                    <label for="product_<?php echo $product['pid']; ?>"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <strong><?php echo $product['pname']; ?></strong>
                                                <br><small class="text-muted">#<?php echo $product['pid']; ?></small>
                                            </td>
                                            <td><?php echo $product['category']; ?></td>
                                            <td>₹<?php echo number_format($product['price'], 2); ?></td>
                                            <td class="<?php echo $stock_class; ?>">
                                                <strong><?php echo $product['stock_quantity']; ?></strong> units
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo $stock_badge; ?>">
                                                    <?php 
                                                    if ($stock_status === 'out_of_stock') {
                                                        echo 'Out of Stock';
                                                    } elseif ($stock_status === 'low_stock') {
                                                        echo 'Low Stock';
                                                    } else {
                                                        echo 'In Stock';
                                                    }
                                                    ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($product['updated_at'] ?? 'now')); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No products available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Toggle price update fields based on method
function togglePriceFields() {
    var method = document.getElementById('priceMethod').value;
    var percentageField = document.getElementById('percentageField');
    var amountField = document.getElementById('amountField');
    var priceField = document.getElementById('priceField');
    
    // Hide all fields first
    percentageField.style.display = 'none';
    amountField.style.display = 'none';
    priceField.style.display = 'none';
    
    // Show relevant field
    if (method.includes('percentage')) {
        percentageField.style.display = 'block';
    } else if (method.includes('fixed')) {
        amountField.style.display = 'block';
    } else if (method === 'set_price') {
        priceField.style.display = 'block';
    }
}

// Update selected product count
function updateSelectedCount() {
    var checkboxes = document.querySelectorAll('input[name="selected_products[]"]:checked');
    var count = checkboxes.length;
    
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('selectedProductsForPrice').textContent = count;
    document.getElementById('selectedProductsForStock').textContent = count;
}

// Toggle all products selection
function toggleAllProducts() {
    var masterCheckbox = document.getElementById('selectAllCheckbox');
    var productCheckboxes = document.querySelectorAll('input[name="selected_products[]"]');
    
    productCheckboxes.forEach(function(checkbox) {
        if (checkbox.closest('tr').style.display !== 'none') {
            checkbox.checked = masterCheckbox.checked;
        }
    });
    
    updateSelectedCount();
}

// Select all products
function selectAllProducts() {
    var productCheckboxes = document.querySelectorAll('input[name="selected_products[]"]');
    productCheckboxes.forEach(function(checkbox) {
        checkbox.checked = true;
    });
    document.getElementById('selectAllCheckbox').checked = true;
    updateSelectedCount();
}

// Select only low stock products
function selectLowStock() {
    clearSelection();
    var rows = document.querySelectorAll('.product-row[data-stock-status="low_stock"], .product-row[data-stock-status="out_of_stock"]');
    rows.forEach(function(row) {
        var checkbox = row.querySelector('input[name="selected_products[]"]');
        if (checkbox) {
            checkbox.checked = true;
        }
    });
    updateSelectedCount();
}

// Select all visible products
function selectAllVisible() {
    var visibleRows = document.querySelectorAll('.product-row:not([style*="display: none"])');
    visibleRows.forEach(function(row) {
        var checkbox = row.querySelector('input[name="selected_products[]"]');
        if (checkbox) {
            checkbox.checked = true;
        }
    });
    updateSelectedCount();
}

// Clear all selections
function clearSelection() {
    var productCheckboxes = document.querySelectorAll('input[name="selected_products[]"]');
    productCheckboxes.forEach(function(checkbox) {
        checkbox.checked = false;
    });
    document.getElementById('selectAllCheckbox').checked = false;
    updateSelectedCount();
}

// Filter products
function filterProducts() {
    var searchTerm = document.getElementById('searchProduct').value.toLowerCase();
    var categoryFilter = document.getElementById('categoryFilter').value;
    var stockStatusFilter = document.getElementById('stockStatusFilter').value;
    var rows = document.querySelectorAll('#productsTable tbody .product-row');
    
    rows.forEach(function(row) {
        var productName = row.getAttribute('data-product-name');
        var category = row.getAttribute('data-category');
        var stockStatus = row.getAttribute('data-stock-status');
        
        var showRow = true;
        
        // Search filter
        if (searchTerm && !productName.includes(searchTerm)) {
            showRow = false;
        }
        
        // Category filter
        if (categoryFilter !== 'all' && category !== categoryFilter) {
            showRow = false;
        }
        
        // Stock status filter
        if (stockStatusFilter !== 'all') {
            if (stockStatusFilter === 'in_stock' && stockStatus !== 'in_stock') {
                showRow = false;
            } else if (stockStatusFilter === 'low_stock' && stockStatus !== 'low_stock') {
                showRow = false;
            } else if (stockStatusFilter === 'out_of_stock' && stockStatus !== 'out_of_stock') {
                showRow = false;
            }
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

// Confirmation for price update
function confirmPriceUpdate() {
    var selectedCount = document.querySelectorAll('input[name="selected_products[]"]:checked').length;
    
    if (selectedCount === 0) {
        alert('Please select at least one product for price update.');
        return false;
    }
    
    return confirm('Are you sure you want to update prices for ' + selectedCount + ' product(s)?');
}

// Confirmation for stock update
function confirmStockUpdate() {
    var selectedCount = document.querySelectorAll('input[name="selected_products[]"]:checked').length;
    
    if (selectedCount === 0) {
        alert('Please select at least one product for stock update.');
        return false;
    }
    
    return confirm('Are you sure you want to update stock for ' + selectedCount + ' product(s)?');
}

// Refresh product data
function refreshProductData() {
    location.reload();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateSelectedCount();
    togglePriceFields();
});
</script>
