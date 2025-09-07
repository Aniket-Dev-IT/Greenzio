<?php
defined('BASEPATH') or exit('No direct script access allowed');

$data = $this->session->userdata();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Greenzio - Your Green Shopping Destination</title>
  <meta name="description" content="Greenzio - Shop eco-friendly and sustainable products. Your one-stop destination for green shopping with free shipping and eco-friendly packaging.">
  <meta name="keywords" content="green shopping, eco-friendly, sustainable products, Greenzio, environment friendly shopping">
  <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/favicon.png'); ?>">

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/bootstrap.css'; ?>">

  <!-- Price RangeBar CSS -->
  <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/nouislider.css'; ?>">

  <!-- Custom Fonts for this CSS -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Libre+Baskerville" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Work+Sans" rel="stylesheet">

  <!-- owl carousel css -->
  <link rel='stylesheet' href="<?php echo base_url() . 'assets/css/owl.carousel.min.css'; ?>">
  <link rel='stylesheet' href="<?php echo base_url() . 'assets/css/owl.theme.default.min.css'; ?>">

  <!-- Custom Stylesheet For this template -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/creative.css'; ?>">
  
  <!-- Search Enhancement Styles -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/search-enhancements.css'; ?>">
  
  <!-- Product Detail Enhancements Styles -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/product-detail-enhancements.css'; ?>">
  
  <!-- Mobile Enhancement Styles -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/mobile-enhancements.css'; ?>">
  
  <!-- Responsive Fix - Solves oversized content issue -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/responsive-fix.css'; ?>">
  
  <!-- Frontend Error Handler -->
  <script src="<?php echo base_url() . 'assets/js/error-handler.js'; ?>"></script>

  <!-- Bootstrap core javascript -->
  <script src="<?php echo base_url() . 'assets/js/jquery.js'; ?>"></script>
  <script src="<?php echo base_url() . 'assets/js/popper.js'; ?>"></script>
  <script src="<?php echo base_url() . 'assets/js/bootstrap.js'; ?>"></script>
  
  <!-- Initialize Bootstrap dropdowns -->
  <script>
  $(document).ready(function() {
    // Initialize all Bootstrap dropdowns
    $('.dropdown-toggle').dropdown();
  });
  </script>
  
  <!-- Mobile Navigation JavaScript -->
  <script src="<?php echo base_url() . 'assets/js/mobile-nav.js'; ?>"></script>
  
  <!-- Lazy Loading JavaScript -->
  <script src="<?php echo base_url() . 'assets/js/lazy-loading.js'; ?>"></script>
  
  <!-- Viewport Optimizer - Fixes oversized content for 100% zoom -->
  <script src="<?php echo base_url() . 'assets/js/viewport-optimizer.js'; ?>"></script>

  <style>
    /* Simple navigation styles */
    .navbar-nav .nav-link {
      font-weight: 500;
      padding: 0.5rem 0.9rem;
      font-size: 0.95rem;
    }
    
    .navbar-nav .nav-link:hover {
      color: #28a745 !important;
    }
    
    .top-bar {
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
    }
    
    .navbar-brand {
      font-size: 1.3rem !important; /* Reduced for proper viewing at 100% zoom */
      font-weight: 700;
      color: #28a745 !important;
      text-transform: uppercase;
      letter-spacing: 0.5px; /* Reduced letter spacing */
    }
    
    /* Force navbar visibility on desktop */
    @media (min-width: 992px) {
      .navbar-collapse {
        display: flex !important;
        visibility: visible !important;
      }
      
      .navbar-nav {
        display: flex !important;
        visibility: visible !important;
      }
    }
    
    .search-wrapper {
      min-width: 200px !important; /* Optimized for 100% zoom */
      max-width: 240px !important; /* Reduced for proper viewing */
    }
    
    .search-form .input-group {
      border-radius: 20px; /* Reduced from 25px */
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1); /* Reduced shadow */
    }
    
    .search-category-select {
      border-right: none;
      background: #f8f9fa;
      font-size: 0.8rem; /* Reduced from 0.9rem */
      max-width: 120px; /* Reduced from 140px */
      padding: 8px 5px; /* Reduced padding */
    }
    
    .search-input {
      border-left: none;
      border-right: none;
      font-size: 0.9rem; /* Reduced from 1rem */
      padding: 8px 10px; /* Reduced padding */
    }
    
    .search-btn {
      background: linear-gradient(135deg, #28a745, #20c997);
      border: none;
      padding: 8px 15px; /* Reduced padding */
      color: white;
      transition: all 0.3s ease;
    }
    
    .search-btn:hover {
      background: linear-gradient(135deg, #218838, #1ea080);
      color: white;
    }
    
    
    /* Better responsive behavior */
    @media (max-width: 1200px) {
      .search-wrapper {
        min-width: 250px;
        max-width: 280px;
      }
      
      .search-category-select {
        max-width: 100px;
        font-size: 0.75rem;
      }
      
      .navbar-brand {
        font-size: 1.4rem;
      }
    }
    
    @media (max-width: 991px) {
      .search-wrapper {
        min-width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
      }
      
      .navbar-nav .nav-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
      }
      
      .navbar-brand {
        font-size: 1.3rem;
      }
    }
    
    /* Ultra-wide screens optimization */
    @media (min-width: 1400px) {
      .search-wrapper {
        min-width: 350px;
        max-width: 400px;
      }
    }
    
    /* Fix user dropdown positioning - FORCE IT TO BREAK OUT OF CONTAINER */
    .navbar .nav-item.dropdown {
      position: static !important;
    }
    
    .navbar .dropdown-menu {
      position: fixed !important;
      top: 70px !important;
      right: 20px !important;
      left: auto !important;
      z-index: 99999 !important;
      min-width: 200px !important;
      margin: 0 !important;
      padding: 0.75rem 0 !important;
      background-color: #ffffff !important;
      border: 1px solid #dee2e6 !important;
      border-radius: 0.5rem !important;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.25) !important;
      transform: none !important;
      will-change: auto !important;
    }
    
    .dropdown-item {
      display: block !important;
      width: 100% !important;
      padding: 0.5rem 1rem !important;
      clear: both !important;
      font-weight: 400 !important;
      color: #212529 !important;
      text-decoration: none !important;
      white-space: nowrap !important;
      background-color: transparent !important;
      border: 0 !important;
    }
    
    .dropdown-item:hover {
      background-color: #f8f9fa !important;
      color: #16181b !important;
    }
    
    .dropdown-divider {
      height: 0 !important;
      margin: 0.5rem 0 !important;
      overflow: hidden !important;
      border-top: 1px solid #e9ecef !important;
    }
    
    /* Ensure navbar doesn't clip dropdown */
    .navbar {
      overflow: visible !important;
    }
    
    .navbar .container-fluid {
      overflow: visible !important;
    }
    
    .navbar-collapse {
      overflow: visible !important;
    }
    
    /* Simple dropdown show/hide */
    .dropdown-menu.show {
      display: block !important;
      opacity: 1 !important;
      visibility: visible !important;
    }
    
    /* Notification animations */
    @keyframes slideInRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
    
    /* Mobile notification adjustments */
    @media (max-width: 768px) {
      #notification-container {
        top: 80px !important;
        right: 10px !important;
        left: 10px !important;
        width: auto !important;
      }
    }
    
  </style>

</head>

<body>

  <header class="header-absolute">

    <!-- Top NavBar -->

    <div class="top-bar" style="height:28px; font-size: 12px;"> <!-- Optimized for 100% zoom viewing -->
      <div class="container-fluid">
        <div class="row d-flex align-items-center">
          <div class="col-lg-12">
            <ul class="list-inline mb-0">
              <li class="list-inline-item mr-2"><i class="fas fa-phone" style="font-size: 0.8rem;"></i> +91-90123-45678</li>
              <li class="list-inline-item border-left px-2 d-none d-md-inline"> <!-- Reduced padding and changed breakpoint -->
                <span class="d-none d-lg-inline">Eco-friendly packaging & </span>Free Shipping on orders over <i class="fa fa-rupee-sign"></i>&nbsp;500
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Nav bar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-airy fixed-top py-lg-2 px-lg-3 text-uppercase mb-5" id="mainNav" data-toggle="affix">
      <div class="container-fluid" id="main-navbar">
        <a class="navbar-brand" href="<?php echo base_url(); ?>">Greenzio</a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse show" id="navbarResponsive">
          <ul class="navbar-nav mx-auto">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url() . 'category/fruits-vegetables'; ?>">
                Fresh
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url() . 'category/dairy-bakery'; ?>">
                Dairy
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url() . 'category/grains-rice'; ?>">
                Pantry
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url() . 'category/beverages'; ?>">
                Beverages
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url() . 'category/personal-care'; ?>">
                Personal
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url() . 'category/household-items'; ?>">
                Household
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url() . 'shop/contact'; ?>">
                Contact
              </a>
            </li>
          </ul>
          <div class="d-flex align-items-center justify-content-between justify-content-lg-end mt-1 mb-2 my-lg-0">
            <!-- Enhanced Search Bar -->
            <div class="nav-item search-container">
              <div class="search-wrapper">
                <form id="globalSearchForm" method="get" action="<?php echo base_url('product/search'); ?>" class="search-form">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <select name="category" id="searchCategory" class="form-control search-category-select">
                        <option value="">All Categories</option>
                        <option value="Fruits & Vegetables">Fruits & Vegetables</option>
                        <option value="Dairy & Bakery">Dairy & Bakery</option>
                        <option value="Grains & Rice">Grains & Rice</option>
                        <option value="Spices & Seasonings">Spices & Seasonings</option>
                        <option value="Snacks & Instant Food">Snacks & Instant Food</option>
                        <option value="Beverages">Beverages</option>
                        <option value="Personal Care">Personal Care</option>
                        <option value="Household Items">Household Items</option>
                        <option value="Cooking Oils">Cooking Oils</option>
                      </select>
                    </div>
                    <input type="search" name="q" id="searchInput" class="form-control search-input" placeholder="Search products..." aria-label="Search" autocomplete="off">
                    <div class="input-group-append">
                      <button type="submit" class="btn search-btn">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                  <!-- Search Suggestions Dropdown -->
                  <div id="searchSuggestions" class="search-suggestions"></div>
                </form>
              </div>
            </div>

            <?php if (isset($data['userID'])) { ?>
              <div class="nav-item dropdown">
                <a id="userdetails" href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                  <i class="fas fa-user"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userdetails"> 
                  <a href="<?php echo base_url('user/profile'); ?>" class="dropdown-item">My Profile</a>
                  <a href="<?php echo base_url('order/orderList'); ?>" class="dropdown-item">Orders</a>
                  <a href="<?php echo base_url('user/addresses'); ?>" class="dropdown-item">Addresses</a>
                  <div class="dropdown-divider my-0"></div>
                  <a href="<?php echo base_url('user/logout'); ?>" class="dropdown-item">Logout</a>
                </div>
              </div>
            <?php
            } else {
              echo '
                <div data-toggle="modal" data-target="#logModal" class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-user"></i></a>
              </div>
  ';
            }

            // <a href="#" class="dropdown-item">Addresses</a>
            //    <a href="#" class="dropdown-item">Profile</a>

            ?>





            <div class="nav-item">
              <a class="nav-link position-relative" href="<?php echo base_url('shopping/cart'); ?>"> 
                <i class="fas fa-shopping-cart"></i>
                <span id="cart-count-badge" class="badge badge-primary badge-pill position-absolute" style="top: -2px; right: -2px; min-width: 18px; height: 18px; font-size: 0.7rem; display: none;">0</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <!-- Notification Container -->
  <div id="notification-container" style="position: fixed; top: 100px; right: 20px; z-index: 10000; width: 300px;"></div>

  <div class="modal fade" id="logModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <!--Content-->
      <div class="modal-content" id="login">
        <!--Header-->
        <div class="modal-header text-center border-0">
          <h3 class="modal-title w-100 font-weight-bold my-3" id="myModalLabel"><strong>Login</strong></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <!--Body-->

        <form action="#" method="post" id="loginForm">
          <div class="modal-body mx-4">

            <div class='text-danger text-center mb-4' id="loginError"></div>
            <!--Body-->

            <div class="mb-3">
              <label data-error="wrong" for="Form-email1">Email&nbsp;/&nbsp;Mobile No.</label>
              <input type="text" id="loginInput" name="loginInput" class="form-control" required>
            </div>

            <div class="pb-3">
              <label data-error="wrong" for="Form-pass1">Password</label>
              <input type="password" id="loginPassword" class="form-control" name="loginPassword" required>
              <span toggle="#loginPassword" class="fas fa-eye field-icon togglePassword"></span>
            </div>
            <div class="font-small blue-text d-flex justify-content-between flex-row flex-wrap">
              <label for="checkbox" class="form-check-label">
                <input type="checkbox" name="loginCheckbox" id="loginCheckbox" value="checked">
                Remember me?
              </label>

              <p>Forgot <a href="#" class="blue-text">
                  Password?</a></p>
            </div>
                        <div class="text-center mb-3">
                            <button type="submit" class="btn btn-primary btn-block" id="submitLogin">Login</button>
                        </div>
            <p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"> or Login
              with:</p>

            <div class="row my-3 d-flex justify-content-center">
              <!--Facebook-->
              <button type="button" class="btn btn-white mr-3 z-depth-1a"><i class="fab fa-facebook-f text-center"></i></button>
              <!--Google +-->
              <button type="button" class="btn btn-white btn-rounded z-depth-1a"><i class="fab fa-google-plus-g"></i></button>
            </div>
          </div>
        </form>
        <!--Footer-->
        <div class="modal-footer pt-3 mb-1">
          <p class="font-small grey-text d-flex justify-content-end">Not a member? <a href="#" id="registerButton" class="blue-text ml-1">
              Register</a></p>
        </div>
      </div>
      <!--/.Content-->

      <!-- Register -->
      <div class="modal-content d-none" id="register">
        <!--Header-->
        <div class="modal-header text-center border-0">
          <h3 class="modal-title w-100 font-weight-bold my-3" id="myModalLabel"><strong>Register</strong></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <!--Body-->
        <form method="post" action="#" id="registerForm">
          <div class="modal-body mx-4">

            <div class='text-danger text-center mb-4' id="registerError">

            </div>
            <!--Body-->
            <div class="mb-3">

              <label data-error="wrong" data-success="right" for="Form-email1">Email&nbsp;/&nbsp;Mobile No.</label>
              <input type="text" id="regInput" name="regInput" class="form-control" required minlength="10">
              <!-- <ul class="input-requirement">
                <li>Please Enter A Valid Email Address</li>
              </ul> -->
            </div>

            <div class="pb-3">
              <label data-error="wrong" data-success="right" for="Form-pass1">Password</label>
              <input type="password" id="regPass" name="regPass" class="form-control" minlength="8" maxlength="50" required>
              <ul class="input-requirement">
                <li>Atleast 8 Characters Long (and less then 50 characters)</li>
                <li>Contains atleast 1 number</li>
                <li>Contains atleast 1 lowercase letter</li>
                <li>Contains atleast 1 uppercase letter</li>
                <li>Contains a special character (e.g. @!)</li>
              </ul>
            </div>

            <div class="pb-3">
              <label data-error="wrong" data-success="right" for="Form-pass2"> Confirm Password</label>
              <input type="password" id="regPass2" name="regPass2" class="form-control" minlength="8" maxlength="50" required>
            </div>
            <div class="form-check form-check-inline"><label class="form-check-label">Gender :</label></div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" id="male" value="m">
              <label class="form-check-label" for="male">Male</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" id="female" value="f">
              <label class="form-check-label" for="female">Female</label>
            </div>

            <div class="text-center my-3">
                            <button type="submit" class="btn btn-primary btn-block" id="submitRegister">Register</button>
            </div>
            <p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"> or Register
              with:</p>

            <div class="row my-3 d-flex justify-content-center">
              <!--Facebook-->
              <button type="button" class="btn btn-white mr-3 z-depth-1a"><i class="fab fa-facebook-f text-center"></i></button>
              <!--Google +-->
              <button type="button" class="btn btn-white btn-rounded z-depth-1a"><i class="fab fa-google-plus-g"></i></button>
            </div>
          </div>

        </form>
        <!--Footer-->
        <div class="modal-footer pt-3 mb-1">
          <p class="font-small grey-text d-flex justify-content-end">Already a member? <a href="#" class="blue-text ml-1" id="loginButton">
              Login</a></p>
        </div>
      </div>



    </div>
  </div>
  <!-- Modal -->


  <script>
    // Search functionality
    $(document).ready(function() {
        // Search suggestions
        $('#searchInput').on('keyup', function() {
            var query = $(this).val();
            
            if (query.length >= 2) {
                $.ajax({
                    url: '<?php echo base_url(); ?>product/searchSuggestions',
                    type: 'GET',
                    data: { q: query },
                    dataType: 'json',
                    success: function(data) {
                        var suggestions = '';
                        
                        if (data.length > 0) {
                            data.forEach(function(item) {
                                var icon = '';
                                if (item.type === 'product') {
                                    icon = '<i class="fas fa-cube mr-2"></i>';
                                } else if (item.type === 'brand') {
                                    icon = '<i class="fas fa-tag mr-2"></i>';
                                } else if (item.type === 'category') {
                                    icon = '<i class="fas fa-list mr-2"></i>';
                                }
                                
                                suggestions += '<div class="suggestion-item" data-value="' + item.suggestion + '" data-type="' + item.type + '">' + 
                                             icon + item.suggestion + 
                                             '</div>';
                            });
                            
                            $('#searchSuggestions').html(suggestions).show();
                        } else {
                            $('#searchSuggestions').hide();
                        }
                    },
                    error: function() {
                        $('#searchSuggestions').hide();
                    }
                });
            } else {
                $('#searchSuggestions').hide();
            }
        });
        
        // Handle suggestion clicks
        $(document).on('click', '.suggestion-item', function() {
            var value = $(this).data('value');
            var type = $(this).data('type');
            
            $('#searchInput').val(value);
            
            if (type === 'category') {
                $('#searchCategory').val(value);
            } else if (type === 'brand') {
                // You might want to add a brand filter here
            }
            
            $('#searchSuggestions').hide();
        });
        
        // Hide suggestions when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-wrapper').length) {
                $('#searchSuggestions').hide();
            }
        });
        
        // Handle Enter key in search
        $('#searchInput').on('keypress', function(e) {
            if (e.which === 13) {
                $('#globalSearchForm').submit();
            }
        });
        
        // Quick search on category change
        $('#searchCategory').on('change', function() {
            if ($('#searchInput').val().length > 0) {
                // Auto-submit form when category changes and there's a search term
                setTimeout(function() {
                    $('#globalSearchForm').submit();
                }, 300);
            }
        });
    });
    
    // Cart count functionality
    function updateCartCount() {
        fetch('<?php echo base_url(); ?>shopping/getCartItemCount')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('cart-count-badge');
                if (data.count && data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching cart count:', error);
            });
    }
    
    // Update cart count on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
    });
    
    // Update cart count every 30 seconds (in case of multiple tabs)
    setInterval(updateCartCount, 30000);
    
    // Function to manually trigger cart count update (can be called after adding items)
    window.refreshCartCount = updateCartCount;
    
    // Notification System
    function showNotification(message, type = 'success') {
        const container = document.getElementById('notification-container');
        if (!container) return;
        
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        notification.style.cssText = 'margin-bottom: 10px; animation: slideInRight 0.3s ease-out;';
        notification.innerHTML = `
            <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;
        
        container.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }
        }, 5000);
    }
    
    // Global function for adding to cart
    window.addProductToCart = function(productId, quantity = 1) {
        fetch('<?php echo base_url(); ?>shopping/addToCart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                updateCartCount();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Add to cart error:', error);
            showNotification('An error occurred while adding to cart. Please try again.', 'error');
        });
    };
    
    // Expose notification function globally
    window.showNotification = showNotification;
    
    // Fix dropdown positioning dynamically
    function positionUserDropdown() {
        const userIcon = document.querySelector('#userdetails');
        const dropdown = document.querySelector('.nav-item.dropdown .dropdown-menu');
        
        if (userIcon && dropdown) {
            const iconRect = userIcon.getBoundingClientRect();
            
            dropdown.style.position = 'fixed';
            dropdown.style.top = (iconRect.bottom + 8) + 'px';
            dropdown.style.right = (window.innerWidth - iconRect.right) + 'px';
            dropdown.style.left = 'auto';
            dropdown.style.zIndex = '99999';
        }
    }
    
    // Position dropdown when it's shown - FIXED VERSION
    $(document).ready(function() {
        // Initialize Bootstrap dropdowns first
        $('.dropdown-toggle').dropdown();
        
        // Handle dropdown show event
        $('.dropdown').on('show.bs.dropdown', function() {
            setTimeout(positionUserDropdown, 10);
        });
        
        // Reposition on window resize
        $(window).on('resize', function() {
            if ($('.dropdown-menu').hasClass('show')) {
                positionUserDropdown();
            }
        });
        
        // Reposition on scroll
        $(window).on('scroll', function() {
            if ($('.dropdown-menu').hasClass('show')) {
                positionUserDropdown();
            }
        });
    });
    
    // Simple form submission handling
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            console.log('Login form submitted');
            e.preventDefault();
            
            var formData = $(this).serialize();
            console.log('Form data:', formData);
            $('#loginError').html('Logging in...').css('color', 'blue');
            
            $.post('<?php echo base_url(); ?>user/login', formData)
                .done(function(response) {
                    console.log('Raw response received:', response);
                    try {
                        var data = JSON.parse(response);
                        console.log('Parsed response data:', data);
                        
                        if (data.error) {
                            console.log('Login error:', data.error);
                            $('#loginError').html(data.error).css('color', 'red');
                        } else {
                            console.log('Login successful, redirecting to:', data.url);
                            $('#loginError').html('Login successful! Redirecting...').css('color', 'green');
                            
                            // Show debug info
                            if (data.debug_session_id) {
                                console.log('Session ID:', data.debug_session_id);
                            }
                            if (data.debug_user_id) {
                                console.log('User ID from session:', data.debug_user_id);
                            }
                            
                            setTimeout(function() {
                                console.log('Redirecting now...');
                                window.location.href = data.url || '<?php echo base_url(); ?>';
                            }, 2000); // Increased delay to 2 seconds for debugging
                        }
                    } catch (e) {
                        console.error('JSON parsing error:', e, 'Raw response:', response);
                        $('#loginError').html('Login failed. Invalid response: ' + e.message).css('color', 'red');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('AJAX error:', status, error, xhr);
                    console.log('Response text:', xhr.responseText);
                    $('#loginError').html('Login failed. Network error: ' + status).css('color', 'red');
                });
        });
        
        $('#registerForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = $(this).serialize();
            $('#registerError').html('Creating account...');
            
            $.post('<?php echo base_url(); ?>user/register', formData)
                .done(function(response) {
                    try {
                        var data = JSON.parse(response);
                        if (data.error) {
                            $('#registerError').html(data.error).css('color', 'red');
                        } else {
                            $('#registerError').html('Registration successful! Redirecting...').css('color', 'green');
                            setTimeout(function() {
                                window.location.href = data.url || '<?php echo base_url(); ?>';
                            }, 1000);
                        }
                    } catch (e) {
                        console.error('JSON parsing error:', e);
                        $('#registerError').html('Registration failed. Invalid response.').css('color', 'red');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    $('#registerError').html('Registration failed. Please try again.').css('color', 'red');
                });
        });
        
        // Modal switching
        $('#registerButton').click(function(e) {
            e.preventDefault();
            $('#login').addClass('d-none');
            $('#register').removeClass('d-none');
        });
        
        $('#loginButton').click(function(e) {
            e.preventDefault();
            $('#register').addClass('d-none');
            $('#login').removeClass('d-none');
        });
        
        // Auto-show login modal if login is required
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('login_required')) {
            $('#logModal').modal('show');
            // Show a message in the login modal
            $('#loginError').html('Please login to proceed with checkout.').css('color', '#007bff');
            
            // Remove the parameter from URL to clean it up
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
        
        // Check for flashdata message and show it
        <?php if ($this->session->flashdata('login_required')): ?>
            $('#logModal').modal('show');
            $('#loginError').html('<?php echo $this->session->flashdata('login_required'); ?>').css('color', '#007bff');
        <?php endif; ?>
    });
    
    // SIMPLE DROPDOWN FIX - JUST MAKE IT WORK!
    $(document).ready(function() {
        // Force dropdown to work with simple click
        $('#userdetails').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var dropdown = $(this).next('.dropdown-menu');
            
            // Toggle visibility
            if (dropdown.hasClass('show')) {
                dropdown.removeClass('show');
            } else {
                $('.dropdown-menu').removeClass('show'); // Hide others
                dropdown.addClass('show');
                
                // Position it correctly
                var iconPos = $(this).offset();
                var iconHeight = $(this).outerHeight();
                
                dropdown.css({
                    'position': 'fixed',
                    'top': (iconPos.top + iconHeight + 5) + 'px',
                    'right': '20px',
                    'left': 'auto',
                    'z-index': '99999',
                    'display': 'block'
                });
            }
        });
        
        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.nav-item.dropdown').length) {
                $('.dropdown-menu').removeClass('show');
            }
        });
    });
  </script>
