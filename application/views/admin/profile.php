<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-user-cog me-2"></i>
                            Admin Profile
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form id="profileForm" method="POST" action="<?php echo base_url('admin/updateProfile'); ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="admin_name" class="form-label">Name</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="admin_name" 
                                            name="admin_name" 
                                            value="<?php echo htmlspecialchars($admin_details['admin_name']); ?>"
                                            required
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="admin_email" class="form-label">Email</label>
                                        <input 
                                            type="email" 
                                            class="form-control" 
                                            id="admin_email" 
                                            name="admin_email" 
                                            value="<?php echo htmlspecialchars($admin_details['admin_email']); ?>"
                                            required
                                        >
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <h5 class="mb-3">Change Password</h5>
                            <p class="text-muted mb-3">Leave password fields empty if you don't want to change your password.</p>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input 
                                            type="password" 
                                            class="form-control" 
                                            id="current_password" 
                                            name="current_password"
                                            placeholder="Enter current password"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input 
                                            type="password" 
                                            class="form-control" 
                                            id="new_password" 
                                            name="new_password"
                                            placeholder="Enter new password"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <input 
                                            type="password" 
                                            class="form-control" 
                                            id="confirm_password" 
                                            name="confirm_password"
                                            placeholder="Confirm new password"
                                        >
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    <small>
                                        <i class="fas fa-info-circle me-1"></i>
                                        Last updated: <?php echo date('M d, Y h:i A'); ?>
                                    </small>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i>
                                        Update Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Profile Information Card -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Profile Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Admin ID:</strong> <?php echo $admin_details['admin_id']; ?></p>
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($admin_details['admin_name']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($admin_details['admin_email']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> <span class="badge badge-success">Active</span></p>
                                <p><strong>Role:</strong> <?php echo htmlspecialchars($admin_details['admin_role']); ?></p>
                                <p><strong>Login Status:</strong> <span class="text-success">Online</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Password validation
    $('#profileForm').on('submit', function(e) {
        const currentPassword = $('#current_password').val();
        const newPassword = $('#new_password').val();
        const confirmPassword = $('#confirm_password').val();
        
        // If any password field is filled, all should be filled
        if (newPassword || confirmPassword || currentPassword) {
            if (!currentPassword) {
                e.preventDefault();
                alert('Please enter your current password to change it.');
                $('#current_password').focus();
                return false;
            }
            
            if (!newPassword) {
                e.preventDefault();
                alert('Please enter a new password.');
                $('#new_password').focus();
                return false;
            }
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New passwords do not match.');
                $('#confirm_password').focus();
                return false;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('New password must be at least 6 characters long.');
                $('#new_password').focus();
                return false;
            }
        }
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Updating...');
        submitBtn.prop('disabled', true);
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});
</script>

