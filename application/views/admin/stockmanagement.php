<?php 
// Stock management metrics for display
$low_stock_count = 0;
$out_of_stock_count = 0;
$expiring_soon_count = 0;

if (isset($productDetails)) {
    foreach ($productDetails as $product) {
        if (isset($product['stock_quantity'])) {
            if ($product['stock_quantity'] <= 0) {
                $out_of_stock_count++;
            } elseif ($product['stock_quantity'] <= 10) {
                $low_stock_count++;
            }
        }
        
        if (isset($product['expiry_date']) && !empty($product['expiry_date'])) {
            $days_to_expiry = floor((strtotime($product['expiry_date']) - strtotime(date('Y-m-d'))) / 86400);
            if ($days_to_expiry >= 0 && $days_to_expiry <= 7) {
                $expiring_soon_count++;
            }
        }
    }
}
?>

<div class="content">
    <div class="container-fluid px-3">
        
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 pt-3">
            <div>
                <h2 class="mb-1 text-primary">Stock Management Dashboard</h2>
                <p class="text-muted mb-0">Monitor inventory levels, track expiry dates, and manage stock efficiently</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" onclick="refreshInventoryData()">
                    <i class="fa fa-sync-alt me-2"></i>Refresh Data
                </button>
                <button class="btn btn-outline-success" onclick="exportStockReport()">
                    <i class="fa fa-download me-2"></i>Export Report
                </button>
            </div>
        </div>
        
        <!-- Stock Overview Cards -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #ff6b6b, #ee5a5a);">
                    <div class="card-body text-white text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="fas fa-exclamation-triangle fa-2x me-2"></i>
                            <div>
                                <h2 class="mb-0 fw-bold"><?php echo $out_of_stock_count; ?></h2>
                                <small class="opacity-75">Out of Stock</small>
                            </div>
                        </div>
                        <p class="mb-0 opacity-75">Products need immediate restock</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #feca57, #ff9f43);">
                    <div class="card-body text-white text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="fas fa-arrow-down fa-2x me-2"></i>
                            <div>
                                <h2 class="mb-0 fw-bold"><?php echo $low_stock_count; ?></h2>
                                <small class="opacity-75">Low Stock</small>
                            </div>
                        </div>
                        <p class="mb-0 opacity-75">Products with ≤4110 units left</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #54a0ff, #2e86de);">
                    <div class="card-body text-white text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="fas fa-clock fa-2x me-2"></i>
                            <div>
                                <h2 class="mb-0 fw-bold"><?php echo $expiring_soon_count; ?></h2>
                                <small class="opacity-75">Expiring Soon</small>
                            </div>
                        </div>
                        <p class="mb-0 opacity-75">Products expire within 7 days</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #5f27cd, #341f97);">
                    <div class="card-body text-white text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="fas fa-boxes fa-2x me-2"></i>
                            <div>
                                <h2 class="mb-0 fw-bold"><?php echo isset($productDetails) ? count($productDetails) : 0; ?></h2>
                                <small class="opacity-75">Total Products</small>
                            </div>
                        </div>
                        <p class="mb-0 opacity-75">All products in inventory</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Management Actions -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Low Stock Alert</h4>
                        <p class="category">Products requiring immediate attention</p>
                    </div>
                    <div class="content table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Current Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (isset($productDetails)) {
                                    foreach ($productDetails as $product) {
                                        if (isset($product['stock_quantity']) && $product['stock_quantity'] <= 10) {
                                ?>
                                <tr class="<?php echo $product['stock_quantity'] <= 0 ? 'danger' : 'warning'; ?>">
                                    <td><?php echo $product['pname']; ?></td>
                                    <td>
                                        <span class="<?php echo $product['stock_quantity'] <= 0 ? 'text-danger' : 'text-warning'; ?>">
                                            <?php echo $product['stock_quantity']; ?>
                                            <?php if ($product['stock_quantity'] <= 0) echo ' (OUT OF STOCK)'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="updateStock(<?php echo $product['pid']; ?>)">
                                            <i class="fa fa-plus"></i> Restock
                                        </button>
                                    </td>
                                </tr>
                                <?php 
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Expiry Alert</h4>
                        <p class="category">Products expiring within 7 days</p>
                    </div>
                    <div class="content table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Expiry Date</th>
                                    <th>Days Left</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (isset($productDetails)) {
                                    foreach ($productDetails as $product) {
                                        if (isset($product['expiry_date']) && !empty($product['expiry_date'])) {
                                            $days_to_expiry = floor((strtotime($product['expiry_date']) - strtotime(date('Y-m-d'))) / 86400);
                                            if ($days_to_expiry >= 0 && $days_to_expiry <= 7) {
                                ?>
                                <tr class="<?php echo $days_to_expiry <= 3 ? 'danger' : 'warning'; ?>">
                                    <td><?php echo $product['pname']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($product['expiry_date'])); ?></td>
                                    <td>
                                        <span class="<?php echo $days_to_expiry <= 3 ? 'text-danger' : 'text-warning'; ?>">
                                            <?php echo $days_to_expiry; ?> days
                                            <?php if ($days_to_expiry <= 3) echo ' (URGENT)'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php 
                                            }
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Stock Update Form -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Quick Stock Update</h4>
                        <p class="category">Update stock quantities for products</p>
                    </div>
                    <div class="content">
                        <form method="post" action="<?php echo base_url('admin/bulkUpdateStock'); ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Select Product</label>
                                        <select class="form-control" name="product_id" required>
                                            <option value="">Choose Product</option>
                                            <?php 
                                            if (isset($productDetails)) {
                                                foreach ($productDetails as $product) {
                                                    echo '<option value="'.$product['pid'].'">'.$product['pname'].' (Current: '.$product['stock_quantity'].')</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Operation</label>
                                        <select class="form-control" name="operation" required>
                                            <option value="add">Add Stock</option>
                                            <option value="subtract">Remove Stock</option>
                                            <option value="set">Set Exact Stock</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input type="number" class="form-control" name="quantity" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-info btn-fill btn-block">
                                        <i class="fa fa-plus"></i> Update Stock
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
                        <h4 class="title">Expiry Date Management</h4>
                        <p class="category">Update product expiry dates</p>
                    </div>
                    <div class="content">
                        <form method="post" action="<?php echo base_url('admin/updateExpiryDate'); ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Select Product</label>
                                        <select class="form-control" name="product_id" required>
                                            <option value="">Choose Product</option>
                                            <?php 
                                            if (isset($productDetails)) {
                                                foreach ($productDetails as $product) {
                                                    $expiry_display = !empty($product['expiry_date']) ? date('M d, Y', strtotime($product['expiry_date'])) : 'No expiry set';
                                                    echo '<option value="'.$product['pid'].'">'.$product['pname'].' (Current: '.$expiry_display.')</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>New Expiry Date</label>
                                        <input type="date" class="form-control" name="expiry_date" min="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-warning btn-fill btn-block">
                                        <i class="fa fa-calendar"></i> Update Expiry Date
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Complete Inventory Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title text-primary mb-1">
                                    <i class="fas fa-warehouse me-2"></i>Complete Product Inventory
                                </h5>
                                <p class="text-muted mb-0 small">Overview of all products with stock and expiry information</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-info" onclick="refreshInventoryData()">
                                    <i class="fa fa-sync-alt"></i> Refresh
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="exportStockReport()">
                                    <i class="fa fa-file-excel"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="content table-responsive">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" id="searchProduct" class="form-control" placeholder="Search products..." onkeyup="filterProducts()">
                            </div>
                            <div class="col-md-3">
                                <select id="stockFilter" class="form-control" onchange="filterProducts()">
                                    <option value="all">All Stock Levels</option>
                                    <option value="in_stock">In Stock</option>
                                    <option value="low_stock">Low Stock (≤10)</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="expiryFilter" class="form-control" onchange="filterProducts()">
                                    <option value="all">All Products</option>
                                    <option value="expiring_soon">Expiring Soon</option>
                                    <option value="expired">Already Expired</option>
                                    <option value="fresh">Fresh Products</option>
                                </select>
                            </div>
                        </div>
                        
                        <table class="table table-striped table-hover table-condensed" id="inventoryTable" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th style="width: 18%;">Product Name</th>
                                    <th style="width: 10%;">Category</th>
                                    <th style="width: 8%;">Brand</th>
                                    <th style="width: 8%;">Weight</th>
                                    <th style="width: 8%;">Price</th>
                                    <th style="width: 10%;">Stock Status</th>
                                    <th style="width: 8%;">Quantity</th>
                                    <th style="width: 12%;">Expiry Date</th>
                                    <th style="width: 10%;">Expiry Status</th>
                                    <th style="width: 8%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (isset($productDetails)) {
                                    foreach ($productDetails as $product) {
                                        // Calculate stock status
                                        $stock_status = 'in_stock';
                                        $stock_class = 'text-success';
                                        $stock_badge = 'success';
                                        
                                        if ($product['stock_quantity'] <= 0) {
                                            $stock_status = 'out_of_stock';
                                            $stock_class = 'text-danger';
                                            $stock_badge = 'danger';
                                        } elseif ($product['stock_quantity'] <= 3) {
                                            $stock_status = 'very_low_stock';
                                            $stock_class = 'text-danger';
                                            $stock_badge = 'danger';
                                        } elseif ($product['stock_quantity'] <= 10) {
                                            $stock_status = 'low_stock';
                                            $stock_class = 'text-warning';
                                            $stock_badge = 'warning';
                                        }
                                        
                                        // Calculate expiry status
                                        $expiry_status = 'no_expiry';
                                        $expiry_class = 'text-muted';
                                        $expiry_display = 'N/A';
                                        
                                        if (!empty($product['expiry_date'])) {
                                            $days_to_expiry = floor((strtotime($product['expiry_date']) - strtotime(date('Y-m-d'))) / 86400);
                                            $expiry_display = date('M d, Y', strtotime($product['expiry_date']));
                                            
                                            if ($days_to_expiry < 0) {
                                                $expiry_status = 'expired';
                                                $expiry_class = 'text-danger';
                                                $expiry_display .= ' (EXPIRED)';
                                            } elseif ($days_to_expiry <= 3) {
                                                $expiry_status = 'expiring_urgent';
                                                $expiry_class = 'text-danger';
                                                $expiry_display .= ' (' . $days_to_expiry . ' days)';
                                            } elseif ($days_to_expiry <= 7) {
                                                $expiry_status = 'expiring_soon';
                                                $expiry_class = 'text-warning';
                                                $expiry_display .= ' (' . $days_to_expiry . ' days)';
                                            } else {
                                                $expiry_status = 'fresh';
                                                $expiry_class = 'text-success';
                                            }
                                        }
                                ?>
                                <tr class="product-row" 
                                    data-stock-status="<?php echo $stock_status; ?>" 
                                    data-expiry-status="<?php echo $expiry_status; ?>"
                                    data-product-name="<?php echo strtolower($product['pname']); ?>">
                                    <td>
                                        <strong style="font-size: 11px;"><?php echo substr($product['pname'], 0, 25) . (strlen($product['pname']) > 25 ? '...' : ''); ?></strong>
                                        <br><small class="text-muted" style="font-size: 9px;">#<?php echo $product['pid']; ?></small>
                                    </td>
                                    <td><small><?php echo $product['category']; ?></small></td>
                                    <td><small><?php echo !empty($product['brand']) ? $product['brand'] : '-'; ?></small></td>
                                    <td><small><?php echo $product['weight'] . ' ' . $product['unit']; ?></small></td>
                                    <td><strong style="font-size: 11px;">₹<?php echo number_format($product['price'], 0); ?></strong></td>
                                    <td>
                                        <span class="badge badge-<?php echo $stock_badge; ?>" style="font-size: 9px; padding: 2px 6px;">
                                            <?php 
                                            if ($stock_status === 'out_of_stock') {
                                                echo 'Out';
                                            } elseif ($stock_status === 'very_low_stock') {
                                                echo 'Critical';
                                            } elseif ($stock_status === 'low_stock') {
                                                echo 'Low';
                                            } else {
                                                echo 'Good';
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td class="<?php echo $stock_class; ?>">
                                        <strong style="font-size: 11px;"><?php echo $product['stock_quantity']; ?></strong><br><small>units</small>
                                    </td>
                                    <td class="<?php echo $expiry_class; ?>">
                                        <small style="font-size: 10px;"><?php echo substr($expiry_display, 0, 15) . (strlen($expiry_display) > 15 ? '...' : ''); ?></small>
                                    </td>
                                    <td>
                                        <?php if ($expiry_status === 'expiring_urgent' || $expiry_status === 'expiring_soon'): ?>
                                        <span class="badge badge-warning" style="font-size: 8px; padding: 1px 4px;">Soon</span>
                                        <?php elseif ($expiry_status === 'expired'): ?>
                                        <span class="badge badge-danger" style="font-size: 8px; padding: 1px 4px;">Expired</span>
                                        <?php elseif ($expiry_status === 'fresh'): ?>
                                        <span class="badge badge-success" style="font-size: 8px; padding: 1px 4px;">Fresh</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-xs btn-info" onclick="quickStockUpdate(<?php echo $product['pid']; ?>)" title="Stock Update" style="padding: 2px 4px;">
                                                <i class="fa fa-plus" style="font-size: 10px;"></i>
                                            </button>
                                            <button class="btn btn-xs btn-warning" onclick="updateExpiry(<?php echo $product['pid']; ?>)" title="Expiry Update" style="padding: 2px 4px;">
                                                <i class="fa fa-calendar" style="font-size: 10px;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Enhanced inventory management functions
function updateStock(productId) {
    var quantity = prompt("Enter quantity to add:");
    if (quantity && quantity > 0) {
        submitStockUpdate(productId, 'add', quantity);
    }
}

function quickStockUpdate(productId) {
    var operation = prompt("Enter operation (add/subtract/set):", "add");
    var quantity = prompt("Enter quantity:");
    
    if (operation && quantity && quantity >= 0) {
        submitStockUpdate(productId, operation, quantity);
    }
}

function updateExpiry(productId) {
    var expiryDate = prompt("Enter new expiry date (YYYY-MM-DD):");
    
    if (expiryDate) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo base_url("admin/updateExpiryDate"); ?>';
        
        var productInput = document.createElement('input');
        productInput.type = 'hidden';
        productInput.name = 'product_id';
        productInput.value = productId;
        
        var expiryInput = document.createElement('input');
        expiryInput.type = 'hidden';
        expiryInput.name = 'expiry_date';
        expiryInput.value = expiryDate;
        
        form.appendChild(productInput);
        form.appendChild(expiryInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function submitStockUpdate(productId, operation, quantity) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo base_url("admin/bulkUpdateStock"); ?>';
    
    var productInput = document.createElement('input');
    productInput.type = 'hidden';
    productInput.name = 'product_id';
    productInput.value = productId;
    
    var operationInput = document.createElement('input');
    operationInput.type = 'hidden';
    operationInput.name = 'operation';
    operationInput.value = operation;
    
    var quantityInput = document.createElement('input');
    quantityInput.type = 'hidden';
    quantityInput.name = 'quantity';
    quantityInput.value = quantity;
    
    form.appendChild(productInput);
    form.appendChild(operationInput);
    form.appendChild(quantityInput);
    
    document.body.appendChild(form);
    form.submit();
}

// Filter products in the inventory table
function filterProducts() {
    var searchTerm = document.getElementById('searchProduct').value.toLowerCase();
    var stockFilter = document.getElementById('stockFilter').value;
    var expiryFilter = document.getElementById('expiryFilter').value;
    var rows = document.querySelectorAll('#inventoryTable tbody .product-row');
    
    rows.forEach(function(row) {
        var productName = row.getAttribute('data-product-name');
        var stockStatus = row.getAttribute('data-stock-status');
        var expiryStatus = row.getAttribute('data-expiry-status');
        
        var showRow = true;
        
        // Search filter
        if (searchTerm && !productName.includes(searchTerm)) {
            showRow = false;
        }
        
        // Stock filter
        if (stockFilter !== 'all') {
            if (stockFilter === 'in_stock' && (stockStatus === 'out_of_stock')) {
                showRow = false;
            } else if (stockFilter === 'low_stock' && (stockStatus !== 'low_stock' && stockStatus !== 'very_low_stock')) {
                showRow = false;
            } else if (stockFilter === 'out_of_stock' && stockStatus !== 'out_of_stock') {
                showRow = false;
            }
        }
        
        // Expiry filter
        if (expiryFilter !== 'all') {
            if (expiryFilter === 'expiring_soon' && (expiryStatus !== 'expiring_soon' && expiryStatus !== 'expiring_urgent')) {
                showRow = false;
            } else if (expiryFilter === 'expired' && expiryStatus !== 'expired') {
                showRow = false;
            } else if (expiryFilter === 'fresh' && expiryStatus !== 'fresh') {
                showRow = false;
            }
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

// Refresh inventory data
function refreshInventoryData() {
    location.reload();
}

// Export stock report function
function exportStockReport() {
    // This would implement CSV/Excel export functionality
    alert('Export functionality will be implemented. This would generate a comprehensive stock report.');
    // In a real implementation, you'd send data to a server endpoint for export
}

// Initialize filters on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for real-time filtering
    document.getElementById('searchProduct').addEventListener('keyup', filterProducts);
    document.getElementById('stockFilter').addEventListener('change', filterProducts);
    document.getElementById('expiryFilter').addEventListener('change', filterProducts);
    
    // Responsive table adjustments
    adjustTableForScreen();
});

// Adjust table display based on screen size
function adjustTableForScreen() {
    var table = document.getElementById('inventoryTable');
    var width = window.innerWidth;
    
    if (width < 768) {
        table.style.fontSize = '10px';
    } else if (width < 992) {
        table.style.fontSize = '11px';
    } else {
        table.style.fontSize = '12px';
    }
}

window.addEventListener('resize', adjustTableForScreen);
</script>

<!-- Additional CSS for improved table layout -->
<style>
#inventoryTable {
    table-layout: fixed;
    width: 100%;
}

#inventoryTable th,
#inventoryTable td {
    padding: 6px 3px;
    vertical-align: middle;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}

#inventoryTable .badge {
    white-space: nowrap;
    display: inline-block;
}

#inventoryTable .btn-group {
    white-space: nowrap;
}

.table-responsive {
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 15px;
}

.card .content {
    padding: 15px;
}

@media (max-width: 768px) {
    #inventoryTable th,
    #inventoryTable td {
        padding: 4px 2px;
        font-size: 10px !important;
    }
    
    .btn-group .btn {
        padding: 1px 3px;
    }
}

/* Ensure table fits within viewport at 100% zoom */
@media (min-width: 1200px) {
    #inventoryTable {
        font-size: 12px;
    }
}

/* Container improvements for full width utilization */
.container-fluid {
    max-width: 100%;
    width: 100%;
}

.px-3 {
    padding-left: 8px !important;
    padding-right: 8px !important;
}

/* Card enhancements */
.card {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Gradient cards for statistics */
.card .card-body {
    border-radius: 8px;
}

/* Button improvements */
.btn {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Enhanced responsive design */
@media (max-width: 1200px) {
    .px-3 {
        padding-left: 5px !important;
        padding-right: 5px !important;
    }
}

@media (max-width: 992px) {
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .card-body {
        padding: 1rem 0.75rem;
    }
}

@media (max-width: 768px) {
    .px-3 {
        padding-left: 3px !important;
        padding-right: 3px !important;
    }
    
    .card-header {
        padding: 0.75rem;
    }
    
    .card-body {
        padding: 0.75rem 0.5rem;
    }
    
    h2 {
        font-size: 1.5rem;
    }
}

/* Remove extra margins and maximize space */
.row {
    margin-left: 0;
    margin-right: 0;
}

.col-12 {
    padding-left: 5px;
    padding-right: 5px;
}

/* Table header styling */
#inventoryTable thead th {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

/* Row hover effects */
#inventoryTable tbody tr {
    transition: all 0.2s ease;
}

#inventoryTable tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
