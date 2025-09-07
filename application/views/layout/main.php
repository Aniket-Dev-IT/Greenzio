<?php
defined('BASEPATH') or exit('No direct script access allowed');

$data = $this->session->userdata();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Greenzio - Your Fresh Grocery Store</title>
  <meta name="description" content="Greenzio - Fresh groceries delivered to your doorstep. Shop organic fruits, vegetables, dairy, and everyday essentials with free delivery.">
  <meta name="keywords" content="fresh groceries, organic food, fruits, vegetables, dairy, home delivery, grocery store, Greenzio">
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
  <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700" rel="stylesheet">

  <!-- owl carousel css -->
  <link rel='stylesheet' href="<?php echo base_url() . 'assets/css/owl.carousel.min.css'; ?>">
  <link rel='stylesheet' href="<?php echo base_url() . 'assets/css/owl.theme.default.min.css'; ?>">

  <!-- Custom Stylesheet For this template -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/creative.css'; ?>">
  
  <!-- Search Enhancement Styles -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/search-enhancements.css'; ?>">
  
  <!-- Product Detail Enhancements Styles -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/product-detail-enhancements.css'; ?>">

  <!-- Bootstrap core javascript -->
  <script src="<?php echo base_url() . 'assets/js/jquery.js'; ?>"></script>
  <script src="<?php echo base_url() . 'assets/js/popper.js'; ?>"></script>
  <script src="<?php echo base_url() . 'assets/js/bootstrap.js'; ?>"></script>

  <style>
    /* Enhanced grocery-focused styles */
    .grocery-nav .nav-link {
      font-weight: 500;
      transition: all 0.3s ease;
      position: relative;
    }
    
    .grocery-nav .nav-link:hover {
      color: #28a745 !important;
      transform: translateY(-2px);
    }
    
    .grocery-nav .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -5px;
      left: 50%;
      background-color: #28a745;
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }
    
    .grocery-nav .nav-link:hover::after {
      width: 80%;
    }
    
    .mega-menu {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: white;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      border-radius: 0 0 10px 10px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-20px);
      transition: all 0.3s ease;
      z-index: 1050;
    }
    
    .navbar-nav .dropdown:hover .mega-menu {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }
    
    .category-icon {
      font-size: 2rem;
      color: #28a745;
      margin-bottom: 1rem;
    }
    
    .quick-shop-btn {
      background: linear-gradient(135deg, #28a745, #20c997);
      border: none;
      padding: 10px 25px;
      border-radius: 25px;
      color: white;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .quick-shop-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
      color: white;
    }
    
    .search-container {
      position: relative;
    }
    
    .search-wrapper {
      min-width: 400px;
    }
    
    .search-form .input-group {
      border-radius: 25px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .search-category-select {
      border-right: none;
      background: #f8f9fa;
      font-size: 0.9rem;
      max-width: 140px;
    }
    
    .search-input {
      border-left: none;
      border-right: none;
      font-size: 1rem;
      padding: 12px 15px;
    }
    
    .search-btn {
      background: linear-gradient(135deg, #28a745, #20c997);
      border: none;
      padding: 12px 20px;
      color: white;
      transition: all 0.3s ease;
    }
    
    .search-btn:hover {
      background: linear-gradient(135deg, #218838, #1ea080);
      color: white;
    }
    
    .top-bar {
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
    }
    
    .navbar-brand {
      font-size: 1.8rem;
      font-weight: 700;
      color: #28a745 !important;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .delivery-info {
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .delivery-info i {
      color: #ffc107;
    }
    
    @media (max-width: 991px) {
      .search-wrapper {
        min-width: 100%;
        margin-bottom: 1rem;
      }
      
      .mega-menu {
        position: static;
        opacity: 1;
        visibility: visible;
        transform: none;
        box-shadow: none;
        border-radius: 0;
        border-top: 1px solid #e9ecef;
      }
    }
  </style>
</head>

<body>

  <header class="header-absolute">

    <!-- Enhanced Top NavBar with Delivery Info -->
    <div class="top-bar" style="height:50px">
      <div class="container-fluid">
        <div class="row d-flex align-items-center">
          <div class="col-lg-6 col-md-12">
            <div class="delivery-info">
              <span><i class="fas fa-phone"></i> +91-90123-45678</span>
              <span><i class="fas fa-truck"></i> Free Delivery on orders ₹500+</span>
            </div>
          </div>
          <div class="col-lg-6 col-md-12 text-right">
            <div class="delivery-info justify-content-end">
              <span><i class="fas fa-clock"></i> Delivery: 30 mins</span>
              <span><i class="fas fa-leaf"></i> 100% Organic</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced Navigation with Grocery Categories -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-airy fixed-top py-lg-3 px-lg-4 text-uppercase grocery-nav" id="mainNav" data-toggle="affix">
      <div class="container-fluid" id="main-navbar">
        <a class="navbar-brand" href="<?php echo base_url(); ?>">
          <i class="fas fa-leaf mr-2"></i>Greenzio
        </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav mx-auto">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>">
                <i class="fas fa-home mr-1"></i>Home
              </a>
            </li>
            
            <!-- Fresh Produce Dropdown -->
            <li class="nav-item dropdown position-relative">
              <a class="nav-link dropdown-toggle" href="#" id="freshProduceDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-apple-alt mr-1"></i>Fresh Produce
              </a>
              <div class="mega-menu" aria-labelledby="freshProduceDropdown">
                <div class="container py-4">
                  <div class="row">
                    <div class="col-md-6">
                      <h6 class="font-weight-bold text-success mb-3">
                        <i class="fas fa-apple-alt mr-2"></i>Fruits & Vegetables
                      </h6>
                      <ul class="list-unstyled">
                        <li><a href="<?php echo base_url() . 'category/fruits-vegetables'; ?>" class="text-decoration-none">Fresh Fruits</a></li>
                        <li><a href="<?php echo base_url() . 'category/fruits-vegetables'; ?>" class="text-decoration-none">Leafy Greens</a></li>
                        <li><a href="<?php echo base_url() . 'category/fruits-vegetables'; ?>" class="text-decoration-none">Root Vegetables</a></li>
                        <li><a href="<?php echo base_url() . 'category/fruits-vegetables'; ?>" class="text-decoration-none">Exotic Fruits</a></li>
                      </ul>
                    </div>
                    <div class="col-md-6">
                      <h6 class="font-weight-bold text-success mb-3">
                        <i class="fas fa-seedling mr-2"></i>Organic Selection
                      </h6>
                      <ul class="list-unstyled">
                        <li><a href="<?php echo base_url() . 'category/fruits-vegetables'; ?>" class="text-decoration-none">Organic Fruits</a></li>
                        <li><a href="<?php echo base_url() . 'category/fruits-vegetables'; ?>" class="text-decoration-none">Organic Vegetables</a></li>
                        <li><a href="<?php echo base_url() . 'category/fruits-vegetables'; ?>" class="text-decoration-none">Pesticide-Free</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url() . 'category/dairy-products'; ?>">
                <i class="fas fa-cheese mr-1"></i>Dairy & Bakery
              </a>
            </li>
            
            <!-- Pantry Essentials Dropdown -->
            <li class="nav-item dropdown position-relative">
              <a class="nav-link dropdown-toggle" href="#" id="pantryDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-boxes mr-1"></i>Pantry Essentials
              </a>
              <div class="mega-menu" aria-labelledby="pantryDropdown">
                <div class="container py-4">
                  <div class="row">
                    <div class="col-md-4">
                      <h6 class="font-weight-bold text-success mb-3">Grains & Cereals</h6>
                      <a href="<?php echo base_url() . 'category/grains-pulses'; ?>" class="btn btn-outline-success btn-sm">Shop Now</a>
                    </div>
                    <div class="col-md-4">
                      <h6 class="font-weight-bold text-success mb-3">Spices & Seasonings</h6>
                      <a href="<?php echo base_url() . 'category/spices-condiments'; ?>" class="btn btn-outline-success btn-sm">Shop Now</a>
                    </div>
                    <div class="col-md-4">
                      <h6 class="font-weight-bold text-success mb-3">Cooking Oils</h6>
                      <a href="<?php echo base_url() . 'category/spices-condiments'; ?>" class="btn btn-outline-success btn-sm">Shop Now</a>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url() . 'category/snacks-beverages'; ?>">
                <i class="fas fa-coffee mr-1"></i>Beverages
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url() . 'category/personal-care'; ?>">
                <i class="fas fa-heart mr-1"></i>Personal Care
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url() . 'category/household-items'; ?>">
                <i class="fas fa-home mr-1"></i>Home Care
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url() . 'shop/contact'; ?>">
                <i class="fas fa-phone mr-1"></i>Contact
              </a>
            </li>
          </ul>
          
          <div class="d-flex align-items-center justify-content-between justify-content-lg-end mt-1 mb-2 my-lg-0">
            <!-- Enhanced Grocery Search Bar with Autocomplete -->
            <div class="nav-item search-container">
              <div class="search-wrapper">
                <form id="globalSearchForm" method="get" action="<?php echo base_url('product/search'); ?>" class="search-form">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <select name="category" id="searchCategory" class="form-control search-category-select">
                        <option value="">All Items</option>
                        <option value="Fruits & Vegetables">Fresh Produce</option>
                        <option value="Dairy Products">Dairy & Bakery</option>
                        <option value="Grains & Pulses">Grains & Pulses</option>
                        <option value="Spices & Condiments">Spices & Oils</option>
                        <option value="Snacks & Beverages">Beverages</option>
                        <option value="Personal Care">Personal Care</option>
                        <option value="Household Items">Home Care</option>
                      </select>
                    </div>
                    <input type="search" name="q" id="searchInput" class="form-control search-input" placeholder="Search for groceries, brands..." aria-label="Search" autocomplete="off" data-grocery-items='["Apples", "Bananas", "Milk", "Bread", "Rice", "Tomatoes", "Onions", "Potatoes", "Chicken", "Fish", "Eggs", "Yogurt", "Cheese", "Butter", "Oil", "Sugar", "Salt", "Flour", "Pasta", "Cereal"]'>
                    <div class="input-group-append">
                      <button type="submit" class="btn search-btn">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                  <!-- Enhanced Search Suggestions with Categories -->
                  <div id="searchSuggestions" class="search-suggestions"></div>
                </form>
              </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="nav-item ml-3">
              <button class="btn quick-shop-btn btn-sm" data-toggle="modal" data-target="#quickShopModal">
                <i class="fas fa-bolt mr-1"></i>Quick Shop
              </button>
            </div>

            <?php if (isset($data['userID'])) {
              echo ' <div class="nav-item dropdown ml-2"><a id="userdetails" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link"><i class="fas fa-user"></i>
              </a>
            <div aria-labelledby="userdetails" class="dropdown-menu dropdown-menu-right"> 
              <a href="'.base_url('order/orderList').'" class="dropdown-item"><i class="fas fa-list-alt mr-2"></i>Orders</a>
              <div class="dropdown-divider my-0"></div><a href="'.base_url('user/logout').'" class="dropdown-item"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
            </div>
          </div>';
            } else {
              echo '<div data-toggle="modal" data-target="#logModal" class="nav-item ml-2">
                <a class="nav-link" href="#"><i class="fas fa-user"></i></a>
              </div>';
            } ?>

            <div class="nav-item ml-2">
              <a class="nav-link position-relative" href="<?php echo base_url('shopping/cart'); ?>"> 
                <i class="fas fa-shopping-cart"></i>
                <span class="badge badge-success badge-pill position-absolute" style="top: -5px; right: -5px; font-size: 0.7rem;" id="cartCount">0</span>        
              </a>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <!-- Quick Shop Modal -->
  <div class="modal fade" id="quickShopModal" tabindex="-1" role="dialog" aria-labelledby="quickShopModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title font-weight-bold" id="quickShopModalLabel">
            <i class="fas fa-bolt text-success mr-2"></i>Quick Shop - Weekly Essentials
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <h6 class="font-weight-bold mb-3"><i class="fas fa-clock text-success mr-2"></i>Fresh Today</h6>
              <div class="list-group">
                <a href="<?php echo base_url() . 'category/fruits-vegetables'; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Fresh Fruits & Vegetables
                  <span class="badge badge-success badge-pill">30% off</span>
                </a>
                <a href="<?php echo base_url() . 'category/dairy-products'; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  Fresh Dairy Products
                  <span class="badge badge-warning badge-pill">New</span>
                </a>
              </div>
            </div>
            <div class="col-md-6">
              <h6 class="font-weight-bold mb-3"><i class="fas fa-list-check text-success mr-2"></i>Weekly Essentials</h6>
              <div class="list-group">
                <a href="<?php echo base_url() . 'category/grains-pulses'; ?>" class="list-group-item list-group-item-action">Rice & Grains</a>
                <a href="<?php echo base_url() . 'category/spices-condiments'; ?>" class="list-group-item list-group-item-action">Cooking Oil & Spices</a>
                <a href="<?php echo base_url() . 'category/dairy-products'; ?>" class="list-group-item list-group-item-action">Milk & Dairy</a>
                <a href="<?php echo base_url() . 'category/snacks-beverages'; ?>" class="list-group-item list-group-item-action">Tea & Coffee</a>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
          <a href="<?php echo base_url(); ?>" class="btn btn-success">
            <i class="fas fa-shopping-cart mr-2"></i>Start Shopping
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Login Modal (existing code) -->
  <div class="modal fade" id="logModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" id="login">
        <div class="modal-header text-center border-0">
          <h3 class="modal-title w-100 font-weight-bold my-3" id="myModalLabel"><strong>Login</strong></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form action="#" method="post">
          <div class="modal-body mx-4">
            <div class='text-danger text-center mb-4' id="loginError"></div>

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
              <p>Forgot <a href="#" class="blue-text">Password?</a></p>
            </div>
            <div class="text-center mb-3">
              <button type="button" class="btn blue-gradient btn-block btn-rounded z-depth-1a" id="submitLogin">Login</button>
            </div>
            <p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"> or Login with:</p>

            <div class="row my-3 d-flex justify-content-center">
              <button type="button" class="btn btn-white mr-3 z-depth-1a"><i class="fab fa-facebook-f text-center"></i></button>
              <button type="button" class="btn btn-white btn-rounded z-depth-1a"><i class="fab fa-google-plus-g"></i></button>
            </div>
          </div>
        </form>
        <div class="modal-footer pt-3 mb-1">
          <p class="font-small grey-text d-flex justify-content-end">Not a member? <a href="#registerButton" id="registerButton" class="blue-text ml-1">Register</a></p>
        </div>
      </div>

      <!-- Register Modal (existing code) -->
      <div class="modal-content d-none" id="register">
        <div class="modal-header text-center border-0">
          <h3 class="modal-title w-100 font-weight-bold my-3" id="myModalLabel"><strong>Register</strong></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="#" id="registration">
          <div class="modal-body mx-4">
            <div class='text-danger text-center mb-4' id="error"></div>
            <div class="mb-3">
              <label data-error="wrong" data-success="right" for="Form-email1">Email&nbsp;/&nbsp;Mobile No.</label>
              <input type="text" id="regInput" name="regInput" class="form-control" required minlength="10">
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
              <button type="button" class="btn blue-gradient btn-block btn-rounded z-depth-1a" id="submitRegister">Register</button>
            </div>
            <p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"> or Register with:</p>

            <div class="row my-3 d-flex justify-content-center">
              <button type="button" class="btn btn-white mr-3 z-depth-1a"><i class="fab fa-facebook-f text-center"></i></button>
              <button type="button" class="btn btn-white btn-rounded z-depth-1a"><i class="fab fa-google-plus-g"></i></button>
            </div>
          </div>
        </form>
        <div class="modal-footer pt-3 mb-1">
          <p class="font-small grey-text d-flex justify-content-end">Already a member? <a href="#loginButton" class="blue-text ml-1" id="loginButton">Login</a></p>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Enhanced search functionality with grocery autocomplete
    $(document).ready(function() {
        // Grocery items for autocomplete
        const commonGroceries = [
            'Apples', 'Bananas', 'Oranges', 'Milk', 'Bread', 'Rice', 'Tomatoes', 'Onions', 
            'Potatoes', 'Chicken', 'Fish', 'Eggs', 'Yogurt', 'Cheese', 'Butter', 'Oil', 
            'Sugar', 'Salt', 'Flour', 'Pasta', 'Cereal', 'Tea', 'Coffee', 'Carrots', 
            'Spinach', 'Bell Peppers', 'Garlic', 'Ginger', 'Lemon', 'Cucumber'
        ];
        
        // Search suggestions with enhanced grocery focus
        $('#searchInput').on('keyup', function() {
            var query = $(this).val();
            
            if (query.length >= 2) {
                // First, filter common groceries
                const groceryMatches = commonGroceries.filter(item => 
                    item.toLowerCase().includes(query.toLowerCase())
                ).slice(0, 3);
                
                // Then get server suggestions
                $.ajax({
                    url: '<?php echo base_url(); ?>product/searchSuggestions',
                    type: 'GET',
                    data: { q: query },
                    dataType: 'json',
                    success: function(data) {
                        var suggestions = '';
                        
                        // Add grocery matches first
                        groceryMatches.forEach(function(item) {
                            suggestions += '<div class="suggestion-item" data-value="' + item + '" data-type="grocery">' + 
                                         '<i class="fas fa-shopping-basket mr-2 text-success"></i>' + item + 
                                         '<small class="text-muted ml-2">Common Grocery</small></div>';
                        });
                        
                        // Add server suggestions
                        if (data.length > 0) {
                            data.slice(0, 4).forEach(function(item) {
                                var icon = '';
                                if (item.type === 'product') {
                                    icon = '<i class="fas fa-cube mr-2 text-primary"></i>';
                                } else if (item.type === 'brand') {
                                    icon = '<i class="fas fa-tag mr-2 text-warning"></i>';
                                } else if (item.type === 'category') {
                                    icon = '<i class="fas fa-list mr-2 text-info"></i>';
                                }
                                
                                suggestions += '<div class="suggestion-item" data-value="' + item.suggestion + '" data-type="' + item.type + '">' + 
                                             icon + item.suggestion + '</div>';
                            });
                        }
                        
                        if (suggestions) {
                            $('#searchSuggestions').html(suggestions).show();
                        } else {
                            $('#searchSuggestions').hide();
                        }
                    },
                    error: function() {
                        // Show grocery matches even if server fails
                        if (groceryMatches.length > 0) {
                            var suggestions = '';
                            groceryMatches.forEach(function(item) {
                                suggestions += '<div class="suggestion-item" data-value="' + item + '" data-type="grocery">' + 
                                             '<i class="fas fa-shopping-basket mr-2 text-success"></i>' + item + '</div>';
                            });
                            $('#searchSuggestions').html(suggestions).show();
                        } else {
                            $('#searchSuggestions').hide();
                        }
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
            }
            
            $('#searchSuggestions').hide();
            $('#globalSearchForm').submit();
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
                setTimeout(function() {
                    $('#globalSearchForm').submit();
                }, 300);
            }
        });
        
        // Update cart count (you can connect this to your cart system)
        function updateCartCount() {
            // This should be connected to your actual cart system
            // $('#cartCount').text(cartItemCount);
        }
    });
    
    // Existing login/register scripts...
    let registerButton = document.querySelector('#submitRegister');
    $(registerButton).click(function() {
      displayRegisterErrors();
    });

    function displayRegisterErrors() {
      let regInput = document.getElementById('regInput').value;
      let regPass = document.getElementById('regPass').value;
      let regPass2 = document.getElementById('regPass2').value;
      let regGender = document.querySelector('input[type ="radio"]').value;

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>user/register',
        dataType: "JSON",
        data: {
          regInput: regInput,
          regPass: regPass,
          regPass2: regPass2,
          gender: regGender
        },
        success: function(data) {
          JSON.stringify(data);
          if (data.error != undefined) {
            $('#error').html(data.error);
          } else {
            window.location.href = data.url;
          }
        },
        error: function(jqXhr, textStatus, errorMessage) {
          console.log("Error: ", errorMessage);
        }
      });
    }

    let loginButton = document.querySelector('#submitLogin');
    $(loginButton).click(function() {
      displayLoginErrors();
    });

    function displayLoginErrors() {
      let loginInput = document.getElementById('loginInput').value;
      let loginPassword = document.getElementById('loginPassword').value;
      let checkBox = document.querySelector('input[type ="checkbox"]').checked;

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>user/login',
        dataType: "JSON",
        data: {
          loginInput: loginInput,
          loginPassword: loginPassword,
          checkBox: checkBox
        },
        success: function(data) {
          console.log('Login response:', data);
          if (data.error != undefined) {
            $('#loginError').html(data.error);
          } else if (data.success === true) {
            console.log('Login successful, redirecting to:', data.url);
            $('#loginError').removeClass('text-danger').addClass('text-success').html(data.message || 'Login successful!');
            setTimeout(function() {
              window.location.href = data.url;
            }, 1000);
          } else {
            console.log('Unexpected response structure:', data);
            $('#loginError').html('Login failed - unexpected response');
          }
        },
        error: function(jqXhr, textStatus, errorMessage) {
          console.log("Error: ", errorMessage);
        }
      });
    }
  </script>

</body>
</html>
