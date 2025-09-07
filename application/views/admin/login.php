<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Login - Greenzio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo base_url().'assets_admin/css/bootstrap.min.css';?>" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 400px;
            width: 100%;
        }
        .brand {
            text-align: center;
            margin-bottom: 30px;
        }
        .brand h2 {
            color: #28a745;
            margin-bottom: 5px;
        }
        .form-control {
            border-radius: 5px;
            padding: 12px;
            border: 1px solid #ddd;
        }
        .btn-success {
            background: #28a745;
            border: none;
            padding: 12px;
            border-radius: 5px;
            width: 100%;
        }
        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand">
            <i class="fas fa-leaf fa-3x text-success mb-3"></i>
            <h2>Greenzio</h2>
            <p class="text-muted">Admin Panel</p>
        </div>
        
        <!-- Display flash messages -->
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        
        <!-- Login Form -->
        <form method="POST" action="<?php echo base_url('admin/'); ?>">
            <div class="form-group mb-3">
                <label>Email Address</label>
                <input type="email" name="admin_email" class="form-control" placeholder="Enter email" required>
            </div>
            
            <div class="form-group mb-4">
                <label>Password</label>
                <input type="password" name="admin_password" class="form-control" placeholder="Enter password" required>
            </div>
            
            <button type="submit" class="btn btn-success">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
    </div>
</body>
</html>
