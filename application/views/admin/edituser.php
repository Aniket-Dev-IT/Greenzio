<?php 
// Edit user form for admin
?>

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
            <div class="col-md-8 col-md-offset-2">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Edit User Details</h4>
                        <p class="category">Modify user information, change passwords, and update contact details</p>
                    </div>
                    <div class="content">
                        <form method="post" action="<?php echo base_url('admin/updateUser'); ?>">
                            <input type="hidden" name="user_id" value="<?php echo $userDetails['uid']; ?>">
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><i class="fa fa-id-badge"></i> User ID</label>
                                        <input type="text" class="form-control" value="<?php echo $userDetails['uid']; ?>" disabled>
                                        <small class="form-text text-muted">User ID cannot be changed</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fa fa-envelope"></i> Email Address *</label>
                                        <input type="email" class="form-control" name="email" 
                                               value="<?php echo $userDetails['email']; ?>" 
                                               required placeholder="Enter email address">
                                        <small class="form-text text-muted">Used for login and notifications</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fa fa-phone"></i> Mobile Number</label>
                                        <input type="text" class="form-control" name="mobile" 
                                               value="<?php echo $userDetails['mobile']; ?>" 
                                               placeholder="Enter 10-digit mobile number" 
                                               pattern="[0-9]{10}" title="Please enter a valid 10-digit mobile number">
                                        <small class="form-text text-muted">Optional - Can be used for login</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fa fa-venus-mars"></i> Gender *</label>
                                        <select class="form-control" name="gender" required>
                                            <option value="m" <?php echo ($userDetails['gender'] == 'm') ? 'selected' : ''; ?>>Male</option>
                                            <option value="f" <?php echo ($userDetails['gender'] == 'f') ? 'selected' : ''; ?>>Female</option>
                                        </select>
                                        <small class="form-text text-muted">Affects product recommendations</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fa fa-key"></i> New Password</label>
                                        <input type="password" class="form-control" name="password" 
                                               placeholder="Enter new password (leave empty to keep current)">
                                        <small class="form-text text-muted">Leave blank to keep current password</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-warning">
                                        <strong><i class="fa fa-exclamation-triangle"></i> Important:</strong>
                                        <ul class="mb-0">
                                            <li>Changing email will affect the user's ability to login</li>
                                            <li>If you change the password, notify the user of the new credentials</li>
                                            <li>Mobile number changes will affect mobile-based login</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-success btn-fill">
                                            <i class="fa fa-save"></i> Update User Details
                                        </button>
                                        <a href="<?php echo base_url('admin/userslist'); ?>" class="btn btn-default">
                                            <i class="fa fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
