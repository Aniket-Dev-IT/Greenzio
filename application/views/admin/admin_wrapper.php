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
    <!-- Admin CSS -->
    <link href="<?php echo base_url().'assets_admin/css/admin-clean.css';?>" rel="stylesheet" />
    
    <style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f8f9fa;
        margin: 0;
        padding: 0;
    }
    
    .admin-wrapper {
        display: flex;
        min-height: 100vh;
        width: 100%;
    }
    
    .admin-sidebar {
        width: 260px;
        background: linear-gradient(135deg, #1a252f 0%, #2c3e50 100%);
        color: #ffffff;
        position: fixed;
        height: 100vh;
        left: 0;
        top: 0;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        border-right: 1px solid rgba(255,255,255,0.1);
    }
    
    .admin-main {
        margin-left: 260px;
        flex: 1;
        background: #f8f9fa;
        min-height: 100vh;
    }
    
    .admin-content {
        padding: 20px;
    }
    
    /* Ensure no main website styles interfere */
    .admin-wrapper * {
        box-sizing: border-box;
    }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Admin Sidebar -->
        <div class="admin-sidebar">
            <?php $this->load->view('admin/sidebar_content'); ?>
        </div>
        
        <!-- Admin Main Content -->
        <div class="admin-main">
            <!-- Admin Header -->
            <?php $this->load->view('admin/header_content'); ?>
            
            <!-- Admin Content -->
            <div class="admin-content">
                <?php echo isset($admin_content) ? $admin_content : ''; ?>
            </div>
            
            <!-- Admin Footer -->
            <?php $this->load->view('admin/footer_content'); ?>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Admin JS -->
    <script>
    $(document).ready(function() {
        console.log('Admin panel loaded successfully');
        
        // Prevent any main website JavaScript from interfering
        if (window.mainSiteScripts) {
            delete window.mainSiteScripts;
        }
        
        // Admin-specific JavaScript
        $('.sidebar-toggle').click(function() {
            $('.admin-sidebar').toggleClass('collapsed');
            $('.admin-main').toggleClass('sidebar-collapsed');
        });
    });
    </script>
</body>
</html>
