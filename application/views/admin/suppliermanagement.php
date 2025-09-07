<?php 
// Initialize supplier metrics
$active_suppliers = 0;
$inactive_suppliers = 0;
$total_suppliers = 0;

if (isset($supplierDetails)) {
    $total_suppliers = count($supplierDetails);
    foreach ($supplierDetails as $supplier) {
        if (isset($supplier['status']) && $supplier['status'] === 'active') {
            $active_suppliers++;
        } else {
            $inactive_suppliers++;
        }
    }
}
?>

<div class="content">
    <div class="container-fluid">
        
        <!-- Supplier Overview Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Total Suppliers</h4>
                    </div>
                    <div class="content text-center">
                        <h1 class="text-info"><?php echo $total_suppliers; ?></h1>
                        <p>Registered suppliers</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Active Suppliers</h4>
                    </div>
                    <div class="content text-center">
                        <h1 class="text-success"><?php echo $active_suppliers; ?></h1>
                        <p>Currently supplying</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Inactive Suppliers</h4>
                    </div>
                    <div class="content text-center">
                        <h1 class="text-warning"><?php echo $inactive_suppliers; ?></h1>
                        <p>Not currently supplying</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center">Quick Actions</h4>
                    </div>
                    <div class="content text-center">
                        <button class="btn btn-success btn-block" onclick="showAddSupplierModal()">
                            <i class="fa fa-plus"></i> Add Supplier
                        </button>
                        <button class="btn btn-info btn-block" onclick="exportSupplierList()">
                            <i class="fa fa-download"></i> Export List
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add New Supplier Form -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Add New Supplier</h4>
                        <p class="category">Register a new grocery supplier</p>
                    </div>
                    <div class="content">
                        <form method="post" action="<?php echo base_url('admin/addSupplier'); ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Supplier Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="supplier_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Person</label>
                                        <input type="text" class="form-control" name="contact_person">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="phone" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control" name="email">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea class="form-control" name="address" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Specialization</label>
                                        <select class="form-control" name="specialization">
                                            <option value="">Select Category</option>
                                            <option value="Fruits & Vegetables">Fruits & Vegetables</option>
                                            <option value="Dairy & Bakery">Dairy & Bakery</option>
                                            <option value="Grains & Rice">Grains & Rice</option>
                                            <option value="Pulses & Beans">Pulses & Beans</option>
                                            <option value="Spices & Condiments">Spices & Condiments</option>
                                            <option value="Oil & Ghee">Oil & Ghee</option>
                                            <option value="Mixed">Mixed Categories</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment Terms</label>
                                        <select class="form-control" name="payment_terms">
                                            <option value="cash_on_delivery">Cash on Delivery</option>
                                            <option value="net_30">Net 30 Days</option>
                                            <option value="net_15">Net 15 Days</option>
                                            <option value="advance_payment">Advance Payment</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success btn-fill btn-block">
                                        <i class="fa fa-plus"></i> Add Supplier
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
                        <h4 class="title">Supplier Performance</h4>
                        <p class="category">Top performing suppliers this month</p>
                    </div>
                    <div class="content">
                        <div class="table-full-width">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Orders</th>
                                        <th>Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($supplierDetails) && !empty($supplierDetails)): ?>
                                        <?php foreach (array_slice($supplierDetails, 0, 5) as $supplier): ?>
                                        <tr>
                                            <td><?php echo $supplier['supplier_name']; ?></td>
                                            <td><?php echo isset($supplier['orders_count']) ? $supplier['orders_count'] : '0'; ?></td>
                                            <td>
                                                <?php 
                                                $rating = isset($supplier['rating']) ? $supplier['rating'] : 4.0;
                                                for ($i = 1; $i <= 5; $i++): 
                                                ?>
                                                    <i class="fa fa-star <?php echo $i <= $rating ? 'text-warning' : 'text-muted'; ?>"></i>
                                                <?php endfor; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">No suppliers found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suppliers List -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Suppliers Management</h4>
                        <p class="category">Manage all registered suppliers</p>
                        <div class="pull-right">
                            <button class="btn btn-sm btn-info" onclick="refreshSupplierData()">
                                <i class="fa fa-refresh"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="content table-responsive">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" id="searchSupplier" class="form-control" placeholder="Search suppliers...">
                            </div>
                            <div class="col-md-4">
                                <select id="statusFilter" class="form-control">
                                    <option value="all">All Status</option>
                                    <option value="active">Active Only</option>
                                    <option value="inactive">Inactive Only</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="categoryFilter" class="form-control">
                                    <option value="all">All Categories</option>
                                    <option value="Fruits & Vegetables">Fruits & Vegetables</option>
                                    <option value="Dairy & Bakery">Dairy & Bakery</option>
                                    <option value="Grains & Rice">Grains & Rice</option>
                                    <option value="Pulses & Beans">Pulses & Beans</option>
                                    <option value="Spices & Condiments">Spices & Condiments</option>
                                </select>
                            </div>
                        </div>
                        
                        <table class="table table-striped table-hover" id="suppliersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Supplier Name</th>
                                    <th>Contact Person</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Specialization</th>
                                    <th>Payment Terms</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($supplierDetails) && !empty($supplierDetails)): ?>
                                    <?php foreach ($supplierDetails as $supplier): ?>
                                        <tr class="supplier-row" 
                                            data-status="<?php echo isset($supplier['status']) ? $supplier['status'] : 'active'; ?>"
                                            data-category="<?php echo isset($supplier['specialization']) ? $supplier['specialization'] : ''; ?>"
                                            data-name="<?php echo strtolower($supplier['supplier_name']); ?>">
                                            <td><?php echo $supplier['supplier_id']; ?></td>
                                            <td>
                                                <strong><?php echo $supplier['supplier_name']; ?></strong>
                                                <?php if (!empty($supplier['address'])): ?>
                                                    <br><small class="text-muted"><?php echo substr($supplier['address'], 0, 30) . '...'; ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo isset($supplier['contact_person']) ? $supplier['contact_person'] : '-'; ?></td>
                                            <td><?php echo $supplier['phone']; ?></td>
                                            <td><?php echo isset($supplier['email']) ? $supplier['email'] : '-'; ?></td>
                                            <td>
                                                <?php if (!empty($supplier['specialization'])): ?>
                                                    <span class="badge badge-info"><?php echo $supplier['specialization']; ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">Not specified</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo isset($supplier['payment_terms']) ? ucwords(str_replace('_', ' ', $supplier['payment_terms'])) : '-'; ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo (isset($supplier['status']) && $supplier['status'] === 'active') ? 'success' : 'warning'; ?>">
                                                    <?php echo isset($supplier['status']) ? ucfirst($supplier['status']) : 'Active'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-xs btn-info" onclick="editSupplier(<?php echo $supplier['supplier_id']; ?>)" title="Edit Supplier">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-<?php echo (isset($supplier['status']) && $supplier['status'] === 'active') ? 'warning' : 'success'; ?>" 
                                                            onclick="toggleSupplierStatus(<?php echo $supplier['supplier_id']; ?>)" 
                                                            title="<?php echo (isset($supplier['status']) && $supplier['status'] === 'active') ? 'Deactivate' : 'Activate'; ?>">
                                                        <i class="fa fa-<?php echo (isset($supplier['status']) && $supplier['status'] === 'active') ? 'pause' : 'play'; ?>"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-danger" onclick="deleteSupplier(<?php echo $supplier['supplier_id']; ?>)" title="Delete Supplier">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No suppliers registered yet</td>
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
// Supplier management functions
function editSupplier(supplierId) {
    // Redirect to edit page or open modal
    window.location.href = '<?php echo base_url("admin/editSupplier?id="); ?>' + supplierId;
}

function toggleSupplierStatus(supplierId) {
    if (confirm('Are you sure you want to change the status of this supplier?')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo base_url("admin/toggleSupplierStatus"); ?>';
        
        var supplierInput = document.createElement('input');
        supplierInput.type = 'hidden';
        supplierInput.name = 'supplier_id';
        supplierInput.value = supplierId;
        
        form.appendChild(supplierInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteSupplier(supplierId) {
    if (confirm('Are you sure you want to delete this supplier? This action cannot be undone.')) {
        window.location.href = '<?php echo base_url("admin/deleteSupplier?id="); ?>' + supplierId;
    }
}

function refreshSupplierData() {
    location.reload();
}

function exportSupplierList() {
    window.location.href = '<?php echo base_url("admin/exportSuppliers"); ?>';
}

// Filter suppliers
function filterSuppliers() {
    var searchTerm = document.getElementById('searchSupplier').value.toLowerCase();
    var statusFilter = document.getElementById('statusFilter').value;
    var categoryFilter = document.getElementById('categoryFilter').value;
    var rows = document.querySelectorAll('#suppliersTable tbody .supplier-row');
    
    rows.forEach(function(row) {
        var supplierName = row.getAttribute('data-name');
        var status = row.getAttribute('data-status');
        var category = row.getAttribute('data-category');
        
        var showRow = true;
        
        // Search filter
        if (searchTerm && !supplierName.includes(searchTerm)) {
            showRow = false;
        }
        
        // Status filter
        if (statusFilter !== 'all' && status !== statusFilter) {
            showRow = false;
        }
        
        // Category filter
        if (categoryFilter !== 'all' && category !== categoryFilter) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

// Initialize filters
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchSupplier').addEventListener('keyup', filterSuppliers);
    document.getElementById('statusFilter').addEventListener('change', filterSuppliers);
    document.getElementById('categoryFilter').addEventListener('change', filterSuppliers);
});
</script>
