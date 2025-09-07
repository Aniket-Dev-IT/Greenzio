<nav class="navbar" style="background: white; padding: 15px 20px; border-bottom: 1px solid #dee2e6; margin-bottom: 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div>
            <h4 style="margin: 0; color: #2c3e50; font-weight: 600;">
                <i class="fas fa-tachometer-alt" style="color: #27ae60; margin-right: 10px;"></i>
                Admin Dashboard
            </h4>
            <small style="color: #6c757d;">Welcome back, <?php echo $this->session->userdata('admin_name') ?: 'Administrator'; ?></small>
        </div>
        
        <div style="display: flex; align-items: center; gap: 15px;">
            <!-- Notifications -->
            <div class="dropdown" style="position: relative;">
                <button id="notificationBtn" style="background: none; border: none; color: #6c757d; font-size: 18px; cursor: pointer; position: relative;">
                    <i class="fas fa-bell"></i>
                    <span style="position: absolute; top: -5px; right: -5px; background: #e74c3c; color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; display: flex; align-items: center; justify-content: center;">3</span>
                </button>
                <div id="notificationDropdown" style="position: absolute; right: 0; top: 100%; background: white; border: 1px solid #dee2e6; border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); min-width: 250px; display: none; z-index: 1050;">
                    <div style="padding: 10px; border-bottom: 1px solid #dee2e6; font-weight: bold; color: #2c3e50;">
                        Notifications
                    </div>
                    <div style="padding: 10px; border-bottom: 1px solid #f8f9fa;">
                        <small style="color: #6c757d;">New order received</small>
                    </div>
                    <div style="padding: 10px; border-bottom: 1px solid #f8f9fa;">
                        <small style="color: #6c757d;">Low stock alert</small>
                    </div>
                    <div style="padding: 10px;">
                        <small style="color: #6c757d;">System maintenance scheduled</small>
                    </div>
                </div>
            </div>
            
            <!-- User Menu -->
            <div class="dropdown" style="position: relative;">
                <button id="userMenuBtn" style="background: none; border: none; color: #6c757d; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-user-circle" style="font-size: 24px;"></i>
                    <span><?php echo $this->session->userdata('admin_name') ?: 'Admin'; ?></span>
                    <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                </button>
                <div id="userDropdown" style="position: absolute; right: 0; top: 100%; background: white; border: 1px solid #dee2e6; border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); min-width: 180px; display: none; z-index: 1050;">
                    <a href="<?php echo base_url('admin/profile'); ?>" style="display: block; padding: 10px 15px; color: #2c3e50; text-decoration: none; border-bottom: 1px solid #f8f9fa;">
                        <i class="fas fa-user-cog" style="margin-right: 8px;"></i>
                        Profile
                    </a>
                    <a href="<?php echo base_url('admin/dashboard'); ?>" style="display: block; padding: 10px 15px; color: #2c3e50; text-decoration: none; border-bottom: 1px solid #f8f9fa;">
                        <i class="fas fa-tachometer-alt" style="margin-right: 8px;"></i>
                        Dashboard
                    </a>
                    <a href="<?php echo base_url('admin/logout'); ?>" style="display: block; padding: 10px 15px; color: #e74c3c; text-decoration: none;">
                        <i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
// Dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    // User dropdown
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    
    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.style.display = userDropdown.style.display === 'none' || !userDropdown.style.display ? 'block' : 'none';
            // Close notification dropdown
            const notificationDropdown = document.getElementById('notificationDropdown');
            if (notificationDropdown) {
                notificationDropdown.style.display = 'none';
            }
        });
    }
    
    // Notification dropdown
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.style.display = notificationDropdown.style.display === 'none' || !notificationDropdown.style.display ? 'block' : 'none';
            // Close user dropdown
            if (userDropdown) {
                userDropdown.style.display = 'none';
            }
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        if (userDropdown) userDropdown.style.display = 'none';
        if (notificationDropdown) notificationDropdown.style.display = 'none';
    });
    
    // Dropdown hover styles
    const dropdownLinks = document.querySelectorAll('#userDropdown a, #notificationDropdown div');
    dropdownLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        link.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>

<style>
/* Ensure Font Awesome icons load properly in header */
.fas {
    font-family: "Font Awesome 6 Free" !important;
    font-weight: 900 !important;
    display: inline-block !important;
    font-style: normal !important;
}
</style>
