        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Admin Activity Log</h4>
                                <p class="category">Track administrative actions and system events</p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Search activities..." id="activitySearch">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select class="form-control" id="actionFilter">
                                                <option value="">All Actions</option>
                                                <option value="Login">Login</option>
                                                <option value="Logout">Logout</option>
                                                <option value="Product Update">Product Update</option>
                                                <option value="User Management">User Management</option>
                                                <option value="Order Management">Order Management</option>
                                                <option value="Settings Change">Settings Change</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="date" class="form-control" id="dateFilter" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Timestamp</th>
                                            <th>Admin</th>
                                            <th>Action</th>
                                            <th>Description</th>
                                            <th>IP Address</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="activityTableBody">
                                        <?php if (!empty($activities)): ?>
                                            <?php foreach ($activities as $index => $activity): ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td>
                                                        <span class="text-primary">
                                                            <i class="fas fa-clock"></i>
                                                            <?php echo date('M d, Y H:i:s', strtotime($activity['timestamp'])); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="admin-avatar-small mr-2">
                                                                <i class="fas fa-user-shield text-success"></i>
                                                            </div>
                                                            <span><?php echo htmlspecialchars($activity['admin_name']); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-<?php 
                                                            echo $activity['action'] == 'Login' ? 'success' : 
                                                                ($activity['action'] == 'Logout' ? 'warning' : 
                                                                ($activity['action'] == 'Product Update' ? 'info' : 'primary')); 
                                                        ?>">
                                                            <?php echo htmlspecialchars($activity['action']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small><?php echo htmlspecialchars($activity['description']); ?></small>
                                                    </td>
                                                    <td>
                                                        <code><?php echo htmlspecialchars($activity['ip_address']); ?></code>
                                                    </td>
                                                    <td>
                                                        <i class="fas fa-check-circle text-success" title="Success"></i>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <div class="py-4">
                                                        <i class="fas fa-info-circle text-muted fa-2x mb-2"></i>
                                                        <p class="text-muted">No activity logs found</p>
                                                        <small>Admin actions will appear here when performed</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                
                                <!-- Sample additional activities for demo -->
                                <script>
                                    // Add some sample activities for demonstration
                                    $(document).ready(function() {
                                        var sampleActivities = [
                                            {
                                                timestamp: new Date(Date.now() - 3600000).toLocaleString(),
                                                admin_name: '<?php echo $this->session->userdata('admin_name'); ?>',
                                                action: 'Stock Update',
                                                description: 'Updated stock levels for Fresh Apples',
                                                ip_address: '<?php echo $this->input->ip_address(); ?>',
                                                status: 'success'
                                            },
                                            {
                                                timestamp: new Date(Date.now() - 7200000).toLocaleString(),
                                                admin_name: '<?php echo $this->session->userdata('admin_name'); ?>',
                                                action: 'Order Processing',
                                                description: 'Processed order #ORD-2024-001',
                                                ip_address: '<?php echo $this->input->ip_address(); ?>',
                                                status: 'success'
                                            },
                                            {
                                                timestamp: new Date(Date.now() - 10800000).toLocaleString(),
                                                admin_name: '<?php echo $this->session->userdata('admin_name'); ?>',
                                                action: 'User Management',
                                                description: 'Reviewed user profile for user ID: 123',
                                                ip_address: '<?php echo $this->input->ip_address(); ?>',
                                                status: 'success'
                                            },
                                            {
                                                timestamp: new Date(Date.now() - 14400000).toLocaleString(),
                                                admin_name: '<?php echo $this->session->userdata('admin_name'); ?>',
                                                action: 'Product Insert',
                                                description: 'Added new product: Organic Bananas',
                                                ip_address: '<?php echo $this->input->ip_address(); ?>',
                                                status: 'success'
                                            }
                                        ];
                                        
                                        // Append sample activities to table
                                        var currentRowCount = $('#activityTableBody tr').length;
                                        if (currentRowCount <= 1) { // If no real data, clear the "no data" row
                                            $('#activityTableBody').empty();
                                            currentRowCount = 0;
                                        }
                                        
                                        sampleActivities.forEach(function(activity, index) {
                                            var badgeClass = activity.action.includes('Stock') ? 'info' : 
                                                           (activity.action.includes('Order') ? 'warning' : 
                                                           (activity.action.includes('User') ? 'primary' : 'success'));
                                            
                                            var row = `
                                                <tr class="sample-activity">
                                                    <td>${currentRowCount + index + 1}</td>
                                                    <td>
                                                        <span class="text-primary">
                                                            <i class="fas fa-clock"></i>
                                                            ${activity.timestamp}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="admin-avatar-small mr-2">
                                                                <i class="fas fa-user-shield text-success"></i>
                                                            </div>
                                                            <span>${activity.admin_name || 'Admin'}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-${badgeClass}">
                                                            ${activity.action}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small>${activity.description}</small>
                                                    </td>
                                                    <td>
                                                        <code>${activity.ip_address}</code>
                                                    </td>
                                                    <td>
                                                        <i class="fas fa-check-circle text-success" title="Success"></i>
                                                    </td>
                                                </tr>
                                            `;
                                            $('#activityTableBody').append(row);
                                        });
                                    });
                                    
                                    // Filter functionality
                                    $('#activitySearch, #actionFilter, #dateFilter').on('input change', function() {
                                        var searchTerm = $('#activitySearch').val().toLowerCase();
                                        var actionFilter = $('#actionFilter').val();
                                        var dateFilter = $('#dateFilter').val();
                                        
                                        $('#activityTableBody tr').each(function() {
                                            var row = $(this);
                                            var text = row.text().toLowerCase();
                                            var action = row.find('.badge').text();
                                            var timestamp = row.find('td:eq(1)').text();
                                            
                                            var matchesSearch = searchTerm === '' || text.includes(searchTerm);
                                            var matchesAction = actionFilter === '' || action === actionFilter;
                                            var matchesDate = dateFilter === '' || timestamp.includes(dateFilter);
                                            
                                            if (matchesSearch && matchesAction && matchesDate) {
                                                row.show();
                                            } else {
                                                row.hide();
                                            }
                                        });
                                    });
                                </script>
                                
                                <style>
                                    .admin-avatar-small {
                                        width: 20px;
                                        height: 20px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                    }
                                    
                                    .table td {
                                        vertical-align: middle;
                                    }
                                    
                                    .badge {
                                        font-size: 0.75rem;
                                    }
                                    
                                    code {
                                        font-size: 0.8rem;
                                        color: #6c757d;
                                    }
                                </style>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
