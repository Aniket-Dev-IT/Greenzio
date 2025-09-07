        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Admin Panel Settings</h4>
                                <p class="category">Configure your admin panel preferences</p>
                            </div>
                            <div class="content">
                                <form method="post" action="<?php echo base_url('admin/updateSettings'); ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Site Name</label>
                                                <input type="text" class="form-control" name="site_name" 
                                                       value="<?php echo htmlspecialchars($settings['site_name']); ?>" 
                                                       placeholder="Admin Panel Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Auto Logout (minutes)</label>
                                                <select class="form-control" name="auto_logout">
                                                    <option value="15" <?php echo $settings['auto_logout'] == 15 ? 'selected' : ''; ?>>15 minutes</option>
                                                    <option value="30" <?php echo $settings['auto_logout'] == 30 ? 'selected' : ''; ?>>30 minutes</option>
                                                    <option value="60" <?php echo $settings['auto_logout'] == 60 ? 'selected' : ''; ?>>1 hour</option>
                                                    <option value="120" <?php echo $settings['auto_logout'] == 120 ? 'selected' : ''; ?>>2 hours</option>
                                                    <option value="240" <?php echo $settings['auto_logout'] == 240 ? 'selected' : ''; ?>>4 hours</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5>System Preferences</h5>
                                            <hr>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="maintenance_mode" 
                                                           value="1" <?php echo $settings['maintenance_mode'] ? 'checked' : ''; ?>>
                                                    Maintenance Mode
                                                </label>
                                                <small class="form-text text-muted">
                                                    Enable maintenance mode to prevent customer access while updating the system.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="notifications_enabled" 
                                                           value="1" <?php echo $settings['notifications_enabled'] ? 'checked' : ''; ?>>
                                                    Enable Notifications
                                                </label>
                                                <small class="form-text text-muted">
                                                    Receive notifications for low stock, expiry alerts, and new orders.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5>Inventory Alert Thresholds</h5>
                                            <hr>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Low Stock Threshold</label>
                                                <input type="number" class="form-control" name="low_stock_threshold" 
                                                       value="10" min="1" max="100" placeholder="10">
                                                <small class="form-text text-muted">Alert when stock falls below this number</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Expiry Alert Days</label>
                                                <input type="number" class="form-control" name="expiry_alert_days" 
                                                       value="7" min="1" max="30" placeholder="7">
                                                <small class="form-text text-muted">Alert for products expiring in X days</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Order Alert Hours</label>
                                                <input type="number" class="form-control" name="order_alert_hours" 
                                                       value="24" min="1" max="168" placeholder="24">
                                                <small class="form-text text-muted">Show new orders from last X hours</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5>System Information</h5>
                                            <hr>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card card-stats">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-5 col-md-4">
                                                            <div class="icon-big text-center icon-warning">
                                                                <i class="fas fa-server text-info"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-7 col-md-8">
                                                            <div class="numbers">
                                                                <p class="card-category">PHP Version</p>
                                                                <p class="card-title"><?php echo phpversion(); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card card-stats">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-5 col-md-4">
                                                            <div class="icon-big text-center icon-warning">
                                                                <i class="fas fa-code text-success"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-7 col-md-8">
                                                            <div class="numbers">
                                                                <p class="card-category">CodeIgniter</p>
                                                                <p class="card-title"><?php echo CI_VERSION; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card card-stats">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-5 col-md-4">
                                                            <div class="icon-big text-center icon-warning">
                                                                <i class="fas fa-database text-warning"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-7 col-md-8">
                                                            <div class="numbers">
                                                                <p class="card-category">MySQL</p>
                                                                <p class="card-title">Active</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card card-stats">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-5 col-md-4">
                                                            <div class="icon-big text-center icon-warning">
                                                                <i class="fas fa-clock text-primary"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-7 col-md-8">
                                                            <div class="numbers">
                                                                <p class="card-category">Timezone</p>
                                                                <p class="card-title"><?php echo date_default_timezone_get(); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-success btn-fill pull-right">Save Settings</button>
                                            <button type="button" class="btn btn-default pull-right mr-2" 
                                                    onclick="window.location.reload();">Reset</button>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
