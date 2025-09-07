<?php 
// Calculate expiry metrics
$expired_count = 0;
$expiring_urgent = 0;  // 1-3 days
$expiring_soon = 0;    // 4-7 days
$expiring_this_month = 0; // 8-30 days

if (isset($productDetails)) {
    foreach ($productDetails as $product) {
        if (isset($product['expiry_date']) && !empty($product['expiry_date'])) {
            $days_to_expiry = floor((strtotime($product['expiry_date']) - strtotime(date('Y-m-d'))) / 86400);
            
            if ($days_to_expiry < 0) {
                $expired_count++;
            } elseif ($days_to_expiry <= 3) {
                $expiring_urgent++;
            } elseif ($days_to_expiry <= 7) {
                $expiring_soon++;
            } elseif ($days_to_expiry <= 30) {
                $expiring_this_month++;
            }
        }
    }
}
?>

<div class="content">
    <div class="container-fluid">
        
        <!-- Expiry Overview Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Expired Products</h4>
                    </div>
                    <div class="content text-center">
                        <h1 class="text-danger"><?php echo $expired_count; ?></h1>
                        <p>Need immediate removal</p>
                        <div class="text-danger">
                            <i class="fa fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Urgent Alert</h4>
                    </div>
                    <div class="content text-center">
                        <h1 class="text-danger"><?php echo $expiring_urgent; ?></h1>
                        <p>Expires in 1-3 days</p>
                        <div class="text-danger">
                            <i class="fa fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Warning</h4>
                    </div>
                    <div class="content text-center">
                        <h1 class="text-warning"><?php echo $expiring_soon; ?></h1>
                        <p>Expires in 4-7 days</p>
                        <div class="text-warning">
                            <i class="fa fa-clock-o fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">This Month</h4>
                    </div>
                    <div class="content text-center">
                        <h1 class="text-info"><?php echo $expiring_this_month; ?></h1>
                        <p>Expires within 30 days</p>
                        <div class="text-info">
                            <i class="fa fa-calendar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Quick Actions</h4>
                        <p class="category">Manage expiry alerts efficiently</p>
                    </div>
                    <div class="content">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-danger btn-block" onclick="markExpiredAsRemoved()">
                                    <i class="fa fa-trash"></i> Remove Expired Items
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-warning btn-block" onclick="discountExpiringSoon()">
                                    <i class="fa fa-percent"></i> Apply Discount
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-info btn-block" onclick="generateExpiryReport()">
                                    <i class="fa fa-file-text"></i> Generate Report
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-success btn-block" onclick="bulkUpdateExpiry()">
                                    <i class="fa fa-calendar-plus-o"></i> Bulk Update Expiry
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Expiry Settings</h4>
                        <p class="category">Configure alert preferences</p>
                    </div>
                    <div class="content">
                        <form method="post" action="<?php echo base_url('admin/updateExpirySettings'); ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Urgent Alert (Days)</label>
                                        <input type="number" class="form-control" name="urgent_days" value="3" min="1" max="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Warning Alert (Days)</label>
                                        <input type="number" class="form-control" name="warning_days" value="7" min="3" max="30">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <input id="emailNotifications" type="checkbox" name="email_notifications" checked>
                                        <label for="emailNotifications">Send email notifications</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <input id="autoDiscount" type="checkbox" name="auto_discount">
                                        <label for="autoDiscount">Auto-apply discount to expiring items</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-info btn-fill btn-block">
                                        <i class="fa fa-save"></i> Save Settings
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expired Products -->
        <?php if ($expired_count > 0): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-danger">
                            <i class="fa fa-times-circle"></i> Expired Products - Immediate Action Required
                        </h4>
                        <p class="category">These products have already expired and should be removed from inventory</p>
                    </div>
                    <div class="content table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Expiry Date</th>
                                    <th>Days Expired</th>
                                    <th>Current Stock</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (isset($productDetails)) {
                                    foreach ($productDetails as $product) {
                                        if (isset($product['expiry_date']) && !empty($product['expiry_date'])) {
                                            $days_to_expiry = floor((strtotime($product['expiry_date']) - strtotime(date('Y-m-d'))) / 86400);
                                            if ($days_to_expiry < 0) {
                                ?>
                                <tr class="danger">
                                    <td><strong><?php echo $product['pname']; ?></strong></td>
                                    <td><?php echo $product['category']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($product['expiry_date'])); ?></td>
                                    <td class="text-danger">
                                        <strong><?php echo abs($days_to_expiry); ?> days ago</strong>
                                    </td>
                                    <td><?php echo $product['stock_quantity']; ?> units</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-xs btn-danger" onclick="removeExpiredProduct(<?php echo $product['pid']; ?>)" title="Remove from inventory">
                                                <i class="fa fa-trash"></i> Remove
                                            </button>
                                            <button class="btn btn-xs btn-warning" onclick="updateExpiry(<?php echo $product['pid']; ?>)" title="Update expiry date">
                                                <i class="fa fa-calendar"></i> Update
                                            </button>
                                        </div>
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
        <?php endif; ?>

        <!-- Urgent Expiry Alert -->
        <?php if ($expiring_urgent > 0): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-danger">
                            <i class="fa fa-exclamation-triangle"></i> Urgent Alert - Expires in 1-3 Days
                        </h4>
                        <p class="category">These products need immediate attention</p>
                    </div>
                    <div class="content table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Expiry Date</th>
                                    <th>Days Left</th>
                                    <th>Current Stock</th>
                                    <th>Current Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (isset($productDetails)) {
                                    foreach ($productDetails as $product) {
                                        if (isset($product['expiry_date']) && !empty($product['expiry_date'])) {
                                            $days_to_expiry = floor((strtotime($product['expiry_date']) - strtotime(date('Y-m-d'))) / 86400);
                                            if ($days_to_expiry >= 0 && $days_to_expiry <= 3) {
                                ?>
                                <tr class="danger">
                                    <td><strong><?php echo $product['pname']; ?></strong></td>
                                    <td><?php echo $product['category']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($product['expiry_date'])); ?></td>
                                    <td class="text-danger">
                                        <strong><?php echo $days_to_expiry; ?> day<?php echo $days_to_expiry != 1 ? 's' : ''; ?></strong>
                                    </td>
                                    <td><?php echo $product['stock_quantity']; ?> units</td>
                                    <td>₹<?php echo number_format($product['price'], 2); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-xs btn-warning" onclick="applyDiscount(<?php echo $product['pid']; ?>)" title="Apply discount">
                                                <i class="fa fa-percent"></i> Discount
                                            </button>
                                            <button class="btn btn-xs btn-info" onclick="updateExpiry(<?php echo $product['pid']; ?>)" title="Update expiry">
                                                <i class="fa fa-calendar"></i> Update
                                            </button>
                                            <button class="btn btn-xs btn-success" onclick="moveToSale(<?php echo $product['pid']; ?>)" title="Move to clearance sale">
                                                <i class="fa fa-tag"></i> Sale
                                            </button>
                                        </div>
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
        <?php endif; ?>

        <!-- Warning Expiry Alert -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-warning">
                            <i class="fa fa-clock-o"></i> Warning - Expires in 4-7 Days
                        </h4>
                        <p class="category">Plan ahead for these products</p>
                        <div class="pull-right">
                            <button class="btn btn-sm btn-warning" onclick="bulkActionExpiringSoon()">
                                <i class="fa fa-cogs"></i> Bulk Actions
                            </button>
                        </div>
                    </div>
                    <div class="content table-responsive">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" id="searchExpiring" class="form-control" placeholder="Search expiring products...">
                            </div>
                            <div class="col-md-4">
                                <select id="expiringCategoryFilter" class="form-control">
                                    <option value="all">All Categories</option>
                                    <option value="Fruits & Vegetables">Fruits & Vegetables</option>
                                    <option value="Dairy & Bakery">Dairy & Bakery</option>
                                    <option value="Grains & Rice">Grains & Rice</option>
                                    <option value="Pulses & Beans">Pulses & Beans</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="expiringDaysFilter" class="form-control">
                                    <option value="all">All Time Ranges</option>
                                    <option value="4">4 days left</option>
                                    <option value="5">5 days left</option>
                                    <option value="6">6 days left</option>
                                    <option value="7">7 days left</option>
                                </select>
                            </div>
                        </div>
                        
                        <table class="table table-striped" id="expiringTable">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox">
                                            <input id="selectAllExpiring" type="checkbox" onchange="toggleAllExpiring()">
                                            <label for="selectAllExpiring"></label>
                                        </div>
                                    </th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Expiry Date</th>
                                    <th>Days Left</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (isset($productDetails)) {
                                    foreach ($productDetails as $product) {
                                        if (isset($product['expiry_date']) && !empty($product['expiry_date'])) {
                                            $days_to_expiry = floor((strtotime($product['expiry_date']) - strtotime(date('Y-m-d'))) / 86400);
                                            if ($days_to_expiry >= 4 && $days_to_expiry <= 7) {
                                ?>
                                <tr class="warning expiring-row" 
                                    data-category="<?php echo $product['category']; ?>"
                                    data-days="<?php echo $days_to_expiry; ?>"
                                    data-name="<?php echo strtolower($product['pname']); ?>">
                                    <td>
                                        <div class="checkbox">
                                            <input id="expiring_<?php echo $product['pid']; ?>" 
                                                   type="checkbox" 
                                                   name="expiring_products[]" 
                                                   value="<?php echo $product['pid']; ?>">
                                            <label for="expiring_<?php echo $product['pid']; ?>"></label>
                                        </div>
                                    </td>
                                    <td><strong><?php echo $product['pname']; ?></strong></td>
                                    <td><?php echo $product['category']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($product['expiry_date'])); ?></td>
                                    <td class="text-warning">
                                        <strong><?php echo $days_to_expiry; ?> day<?php echo $days_to_expiry != 1 ? 's' : ''; ?></strong>
                                    </td>
                                    <td><?php echo $product['stock_quantity']; ?></td>
                                    <td>₹<?php echo number_format($product['price'], 2); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-xs btn-warning" onclick="applyDiscount(<?php echo $product['pid']; ?>)">
                                                <i class="fa fa-percent"></i>
                                            </button>
                                            <button class="btn btn-xs btn-info" onclick="updateExpiry(<?php echo $product['pid']; ?>)">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                            <button class="btn btn-xs btn-primary" onclick="promoteProduct(<?php echo $product['pid']; ?>)">
                                                <i class="fa fa-bullhorn"></i>
                                            </button>
                                        </div>
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

    </div>
</div>

<script>
// Expiry management functions
function removeExpiredProduct(productId) {
    if (confirm('Are you sure you want to remove this expired product from inventory?')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo base_url("admin/removeExpiredProduct"); ?>';
        
        var productInput = document.createElement('input');
        productInput.type = 'hidden';
        productInput.name = 'product_id';
        productInput.value = productId;
        
        form.appendChild(productInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function updateExpiry(productId) {
    var newDate = prompt('Enter new expiry date (YYYY-MM-DD):');
    if (newDate && isValidDate(newDate)) {
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

function applyDiscount(productId) {
    var discount = prompt('Enter discount percentage (0-90):');
    if (discount && discount >= 0 && discount <= 90) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo base_url("admin/applyExpiryDiscount"); ?>';
        
        var productInput = document.createElement('input');
        productInput.type = 'hidden';
        productInput.name = 'product_id';
        productInput.value = productId;
        
        var discountInput = document.createElement('input');
        discountInput.type = 'hidden';
        discountInput.name = 'discount';
        discountInput.value = discount;
        
        form.appendChild(productInput);
        form.appendChild(discountInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function moveToSale(productId) {
    if (confirm('Move this product to clearance sale?')) {
        window.location.href = '<?php echo base_url("admin/moveToSale?id="); ?>' + productId;
    }
}

function promoteProduct(productId) {
    if (confirm('Add this product to promotional campaigns?')) {
        window.location.href = '<?php echo base_url("admin/addToPromotion?id="); ?>' + productId;
    }
}

function markExpiredAsRemoved() {
    if (confirm('Mark all expired products as removed from inventory?')) {
        window.location.href = '<?php echo base_url("admin/markExpiredAsRemoved"); ?>';
    }
}

function discountExpiringSoon() {
    var discount = prompt('Enter discount percentage for products expiring soon (0-50):', '20');
    if (discount && discount >= 0 && discount <= 50) {
        window.location.href = '<?php echo base_url("admin/bulkDiscountExpiringSoon?discount="); ?>' + discount;
    }
}

function generateExpiryReport() {
    window.open('<?php echo base_url("admin/generateExpiryReport"); ?>', '_blank');
}

function bulkUpdateExpiry() {
    window.location.href = '<?php echo base_url("admin/bulkExpiryUpdate"); ?>';
}

function bulkActionExpiringSoon() {
    var selected = document.querySelectorAll('input[name="expiring_products[]"]:checked');
    if (selected.length === 0) {
        alert('Please select products first.');
        return;
    }
    
    var action = prompt('Choose action: discount, promote, or extend_expiry', 'discount');
    if (action && ['discount', 'promote', 'extend_expiry'].includes(action)) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo base_url("admin/bulkExpiryAction"); ?>';
        
        var actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        
        form.appendChild(actionInput);
        
        selected.forEach(function(checkbox) {
            var productInput = document.createElement('input');
            productInput.type = 'hidden';
            productInput.name = 'product_ids[]';
            productInput.value = checkbox.value;
            form.appendChild(productInput);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

function toggleAllExpiring() {
    var masterCheckbox = document.getElementById('selectAllExpiring');
    var checkboxes = document.querySelectorAll('input[name="expiring_products[]"]');
    
    checkboxes.forEach(function(checkbox) {
        if (checkbox.closest('tr').style.display !== 'none') {
            checkbox.checked = masterCheckbox.checked;
        }
    });
}

function filterExpiringProducts() {
    var searchTerm = document.getElementById('searchExpiring').value.toLowerCase();
    var categoryFilter = document.getElementById('expiringCategoryFilter').value;
    var daysFilter = document.getElementById('expiringDaysFilter').value;
    var rows = document.querySelectorAll('#expiringTable tbody .expiring-row');
    
    rows.forEach(function(row) {
        var productName = row.getAttribute('data-name');
        var category = row.getAttribute('data-category');
        var days = row.getAttribute('data-days');
        
        var showRow = true;
        
        if (searchTerm && !productName.includes(searchTerm)) {
            showRow = false;
        }
        
        if (categoryFilter !== 'all' && category !== categoryFilter) {
            showRow = false;
        }
        
        if (daysFilter !== 'all' && days !== daysFilter) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

function isValidDate(dateString) {
    var regEx = /^\d{4}-\d{2}-\d{2}$/;
    if(!dateString.match(regEx)) return false;
    var d = new Date(dateString);
    var dNum = d.getTime();
    if(!dNum && dNum !== 0) return false;
    return d.toISOString().slice(0,10) === dateString;
}

// Initialize filters
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('searchExpiring')) {
        document.getElementById('searchExpiring').addEventListener('keyup', filterExpiringProducts);
        document.getElementById('expiringCategoryFilter').addEventListener('change', filterExpiringProducts);
        document.getElementById('expiringDaysFilter').addEventListener('change', filterExpiringProducts);
    }
});
</script>
