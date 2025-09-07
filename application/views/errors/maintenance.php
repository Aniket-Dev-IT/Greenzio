<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Greenzio</title>
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/favicon.png'); ?>">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .maintenance-container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15);
            text-align: center;
            max-width: 600px;
            width: 90%;
            position: relative;
            overflow: hidden;
        }
        
        .maintenance-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #28a745, #20c997, #17a2b8, #6f42c1);
            animation: shimmer 2s ease-in-out infinite;
        }
        
        .maintenance-icon {
            font-size: 6rem;
            background: linear-gradient(135deg, #28a745, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 2rem;
            animation: pulse 2s ease-in-out infinite;
        }
        
        .maintenance-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .maintenance-message {
            font-size: 1.2rem;
            color: #6c757d;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .progress-container {
            background: #f1f3f4;
            border-radius: 10px;
            padding: 1rem;
            margin: 2rem 0;
        }
        
        .progress {
            height: 8px;
            border-radius: 4px;
            background: #e9ecef;
            overflow: hidden;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, #28a745, #20c997);
            animation: loading 3s ease-in-out infinite;
        }
        
        .eta-text {
            color: #495057;
            font-weight: 500;
            margin-top: 1rem;
        }
        
        .contact-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
            border-left: 4px solid #28a745;
        }
        
        .contact-info h5 {
            color: #28a745;
            margin-bottom: 1rem;
        }
        
        .contact-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }
        
        .contact-link {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .contact-link:hover {
            background: #218838;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        
        .social-links {
            margin-top: 2rem;
        }
        
        .social-link {
            display: inline-block;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #f8f9fa;
            color: #6c757d;
            text-decoration: none;
            line-height: 50px;
            text-align: center;
            margin: 0 5px;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }
        
        .social-link:hover {
            background: #28a745;
            color: white;
            text-decoration: none;
            transform: translateY(-3px);
        }
        
        .newsletter-signup {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .newsletter-form {
            display: flex;
            gap: 10px;
            margin-top: 1rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .newsletter-form input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #dee2e6;
            border-radius: 25px;
            font-size: 1rem;
        }
        
        .newsletter-form input:focus {
            border-color: #28a745;
            outline: none;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }
        
        .newsletter-form button {
            padding: 12px 20px;
            background: #28a745;
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .newsletter-form button:hover {
            background: #218838;
            transform: translateY(-1px);
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: 200px 0; }
        }
        
        @keyframes loading {
            0% { width: 0; }
            50% { width: 70%; }
            100% { width: 100%; }
        }
        
        @media (max-width: 768px) {
            .maintenance-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .maintenance-title {
                font-size: 2rem;
            }
            
            .maintenance-message {
                font-size: 1.1rem;
            }
            
            .contact-links {
                flex-direction: column;
                align-items: center;
            }
            
            .newsletter-form {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            <i class="fas fa-tools"></i>
        </div>
        
        <h1 class="maintenance-title"><?php echo $heading; ?></h1>
        <p class="maintenance-message"><?php echo $message; ?></p>
        
        <div class="progress-container">
            <h6 class="mb-3">
                <i class="fas fa-cog fa-spin text-success me-2"></i>
                Maintenance in Progress
            </h6>
            <div class="progress">
                <div class="progress-bar" role="progressbar"></div>
            </div>
            <div class="eta-text">
                <i class="fas fa-clock me-1"></i>
                Estimated completion: 2-4 hours
            </div>
        </div>
        
        <div class="contact-info">
            <h5>
                <i class="fas fa-headset me-2"></i>
                Need Immediate Assistance?
            </h5>
            <p class="text-muted">Our customer service team is still available to help you:</p>
            
            <div class="contact-links">
                <a href="tel:+919012345678" class="contact-link">
                    <i class="fas fa-phone"></i>
                    Call Us
                </a>
                <a href="mailto:support@greenzio.com" class="contact-link">
                    <i class="fas fa-envelope"></i>
                    Email Us
                </a>
                <a href="https://wa.me/919012345678" class="contact-link" target="_blank">
                    <i class="fab fa-whatsapp"></i>
                    WhatsApp
                </a>
            </div>
        </div>
        
        <div class="newsletter-signup">
            <h5 class="mb-3">
                <i class="fas fa-bell text-warning me-2"></i>
                Get Notified When We're Back
            </h5>
            <p class="text-muted">Subscribe to receive updates and exclusive offers:</p>
            
            <form class="newsletter-form" onsubmit="handleNewsletter(event)">
                <input type="email" placeholder="Your email address" required>
                <button type="submit">
                    <i class="fas fa-paper-plane me-1"></i>
                    Notify Me
                </button>
            </form>
        </div>
        
        <div class="social-links">
            <h6 class="mb-3">Stay Connected</h6>
            <a href="#" class="social-link" title="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-link" title="Twitter">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="social-link" title="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="social-link" title="LinkedIn">
                <i class="fab fa-linkedin-in"></i>
            </a>
        </div>
        
        <div class="mt-4">
            <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i>
                Your data is safe. We'll be back online shortly.
            </small>
        </div>
    </div>
    
    <script>
        // Auto-refresh page every 5 minutes
        setTimeout(function() {
            window.location.reload();
        }, 300000); // 5 minutes
        
        function handleNewsletter(event) {
            event.preventDefault();
            const email = event.target.querySelector('input[type="email"]').value;
            
            // Simulate newsletter signup
            alert('Thank you! We\'ll notify you at ' + email + ' when Greenzio is back online.');
            event.target.reset();
        }
        
        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Update progress bar periodically
            setInterval(function() {
                const progressBar = document.querySelector('.progress-bar');
                const randomWidth = Math.floor(Math.random() * 40) + 60; // 60-100%
                progressBar.style.width = randomWidth + '%';
            }, 2000);
            
            // Show current time
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString();
                const etaElement = document.querySelector('.eta-text');
                etaElement.innerHTML = '<i class="fas fa-clock me-1"></i>Current time: ' + timeString + ' | Estimated completion: 2-4 hours';
            }
            
            updateTime();
            setInterval(updateTime, 1000);
        });
    </script>
</body>
</html>
