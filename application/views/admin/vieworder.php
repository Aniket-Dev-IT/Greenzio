<?php 
// Detailed order view for admin
?>

<div class="content">
    <div class="container-fluid">
        
        <?php if ($this->session->flashdata('item')) { ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong><?php echo $this->session->flashdata('item')['message']; ?></strong>
                <button type=\"button\" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>

        <!-- Back to Orders Button -->
        <div class="row">
            <div class="col-md-12">
                <a href="<?php echo base_url('admin/manageorders'); ?>" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Back to Orders List
                </a>
            </div>
        </div>
        <br>

        <div class="row">
            <!-- Order Summary -->
            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Order Summary</h4>
                        <p class="category">Order #ORD<?php echo str_pad($orderDetails['order_id'], 4, '0', STR_PAD_LEFT); ?></p>
                    </div>
                    <div class="content">
                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fa fa-calendar"></i> Order Date:</strong><br>
                                <?php echo date('F d, Y', strtotime($orderDetails['order_date'])); ?>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fa fa-money-bill"></i> Total Amount:</strong><br>
                                <h4 class="text-success">₹<?php echo number_format($orderDetails['order_total']); ?></h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <strong><i class="fa fa-info-circle"></i> Current Status:</strong><br>
                                <?php
                                $status_class = 'label-default';
                                switch(strtolower($orderDetails['order_status'])) {
                                    case 'pending': $status_class = 'label-warning'; break;
                                    case 'confirmed': case 'confirm': $status_class = 'label-info'; break;
                                    case 'shipped': $status_class = 'label-primary'; break;
                                    case 'delivered': $status_class = 'label-success'; break;
                                    case 'cancelled': $status_class = 'label-danger'; break;
                                }
                                ?>
                                <span class="label <?php echo $status_class; ?>" style="font-size: 14px; padding: 8px 12px;">
                                    <?php echo ucfirst($orderDetails['order_status']); ?>
                                </span>
                            </div>
                        </div>
                        <hr>
                        <!-- Status Update Form -->
                        <form method="post" action="<?php echo base_url('admin/updateOrderStatus'); ?>">
                            <input type="hidden" name="order_id" value="<?php echo $orderDetails['order_id']; ?>">
                            <div class="form-group">
                                <label><strong><i class="fa fa-edit"></i> Update Status:</strong></label>
                                <select name="status" class="form-control">
                                    <option value="pending" <?php echo ($orderDetails['order_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo ($orderDetails['order_status'] == 'confirmed' || $orderDetails['order_status'] == 'confirm') ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="shipped" <?php echo ($orderDetails['order_status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo ($orderDetails['order_status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo ($orderDetails['order_status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-fill">
                                <i class="fa fa-save"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="col-md-6">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Customer Information</h4>
                        <p class="category">User ID: <?php echo $orderDetails['user_id']; ?></p>
                    </div>
                    <div class="content">
                        <div class="row">
                            <div class="col-md-12">
                                <strong><i class="fa fa-envelope"></i> Email:</strong><br>
                                <?php echo !empty($orderDetails['email']) ? $orderDetails['email'] : '<em>Not available</em>'; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <strong><i class="fa fa-phone"></i> Mobile:</strong><br>
                                <?php echo !empty($orderDetails['mobile']) ? $orderDetails['mobile'] : '<em>Not provided</em>'; ?>
                            </div>
                        </div>
                        
                        <?php if (!empty($billingDetails)) { ?>
                        <hr>
                        <h5><i class="fa fa-map-marker"></i> Billing Address:</h5>
                        <address>
                            <strong><?php echo $billingDetails['name']; ?></strong><br>
                            <?php echo $billingDetails['street']; ?><br>
                            <?php echo $billingDetails['city']; ?>, <?php echo $billingDetails['state']; ?> - <?php echo $billingDetails['zip']; ?><br>
                            <strong>Mobile:</strong> <?php echo $billingDetails['mobile']; ?>
                        </address>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fa fa-truck"></i> Delivery:</strong><br>
                                <?php echo ucfirst($billingDetails['delivery_method']); ?>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fa fa-credit-card"></i> Payment:</strong><br>
                                <?php echo strtoupper($billingDetails['payment_method']); ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Order Items</h4>
                        <p class="category">Products ordered by the customer</p>
                    </div>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Image</th>
                                    <th>Size</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orderItems)) { 
                                    $subtotal = 0;
                                    foreach ($orderItems as $item) { 
                                        $item_total = $item['product_price'] * $item['product_quantity'];
                                        $subtotal += $item_total;
                                ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $item['pname']; ?></strong><br>
                                            <small class="text-muted">Product ID: <?php echo $item['product_id']; ?></small>
                                        </td>
                                        <td>
                                            <?php if (!empty($item['pimage'])) { ?>
                                                <img src="<?php echo base_url('assets/img/' . $item['pimage']); ?>" 
                                                     alt="Product Image" 
                                                     style="width: 50px; height: 50px; object-fit: cover;" 
                                                     class="img-thumbnail">
                                            <?php } else { ?>
                                                <div style="width: 50px; height: 50px;" class="bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fa fa-image text-muted"></i>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary"><?php echo strtoupper($item['product_size']); ?></span>
                                        </td>
                                        <td>
                                            <strong><?php echo $item['product_quantity']; ?></strong>
                                        </td>
                                        <td>
                                            ₹<?php echo number_format($item['product_price']); ?>
                                        </td>
                                        <td>
                                            <strong>₹<?php echo number_format($item_total); ?></strong>
                                        </td>
                                    </tr>
                                <?php } 
                                } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <i class="fa fa-shopping-cart fa-2x text-muted"></i><br>
                                            <strong>No items found for this order</strong>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <?php if (!empty($orderItems)) { ?>
                            <tfoot>
                                <tr class="table-active">
                                    <td colspan="5" class="text-right"><strong>Order Total:</strong></td>
                                    <td><strong>₹<?php echo number_format($orderDetails['order_total']); ?></strong></td>
                                </tr>
                            </tfoot>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
