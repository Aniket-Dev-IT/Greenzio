<?php 
// Calculate perishable inventory metrics
$total_perishable = 0;
$fresh_items = 0;
$expiring_soon = 0;
$spoiled_items = 0;
$refrigerated_items = 0;

if (isset($perishableProducts)) {
    foreach ($perishableProducts as $product) {
        $total_perishable++;
        
        // Check storage requirements
        if (isset($product['storage_temp']) && $product['storage_temp'] == 'refrigerated') {
            $refrigerated_items++;
        }
        
        // Check expiry status
        if (isset($product['expiry_date']) && !empty($product['expiry_date'])) {
            $days_to_expiry = floor((strtotime($product['expiry_date']) - strtotime(date('Y-m-d'))) / 86400);
            
            if ($days_to_expiry < 0) {
                $spoiled_items++;
            } elseif ($days_to_expiry <= 3) {
                $expiring_soon++;
            } else {
                $fresh_items++;
            }
        } else {
            $fresh_items++;
        }
    }
}
?>

<div class="content">
    <div class="container-fluid">
        
        <!-- Perishable Overview Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Total Perishable</h4>
                    </div>
                    <div class="content text-center">
                        <h1 class="text-info"><?php echo $total_perishable; ?></h1>
                        <p>Items in inventory</p>
                        <div class="text-info">
                            <i class="fa fa-leaf fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Fresh Items</h4>
                    </div>
                    <div class="content text-center">
                        <h1 class="text-success"><?php echo $fresh_items; ?></h1>
                        <p>Good condition</p>
                        <div class="text-success">
                            <i class="fa fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Expiring Soon</h4>
                    </div>
                    <div class="content text-center">
                        <h1 class="text-warning"><?php echo $expiring_soon; ?></h1>
                        <p>Need quick sale</p>
                        <div class="text-warning">
                            <i class="fa fa-clock-o fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Refrigerated</h4>
                    </div>
                    <div class="content text-center">
                        <h1 class="text-primary"><?php echo $refrigerated_items; ?></h1>
                        <p>Cold storage required</p>
                        <div class="text-primary">
                            <i class="fa fa-snowflake-o fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Perishable Management Actions -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Quality Control</h4>
                        <p class="category">Monitor freshness and quality</p>
                    </div>
                    <div class="content">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-success btn-block" onclick="markAsFresh()">
                                    <i class="fa fa-leaf"></i> Mark as Fresh
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-warning btn-block" onclick="applyQuickSale()">
                                    <i class="fa fa-percent"></i> Quick Sale Price
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-danger btn-block" onclick="removeSpoiled()">
                                    <i class="fa fa-trash"></i> Remove Spoiled
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-info btn-block" onclick="updateQualityGrade()">
                                    <i class="fa fa-star"></i> Grade Quality
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Storage Management</h4>
                        <p class="category">Optimize storage conditions</p>
                    </div>
                    <div class="content">
                        <form method="post" action="<?php echo base_url('admin/updateStorageConditions'); ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Temperature (°C)</label>
                                        <input type="number" class="form-control" name="temperature" value="4" min="-20" max="30">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Humidity (%)</label>
                                        <input type="number" class="form-control" name="humidity" value="85" min="0" max="100">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Storage Location</label>
                                        <select class="form-control" name="storage_location">
                                            <option value="refrigerated_section">Refrigerated Section</option>
                                            <option value="ambient_storage">Ambient Storage</option>
                                            <option value="frozen_section">Frozen Section</option>
                                            <option value="dry_storage">Dry Storage</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-fill btn-block">
                                        <i class="fa fa-save"></i> Update Storage Settings
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Perishable Items by Category -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Fruits & Vegetables</h4>
                        <p class="category">Fresh produce inventory</p>
                    </div>
                    <div class="content table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (isset($perishableProducts)) {
                                    $fruits_vegetables = array_filter($perishableProducts, function($product) {
                                        return $product['category'] === 'Fruits & Vegetables';
                                    });
                                    
                                    foreach (array_slice($fruits_vegetables, 0, 5) as $product) {
                                        $status_class = 'success';
                                        $status_text = 'Fresh';
                                        
                                        if (isset($product['expiry_date']) && !empty($product['expiry_date'])) {
                                            $days_to_expiry = floor((strtotime($product['expiry_date']) - strtotime(date('Y-m-d'))) / 86400);
                                            
                                            if ($days_to_expiry < 0) {
                                                $status_class = 'danger';
                                                $status_text = 'Expired';
                                            } elseif ($days_to_expiry <= 2) {
                                                $status_class = 'warning';
                                                $status_text = 'Expiring';
                                            }
                                        }
                                ?>
                                <tr>
                                    <td><?php echo $product['pname']; ?></td>
                                    <td><?php echo $product['stock_quantity']; ?></td>
                                    <td><span class="badge badge-<?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
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
            
            <div class="col-md-4">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Dairy & Bakery</h4>
                        <p class="category">Refrigerated products</p>
                    </div>
                    <div class="content table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                    <th>Expiry</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (isset($perishableProducts)) {
                                    $dairy_bakery = array_filter($perishableProducts, function($product) {
                                        return $product['category'] === 'Dairy & Bakery';
                                    });
                                    
                                    foreach (array_slice($dairy_bakery, 0, 5) as $product) {
                                        $expiry_display = 'N/A';
                                        if (isset($product['expiry_date']) && !empty($product['expiry_date'])) {
                                            $expiry_display = date('M d', strtotime($product['expiry_date']));
                                        }
                                ?>
                                <tr>
                                    <td><?php echo $product['pname']; ?></td>
                                    <td><?php echo $product['stock_quantity']; ?></td>
                                    <td><?php echo $expiry_display; ?></td>
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
            
            <div class="col-md-4">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Quality Alerts</h4>
                        <p class="category">Items requiring attention</p>
                    </div>
                    <div class="content">
                        <div class="table-full-width">
                            <table class="table">
                                <tbody>
                                    <?php 
                                    if (isset($perishableProducts)) {
                                        $alert_count = 0;
                                        foreach ($perishableProducts as $product) {
                                            if ($alert_count >= 6) break;
                                            
                                            if (isset($product['expiry_date']) && !empty($product['expiry_date'])) {
                                                $days_to_expiry = floor((strtotime($product['expiry_date']) - strtotime(date('Y-m-d'))) / 86400);
                                                
                                                if ($days_to_expiry <= 2) {
                                                    $alert_count++;
                                    ?>
                                    <tr class="<?php echo $days_to_expiry < 0 ? 'danger' : 'warning'; ?>">
                                        <td>
                                            <strong><?php echo $product['pname']; ?></strong>
                                            <br><small>
                                                <?php 
                                                if ($days_to_expiry < 0) {
                                                    echo 'Expired ' . abs($days_to_expiry) . ' days ago';
                                                } else {
                                                    echo 'Expires in ' . $days_to_expiry . ' day' . ($days_to_expiry != 1 ? 's' : '');
                                                }
                                                ?>
                                            </small>
                                        </td>
                                        <td>
                                            <button class="btn btn-xs btn-warning" onclick="handlePerishableAlert(<?php echo $product['pid']; ?>)">
                                                <i class="fa fa-exclamation"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php 
                                                }
                                            }
                                        }
                                        
                                        if ($alert_count === 0) {
                                            echo '<tr><td colspan="2" class="text-center text-success">No quality alerts</td></tr>';
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

        <!-- Detailed Perishable Inventory Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Detailed Perishable Inventory</h4>
                        <p class="category">Complete overview of all perishable items</p>
                        <div class="pull-right">
                            <button class="btn btn-sm btn-success" onclick="addPerishableItem()">
                                <i class="fa fa-plus"></i> Add Item
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="bulkQualityCheck()">
                                <i class="fa fa-check"></i> Quality Check
                            </button>
                        </div>
                    </div>
                    <div class="content table-responsive">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" id="searchPerishable" class="form-control" placeholder="Search products...">
                            </div>
                            <div class="col-md-3">
                                <select id="categoryFilter" class="form-control">
                                    <option value="all">All Categories</option>
                                    <option value="Fruits & Vegetables">Fruits & Vegetables</option>
                                    <option value="Dairy & Bakery">Dairy & Bakery</option>
                                    <option value="Beverages">Beverages</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="statusFilter" class="form-control">
                                    <option value="all">All Status</option>
                                    <option value="fresh">Fresh</option>
                                    <option value="expiring">Expiring Soon</option>
                                    <option value="expired">Expired</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="storageFilter" class="form-control">
                                    <option value="all">All Storage</option>
                                    <option value="refrigerated">Refrigerated</option>
                                    <option value="frozen">Frozen</option>
                                    <option value="room_temperature">Room Temperature</option>
                                </select>
                            </div>
                        </div>
                        
                        <table class="table table-striped table-hover" id="perishableTable">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Expiry Date</th>
                                    <th>Days Left</th>
                                    <th>Storage</th>
                                    <th>Quality</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($perishableProducts) && !empty($perishableProducts)): ?>
                                    <?php foreach ($perishableProducts as $product): ?>
                                        <?php
                                        // Calculate status
                                        $status = 'fresh';
                                        $status_class = 'success';
                                        $days_left = 'N/A';
                                        
                                        if (isset($product['expiry_date']) && !empty($product['expiry_date'])) {
                                            $days_to_expiry = floor((strtotime($product['expiry_date']) - strtotime(date('Y-m-d'))) / 86400);
                                            $days_left = $days_to_expiry;
                                            
                                            if ($days_to_expiry < 0) {
                                                $status = 'expired';
                                                $status_class = 'danger';
                                                $days_left = 'Expired';
                                            } elseif ($days_to_expiry <= 3) {
                                                $status = 'expiring';
                                                $status_class = 'warning';
                                                $days_left = $days_to_expiry . ' days';
                                            } else {
                                                $days_left = $days_to_expiry . ' days';
                                            }
                                        }
                                        ?>
                                        <tr class="perishable-row" 
                                            data-category="<?php echo $product['category']; ?>"
                                            data-status="<?php echo $status; ?>"
                                            data-storage="<?php echo isset($product['storage_temp']) ? $product['storage_temp'] : 'room_temperature'; ?>"
                                            data-name="<?php echo strtolower($product['pname']); ?>">
                                            <td>
                                                <strong><?php echo $product['pname']; ?></strong>
                                                <br><small class="text-muted"><?php echo $product['weight'] . ' ' . $product['unit']; ?></small>
                                            </td>
                                            <td><?php echo $product['category']; ?></td>
                                            <td class="<?php echo $product['stock_quantity'] <= 10 ? 'text-warning' : ''; ?>">
                                                <?php echo $product['stock_quantity']; ?> units
                                            </td>
                                            <td><?php echo isset($product['expiry_date']) && !empty($product['expiry_date']) ? date('M d, Y', strtotime($product['expiry_date'])) : 'N/A'; ?></td>
                                            <td class="<?php echo $status_class == 'danger' ? 'text-danger' : ($status_class == 'warning' ? 'text-warning' : ''); ?>">
                                                <?php echo $days_left; ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?php echo isset($product['storage_temp']) ? ucwords(str_replace('_', ' ', $product['storage_temp'])) : 'Room Temp'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo $status_class; ?>">
                                                    <?php echo ucfirst($status); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-xs btn-info" onclick="updateQuality(<?php echo $product['pid']; ?>)" title="Update Quality">
                                                        <i class="fa fa-star"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-warning" onclick="adjustPrice(<?php echo $product['pid']; ?>)" title="Adjust Price">
                                                        <i class="fa fa-tag"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-success" onclick="updateExpiry(<?php echo $product['pid']; ?>)" title="Update Expiry">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No perishable products found</td>
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
// Perishable inventory management functions
function markAsFresh() {
    var selected = getSelectedProducts();
    if (selected.length === 0) {
        alert('Please select products first.');
        return;
    }
    
    if (confirm('Mark selected products as fresh?')) {
        // Implementation for marking as fresh
        console.log('Marking as fresh:', selected);
    }
}

function applyQuickSale() {
    var discount = prompt('Enter discount percentage for quick sale (10-50):', '25');
    if (discount && discount >= 10 && discount <= 50) {
        window.location.href = '<?php echo base_url("admin/applyQuickSaleDiscount?discount="); ?>' + discount;
    }
}

function removeSpoiled() {
    if (confirm('Remove all expired/spoiled products from inventory?')) {
        window.location.href = '<?php echo base_url("admin/removeSpoiledProducts"); ?>';
    }
}

function updateQualityGrade() {
    var grade = prompt('Enter quality grade (A, B, C):', 'A');
    if (grade && ['A', 'B', 'C'].includes(grade.toUpperCase())) {
        // Implementation for updating quality grade
        console.log('Updating quality grade to:', grade);
    }
}

function handlePerishableAlert(productId) {
    var action = prompt('Choose action: discount, extend, remove', 'discount');
    if (action && ['discount', 'extend', 'remove'].includes(action)) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo base_url("admin/handlePerishableAlert"); ?>';
        
        var productInput = document.createElement('input');
        productInput.type = 'hidden';
        productInput.name = 'product_id';
        productInput.value = productId;
        
        var actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        
        form.appendChild(productInput);
        form.appendChild(actionInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function updateQuality(productId) {
    var quality = prompt('Enter quality rating (1-5):', '4');
    if (quality && quality >= 1 && quality <= 5) {
        // Implementation for updating quality
        console.log('Updating quality for product', productId, 'to', quality);
    }
}

function adjustPrice(productId) {
    var newPrice = prompt('Enter new price:');
    if (newPrice && newPrice > 0) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo base_url("admin/updateProductPrice"); ?>';
        
        var productInput = document.createElement('input');
        productInput.type = 'hidden';
        productInput.name = 'product_id';
        productInput.value = productId;
        
        var priceInput = document.createElement('input');
        priceInput.type = 'hidden';
        priceInput.name = 'new_price';
        priceInput.value = newPrice;
        
        form.appendChild(productInput);
        form.appendChild(priceInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function updateExpiry(productId) {
    var newDate = prompt('Enter new expiry date (YYYY-MM-DD):');
    if (newDate) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo base_url("admin/updateExpiryDate"); ?>';
        
        var productInput = document.createElement('input');
        productInput.type = 'hidden';
        productInput.name = 'product_id';
        productInput.value = productId;
        
        var dateInput = document.createElement('input');
        dateInput.type = 'hidden';
        dateInput.name = 'expiry_date';
        dateInput.value = newDate;
        
        form.appendChild(productInput);
        form.appendChild(dateInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function addPerishableItem() {
    window.location.href = '<?php echo base_url("admin/productinsert"); ?>';
}

function bulkQualityCheck() {
    window.location.href = '<?php echo base_url("admin/bulkQualityCheck"); ?>';
}

// Filter functions
function filterPerishableProducts() {
    var searchTerm = document.getElementById('searchPerishable').value.toLowerCase();
    var categoryFilter = document.getElementById('categoryFilter').value;
    var statusFilter = document.getElementById('statusFilter').value;
    var storageFilter = document.getElementById('storageFilter').value;
    var rows = document.querySelectorAll('#perishableTable tbody .perishable-row');
    
    rows.forEach(function(row) {
        var productName = row.getAttribute('data-name');
        var category = row.getAttribute('data-category');
        var status = row.getAttribute('data-status');
        var storage = row.getAttribute('data-storage');
        
        var showRow = true;
        
        if (searchTerm && !productName.includes(searchTerm)) {
            showRow = false;
        }
        
        if (categoryFilter !== 'all' && category !== categoryFilter) {
            showRow = false;
        }
        
        if (statusFilter !== 'all' && status !== statusFilter) {
            showRow = false;
        }
        
        if (storageFilter !== 'all' && storage !== storageFilter) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

function getSelectedProducts() {
    // This would get selected products if we had checkboxes
    return [];
}

// Initialize filters
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('searchPerishable')) {
        document.getElementById('searchPerishable').addEventListener('keyup', filterPerishableProducts);
        document.getElementById('categoryFilter').addEventListener('change', filterPerishableProducts);
        document.getElementById('statusFilter').addEventListener('change', filterPerishableProducts);
        document.getElementById('storageFilter').addEventListener('change', filterPerishableProducts);
    }
});
</script>
