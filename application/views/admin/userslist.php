<?php 
// Display users list for admin with enhanced management capabilities
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
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Manage Users</h4>
                        <p class="category">Complete user management system - Edit, Delete, Manage user accounts</p>
                    </div>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>User ID</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Gender</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                <?php if (!empty($usersList)) { ?>
                                    <?php foreach ($usersList as $user) { ?>
                                        <tr>
                                            <td><strong><?php echo $user['uid']; ?></strong></td>
                                            <td>
                                                <i class="fa fa-envelope"></i> 
                                                <?php echo $user['email']; ?>
                                            </td>
                                            <td>
                                                <i class="fa fa-phone"></i> 
                                                <?php echo !empty($user['mobile']) ? $user['mobile'] : '<span class="text-muted">Not provided</span>'; ?>
                                            </td>
                                            <td>
                                                <?php if ($user['gender'] == 'm') { ?>
                                                    <i class="fa fa-mars text-primary"></i> Male
                                                <?php } else { ?>
                                                    <i class="fa fa-venus text-danger"></i> Female
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">Active</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo base_url('admin/editUser?id=' . $user['uid']); ?>" 
                                                       class="btn btn-info btn-sm" 
                                                       title="Edit User">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <a href="<?php echo base_url('admin/deleteUser?id=' . $user['uid']); ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       title="Delete User"
                                                       onclick="return confirm('Are you sure you want to delete this user? This will also delete all their orders and data. This action cannot be undone!')">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <i class="fa fa-users fa-3x text-muted"></i><br>
                                            <strong>No users found</strong><br>
                                            <small>There are no registered users in the system.</small>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        
                        <?php if (!empty($usersList)) { ?>
                            <div class="footer">
                                <hr>
                                <div class="stats">
                                    <i class="fa fa-users"></i> Total Users: <strong><?php echo count($usersList); ?></strong>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
