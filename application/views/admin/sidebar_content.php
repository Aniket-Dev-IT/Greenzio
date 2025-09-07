<div class="sidebar-wrapper" style="padding: 15px 0;">
    <!-- Logo Section -->
    <div class="logo" style="padding: 20px; margin-bottom: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <a href="<?php echo base_url('admin/dashboard')?>" style="color: #ffffff; text-decoration: none; font-size: 26px; font-weight: bold; display: block;">
            <i class="fas fa-leaf" style="color: #27ae60; margin-right: 10px; font-size: 28px;"></i>
            <span style="color: #ffffff;">Greenzio</span>
        </a>
        <div style="font-size: 11px; color: #bdc3c7; margin-top: 8px; text-transform: uppercase; letter-spacing: 1px;">Admin Panel</div>
    </div>

    <!-- Navigation Menu -->
    <ul class="nav-menu" style="list-style: none; padding: 0; margin: 0;">
        <!-- Dashboard -->
        <li style="margin-bottom: 2px;">
            <a href="<?php echo base_url('admin/dashboard');?>" class="nav-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ffffff; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-tachometer-alt" style="width: 20px; margin-right: 12px; color: #ffffff; font-size: 16px;"></i>
                <span style="color: #ffffff;">Dashboard</span>
            </a>
        </li>
        
        <!-- Products Section -->
        <li style="margin-bottom: 2px;">
            <a href="<?php echo base_url('admin/productlist');?>" class="nav-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ffffff; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-box-open" style="width: 20px; margin-right: 12px; color: #ffffff; font-size: 16px;"></i>
                <span style="color: #ffffff;">Manage Products</span>
            </a>
        </li>
        
        <li style="margin-bottom: 2px;">
            <a href="<?php echo base_url('admin/productinsert');?>" class="nav-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ffffff; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-plus-circle" style="width: 20px; margin-right: 12px; color: #ffffff; font-size: 16px;"></i>
                <span style="color: #ffffff;">Add Product</span>
            </a>
        </li>
        
        <li style="margin-bottom: 2px;">
            <a href="<?php echo base_url('admin/stockmanagement');?>" class="nav-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ffffff; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-warehouse" style="width: 20px; margin-right: 12px; color: #ffffff; font-size: 16px;"></i>
                <span style="color: #ffffff;">Stock Management</span>
            </a>
        </li>
        
        <!-- Orders Section -->
        <li style="margin-bottom: 2px;">
            <a href="<?php echo base_url('admin/manageorders');?>" class="nav-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ffffff; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-shopping-cart" style="width: 20px; margin-right: 12px; color: #ffffff; font-size: 16px;"></i>
                <span style="color: #ffffff;">Manage Orders</span>
            </a>
        </li>
        
        <!-- User Management -->
        <li style="margin-bottom: 2px;">
            <a href="<?php echo base_url('admin/userslist');?>" class="nav-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ffffff; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-users" style="width: 20px; margin-right: 12px; color: #ffffff; font-size: 16px;"></i>
                <span style="color: #ffffff;">Manage Users</span>
            </a>
        </li>
        
        <li style="margin-bottom: 2px;">
            <a href="<?php echo base_url('admin/manageAdmin');?>" class="nav-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ffffff; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-user-shield" style="width: 20px; margin-right: 12px; color: #ffffff; font-size: 16px;"></i>
                <span style="color: #ffffff;">Manage Admins</span>
            </a>
        </li>
        
        <!-- Categories & Content -->
        <li style="margin-bottom: 2px;">
            <a href="<?php echo base_url('admin/manageCategories');?>" class="nav-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ffffff; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-tags" style="width: 20px; margin-right: 12px; color: #ffffff; font-size: 16px;"></i>
                <span style="color: #ffffff;">Categories</span>
            </a>
        </li>
        
        <!-- Alerts & Notifications -->
        <li style="margin-bottom: 2px;">
            <a href="<?php echo base_url('admin/expiryalerts');?>" class="nav-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ffffff; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-exclamation-triangle" style="width: 20px; margin-right: 12px; color: #ffa726; font-size: 16px;"></i>
                <span style="color: #ffffff;">Expiry Alerts</span>
            </a>
        </li>
        
        <!-- Communication -->
        <li style="margin-bottom: 2px;">
            <a href="<?php echo base_url('admin/manageContacts');?>" class="nav-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ffffff; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-envelope" style="width: 20px; margin-right: 12px; color: #42a5f5; font-size: 16px;"></i>
                <span style="color: #ffffff;">Messages</span>
            </a>
        </li>
        
        <!-- Reports & Analytics -->
        <li style="margin-bottom: 2px;">
            <a href="<?php echo base_url('admin/reports');?>" class="nav-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ffffff; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-chart-bar" style="width: 20px; margin-right: 12px; color: #66bb6a; font-size: 16px;"></i>
                <span style="color: #ffffff;">Reports</span>
            </a>
        </li>
        
        <!-- Settings -->
        <li style="margin-bottom: 2px;">
            <a href="<?php echo base_url('admin/settings');?>" class="nav-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ffffff; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-cogs" style="width: 20px; margin-right: 12px; color: #ab47bc; font-size: 16px;"></i>
                <span style="color: #ffffff;">Settings</span>
            </a>
        </li>
        
        <!-- Profile -->
        <li style="margin-bottom: 2px;">
            <a href="<?php echo base_url('admin/profile');?>" class="nav-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ffffff; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-user-cog" style="width: 20px; margin-right: 12px; color: #26c6da; font-size: 16px;"></i>
                <span style="color: #ffffff;">Profile</span>
            </a>
        </li>
        
        <!-- Logout -->
        <li style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
            <a href="<?php echo base_url('admin/logout');?>" class="nav-link logout-link" style="display: flex; align-items: center; padding: 12px 20px; color: #ff5722; text-decoration: none; transition: all 0.3s ease; font-size: 14px;">
                <i class="fas fa-sign-out-alt" style="width: 20px; margin-right: 12px; color: #ff5722; font-size: 16px;"></i>
                <span style="color: #ff5722; font-weight: 500;">Logout</span>
            </a>
        </li>
    </ul>
</div>

<style>
/* Sidebar Navigation Styling */
.nav-link {
    border-left: 3px solid transparent;
    position: relative;
    font-weight: 500;
}

.nav-link:hover {
    background-color: rgba(255,255,255,0.08) !important;
    border-left: 3px solid #27ae60;
    transform: translateX(3px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.nav-link.active {
    background-color: rgba(39,174,96,0.2) !important;
    border-left: 3px solid #27ae60;
}

.logout-link:hover {
    background-color: rgba(255,87,34,0.1) !important;
    border-left: 3px solid #ff5722;
}

/* Logo hover effect */
.logo a:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease;
}

/* Ensure Font Awesome icons are always visible */
.fas {
    font-family: "Font Awesome 6 Free" !important;
    font-weight: 900 !important;
    display: inline-block !important;
    font-style: normal !important;
    font-variant: normal !important;
    text-rendering: auto !important;
    -webkit-font-smoothing: antialiased !important;
    color: inherit !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* Ensure text is visible */
.nav-link span {
    color: #ffffff !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* Ensure proper contrast */
.sidebar-wrapper {
    background: rgba(0,0,0,0.1);
    border-radius: 0;
}

/* Navigation menu scroll */
.nav-menu {
    max-height: calc(100vh - 150px);
    overflow-y: auto;
    overflow-x: hidden;
}

.nav-menu::-webkit-scrollbar {
    width: 4px;
}

.nav-menu::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
}

.nav-menu::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 2px;
}

.nav-menu::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.5);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .nav-link {
        padding: 10px 15px !important;
        font-size: 13px !important;
    }
    
    .logo {
        padding: 15px !important;
    }
    
    .logo a {
        font-size: 22px !important;
    }
    
    .fas {
        font-size: 14px !important;
    }
}
</style>
