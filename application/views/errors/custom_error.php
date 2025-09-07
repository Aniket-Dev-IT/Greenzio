<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
    .error-page {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .error-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.1;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cg fill-opacity='0.03'%3E%3Cpolygon fill='%23000' points='50 0 60 40 100 50 60 60 50 100 40 60 0 50 40 40'/%3E%3C/g%3E%3C/svg%3E");
    }
    
    .error-container {
        background: white;
        border-radius: 15px;
        padding: 3rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        text-align: center;
        max-width: 600px;
        width: 90%;
        position: relative;
        z-index: 2;
    }
    
    .error-icon {
        font-size: 5rem;
        color: #28a745;
        margin-bottom: 1.5rem;
        animation: float 3s ease-in-out infinite;
    }
    
    .error-code {
        font-size: 4rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #28a745, #20c997);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .error-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 1rem;
    }
    
    .error-message {
        font-size: 1.1rem;
        color: #6c757d;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    .error-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }
    
    .btn-home {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        padding: 12px 30px;
        color: white;
        font-weight: 500;
        border-radius: 25px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-home:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-back {
        background: transparent;
        border: 2px solid #28a745;
        padding: 10px 25px;
        color: #28a745;
        font-weight: 500;
        border-radius: 25px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-back:hover {
        background: #28a745;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }
    
    .search-suggestion {
        margin-top: 2rem;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid #28a745;
    }
    
    .search-form {
        display: flex;
        gap: 10px;
        margin-top: 1rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .search-form input {
        flex: 1;
        min-width: 250px;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 25px;
        font-size: 1rem;
    }
    
    .search-form input:focus {
        border-color: #28a745;
        outline: none;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
    }
    
    .search-form button {
        padding: 12px 20px;
        background: #28a745;
        border: none;
        border-radius: 25px;
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .search-form button:hover {
        background: #218838;
        transform: translateY(-1px);
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    @media (max-width: 768px) {
        .error-container {
            padding: 2rem 1.5rem;
            margin: 1rem;
        }
        
        .error-code {
            font-size: 3rem;
        }
        
        .error-title {
            font-size: 1.5rem;
        }
        
        .error-actions {
            flex-direction: column;
        }
        
        .search-form {
            flex-direction: column;
        }
        
        .search-form input {
            min-width: 100%;
        }
    }
</style>

<main class="error-page">
    <div class="error-background"></div>
    
    <div class="error-container">
        <?php 
        // Determine icon based on page title
        $icon = 'fas fa-exclamation-triangle';
        $code = '404';
        
        if (strpos($page_title, '404') !== false) {
            $icon = 'fas fa-search';
            $code = '404';
        } elseif (strpos($page_title, '401') !== false) {
            $icon = 'fas fa-lock';
            $code = '401';
        } elseif (strpos($page_title, '403') !== false) {
            $icon = 'fas fa-ban';
            $code = '403';
        } elseif (strpos($page_title, '500') !== false || strpos($page_title, 'Database') !== false) {
            $icon = 'fas fa-server';
            $code = '500';
        }
        ?>
        
        <div class="error-icon">
            <i class="<?php echo $icon; ?>"></i>
        </div>
        
        <div class="error-code"><?php echo $code; ?></div>
        <h1 class="error-title"><?php echo $heading; ?></h1>
        <p class="error-message"><?php echo $message; ?></p>
        
        <div class="error-actions">
            <div class="d-flex flex-column flex-sm-row gap-3">
                <a href="<?php echo base_url(); ?>" class="btn-home">
                    <i class="fas fa-home"></i>
                    Go to Homepage
                </a>
                
                <a href="javascript:history.back()" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Go Back
                </a>
            </div>
        </div>
        
        <?php if ($code === '404'): ?>
        <div class="search-suggestion">
            <h5 class="mb-3">
                <i class="fas fa-lightbulb text-warning me-2"></i>
                Looking for something specific?
            </h5>
            <p class="mb-3 text-muted">Try searching for products or browse our categories:</p>
            
            <form class="search-form" method="get" action="<?php echo base_url('product/search'); ?>">
                <input type="search" name="q" placeholder="Search for products..." class="form-control">
                <button type="submit">
                    <i class="fas fa-search me-1"></i>
                    Search
                </button>
            </form>
            
            <div class="mt-3">
                <h6 class="mb-2">Popular Categories:</h6>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="<?php echo base_url('category/fruits-vegetables'); ?>" class="badge badge-success badge-pill px-3 py-2">Fresh Produce</a>
                    <a href="<?php echo base_url('category/dairy-bakery'); ?>" class="badge badge-info badge-pill px-3 py-2">Dairy & Bakery</a>
                    <a href="<?php echo base_url('category/beverages'); ?>" class="badge badge-primary badge-pill px-3 py-2">Beverages</a>
                    <a href="<?php echo base_url('category/personal-care'); ?>" class="badge badge-warning badge-pill px-3 py-2">Personal Care</a>
                </div>
            </div>
        </div>
        <?php elseif ($code === '401'): ?>
        <div class="search-suggestion">
            <h5 class="mb-3">
                <i class="fas fa-user-circle text-primary me-2"></i>
                Need to sign in?
            </h5>
            <p class="mb-3 text-muted">Access your account to continue shopping:</p>
            
            <div class="d-flex gap-2 justify-content-center">
                <a href="#" data-toggle="modal" data-target="#logModal" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-1"></i>
                    Login
                </a>
                <a href="#" data-toggle="modal" data-target="#logModal" class="btn btn-outline-primary" onclick="$('#registerButton').click();">
                    <i class="fas fa-user-plus me-1"></i>
                    Register
                </a>
            </div>
        </div>
        <?php elseif ($code === '500'): ?>
        <div class="search-suggestion">
            <h5 class="mb-3">
                <i class="fas fa-tools text-danger me-2"></i>
                We're fixing this!
            </h5>
            <p class="mb-3 text-muted">Our team has been notified. In the meantime, try:</p>
            
            <div class="d-flex flex-column gap-2">
                <button onclick="window.location.reload()" class="btn btn-outline-success">
                    <i class="fas fa-sync-alt me-1"></i>
                    Refresh Page
                </button>
                <small class="text-muted">If the problem persists, please contact support at <strong>+91-90123-45678</strong></small>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<script>
// Enhanced error reporting for development
if (window.location.hostname === 'localhost') {
    console.log('Error Page Details:', {
        code: '<?php echo $code; ?>',
        title: '<?php echo $page_title; ?>',
        heading: '<?php echo $heading; ?>',
        message: '<?php echo $message; ?>',
        referrer: document.referrer,
        userAgent: navigator.userAgent
    });
}

// Auto-report error to analytics (if implemented)
if (typeof gtag !== 'undefined') {
    gtag('event', 'page_view', {
        page_title: 'Error <?php echo $code; ?>',
        page_location: window.location.href
    });
}
</script>
