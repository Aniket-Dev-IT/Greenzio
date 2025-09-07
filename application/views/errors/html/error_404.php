<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - Greenzio</title>
    <meta name="description" content="The page you're looking for couldn't be found on Greenzio. Let's get you back to shopping for fresh groceries!">
    
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(45deg, #f8f9fa 25%, transparent 25%, transparent 75%, #f8f9fa 75%, #f8f9fa),
                        linear-gradient(45deg, #f8f9fa 25%, transparent 25%, transparent 75%, #f8f9fa 75%, #f8f9fa);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.05;
            z-index: -1;
        }
        
        .error-icon {
            font-size: 6rem;
            color: #28a745;
            margin-bottom: 1rem;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-30px);
            }
            60% {
                transform: translateY(-15px);
            }
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 700;
            color: #28a745;
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(40, 167, 69, 0.1);
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
        
        .popular-links {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e9ecef;
        }
        
        .popular-links h6 {
            color: #666;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .quick-links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
        }
        
        .quick-link {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            background: #f8f9fa;
            color: #495057;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .quick-link:hover {
            background: #28a745;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }
        
        .quick-link i {
            margin-right: 8px;
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
            
            .quick-links {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            🥬
        </div>
        
        <div class="error-code">404</div>
        
        <h1 class="error-title">Oops! Page Not Found</h1>
        
        <p class="error-message">
            We couldn't find the page you're looking for. It might have been moved, deleted, or the URL might be incorrect.
            <br>But don't worry – let's get you back to shopping for fresh groceries!
        </p>
        
        <div class="error-actions">
            <a href="/Greenzio/" class="btn-home">
                <i class="fas fa-home"></i> Back to Home
            </a>
            <a href="javascript:history.back()" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Go Back
            </a>
        </div>
        
        <div class="popular-links">
            <h6>Popular Pages</h6>
            <div class="quick-links">
                <a href="/Greenzio/category/fruits-vegetables" class="quick-link">
                    <i class="fas fa-apple-alt"></i> Fresh Produce
                </a>
                <a href="/Greenzio/category/dairy-bakery" class="quick-link">
                    <i class="fas fa-cheese"></i> Dairy & Bakery
                </a>
                <a href="/Greenzio/category/beverages" class="quick-link">
                    <i class="fas fa-coffee"></i> Beverages
                </a>
                <a href="/Greenzio/shopping/cart" class="quick-link">
                    <i class="fas fa-shopping-cart"></i> My Cart
                </a>
                <a href="/Greenzio/shop/contact" class="quick-link">
                    <i class="fas fa-phone"></i> Contact Us
                </a>
            </div>
        </div>
    </div>
    
    <!-- Optional: Auto-redirect after 10 seconds -->
    <script>
        // Uncomment below to auto-redirect to home after 10 seconds
        /*
        setTimeout(function() {
            if (confirm('This page will redirect to home in 5 seconds. Click OK to redirect now or Cancel to stay.')) {
                window.location.href = '/Greenzio/';
            }
        }, 10000);
        */
    </script>
</body>
</html>
