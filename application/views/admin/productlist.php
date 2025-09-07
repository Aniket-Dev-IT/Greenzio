<?php 
// print_r($productDetails);

?>

<div class="content">
    <div class="container-fluid px-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 pt-3">
            <div>
                <h2 class="mb-1">Product Management</h2>
                <p class="text-muted mb-0">Manage all your products, stock levels, and inventory</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" onclick="window.location.href='<?php echo base_url('admin/addProduct'); ?>'">
                    <i class="fas fa-plus me-2"></i>Add Product
                </button>
                <button class="btn btn-outline-secondary" onclick="exportTable()">
                    <i class="fas fa-download me-2"></i>Export
                </button>
            </div>
        </div>

    <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="card-title text-primary mb-0">
                            <i class="fas fa-boxes me-2"></i>Product List
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <!-- Advanced Search and Filter Controls -->
                        <div class="bg-light p-3 border-bottom">
                            <div class="row g-3 align-items-end">
                                <div class="col-lg-4 col-md-6">
                                    <label class="form-label fw-semibold">Search Products</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" id="productSearch" class="form-control" placeholder="Search by name, brand, category..." onkeyup="filterTable()">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3">
                                    <label class="form-label fw-semibold">Category</label>
                                    <select id="categoryFilter" class="form-select" onchange="filterTable()">
                                        <option value="">All Categories</option>
                                        <?php 
                                        $categories = array();
                                        foreach ($productDetails as $detail) {
                                            if (!in_array($detail['category'], $categories)) {
                                                $categories[] = $detail['category'];
                                            }
                                        }
                                        foreach ($categories as $cat) {
                                            echo '<option value="'.$cat.'">'.$cat.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-3">
                                    <label class="form-label fw-semibold">Stock Level</label>
                                    <select id="stockFilter" class="form-select" onchange="filterTable()">
                                        <option value="">All Levels</option>
                                        <option value="low">Low Stock</option>
                                        <option value="out">Out of Stock</option>
                                        <option value="good">Good Stock</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <label class="form-label fw-semibold">Price Range</label>
                                    <select id="priceFilter" class="form-select" onchange="filterTable()">
                                        <option value="">All Prices</option>
                                        <option value="0-50">Under ₹50</option>
                                        <option value="50-100">₹50 - ₹100</option>
                                        <option value="100-200">₹100 - ₹200</option>
                                        <option value="200+">Above ₹200</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-outline-primary flex-fill" onclick="resetFilters()" title="Reset Filters">
                                            <i class="fas fa-undo"></i> Reset
                                        </button>
                                        <button class="btn btn-success flex-fill" onclick="refreshData()" title="Refresh Data">
                                            <i class="fas fa-sync-alt"></i> Refresh
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statistics Row -->
                        <div class="row g-3 p-3 bg-white border-bottom">
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-soft p-2 rounded me-2">
                                        <i class="fas fa-boxes text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold"><?php echo count($productDetails); ?></div>
                                        <small class="text-muted">Total Products</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success-soft p-2 rounded me-2">
                                        <i class="fas fa-check-circle text-success"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-success"><?php 
                                            $in_stock = 0;
                                            foreach ($productDetails as $p) { 
                                                if (isset($p['stock_quantity']) && $p['stock_quantity'] > 10) $in_stock++; 
                                            } 
                                            echo $in_stock; 
                                        ?></div>
                                        <small class="text-muted">In Stock</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning-soft p-2 rounded me-2">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-warning"><?php 
                                            $low_stock = 0;
                                            foreach ($productDetails as $p) { 
                                                if (isset($p['stock_quantity']) && $p['stock_quantity'] > 0 && $p['stock_quantity'] <= 10) $low_stock++; 
                                            } 
                                            echo $low_stock; 
                                        ?></div>
                                        <small class="text-muted">Low Stock</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger-soft p-2 rounded me-2">
                                        <i class="fas fa-times-circle text-danger"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-danger"><?php 
                                            $out_stock = 0;
                                            foreach ($productDetails as $p) { 
                                                if (isset($p['stock_quantity']) && $p['stock_quantity'] <= 0) $out_stock++; 
                                            } 
                                            echo $out_stock; 
                                        ?></div>
                                        <small class="text-muted">Out of Stock</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0" id="productsTable" style="font-size: 14px;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 4%;">ID</th>
                                        <th style="width: 18%;">Product Name</th>
                                        <th style="width: 10%;">Category</th>
                                        <th style="width: 10%;">SubCategory</th>
                                        <th style="width: 8%;">Brand</th>
                                        <th style="width: 8%;">Price</th>
                                        <th style="width: 5%;">Disc%</th>
                                        <th style="width: 8%;">Weight/Unit</th>
                                        <th style="width: 7%;">Stock</th>
                                        <th style="width: 8%;">Expiry Date</th>
                                        <th style="width: 6%;">Type</th>
                                        <th style="width: 4%;">Image</th>
                                        <th style="width: 4%;">Actions</th>
                                    </tr>
                                </thead>
                            <tbody>
                                <?php foreach ($productDetails as $details) { 
                                    // Handle stock quantity display with status
                                    $stockStatus = '';
                                    $stockClass = '';
                                    if (isset($details['stock_quantity'])) {
                                        if ($details['stock_quantity'] <= 0) {
                                            $stockStatus = ' (Out of Stock)';
                                            $stockClass = 'text-danger';
                                        } elseif ($details['stock_quantity'] <= 10) {
                                            $stockStatus = ' (Low Stock)';
                                            $stockClass = 'text-warning';
                                        } else {
                                            $stockClass = 'text-success';
                                        }
                                    }
                                    
                                    // Handle expiry date with status
                                    $expiryStatus = '';
                                    $expiryClass = '';
                                    if (isset($details['expiry_date']) && !empty($details['expiry_date'])) {
                                        $expiryDate = date('Y-m-d', strtotime($details['expiry_date']));
                                        $today = date('Y-m-d');
                                        $daysToExpiry = floor((strtotime($expiryDate) - strtotime($today)) / 86400);
                                        
                                        if ($daysToExpiry < 0) {
                                            $expiryStatus = ' (Expired)';
                                            $expiryClass = 'text-danger';
                                        } elseif ($daysToExpiry <= 7) {
                                            $expiryStatus = ' (Expires Soon)';
                                            $expiryClass = 'text-warning';
                                        }
                                    }
                                ?>
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-secondary"><?php echo $details['pid']; ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo base_url().$details['pimage']; ?>" class="img-thumbnail me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                <div>
                                                    <div class="fw-bold" style="font-size: 13px;"><?php echo $details['pname']; ?></div>
                                                    <small class="text-muted">SKU: #<?php echo $details['pid']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-soft text-primary"><?php echo $details['category']; ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?php echo $details['subcategory']; ?></small>
                                        </td>
                                        <td>
                                            <small class="fw-semibold"><?php echo isset($details['brand']) ? $details['brand'] : '-'; ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-success">₹<?php echo number_format($details['price'], 0); ?></div>
                                            <?php if($details['discount'] > 0): ?>
                                                <small class="text-danger">-<?php echo $details['discount']; ?>%</small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if($details['discount'] > 0): ?>
                                                <span class="badge bg-danger"><?php echo $details['discount']; ?>%</span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="fw-semibold"><?php echo (isset($details['weight']) ? $details['weight'] : '-') . ' ' . (isset($details['unit']) ? $details['unit'] : ''); ?></small>
                                        </td>
                                        <td class="text-center">
                                            <div class="fw-bold <?php echo $stockClass; ?>"><?php echo isset($details['stock_quantity']) ? $details['stock_quantity'] : '0'; ?></div>
                                            <?php if (isset($details['stock_quantity']) && $details['stock_quantity'] <= 0): ?>
                                                <span class="badge bg-danger">Out</span>
                                            <?php elseif (isset($details['stock_quantity']) && $details['stock_quantity'] <= 10): ?>
                                                <span class="badge bg-warning">Low</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Good</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="<?php echo $expiryClass; ?>">
                                            <?php if (isset($details['expiry_date']) && !empty($details['expiry_date'])): ?>
                                                <div style="font-size: 12px;"><?php echo date('M d, Y', strtotime($details['expiry_date'])); ?></div>
                                                <?php if ($expiryStatus): ?>
                                                    <small class="<?php echo $expiryClass; ?>"><?php echo $expiryStatus; ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">No expiry</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php 
                                            $productType = isset($details['product_type']) ? $details['product_type'] : 'regular';
                                            switch($productType) {
                                                case 'perishable': 
                                                    echo '<span class="badge bg-warning text-dark">Perishable</span>';
                                                    break;
                                                case 'organic': 
                                                    echo '<span class="badge bg-success">Organic</span>';
                                                    break;
                                                case 'premium': 
                                                    echo '<span class="badge bg-info">Premium</span>';
                                                    break;
                                                default: 
                                                    echo '<span class="badge bg-secondary">Regular</span>';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <!-- Image moved to Product Name column -->
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo base_url() . 'admin/editProduct?id=' . $details['pid']; ?>" class="btn btn-outline-primary" title="Edit Product">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?php echo base_url() . 'admin/deleteProduct?id=' . $details['pid']; ?>" class="btn btn-outline-danger" title="Delete Product" onclick="return confirm('Are you sure you want to delete this product?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Add JavaScript for filtering -->
<script>
function filterTable() {
    var searchInput = document.getElementById('productSearch').value.toLowerCase();
    var categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
    var stockFilter = document.getElementById('stockFilter').value.toLowerCase();
    var priceFilter = document.getElementById('priceFilter').value;
    var table = document.getElementById('productsTable');
    var rows = table.getElementsByTagName('tr');
    
    var visibleCount = 0;
    
    for (var i = 1; i < rows.length; i++) {
        var cells = rows[i].getElementsByTagName('td');
        if (cells.length > 0) {
            var productName = cells[1].textContent.toLowerCase();
            var brand = cells[4].textContent.toLowerCase();
            var category = cells[2].textContent.toLowerCase();
            var priceText = cells[5].textContent.replace('₹', '').replace(',', '');
            var price = parseFloat(priceText);
            var stockBadges = cells[8].getElementsByClassName('badge');
            var stockStatus = stockBadges.length > 0 ? stockBadges[0].textContent.toLowerCase() : 'good';
            
            var showRow = true;
            
            // Enhanced search filter - searches name, brand, category
            if (searchInput && !(productName.includes(searchInput) || brand.includes(searchInput) || category.includes(searchInput))) {
                showRow = false;
            }
            
            // Category filter
            if (categoryFilter && !category.includes(categoryFilter)) {
                showRow = false;
            }
            
            // Stock filter
            if (stockFilter) {
                if (stockFilter === 'low' && stockStatus !== 'low') showRow = false;
                if (stockFilter === 'out' && stockStatus !== 'out') showRow = false;
                if (stockFilter === 'good' && stockStatus !== 'good') showRow = false;
            }
            
            // Price filter
            if (priceFilter && !isNaN(price)) {
                if (priceFilter === '0-50' && price > 50) showRow = false;
                if (priceFilter === '50-100' && (price <= 50 || price > 100)) showRow = false;
                if (priceFilter === '100-200' && (price <= 100 || price > 200)) showRow = false;
                if (priceFilter === '200+' && price <= 200) showRow = false;
            }
            
            rows[i].style.display = showRow ? '' : 'none';
            if (showRow) visibleCount++;
        }
    }
    
    // Update results count
    updateResultsCount(visibleCount);
}

function resetFilters() {
    document.getElementById('productSearch').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('stockFilter').value = '';
    document.getElementById('priceFilter').value = '';
    filterTable();
}

function refreshData() {
    window.location.reload();
}

function updateResultsCount(count) {
    var totalProducts = document.querySelectorAll('#productsTable tbody tr').length;
    // You can add a results counter element if needed
}

function exportTable() {
    alert('Export functionality would be implemented here');
}

// Add responsive text on window resize
function adjustTableText() {
    var table = document.getElementById('productsTable');
    var width = window.innerWidth;
    
    if (width < 768) {
        table.style.fontSize = '11px';
    } else if (width < 992) {
        table.style.fontSize = '12px';
    } else {
        table.style.fontSize = '13px';
    }
}

// Initialize on load
document.addEventListener('DOMContentLoaded', adjustTableText);
window.addEventListener('resize', adjustTableText);
</script>

<!-- Enhanced CSS for fully responsive design -->
<style>
/* Container and layout improvements */
.container-fluid {
    max-width: 100%;
    padding-left: 8px;
    padding-right: 8px;
}

/* Card and content styling */
.card {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px 8px 0 0 !important;
}

/* Table styling */
#productsTable {
    font-size: 14px;
    width: 100%;
    margin-bottom: 0;
}

#productsTable th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    white-space: nowrap;
    padding: 12px 8px;
}

#productsTable td {
    padding: 10px 8px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f1f1;
}

#productsTable tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

/* Badge styling */
.badge {
    font-size: 11px;
    font-weight: 500;
    padding: 4px 8px;
    border-radius: 4px;
}

.bg-primary-soft {
    background-color: rgba(13, 110, 253, 0.1) !important;
    color: #0d6efd !important;
}

.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1) !important;
    color: #198754 !important;
}

.bg-warning-soft {
    background-color: rgba(255, 193, 7, 0.1) !important;
    color: #ffc107 !important;
}

.bg-danger-soft {
    background-color: rgba(220, 53, 69, 0.1) !important;
    color: #dc3545 !important;
}

/* Button styling */
.btn-group-sm > .btn {
    padding: 4px 8px;
    font-size: 12px;
}

/* Statistics cards */
.bg-primary-soft .p-2 {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-success-soft .p-2 {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-warning-soft .p-2 {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-danger-soft .p-2 {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

/* Filter section */
.bg-light {
    background-color: #f8f9fa !important;
}

/* Responsive design */
@media (max-width: 1200px) {
    .container-fluid {
        padding-left: 5px;
        padding-right: 5px;
    }
    
    #productsTable {
        font-size: 13px;
    }
    
    #productsTable th,
    #productsTable td {
        padding: 8px 5px;
    }
}

@media (max-width: 992px) {
    #productsTable {
        font-size: 12px;
    }
    
    #productsTable th,
    #productsTable td {
        padding: 6px 3px;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .btn {
        font-size: 12px;
        padding: 6px 12px;
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 2px;
        padding-right: 2px;
    }
    
    #productsTable {
        font-size: 11px;
    }
    
    #productsTable th,
    #productsTable td {
        padding: 4px 2px;
    }
    
    .card-body {
        padding: 0.5rem;
    }
    
    .bg-light.p-3 {
        padding: 1rem !important;
    }
    
    .row.g-3 {
        gap: 0.5rem !important;
    }
}

/* Table responsive wrapper */
.table-responsive {
    border-radius: 0 0 8px 8px;
    border: none;
    box-shadow: none;
    margin: 0;
}

/* Utility classes */
.mb-3 {
    margin-bottom: 1rem !important;
}

.gap-2 {
    gap: 0.5rem !important;
}

.fw-bold {
    font-weight: 600 !important;
}

.fw-semibold {
    font-weight: 500 !important;
}

/* Image styling */
.img-thumbnail {
    border: 2px solid #dee2e6;
    border-radius: 4px;
}

/* Hover effects */
.btn:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

/* Full width utilization */
.container-fluid {
    width: 100%;
    max-width: none;
}

/* Remove unused space */
.row {
    margin-left: 0;
    margin-right: 0;
}

.col-12 {
    padding-left: 5px;
    padding-right: 5px;
}
</style>
