<div class="content">
    <div class="container-fluid">
        
        <?php if ($this->session->flashdata('item')) { ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong><?php echo $this->session->flashdata('item')['message']; ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Complete Order Management</h4>
                        <p class="category">Manage all customer orders - View details, update status, track delivery</p>
                    </div>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Current Status</th>
                                    <th>Quick Status Change</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orderList)) {
                                    foreach ($orderList as $order) {
                                        // Determine status color
                                        $status_class = 'badge-secondary';
                                        switch(strtolower($order['order_status'])) {
                                            case 'pending': $status_class = 'badge-warning'; break;
                                            case 'confirmed': case 'confirm': $status_class = 'badge-info'; break;
                                            case 'shipped': $status_class = 'badge-primary'; break;
                                            case 'delivered': $status_class = 'badge-success'; break;
                                            case 'cancelled': $status_class = 'badge-danger'; break;
                                        }
                                        ?>
                                        <tr>
                                            <td><strong>#ORD<?php echo str_pad($order['order_id'], 4, '0', STR_PAD_LEFT); ?></strong></td>
                                            <td>
                                                <i class="fa fa-user"></i> User ID: <?php echo $order['user_id']; ?>
                                            </td>
                                            <td>
                                                <i class="fa fa-calendar"></i> <?php echo date('M d, Y', strtotime($order['order_date'])); ?>
                                            </td>
                                            <td>
                                                <strong><i class="fa fa-rupee-sign"></i> ₹<?php echo number_format($order['order_total']); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo $status_class; ?>">
                                                    <?php echo ucfirst($order['order_status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <form method="post" action="<?php echo base_url('admin/updateOrderStatus'); ?>" style="display: inline;">
                                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                    <select name="status" class="form-control form-control-sm" onchange="this.form.submit()" style="width: 120px; display: inline-block;">
                                                        <option value="pending" <?php echo ($order['order_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="confirmed" <?php echo ($order['order_status'] == 'confirmed' || $order['order_status'] == 'confirm') ? 'selected' : ''; ?>>Confirmed</option>
                                                        <option value="shipped" <?php echo ($order['order_status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                                                        <option value="delivered" <?php echo ($order['order_status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                                        <option value="cancelled" <?php echo ($order['order_status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo base_url('admin/viewOrder?id=' . $order['order_id']); ?>" 
                                                       class="btn btn-info btn-sm" 
                                                       title="View Full Order Details">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

<?php
// Get order counts for the dashboard
$orderCounts = $this->adminmodel->getOrderStatusCounts();
?>

<style>
.order-status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.8em;
    font-weight: bold;
    text-transform: uppercase;
}
.status-pending { background: #ffeaa7; color: #2d3436; }
.status-accepted { background: #74b9ff; color: white; }
.status-processing { background: #0984e3; color: white; }
.status-shipped { background: #00b894; color: white; }
.status-delivered { background: #00a085; color: white; }
.status-cancelled { background: #636e72; color: white; }
.status-rejected { background: #d63031; color: white; }

.order-actions .btn {
    margin: 2px;
    padding: 5px 10px;
    font-size: 0.8em;
}

.order-filter {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.status-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.status-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.status-card:hover, .status-card.active {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    border-color: #007bff;
}

.status-card .count {
    font-size: 2em;
    font-weight: bold;
    margin-bottom: 10px;
}

.status-card .label {
    font-size: 0.9em;
    text-transform: uppercase;
    font-weight: 600;
}

.order-table {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table th {
    background: #f8f9fa;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8em;
    color: #495057;
}

.table td {
    border: none;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.modal-header {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    border-radius: 15px 15px 0 0;
}

.btn-action {
    border-radius: 20px;
    padding: 8px 15px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8em;
}
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-shopping-cart"></i> Order Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            
            <!-- Order Status Cards -->
            <div class="status-cards">
                <div class="status-card" data-status="all" onclick="filterOrders('all')">
                    <div class="count text-primary"><?= $orderCounts['total'] ?></div>
                    <div class="label text-primary">Total Orders</div>
                </div>
                <div class="status-card" data-status="today" onclick="filterOrders('today')">
                    <div class="count text-info"><?= $orderCounts['today'] ?></div>
                    <div class="label text-info">Today's Orders</div>
                </div>
                <div class="status-card" data-status="pending" onclick="filterOrders('pending')">
                    <div class="count text-warning"><?= $orderCounts['pending'] ?></div>
                    <div class="label text-warning">Pending</div>
                </div>
                <div class="status-card" data-status="accepted" onclick="filterOrders('accepted')">
                    <div class="count text-info"><?= $orderCounts['accepted'] ?></div>
                    <div class="label text-info">Accepted</div>
                </div>
                <div class="status-card" data-status="processing" onclick="filterOrders('processing')">
                    <div class="count text-primary"><?= $orderCounts['processing'] ?></div>
                    <div class="label text-primary">Processing</div>
                </div>
                <div class="status-card" data-status="shipped" onclick="filterOrders('shipped')">
                    <div class="count text-success"><?= $orderCounts['shipped'] ?></div>
                    <div class="label text-success">Shipped</div>
                </div>
                <div class="status-card" data-status="delivered" onclick="filterOrders('delivered')">
                    <div class="count text-success"><?= $orderCounts['delivered'] ?></div>
                    <div class="label text-success">Delivered</div>
                </div>
            </div>

            <!-- Order Filters -->
            <div class="order-filter">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="mb-1"><strong>Filter by Status:</strong></label>
                            <select id="statusFilter" class="form-control" onchange="filterOrders(this.value)">
                                <option value="all">All Orders</option>
                                <option value="pending">Pending Orders</option>
                                <option value="accepted">Accepted Orders</option>
                                <option value="processing">Processing Orders</option>
                                <option value="shipped">Shipped Orders</option>
                                <option value="delivered">Delivered Orders</option>
                                <option value="cancelled">Cancelled Orders</option>
                                <option value="rejected">Rejected Orders</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="mb-1"><strong>Search Orders:</strong></label>
                            <input type="text" id="orderSearch" class="form-control" placeholder="Search by Order ID, Customer Email..." onkeyup="searchOrders()">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="mb-1"><strong>Actions:</strong></label><br>
                            <button class="btn btn-primary btn-action" onclick="refreshOrders()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <button class="btn btn-success btn-action" onclick="exportOrders()">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Table -->
            <div class="order-table">
                <div class="table-responsive">
                    <table class="table table-hover" id="ordersTable">
                        <thead>
                            <tr>
                                <th width="8%">Order ID</th>
                                <th width="12%">Customer</th>
                                <th width="10%">Date</th>
                                <th width="8%">Total</th>
                                <th width="10%">Status</th>
                                <th width="8%">Items</th>
                                <th width="12%">Contact</th>
                                <th width="18%">Actions</th>
                                <th width="14%">Quick Actions</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                            <!-- Orders will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                <div id="loadingSpinner" class="text-center py-5" style="display: none;">
                    <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                    <p class="mt-3 text-muted">Loading orders...</p>
                </div>
                <div id="noOrdersMessage" class="text-center py-5" style="display: none;">
                    <i class="fas fa-inbox fa-3x text-muted"></i>
                    <p class="mt-3 text-muted">No orders found</p>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Quick Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Update Order Status</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="statusUpdateForm">
                <div class="modal-body">
                    <input type="hidden" id="updateOrderId" name="order_id">
                    <div class="form-group">
                        <label><strong>New Status:</strong></label>
                        <select id="newStatus" name="status" class="form-control" required>
                            <option value="">Select Status</option>
                            <option value="accepted">Accept Order</option>
                            <option value="rejected">Reject Order</option>
                            <option value="processing">Start Processing</option>
                            <option value="shipped">Mark as Shipped</option>
                            <option value="delivered">Mark as Delivered</option>
                            <option value="cancelled">Cancel Order</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><strong>Admin Notes:</strong></label>
                        <textarea id="adminNotes" name="admin_notes" class="form-control" rows="3" placeholder="Add notes about this status change..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Accept Modal -->
<div class="modal fade" id="acceptModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white"><i class="fas fa-check"></i> Accept Order</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="acceptForm">
                <div class="modal-body">
                    <input type="hidden" id="acceptOrderId" name="order_id">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> You are about to accept this order. The customer will be notified.
                    </div>
                    <div class="form-group">
                        <label><strong>Acceptance Notes:</strong></label>
                        <textarea id="acceptNotes" name="admin_notes" class="form-control" rows="2" placeholder="Order accepted and will be processed soon...">Order accepted and will be processed soon.</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Accept Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white"><i class="fas fa-times"></i> Reject Order</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <input type="hidden" id="rejectOrderId" name="order_id">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> You are about to reject this order. Product stock will be restored.
                    </div>
                    <div class="form-group">
                        <label><strong>Rejection Reason:</strong></label>
                        <select class="form-control mb-2" onchange="updateRejectionReason(this.value)">
                            <option value="">Select a reason...</option>
                            <option value="Out of stock">Out of stock</option>
                            <option value="Payment issue">Payment issue</option>
                            <option value="Delivery area not covered">Delivery area not covered</option>
                            <option value="Customer request">Customer request</option>
                            <option value="Technical issue">Technical issue</option>
                            <option value="custom">Other (custom reason)</option>
                        </select>
                        <textarea id="rejectReason" name="rejection_reason" class="form-control" rows="3" placeholder="Please provide reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Global variables
let currentFilter = 'all';
let currentOrders = [];

// Initialize page
$(document).ready(function() {
    loadOrders('all');
    
    // Form submissions
    $('#statusUpdateForm').submit(function(e) {
        e.preventDefault();
        updateOrderStatus();
    });
    
    $('#acceptForm').submit(function(e) {
        e.preventDefault();
        acceptOrder();
    });
    
    $('#rejectForm').submit(function(e) {
        e.preventDefault();
        rejectOrder();
    });
});

// Load orders based on filter
function loadOrders(status) {
    currentFilter = status;
    
    // Update active status card
    $('.status-card').removeClass('active');
    $(`[data-status="${status}"]`).addClass('active');
    
    // Update select box
    $('#statusFilter').val(status);
    
    // Show loading
    $('#loadingSpinner').show();
    $('#ordersTableBody').hide();
    $('#noOrdersMessage').hide();
    
    // AJAX request
    $.get(`<?= base_url('admin/getOrdersByStatus') ?>`, { status: status }, function(response) {
        if (response.success) {
            currentOrders = response.orders;
            displayOrders(response.orders);
        } else {
            showAlert('error', 'Failed to load orders: ' + response.message);
        }
    }).fail(function() {
        showAlert('error', 'Failed to load orders. Please try again.');
    }).always(function() {
        $('#loadingSpinner').hide();
        $('#ordersTableBody').show();
    });
}

// Display orders in table
function displayOrders(orders) {
    const tbody = $('#ordersTableBody');
    tbody.empty();
    
    if (orders.length === 0) {
        $('#noOrdersMessage').show();
        return;
    }
    
    orders.forEach(order => {
        const row = createOrderRow(order);
        tbody.append(row);
    });
}

// Create order table row
function createOrderRow(order) {
    const statusClass = `status-${order.order_status}`;
    const statusBadge = `<span class="order-status-badge ${statusClass}">${order.order_status}</span>`;
    
    // Format date
    const orderDate = new Date(order.order_date);
    const formattedDate = orderDate.toLocaleDateString() + '<br><small>' + orderDate.toLocaleTimeString() + '</small>';
    
    // Customer info
    const customerInfo = `${order.email}<br><small class="text-muted">${order.mobile || 'No phone'}</small>`;
    
    // Actions based on current status
    let quickActions = '';
    if (order.order_status === 'pending') {
        quickActions = `
            <button class="btn btn-success btn-sm" onclick="showAcceptModal(${order.order_id})" title="Accept Order">
                <i class="fas fa-check"></i>
            </button>
            <button class="btn btn-danger btn-sm" onclick="showRejectModal(${order.order_id})" title="Reject Order">
                <i class="fas fa-times"></i>
            </button>`;
    } else if (order.order_status === 'accepted') {
        quickActions = `
            <button class="btn btn-primary btn-sm" onclick="quickStatusUpdate(${order.order_id}, 'processing')" title="Start Processing">
                <i class="fas fa-cog"></i>
            </button>`;
    } else if (order.order_status === 'processing') {
        quickActions = `
            <button class="btn btn-info btn-sm" onclick="quickStatusUpdate(${order.order_id}, 'shipped')" title="Mark as Shipped">
                <i class="fas fa-shipping-fast"></i>
            </button>`;
    } else if (order.order_status === 'shipped') {
        quickActions = `
            <button class="btn btn-success btn-sm" onclick="quickStatusUpdate(${order.order_id}, 'delivered')" title="Mark as Delivered">
                <i class="fas fa-check-circle"></i>
            </button>`;
    }
    
    return `
        <tr>
            <td><strong>#${order.order_id}</strong></td>
            <td>${customerInfo}</td>
            <td>${formattedDate}</td>
            <td><strong>₹${parseFloat(order.order_total).toFixed(2)}</strong></td>
            <td>${statusBadge}</td>
            <td><span class="badge badge-secondary">View Items</span></td>
            <td>${order.mobile || order.email}</td>
            <td>
                <button class="btn btn-info btn-sm" onclick="viewOrder(${order.order_id})" title="View Details">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="btn btn-warning btn-sm" onclick="showStatusUpdateModal(${order.order_id})" title="Update Status">
                    <i class="fas fa-edit"></i> Update
                </button>
            </td>
            <td>${quickActions}</td>
        </tr>`;
}

// Filter orders
function filterOrders(status) {
    if (status === 'today') {
        // Filter current orders for today
        const today = new Date().toDateString();
        const todayOrders = currentOrders.filter(order => {
            return new Date(order.order_date).toDateString() === today;
        });
        displayOrders(todayOrders);
        
        $('.status-card').removeClass('active');
        $(`[data-status="today"]`).addClass('active');
    } else {
        loadOrders(status);
    }
}

// Search orders
function searchOrders() {
    const query = $('#orderSearch').val().toLowerCase();
    if (query.length === 0) {
        displayOrders(currentOrders);
        return;
    }
    
    const filtered = currentOrders.filter(order => {
        return order.order_id.toString().includes(query) ||
               (order.email && order.email.toLowerCase().includes(query)) ||
               (order.mobile && order.mobile.includes(query));
    });
    
    displayOrders(filtered);
}

// Show status update modal
function showStatusUpdateModal(orderId) {
    $('#updateOrderId').val(orderId);
    $('#newStatus').val('');
    $('#adminNotes').val('');
    $('#statusUpdateModal').modal('show');
}

// Show accept modal
function showAcceptModal(orderId) {
    $('#acceptOrderId').val(orderId);
    $('#acceptModal').modal('show');
}

// Show reject modal
function showRejectModal(orderId) {
    $('#rejectOrderId').val(orderId);
    $('#rejectReason').val('');
    $('#rejectModal').modal('show');
}

// Update order status
function updateOrderStatus() {
    const formData = $('#statusUpdateForm').serialize();
    
    $.post('<?= base_url("admin/updateOrderStatus") ?>', formData, function(response) {
        if (response.success) {
            showAlert('success', response.message);
            $('#statusUpdateModal').modal('hide');
            loadOrders(currentFilter);
        } else {
            showAlert('error', response.message);
        }
    }).fail(function() {
        showAlert('error', 'Failed to update order status. Please try again.');
    });
}

// Accept order
function acceptOrder() {
    const formData = $('#acceptForm').serialize();
    
    $.post('<?= base_url("admin/acceptOrder") ?>', formData, function(response) {
        if (response.success) {
            showAlert('success', response.message);
            $('#acceptModal').modal('hide');
            loadOrders(currentFilter);
        } else {
            showAlert('error', response.message);
        }
    }).fail(function() {
        showAlert('error', 'Failed to accept order. Please try again.');
    });
}

// Reject order
function rejectOrder() {
    const formData = $('#rejectForm').serialize();
    
    $.post('<?= base_url("admin/rejectOrder") ?>', formData, function(response) {
        if (response.success) {
            showAlert('success', response.message);
            $('#rejectModal').modal('hide');
            loadOrders(currentFilter);
        } else {
            showAlert('error', response.message);
        }
    }).fail(function() {
        showAlert('error', 'Failed to reject order. Please try again.');
    });
}

// Quick status update
function quickStatusUpdate(orderId, status) {
    const confirmMessage = `Are you sure you want to change this order to "${status}" status?`;
    
    if (confirm(confirmMessage)) {
        $.post('<?= base_url("admin/updateOrderStatus") ?>', {
            order_id: orderId,
            status: status,
            admin_notes: `Status changed to ${status} by admin`
        }, function(response) {
            if (response.success) {
                showAlert('success', response.message);
                loadOrders(currentFilter);
            } else {
                showAlert('error', response.message);
            }
        }).fail(function() {
            showAlert('error', 'Failed to update order status. Please try again.');
        });
    }
}

// View order details
function viewOrder(orderId) {
    window.location.href = `<?= base_url('admin/viewOrder?id=') ?>${orderId}`;
}

// Refresh orders
function refreshOrders() {
    loadOrders(currentFilter);
    showAlert('info', 'Orders refreshed successfully!');
}

// Export orders
function exportOrders() {
    showAlert('info', 'Export functionality will be implemented soon.');
}

// Update rejection reason from dropdown
function updateRejectionReason(reason) {
    if (reason && reason !== 'custom') {
        $('#rejectReason').val(reason);
    } else if (reason === 'custom') {
        $('#rejectReason').val('').focus();
    }
}

// Show alert messages
function showAlert(type, message) {
    const alertClass = type === 'error' ? 'danger' : type;
    const alertHtml = `
        <div class="alert alert-${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>`;
    
    // Remove existing alerts
    $('.alert').remove();
    
    // Add new alert to the top
    $('.content-wrapper').prepend(alertHtml);
    
    // Auto dismiss after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>

<?php
                                } else { ?>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <i class="fa fa-shopping-bag fa-3x text-muted"></i><br>
                                            <strong>No orders found</strong><br>
                                            <small>There are no orders in the system yet.</small>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                        
                        <?php if (!empty($orderList)) { ?>
                            <div class="footer">
                                <hr>
                                <div class="stats">
                                    <i class="fa fa-shopping-cart"></i> Total Orders: <strong><?php echo count($orderList); ?></strong>
                                    | Total Revenue: <strong>₹<?php 
                                        $total_revenue = 0;
                                        foreach ($orderList as $order) {
                                            $total_revenue += $order['order_total'];
                                        }
                                        echo number_format($total_revenue);
                                    ?></strong>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
