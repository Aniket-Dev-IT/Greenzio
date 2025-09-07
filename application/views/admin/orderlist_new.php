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

            <!-- Order Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order List</h3>
                    <div class="card-tools">
                        <select id="statusFilter" class="form-control form-control-sm" style="width: 200px;" onchange="filterOrders(this.value)">
                            <option value="all">All Orders</option>
                            <option value="pending">Pending Orders</option>
                            <option value="accepted">Accepted Orders</option>
                            <option value="processing">Processing Orders</option>
                            <option value="shipped">Shipped Orders</option>
                            <option value="delivered">Delivered Orders</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="ordersTable">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                                <!-- Orders loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div id="loadingSpinner" class="text-center" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Loading orders...
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Order Status</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="statusForm">
                <div class="modal-body">
                    <input type="hidden" id="orderId" name="order_id">
                    <div class="form-group">
                        <label>New Status:</label>
                        <select id="newStatus" name="status" class="form-control" required>
                            <option value="">Select Status</option>
                            <option value="accepted">Accept Order</option>
                            <option value="rejected">Reject Order</option>
                            <option value="processing">Start Processing</option>
                            <option value="shipped">Mark as Shipped</option>
                            <option value="delivered">Mark as Delivered</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Admin Notes:</label>
                        <textarea id="adminNotes" name="admin_notes" class="form-control" rows="3"></textarea>
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

<script>
let currentFilter = 'all';
let currentOrders = [];

$(document).ready(function() {
    loadOrders('all');
    
    $('#statusForm').submit(function(e) {
        e.preventDefault();
        updateOrderStatus();
    });
});

function loadOrders(status) {
    currentFilter = status;
    $('.status-card').removeClass('active');
    $(`[data-status="${status}"]`).addClass('active');
    $('#statusFilter').val(status);
    
    $('#loadingSpinner').show();
    
    $.get('<?= base_url("admin/getOrdersByStatus") ?>', { status: status }, function(response) {
        if (response.success) {
            currentOrders = response.orders;
            displayOrders(response.orders);
        }
    }).always(function() {
        $('#loadingSpinner').hide();
    });
}

function displayOrders(orders) {
    const tbody = $('#ordersTableBody');
    tbody.empty();
    
    if (orders.length === 0) {
        tbody.append('<tr><td colspan="6" class="text-center">No orders found</td></tr>');
        return;
    }
    
    orders.forEach(order => {
        const statusClass = `status-${order.order_status}`;
        const statusBadge = `<span class="order-status-badge ${statusClass}">${order.order_status}</span>`;
        
        const orderDate = new Date(order.order_date);
        const formattedDate = orderDate.toLocaleDateString();
        
        let actions = `
            <button class="btn btn-info btn-sm" onclick="viewOrder(${order.order_id})" title="View Details">
                <i class="fas fa-eye"></i>
            </button>
            <button class="btn btn-warning btn-sm" onclick="showStatusModal(${order.order_id})" title="Update Status">
                <i class="fas fa-edit"></i>
            </button>`;
        
        // Quick action buttons based on status
        if (order.order_status === 'pending') {
            actions += `
                <button class="btn btn-success btn-sm" onclick="quickAccept(${order.order_id})" title="Accept">
                    <i class="fas fa-check"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="quickReject(${order.order_id})" title="Reject">
                    <i class="fas fa-times"></i>
                </button>`;
        } else if (order.order_status === 'accepted') {
            actions += `
                <button class="btn btn-primary btn-sm" onclick="quickProcess(${order.order_id})" title="Start Processing">
                    <i class="fas fa-cog"></i>
                </button>`;
        } else if (order.order_status === 'processing') {
            actions += `
                <button class="btn btn-info btn-sm" onclick="quickShip(${order.order_id})" title="Mark as Shipped">
                    <i class="fas fa-shipping-fast"></i>
                </button>`;
        } else if (order.order_status === 'shipped') {
            actions += `
                <button class="btn btn-success btn-sm" onclick="quickDeliver(${order.order_id})" title="Mark as Delivered">
                    <i class="fas fa-check-circle"></i>
                </button>`;
        }
        
        const row = `
            <tr>
                <td><strong>#${order.order_id}</strong></td>
                <td>${order.email}<br><small>${order.mobile || 'No phone'}</small></td>
                <td>${formattedDate}</td>
                <td><strong>₹${parseFloat(order.order_total).toFixed(2)}</strong></td>
                <td>${statusBadge}</td>
                <td>${actions}</td>
            </tr>`;
        
        tbody.append(row);
    });
}

function filterOrders(status) {
    loadOrders(status);
}

function showStatusModal(orderId) {
    $('#orderId').val(orderId);
    $('#newStatus').val('');
    $('#adminNotes').val('');
    $('#statusModal').modal('show');
}

function updateOrderStatus() {
    const formData = $('#statusForm').serialize();
    
    $.post('<?= base_url("admin/updateOrderStatus") ?>', formData, function(response) {
        if (response.success) {
            alert('Order status updated successfully!');
            $('#statusModal').modal('hide');
            loadOrders(currentFilter);
        } else {
            alert('Error: ' + response.message);
        }
    });
}

function quickAccept(orderId) {
    if (confirm('Accept this order?')) {
        $.post('<?= base_url("admin/acceptOrder") ?>', {
            order_id: orderId,
            admin_notes: 'Order accepted by admin'
        }, function(response) {
            if (response.success) {
                alert('Order accepted successfully!');
                loadOrders(currentFilter);
            } else {
                alert('Error: ' + response.message);
            }
        });
    }
}

function quickReject(orderId) {
    const reason = prompt('Reason for rejection:');
    if (reason) {
        $.post('<?= base_url("admin/rejectOrder") ?>', {
            order_id: orderId,
            rejection_reason: reason
        }, function(response) {
            if (response.success) {
                alert('Order rejected successfully!');
                loadOrders(currentFilter);
            } else {
                alert('Error: ' + response.message);
            }
        });
    }
}

function quickProcess(orderId) {
    if (confirm('Start processing this order?')) {
        $.post('<?= base_url("admin/updateOrderStatus") ?>', {
            order_id: orderId,
            status: 'processing',
            admin_notes: 'Order processing started by admin'
        }, function(response) {
            if (response.success) {
                alert('Order moved to processing!');
                loadOrders(currentFilter);
            } else {
                alert('Error: ' + response.message);
            }
        });
    }
}

function quickShip(orderId) {
    if (confirm('Mark this order as shipped?')) {
        $.post('<?= base_url("admin/updateOrderStatus") ?>', {
            order_id: orderId,
            status: 'shipped',
            admin_notes: 'Order shipped by admin'
        }, function(response) {
            if (response.success) {
                alert('Order marked as shipped!');
                loadOrders(currentFilter);
            } else {
                alert('Error: ' + response.message);
            }
        });
    }
}

function quickDeliver(orderId) {
    if (confirm('Mark this order as delivered?')) {
        $.post('<?= base_url("admin/updateOrderStatus") ?>', {
            order_id: orderId,
            status: 'delivered',
            admin_notes: 'Order delivered by admin'
        }, function(response) {
            if (response.success) {
                alert('Order marked as delivered!');
                loadOrders(currentFilter);
            } else {
                alert('Error: ' + response.message);
            }
        });
    }
}

function viewOrder(orderId) {
    window.location.href = '<?= base_url("admin/viewOrder?id=") ?>' + orderId;
}
</script>
