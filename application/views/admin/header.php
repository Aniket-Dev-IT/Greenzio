
    <div class="main-panel">
        <nav class="navbar navbar-expand-lg navbar-light bg-white navbar-fixed admin-navbar shadow-sm">
            <div class="container-fluid">
                <!-- Brand and Hamburger -->
                <div class="navbar-header d-flex align-items-center">
                    <button type="button" class="navbar-toggle sidebar-toggle mr-3" data-toggle="collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <i class="fas fa-bars"></i>
                    </button>
                    <a class="navbar-brand admin-brand" href="<?php echo base_url('admin/dashboard'); ?>">
                        <i class="fas fa-leaf mr-2 text-success"></i>
                        <strong>Greenzio</strong> <span class="admin-badge">Admin</span>
                    </a>
                </div>

                <!-- Breadcrumb Navigation -->
                <div class="navbar-nav ml-auto mr-auto d-none d-lg-flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb admin-breadcrumb mb-0 bg-transparent">
                            <li class="breadcrumb-item">
                                <a href="<?php echo base_url('admin/dashboard'); ?>" class="breadcrumb-link">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active admin-breadcrumb-active" aria-current="page">
                                <?php 
                                    $current_page = ucwords(str_replace('-', ' ', $this->uri->segment(2, 'Dashboard')));
                                    echo $current_page;
                                ?>
                            </li>
                        </ol>
                    </nav>
                </div>

                <!-- Right Side Navigation -->
                <div class="navbar-nav ml-auto d-flex flex-row align-items-center">
                    <!-- Search Bar -->
                    <div class="nav-item mr-3 d-none d-md-block">
                        <form class="admin-search-form">
                            <div class="input-group admin-search-group">
                                <input type="search" class="form-control admin-search-input" placeholder="Search admin..." aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn admin-search-btn" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Notifications Dropdown -->
                    <div class="nav-item dropdown mr-3">
                        <a class="nav-link admin-notification-btn" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right admin-notification-dropdown" aria-labelledby="notificationDropdown">
                            <div class="dropdown-header">
                                <i class="fas fa-bell mr-2"></i> Notifications
                                <span class="badge badge-primary badge-pill float-right">3</span>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item notification-item" href="<?php echo base_url('admin/expiryalerts'); ?>">
                                <div class="notification-icon bg-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">Expiry Alerts</div>
                                    <div class="notification-desc">5 products expiring soon</div>
                                    <div class="notification-time">2 minutes ago</div>
                                </div>
                            </a>
                            <a class="dropdown-item notification-item" href="<?php echo base_url('admin/stockmanagement'); ?>">
                                <div class="notification-icon bg-danger">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">Low Stock Alert</div>
                                    <div class="notification-desc">8 products low in stock</div>
                                    <div class="notification-time">15 minutes ago</div>
                                </div>
                            </a>
                            <a class="dropdown-item notification-item" href="<?php echo base_url('admin/manageorders'); ?>">
                                <div class="notification-icon bg-success">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">New Orders</div>
                                    <div class="notification-desc">12 new orders received</div>
                                    <div class="notification-time">1 hour ago</div>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center view-all-notifications" href="#">
                                <i class="fas fa-eye mr-1"></i> View All Notifications
                            </a>
                        </div>
                    </div>

                    <!-- Quick Actions Dropdown -->
                    <div class="nav-item dropdown mr-3">
                        <a class="nav-link admin-quick-action-btn" href="#" id="quickActionsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-plus-circle"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right admin-quick-actions-dropdown" aria-labelledby="quickActionsDropdown">
                            <div class="dropdown-header">
                                <i class="fas fa-bolt mr-2"></i> Quick Actions
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo base_url('admin/productinsert'); ?>">
                                <i class="fas fa-plus mr-2 text-success"></i> Add New Product
                            </a>
                            <a class="dropdown-item" href="<?php echo base_url('admin/manageCategories'); ?>">
                                <i class="fas fa-folder-plus mr-2 text-info"></i> Add Category
                            </a>
                            <a class="dropdown-item" href="<?php echo base_url('admin/suppliermanagement'); ?>">
                                <i class="fas fa-truck mr-2 text-warning"></i> Add Supplier
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo base_url('admin/batchupdate'); ?>">
                                <i class="fas fa-edit mr-2 text-primary"></i> Batch Update
                            </a>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="nav-item dropdown">
                        <a class="nav-link admin-user-btn" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="admin-user-avatar">
                                <img src="<?php echo base_url('assets_admin/img/default-avatar.jpg'); ?>" alt="Admin" class="admin-avatar-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="admin-avatar-fallback" style="display: none;">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                            </div>
                            <span class="admin-user-name d-none d-lg-inline ml-2">
                                <?php 
                                    $session_data = $this->session->userdata();
                                    echo isset($session_data['admin_name']) ? $session_data['admin_name'] : 'Admin';
                                ?>
                            </span>
                            <i class="fas fa-chevron-down ml-1 d-none d-lg-inline"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right admin-user-dropdown" aria-labelledby="userDropdown">
                            <div class="dropdown-header admin-user-header">
                                <div class="admin-user-info">
                                    <div class="admin-user-avatar-large">
                                        <img src="<?php echo base_url('assets_admin/img/default-avatar.jpg'); ?>" alt="Admin" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="admin-avatar-fallback-large" style="display: none;">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                    </div>
                                    <div class="admin-user-details">
                                        <div class="admin-user-name-large">
                                            <?php echo isset($session_data['admin_name']) ? $session_data['admin_name'] : 'Administrator'; ?>
                                        </div>
                                        <div class="admin-user-role">
                                            <?php echo isset($session_data['admin_role']) ? $session_data['admin_role'] : 'Super Admin'; ?>
                                        </div>
                                        <div class="admin-user-status">
                                            <i class="fas fa-circle text-success"></i> Online
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo base_url('admin/profile'); ?>">
                                <i class="fas fa-user mr-2"></i> My Profile
                            </a>
                            <a class="dropdown-item" href="<?php echo base_url('admin/settings'); ?>">
                                <i class="fas fa-cog mr-2"></i> Admin Settings
                            </a>
                            <a class="dropdown-item" href="<?php echo base_url('admin/activitylog'); ?>">
                                <i class="fas fa-history mr-2"></i> Activity Log
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo base_url(); ?>" target="_blank">
                                <i class="fas fa-external-link-alt mr-2"></i> View Site
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="<?php echo base_url('admin/logout'); ?>">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Responsive Alert Bar -->
        <div class="d-lg-none mobile-admin-bar">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <button class="btn btn-sm mobile-search-toggle">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="col">
                        <div class="mobile-admin-stats">
                            <span class="stat-item">
                                <i class="fas fa-bell text-warning"></i> 3
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-shopping-cart text-success"></i> 12
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-exclamation-triangle text-danger"></i> 5
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="mobile-time">
                            <small id="current-time"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Search Overlay -->
        <div class="mobile-search-overlay" id="mobileSearchOverlay">
            <div class="mobile-search-container">
                <form class="mobile-admin-search-form">
                    <div class="input-group">
                        <input type="search" class="form-control mobile-admin-search-input" placeholder="Search admin panel..." aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-success" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <button class="mobile-search-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <script>
        $(document).ready(function() {
            // Mobile search toggle
            $('.mobile-search-toggle').click(function() {
                $('#mobileSearchOverlay').fadeIn();
            });
            
            $('.mobile-search-close').click(function() {
                $('#mobileSearchOverlay').fadeOut();
            });
            
            // Update mobile time
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                $('#current-time').text(timeString);
            }
            
            updateTime();
            setInterval(updateTime, 60000); // Update every minute
            
            // Sidebar toggle functionality
            $('.sidebar-toggle').click(function() {
                $('body').toggleClass('sidebar-mini');
            });
            
            // Dynamic notification loading
            function loadNotifications() {
                $.ajax({
                    url: '<?php echo base_url('admin/getNotifications'); ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            updateNotificationDropdown(response.notifications);
                            updateNotificationBadge(response.count);
                            updateMobileStats(response.count);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.warn('Failed to load notifications:', error);
                    }
                });
            }
            
            function updateNotificationDropdown(notifications) {
                const dropdown = $('.admin-notification-dropdown');
                const itemsContainer = dropdown.find('.notification-item').parent();
                
                // Clear existing notification items (keep header and footer)
                dropdown.find('.notification-item').remove();
                
                if (notifications.length === 0) {
                    const noNotifications = $('<a class="dropdown-item text-center text-muted notification-item">No new notifications</a>');
                    dropdown.find('.dropdown-divider').first().after(noNotifications);
                } else {
                    notifications.forEach(function(notification) {
                        const notificationHtml = `
                            <a class="dropdown-item notification-item" href="${notification.link}">
                                <div class="notification-icon bg-${notification.type}">
                                    <i class="${notification.icon}"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">${notification.title}</div>
                                    <div class="notification-desc">${notification.message}</div>
                                    <div class="notification-time">${notification.time}</div>
                                </div>
                            </a>
                        `;
                        dropdown.find('.dropdown-divider').first().after(notificationHtml);
                    });
                }
            }
            
            function updateNotificationBadge(count) {
                const badge = $('.notification-badge');
                const headerBadge = $('.dropdown-header .badge-pill');
                
                if (count > 0) {
                    badge.text(count).show();
                    headerBadge.text(count);
                } else {
                    badge.hide();
                    headerBadge.text('0');
                }
            }
            
            function updateMobileStats(notificationCount) {
                // Update mobile stats if needed
                $('.mobile-admin-stats .stat-item').first().html('<i class="fas fa-bell text-warning"></i> ' + notificationCount);
            }
            
            // Load notifications on page load
            loadNotifications();
            
            // Refresh notifications every 30 seconds
            setInterval(loadNotifications, 30000);
            
            // Mark notification as read when clicked
            $(document).on('click', '.notification-item', function() {
                const notificationId = $(this).data('id');
                if (notificationId) {
                    $.post('<?php echo base_url('admin/markNotificationRead'); ?>', {
                        notification_id: notificationId
                    });
                }
            });
            
            // Auto-hide notifications after interaction
            $('.notification-item').click(function() {
                $(this).fadeOut();
            });
            
            // Search functionality for admin panel
            $('.admin-search-form').on('submit', function(e) {
                e.preventDefault();
                const searchTerm = $('.admin-search-input').val();
                if (searchTerm.trim()) {
                    // Implement search functionality here
                    console.log('Searching for:', searchTerm);
                    // For now, just focus on implementing the basic search
                    alert('Search functionality ready for implementation: ' + searchTerm);
                }
            });
            
            // Mobile search form submission
            $('.mobile-admin-search-form').on('submit', function(e) {
                e.preventDefault();
                const searchTerm = $('.mobile-admin-search-input').val();
                if (searchTerm.trim()) {
                    console.log('Mobile searching for:', searchTerm);
                    alert('Search functionality ready for implementation: ' + searchTerm);
                    $('#mobileSearchOverlay').fadeOut();
                }
            });
        });
        </script>

