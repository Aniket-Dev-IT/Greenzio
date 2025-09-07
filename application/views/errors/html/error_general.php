<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error - Greenzio</title>
    <meta name="description" content="We're experiencing technical difficulties. Our team has been notified and is working to resolve the issue.">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .error-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 3rem;
            text-align: center;
            max-width: 600px;
            margin: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .error-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, #fff5f5 25%, transparent 25%, transparent 75%, #fff5f5 75%, #fff5f5),
                        linear-gradient(45deg, #fff5f5 25%, transparent 25%, transparent 75%, #fff5f5 75%, #fff5f5);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.05;
            z-index: -1;
        }
        
        .error-icon {
            font-size: 6rem;
            color: #ff6b6b;
            margin-bottom: 1rem;
            animation: shake 0.8s infinite;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 700;
            color: #ff6b6b;
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(255, 107, 107, 0.1);
        }
        
        .error-title {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .btn-home {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
            color: white;
            text-decoration: none;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(108, 117, 125, 0.3);
            color: white;
            text-decoration: none;
        }
        
        .status-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .status-info h6 {
            color: #495057;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .status-info p {
            color: #6c757d;
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        
        .support-info {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }
        
        .support-info small {
            color: #999;
        }
        
        @media (max-width: 768px) {
            .error-container {
                padding: 2rem 1.5rem;
                margin: 10px;
            }
            
            .error-code {
                font-size: 5rem;
            }
            
            .error-title {
                font-size: 1.5rem;
            }
            
            .error-message {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-server"></i>
        </div>
        
        <div class="error-code">500</div>
        
        <h1 class="error-title">Server Error</h1>
        
        <p class="error-message">
            We're experiencing some technical difficulties right now.
            <br>Our team has been automatically notified and is working to fix the issue.
        </p>
        
        <div class="error-actions">
            <a href="/Greenzio/" class="btn-home">
                <i class="fas fa-home"></i> Back to Home
            </a>
            <a href="javascript:location.reload()" class="btn-secondary">
                <i class="fas fa-redo"></i> Try Again
            </a>
        </div>
        
        <div class="status-info">
            <h6><i class="fas fa-tools"></i> What happened?</h6>
            <p>This is usually a temporary issue. The server encountered an unexpected condition that prevented it from fulfilling your request.</p>
        </div>
        
        <div class="support-info">
            <small>
                <strong>Need immediate help?</strong> Contact our support team at 
                <a href="mailto:support@greenzio.com">support@greenzio.com</a> 
                or call <a href="tel:+919012345678">+91-90123-45678</a>
            </small>
        </div>
    </div>
    
    <script>
        // Auto-retry after 30 seconds
        setTimeout(function() {
            if (confirm('The page will automatically retry loading. Click OK to retry now or Cancel to stay.')) {
                location.reload();
            }
        }, 30000);
    </script>
</body>
</html>
