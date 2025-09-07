<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Greenzio Admin Panel</title>
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/favicon.png'); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Clean Admin CSS -->
    <link href="<?php echo base_url().'assets_admin/css/admin-clean.css';?>" rel="stylesheet" />
</head>
<body>

<div class="wrapper">
    <div class="sidebar">
        <div class="sidebar-wrapper">
            <div class="logo">
                <a href="<?php echo base_url('admin/dashboard')?>">
                    <i class="fas fa-leaf logo-icon"></i>
                    Greenzio
                </a>
            </div>

            <ul class="nav">
                <li>
                    <a href="<?php echo base_url('admin/dashboard');?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/productlist');?>">
                        <i class="fas fa-box-open"></i>
                        <p>Manage Products</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/productinsert');?>">
                        <i class="fas fa-plus-circle"></i>
                        <p>Add Product</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/stockmanagement');?>">
                        <i class="fas fa-warehouse"></i>
                        <p>Stock Management</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/manageorders');?>">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Manage Orders</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/userslist');?>">
                        <i class="fas fa-users"></i>
                        <p>Manage Users</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/manageAdmin');?>">
                        <i class="fas fa-user-shield"></i>
                        <p>Manage Admins</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/manageCategories');?>">
                        <i class="fas fa-tags"></i>
                        <p>Categories</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/expiryalerts');?>">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Expiry Alerts</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/manageContacts');?>">
                        <i class="fas fa-envelope"></i>
                        <p>Messages</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/profile');?>">
                        <i class="fas fa-user-cog"></i>
                        <p>Profile</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/logout');?>">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
                <!-- <li>
                    <a href="notifications.html">
                        <i class="pe-7s-bell"></i>
                        <p>Notifications</p>
                    </a>
                </li> -->
				<!-- <li class="active-pro">
                    <a href="upgrade.html">
                        <i class="pe-7s-rocket"></i>
                        <p>Upgrade to PRO</p>
                    </a>
                </li> -->
            </ul>
    	</div>
    </div>
