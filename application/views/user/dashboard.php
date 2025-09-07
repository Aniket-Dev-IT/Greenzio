<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Check if user is logged in
$userData = $this->session->userdata();
if (!isset($userData['userID'])) {
    redirect('user/login');
}
?>

<style>
.dashboard-container {
    padding: 2rem 0;
    min-height: 80vh;
}

.dashboard-header {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 2rem 0;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.dashboard-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: transform 0.3s;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.stat-card {
    text-align: center;
    border-left: 4px solid #28a745;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #28a745;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #666;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.9rem;
}

.recent-orders {
    max-height: 400px;
    overflow-y: auto;
}

.order-item {
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 0;
}

.order-item:last-child {
    border-bottom: none;
}

.order-status {
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background: #cce5ff;
    color: #0c5aa6;
}

.status-shipped {
    background: #d1ecf1;
    color: #0c5460;
}

.status-delivered {
    background: #d4edda;
    color: #155724;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.quick-action-btn {
    background: white;
    border: 2px solid #28a745;
    color: #28a745;
    padding: 1rem;
    border-radius: 10px;
    text-decoration: none;
    display: block;
    text-align: center;
    transition: all 0.3s;
    margin-bottom: 1rem;
}

.quick-action-btn:hover {
    background: #28a745;
    color: white;
    text-decoration: none;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #28a745;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    font-weight: 700;
    margin: 0 auto 1rem;
}

.address-card {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #28a745;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .dashboard-header {
        text-align: center;
    }
    
    .stat-number {
        font-size: 2rem;
    }
}
</style>

<main class="dashboard-container">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header text-center">
            <div class="profile-avatar">
                <?php echo strtoupper(substr($userData['email'] ?? 'G', 0, 1)); ?>
            </div>
            <h1 class="h3 mb-2">Welcome back!</h1>
            <p class="mb-0">
                <?php echo $userData['email'] ?? ($userData['mobile'] ?? 'Valued Customer'); ?>
            </p>
        </div>

        <div class="row">
            <!-- Left Column - Stats and Quick Actions -->
            <div class="col-lg-8">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="dashboard-card stat-card">
                            <div class="stat-number" id="totalOrders">0</div>
                            <div class="stat-label">Total Orders</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card stat-card">
                            <div class="stat-number" id="pendingOrders">0</div>
                            <div class="stat-label">Pending Orders</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card stat-card">
                            <div class="stat-number" id="totalSpent">₹0</div>
                            <div class="stat-label">Total Spent</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card stat-card">
                            <div class="stat-number" id="rewardPoints">0</div>
                            <div class="stat-label">Reward Points</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="dashboard-card">
                    <h5 class="mb-3">
                        <i class="fas fa-shopping-bag text-primary"></i>
                        Recent Orders
                    </h5>
                    
                    <div class="recent-orders" id="recentOrdersList">
                        <!-- Orders will be loaded via AJAX -->
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading your orders...</p>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="<?php echo base_url('order/orderList'); ?>" class="btn btn-outline-primary">
                            View All Orders
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column - Profile and Quick Actions -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="dashboard-card">
                    <h5 class="mb-3">
                        <i class="fas fa-bolt text-warning"></i>
                        Quick Actions
                    </h5>
                    
                    <a href="<?php echo base_url(); ?>" class="quick-action-btn">
                        <i class="fas fa-shopping-cart d-block mb-2"></i>
                        <strong>Start Shopping</strong>
                        <small class="d-block text-muted">Browse fresh groceries</small>
                    </a>
                    
                    <a href="<?php echo base_url('shopping/cart'); ?>" class="quick-action-btn">
                        <i class="fas fa-shopping-basket d-block mb-2"></i>
                        <strong>View Cart</strong>
                        <small class="d-block text-muted">Check cart items</small>
                    </a>
                    
                    <a href="<?php echo base_url('user/profile'); ?>" class="quick-action-btn">
                        <i class="fas fa-user-edit d-block mb-2"></i>
                        <strong>Edit Profile</strong>
                        <small class="d-block text-muted">Update your information</small>
                    </a>
                    
                    <a href="<?php echo base_url('user/addresses'); ?>" class="quick-action-btn">
                        <i class="fas fa-map-marker-alt d-block mb-2"></i>
                        <strong>Manage Addresses</strong>
                        <small class="d-block text-muted">Add or edit addresses</small>
                    </a>
                </div>

                <!-- Profile Summary -->
                <div class="dashboard-card">
                    <h5 class="mb-3">
                        <i class="fas fa-user text-success"></i>
                        Profile Summary
                    </h5>
                    
                    <div class="profile-info">
                        <div class="row mb-2">
                            <div class="col-4"><strong>Email:</strong></div>
                            <div class="col-8"><?php echo $userData['email'] ?? 'Not provided'; ?></div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-4"><strong>Mobile:</strong></div>
                            <div class="col-8"><?php echo $userData['mobile'] ?? 'Not provided'; ?></div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-4"><strong>Gender:</strong></div>
                            <div class="col-8">
                                <?php 
                                echo ($userData['gender'] ?? '') == 'm' ? 'Male' : (($userData['gender'] ?? '') == 'f' ? 'Female' : 'Not specified');
                                ?>
                            </div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-4"><strong>Member Since:</strong></div>
                            <div class="col-8" id="memberSince">-</div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="<?php echo base_url('user/profile'); ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                            Edit Profile
                        </a>
                    </div>
                </div>

                <!-- Saved Addresses -->
                <div class="dashboard-card">
                    <h5 class="mb-3">
                        <i class="fas fa-home text-info"></i>
                        Saved Addresses
                    </h5>
                    
                    <div id="savedAddresses">
                        <!-- Addresses will be loaded via AJAX -->
                        <div class="text-center py-3">
                            <div class="spinner-border text-info" role="status" style="width: 1.5rem; height: 1.5rem;">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted small">Loading addresses...</p>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="<?php echo base_url('user/addresses'); ?>" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-plus"></i>
                            Manage Addresses
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard data
    loadDashboardStats();
    loadRecentOrders();
    loadSavedAddresses();
});

function loadDashboardStats() {
    fetch('<?php echo base_url(); ?>user/getDashboardStats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalOrders').textContent = data.stats.total_orders || 0;
                document.getElementById('pendingOrders').textContent = data.stats.pending_orders || 0;
                document.getElementById('totalSpent').textContent = '₹' + (data.stats.total_spent || 0);
                document.getElementById('rewardPoints').textContent = data.stats.reward_points || 0;
                
                if (data.stats.member_since) {
                    document.getElementById('memberSince').textContent = new Date(data.stats.member_since).toLocaleDateString();
                }
            }
        })
        .catch(error => {
            console.error('Error loading dashboard stats:', error);
            // Set default values on error
            document.getElementById('totalOrders').textContent = '-';
            document.getElementById('pendingOrders').textContent = '-';
            document.getElementById('totalSpent').textContent = '₹-';
            document.getElementById('rewardPoints').textContent = '-';
        });
}

function loadRecentOrders() {
    fetch('<?php echo base_url(); ?>order/getRecentOrders?limit=5')
        .then(response => response.json())
        .then(data => {
            const ordersList = document.getElementById('recentOrdersList');
            
            if (data.success && data.orders && data.orders.length > 0) {
                let html = '';
                
                data.orders.forEach(order => {
                    const statusClass = getStatusClass(order.status);
                    const orderDate = new Date(order.created_at).toLocaleDateString();
                    
                    html += `
                        <div class="order-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Order #${order.id}</h6>
                                    <p class="text-muted mb-1">${orderDate}</p>
                                    <small class="text-muted">${order.item_count} items</small>
                                </div>
                                <div class="text-right">
                                    <div class="order-status ${statusClass}">${order.status}</div>
                                    <div class="mt-1"><strong>₹${order.total}</strong></div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                ordersList.innerHTML = html;
            } else {
                ordersList.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No orders yet</h6>
                        <p class="text-muted">Start shopping to see your orders here!</p>
                        <a href="<?php echo base_url(); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-shopping-cart"></i>
                            Start Shopping
                        </a>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading recent orders:', error);
            document.getElementById('recentOrdersList').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h6 class="text-muted">Unable to load orders</h6>
                    <button class="btn btn-outline-primary btn-sm" onclick="loadRecentOrders()">
                        <i class="fas fa-refresh"></i>
                        Try Again
                    </button>
                </div>
            `;
        });
}

function loadSavedAddresses() {
    fetch('<?php echo base_url(); ?>user/getSavedAddresses')
        .then(response => response.json())
        .then(data => {
            const addressesContainer = document.getElementById('savedAddresses');
            
            if (data.success && data.addresses && data.addresses.length > 0) {
                let html = '';
                
                data.addresses.slice(0, 2).forEach(address => { // Show only first 2
                    html += `
                        <div class="address-card">
                            <h6 class="mb-1">${address.type || 'Address'}</h6>
                            <p class="mb-0 small text-muted">
                                ${address.line1}, ${address.city}<br>
                                ${address.state} - ${address.pincode}
                            </p>
                        </div>
                    `;
                });
                
                addressesContainer.innerHTML = html;
            } else {
                addressesContainer.innerHTML = `
                    <div class="text-center py-3">
                        <i class="fas fa-map-marker-alt fa-2x text-muted mb-2"></i>
                        <p class="text-muted small mb-0">No saved addresses</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading saved addresses:', error);
            document.getElementById('savedAddresses').innerHTML = `
                <div class="text-center py-3">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    <p class="text-muted small mb-0">Unable to load addresses</p>
                </div>
            `;
        });
}

function getStatusClass(status) {
    const statusMap = {
        'pending': 'status-pending',
        'confirmed': 'status-confirmed',
        'shipped': 'status-shipped',
        'delivered': 'status-delivered',
        'cancelled': 'status-cancelled'
    };
    
    return statusMap[status.toLowerCase()] || 'status-pending';
}
</script>
