<!-- Grocery Hero Section with Fresh Daily Deals -->
<section class="hero-section position-relative overflow-hidden">
  <div class="hero-bg-gradient"></div>
  <div class="container-fluid px-0">
    <div class="row no-gutters min-vh-100">
      <!-- Hero Content -->
      <div class="col-lg-6 d-flex align-items-center hero-content">
        <div class="container">
          <div class="hero-text-content">
            <div class="hero-badge mb-4">
              <i class="fas fa-leaf mr-2"></i>
              <span>100% Fresh & Organic</span>
            </div>
            <h1 class="display-3 font-weight-bold mb-4">
              Fresh Groceries<br>
              <span class="text-success">Delivered Daily</span>
            </h1>
            <p class="lead mb-4">Farm-fresh produce, dairy, and everyday essentials delivered to your doorstep. Quality guaranteed, always fresh!</p>
            
            <!-- Quick Action Buttons -->
            <div class="hero-actions mb-4">
              <a href="#weekly-essentials" class="btn btn-success btn-lg mr-3 hero-btn">
                <i class="fas fa-shopping-cart mr-2"></i>Shop Weekly Essentials
              </a>
              <a href="#fresh-today" class="btn btn-outline-success btn-lg hero-btn">
                <i class="fas fa-clock mr-2"></i>Fresh Today
              </a>
            </div>
            
            <!-- Delivery Promise -->
            <div class="delivery-promise d-flex flex-wrap">
              <div class="promise-item mr-4 mb-2">
                <i class="fas fa-truck text-success mr-2"></i>
                <span>30-min delivery</span>
              </div>
              <div class="promise-item mr-4 mb-2">
                <i class="fas fa-shield-alt text-success mr-2"></i>
                <span>Quality guaranteed</span>
              </div>
              <div class="promise-item mb-2">
                <i class="fas fa-rupee-sign text-success mr-2"></i>
                <span>Free delivery on ₹500+</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Hero Image Slider -->
      <div class="col-lg-6 hero-slider">
        <div class="owl-carousel owl-theme hero-carousel">
          <div class="hero-slide">
            <img src="<?php echo base_url('assets/img/1.jpg'); ?>" alt="Fresh Fruits & Vegetables" class="img-fluid">
            <div class="slide-overlay">
              <div class="slide-content">
                <h3>Fresh Produce</h3>
                <p>Farm to table freshness</p>
                <span class="sale-badge">30% OFF</span>
              </div>
            </div>
          </div>
          <div class="hero-slide">
            <img src="<?php echo base_url('assets/img/2.jpg'); ?>" alt="Dairy Products" class="img-fluid">
            <div class="slide-overlay">
              <div class="slide-content">
                <h3>Fresh Dairy</h3>
                <p>Pure & natural</p>
                <span class="sale-badge">NEW</span>
              </div>
            </div>
          </div>
          <div class="hero-slide">
            <img src="<?php echo base_url('assets/img/3.jpg'); ?>" alt="Pantry Essentials" class="img-fluid">
            <div class="slide-overlay">
              <div class="slide-content">
                <h3>Pantry Essentials</h3>
                <p>Stock up & save</p>
                <span class="sale-badge">BULK DEALS</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Quick Shop Categories -->
<section class="quick-shop-section py-5 bg-light" id="weekly-essentials">
  <div class="container">
    <div class="section-header text-center mb-5">
      <h2 class="section-title">Weekly Essentials</h2>
      <p class="section-subtitle">Your go-to grocery items, always in stock</p>
    </div>
    
    <div class="row">
      <!-- Fruits & Vegetables -->
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="quick-category-card">
          <div class="category-image-wrapper">
            <img src="<?php echo base_url('assets/img/category-fruits.jpg'); ?>" alt="Fruits & Vegetables" class="category-image">
            <div class="category-overlay">
              <div class="category-badge">Fresh Daily</div>
            </div>
          </div>
          <div class="category-content">
            <div class="category-icon">
              <i class="fas fa-apple-alt text-success"></i>
            </div>
            <h5 class="category-title">Fruits & Vegetables</h5>
            <p class="category-description">Farm-fresh produce delivered daily</p>
            <div class="category-stats mb-3">
              <span class="stat-item mr-3">
                <i class="fas fa-leaf mr-1"></i>Organic
              </span>
              <span class="stat-item">
                <i class="fas fa-percentage mr-1"></i>30% Off
              </span>
            </div>
            <a href="<?php echo base_url('category/fruits-vegetables'); ?>" class="btn btn-success btn-block category-btn">
              Shop Fresh Produce
            </a>
          </div>
        </div>
      </div>
      
      <!-- Dairy & Bakery -->
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="quick-category-card">
          <div class="category-image-wrapper">
            <img src="<?php echo base_url('assets/img/category-dairy.jpg'); ?>" alt="Dairy & Bakery" class="category-image">
            <div class="category-overlay">
              <div class="category-badge">Pure & Natural</div>
            </div>
          </div>
          <div class="category-content">
            <div class="category-icon">
              <i class="fas fa-cheese text-warning"></i>
            </div>
            <h5 class="category-title">Dairy & Bakery</h5>
            <p class="category-description">Fresh milk, cheese, bread & more</p>
            <div class="category-stats mb-3">
              <span class="stat-item mr-3">
                <i class="fas fa-thermometer-quarter mr-1"></i>Fresh
              </span>
              <span class="stat-item">
                <i class="fas fa-award mr-1"></i>Premium
              </span>
            </div>
            <a href="<?php echo base_url('category/dairy-products'); ?>" class="btn btn-warning btn-block category-btn">
              Shop Dairy
            </a>
          </div>
        </div>
      </div>
      
      <!-- Pantry Essentials -->
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="quick-category-card">
          <div class="category-image-wrapper">
            <img src="<?php echo base_url('assets/img/category-pantry.jpg'); ?>" alt="Pantry Essentials" class="category-image">
            <div class="category-overlay">
              <div class="category-badge">Stock & Save</div>
            </div>
          </div>
          <div class="category-content">
            <div class="category-icon">
              <i class="fas fa-boxes text-info"></i>
            </div>
            <h5 class="category-title">Pantry Essentials</h5>
            <p class="category-description">Grains, spices, oils & cooking needs</p>
            <div class="category-stats mb-3">
              <span class="stat-item mr-3">
                <i class="fas fa-box mr-1"></i>Bulk
              </span>
              <span class="stat-item">
                <i class="fas fa-tag mr-1"></i>Best Price
              </span>
            </div>
            <a href="<?php echo base_url('category/grains-pulses'); ?>" class="btn btn-info btn-block category-btn">
              Shop Pantry
            </a>
          </div>
        </div>
      </div>
      
      <!-- Beverages -->
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="quick-category-card">
          <div class="category-image-wrapper">
            <img src="<?php echo base_url('assets/img/category-beverages.jpg'); ?>" alt="Beverages" class="category-image">
            <div class="category-overlay">
              <div class="category-badge">Refreshing</div>
            </div>
          </div>
          <div class="category-content">
            <div class="category-icon">
              <i class="fas fa-coffee text-danger"></i>
            </div>
            <h5 class="category-title">Beverages</h5>
            <p class="category-description">Tea, coffee, juices & soft drinks</p>
            <div class="category-stats mb-3">
              <span class="stat-item mr-3">
                <i class="fas fa-mug-hot mr-1"></i>Hot & Cold
              </span>
              <span class="stat-item">
                <i class="fas fa-star mr-1"></i>Popular
              </span>
            </div>
            <a href="<?php echo base_url('category/snacks-beverages'); ?>" class="btn btn-danger btn-block category-btn">
              Shop Beverages
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Fresh Today Section -->
<section class="fresh-today-section py-5" id="fresh-today">
  <div class="container">
    <div class="section-header text-center mb-5">
      <h2 class="section-title">Fresh Today</h2>
      <p class="section-subtitle">Handpicked fresh items delivered today</p>
    </div>
    
    <div class="fresh-today-content">
      <div class="row">
        <!-- Today's Special -->
        <div class="col-lg-6 mb-4">
          <div class="special-deal-card">
            <div class="deal-image-wrapper">
              <img src="<?php echo base_url('assets/img/5.jpg'); ?>" alt="Today's Special" class="deal-image">
              <div class="deal-badge">
                <span class="badge-text">TODAY'S SPECIAL</span>
                <span class="badge-discount">50% OFF</span>
              </div>
            </div>
            <div class="deal-content">
              <h4 class="deal-title">Farm Fresh Vegetable Bundle</h4>
              <p class="deal-description">Seasonal mix of 5kg fresh vegetables including tomatoes, onions, potatoes, and greens</p>
              <div class="price-section">
                <span class="original-price">₹500</span>
                <span class="sale-price">₹250</span>
                <span class="savings">Save ₹250</span>
              </div>
              <a href="<?php echo base_url('category/fruits-vegetables'); ?>" class="btn btn-success btn-lg btn-block">
                <i class="fas fa-cart-plus mr-2"></i>Add to Cart
              </a>
            </div>
          </div>
        </div>
        
        <!-- Fresh Picks Grid -->
        <div class="col-lg-6">
          <div class="fresh-picks-grid">
            <div class="row">
              <div class="col-6 mb-3">
                <div class="mini-product-card">
                  <img src="<?php echo base_url('assets/img/fresh-fruits.jpg'); ?>" alt="Fresh Fruits" class="mini-product-image">
                  <div class="mini-product-content">
                    <h6 class="mini-product-title">Seasonal Fruits</h6>
                    <span class="mini-product-price">From ₹30/kg</span>
                    <a href="<?php echo base_url('category/fruits-vegetables'); ?>" class="btn btn-sm btn-outline-success">Shop</a>
                  </div>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div class="mini-product-card">
                  <img src="<?php echo base_url('assets/img/fresh-milk.jpg'); ?>" alt="Fresh Milk" class="mini-product-image">
                  <div class="mini-product-content">
                    <h6 class="mini-product-title">Fresh Milk</h6>
                    <span class="mini-product-price">₹60/litre</span>
                    <a href="<?php echo base_url('category/dairy-products'); ?>" class="btn btn-sm btn-outline-success">Shop</a>
                  </div>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div class="mini-product-card">
                  <img src="<?php echo base_url('assets/img/fresh-bread.jpg'); ?>" alt="Fresh Bread" class="mini-product-image">
                  <div class="mini-product-content">
                    <h6 class="mini-product-title">Fresh Bread</h6>
                    <span class="mini-product-price">₹25/loaf</span>
                    <a href="<?php echo base_url('category/dairy-products'); ?>" class="btn btn-sm btn-outline-success">Shop</a>
                  </div>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div class="mini-product-card">
                  <img src="<?php echo base_url('assets/img/fresh-eggs.jpg'); ?>" alt="Fresh Eggs" class="mini-product-image">
                  <div class="mini-product-content">
                    <h6 class="mini-product-title">Farm Eggs</h6>
                    <span class="mini-product-price">₹80/dozen</span>
                    <a href="<?php echo base_url('category/dairy-products'); ?>" class="btn btn-sm btn-outline-success">Shop</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Category Banners Section -->
<section class="category-banners-section py-5 bg-light">
  <div class="container">
    <div class="section-header text-center mb-5">
      <h2 class="section-title">Shop by Category</h2>
      <p class="section-subtitle">Everything you need for your home</p>
    </div>
    
    <div class="category-banners">
      <div class="row">
        <!-- Large Banner 1 -->
        <div class="col-lg-8 mb-4">
          <div class="large-category-banner">
            <img src="<?php echo base_url('assets/img/banner-organic.jpg'); ?>" alt="Organic Products" class="banner-image">
            <div class="banner-overlay">
              <div class="banner-content">
                <div class="banner-icon">
                  <i class="fas fa-leaf"></i>
                </div>
                <h3 class="banner-title">100% Organic Products</h3>
                <p class="banner-subtitle">Certified organic fruits, vegetables & grains</p>
                <div class="banner-offer">
                  <span class="offer-text">ORGANIC WEEK</span>
                  <span class="offer-discount">40% OFF</span>
                </div>
                <a href="<?php echo base_url('category/fruits-vegetables'); ?>" class="btn btn-light btn-lg">
                  Shop Organic <i class="fas fa-arrow-right ml-2"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Small Banners -->
        <div class="col-lg-4">
          <div class="row">
            <div class="col-12 mb-4">
              <div class="small-category-banner">
                <img src="<?php echo base_url('assets/img/banner-spices.jpg'); ?>" alt="Spices & Condiments" class="banner-image">
                <div class="banner-overlay">
                  <div class="banner-content text-center">
                    <h5 class="banner-title">Premium Spices</h5>
                    <p class="banner-subtitle">Authentic flavors</p>
                    <a href="<?php echo base_url('category/spices-condiments'); ?>" class="btn btn-outline-light">Shop Now</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="small-category-banner">
                <img src="<?php echo base_url('assets/img/banner-household.jpg'); ?>" alt="Household Items" class="banner-image">
                <div class="banner-overlay">
                  <div class="banner-content text-center">
                    <h5 class="banner-title">Home Care</h5>
                    <p class="banner-subtitle">Clean & fresh</p>
                    <a href="<?php echo base_url('category/household-items'); ?>" class="btn btn-outline-light">Shop Now</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Featured Products Section -->
<section class="featured-products-section py-5">
  <div class="container">
    <div class="section-header text-center mb-5">
      <h2 class="section-title">Featured Products</h2>
      <p class="section-subtitle">Customer favorites and bestsellers</p>
    </div>
    
    <div class="featured-products-carousel">
      <div class="owl-carousel owl-theme products-carousel">
        <!-- Product 1 -->
        <div class="product-card">
          <div class="product-image-wrapper">
            <img src="<?php echo base_url('assets/img/product-apples.jpg'); ?>" alt="Fresh Apples" class="product-image">
            <div class="product-badges">
              <span class="badge badge-success">Fresh</span>
              <span class="badge badge-warning">Popular</span>
            </div>
            <div class="product-actions">
              <button class="btn btn-sm btn-light quick-view-btn">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-success add-to-cart-btn">
                <i class="fas fa-cart-plus"></i>
              </button>
            </div>
          </div>
          <div class="product-content">
            <h6 class="product-title">Fresh Red Apples</h6>
            <p class="product-description">Premium quality Kashmiri apples</p>
            <div class="product-rating">
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star-half-alt text-warning"></i>
              <span class="rating-text">(4.5)</span>
            </div>
            <div class="product-price">
              <span class="current-price">₹120/kg</span>
              <span class="original-price">₹150/kg</span>
            </div>
          </div>
        </div>
        
        <!-- Product 2 -->
        <div class="product-card">
          <div class="product-image-wrapper">
            <img src="<?php echo base_url('assets/img/product-milk.jpg'); ?>" alt="Fresh Milk" class="product-image">
            <div class="product-badges">
              <span class="badge badge-primary">Pure</span>
            </div>
            <div class="product-actions">
              <button class="btn btn-sm btn-light quick-view-btn">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-success add-to-cart-btn">
                <i class="fas fa-cart-plus"></i>
              </button>
            </div>
          </div>
          <div class="product-content">
            <h6 class="product-title">Farm Fresh Milk</h6>
            <p class="product-description">Full cream toned milk</p>
            <div class="product-rating">
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <span class="rating-text">(5.0)</span>
            </div>
            <div class="product-price">
              <span class="current-price">₹60/litre</span>
            </div>
          </div>
        </div>
        
        <!-- Product 3 -->
        <div class="product-card">
          <div class="product-image-wrapper">
            <img src="<?php echo base_url('assets/img/product-rice.jpg'); ?>" alt="Basmati Rice" class="product-image">
            <div class="product-badges">
              <span class="badge badge-info">Premium</span>
            </div>
            <div class="product-actions">
              <button class="btn btn-sm btn-light quick-view-btn">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-success add-to-cart-btn">
                <i class="fas fa-cart-plus"></i>
              </button>
            </div>
          </div>
          <div class="product-content">
            <h6 class="product-title">Premium Basmati Rice</h6>
            <p class="product-description">Long grain aged basmati</p>
            <div class="product-rating">
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="far fa-star text-warning"></i>
              <span class="rating-text">(4.0)</span>
            </div>
            <div class="product-price">
              <span class="current-price">₹180/kg</span>
              <span class="original-price">₹200/kg</span>
            </div>
          </div>
        </div>
        
        <!-- More products can be added here -->
        <div class="product-card">
          <div class="product-image-wrapper">
            <img src="<?php echo base_url('assets/img/product-tomatoes.jpg'); ?>" alt="Fresh Tomatoes" class="product-image">
            <div class="product-badges">
              <span class="badge badge-success">Fresh</span>
            </div>
            <div class="product-actions">
              <button class="btn btn-sm btn-light quick-view-btn">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-success add-to-cart-btn">
                <i class="fas fa-cart-plus"></i>
              </button>
            </div>
          </div>
          <div class="product-content">
            <h6 class="product-title">Fresh Tomatoes</h6>
            <p class="product-description">Farm fresh red tomatoes</p>
            <div class="product-rating">
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <i class="fas fa-star text-warning"></i>
              <span class="rating-text">(4.8)</span>
            </div>
            <div class="product-price">
              <span class="current-price">₹40/kg</span>
              <span class="original-price">₹50/kg</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-choose-us-section py-5 bg-success text-white">
  <div class="container">
    <div class="section-header text-center mb-5">
      <h2 class="section-title">Why Choose Greenzio?</h2>
      <p class="section-subtitle">Your trusted grocery partner</p>
    </div>
    
    <div class="row">
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="feature-box text-center">
          <div class="feature-icon mb-3">
            <i class="fas fa-leaf fa-3x"></i>
          </div>
          <h5 class="feature-title">100% Fresh</h5>
          <p class="feature-description">Sourced directly from farms, delivered fresh daily</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="feature-box text-center">
          <div class="feature-icon mb-3">
            <i class="fas fa-shipping-fast fa-3x"></i>
          </div>
          <h5 class="feature-title">30-Min Delivery</h5>
          <p class="feature-description">Ultra-fast delivery to your doorstep</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="feature-box text-center">
          <div class="feature-icon mb-3">
            <i class="fas fa-shield-alt fa-3x"></i>
          </div>
          <h5 class="feature-title">Quality Assured</h5>
          <p class="feature-description">100% satisfaction guarantee or money back</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="feature-box text-center">
          <div class="feature-icon mb-3">
            <i class="fas fa-tags fa-3x"></i>
          </div>
          <h5 class="feature-title">Best Prices</h5>
          <p class="feature-description">Competitive prices with regular deals & offers</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section py-5 bg-light">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="newsletter-content">
          <h3 class="newsletter-title">Stay Fresh with Our Updates!</h3>
          <p class="newsletter-description">Get notified about fresh arrivals, special deals, and weekly grocery offers.</p>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="newsletter-form">
          <form class="d-flex">
            <input type="email" class="form-control mr-3" placeholder="Enter your email address" required>
            <button type="submit" class="btn btn-success">Subscribe</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
/* Hero Section Styles */
.hero-section {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  min-height: 100vh;
}

.hero-bg-gradient::before {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(32, 201, 151, 0.05) 100%);
  z-index: 1;
}

.hero-content {
  z-index: 2;
  position: relative;
}

.hero-badge {
  display: inline-block;
  background: rgba(40, 167, 69, 0.1);
  color: #28a745;
  padding: 8px 20px;
  border-radius: 25px;
  font-weight: 600;
  font-size: 0.9rem;
  border: 2px solid rgba(40, 167, 69, 0.2);
}

.hero-btn {
  padding: 12px 30px;
  border-radius: 30px;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.hero-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
}

.delivery-promise {
  font-size: 0.9rem;
  color: #6c757d;
}

.promise-item {
  display: flex;
  align-items: center;
}

.hero-slider {
  position: relative;
}

.hero-carousel .owl-nav {
  position: absolute;
  top: 50%;
  width: 100%;
  transform: translateY(-50%);
}

.hero-carousel .owl-nav button {
  position: absolute;
  background: rgba(255,255,255,0.9) !important;
  color: #28a745 !important;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  font-size: 20px;
  transition: all 0.3s ease;
}

.hero-carousel .owl-nav .owl-prev {
  left: 20px;
}

.hero-carousel .owl-nav .owl-next {
  right: 20px;
}

.hero-slide {
  position: relative;
  height: 600px;
  overflow: hidden;
  border-radius: 15px;
}

.hero-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.slide-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, rgba(0,0,0,0.5), transparent);
  display: flex;
  align-items: flex-end;
  padding: 30px;
}

.slide-content {
  color: white;
}

.slide-content h3 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 10px;
}

.sale-badge {
  background: #ffc107;
  color: #212529;
  padding: 5px 15px;
  border-radius: 15px;
  font-weight: 600;
  font-size: 0.8rem;
}

/* Quick Shop Categories */
.section-header {
  margin-bottom: 3rem;
}

.section-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 15px;
}

.section-subtitle {
  font-size: 1.1rem;
  color: #6c757d;
}

.quick-category-card {
  background: white;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  height: 100%;
}

.quick-category-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.category-image-wrapper {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.category-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: all 0.3s ease;
}

.quick-category-card:hover .category-image {
  transform: scale(1.1);
}

.category-overlay {
  position: absolute;
  top: 15px;
  right: 15px;
}

.category-badge {
  background: rgba(40, 167, 69, 0.9);
  color: white;
  padding: 5px 12px;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 600;
}

.category-content {
  padding: 25px;
  text-align: center;
}

.category-icon {
  font-size: 3rem;
  margin-bottom: 15px;
}

.category-title {
  font-size: 1.3rem;
  font-weight: 600;
  margin-bottom: 10px;
  color: #2c3e50;
}

.category-description {
  color: #6c757d;
  margin-bottom: 15px;
  font-size: 0.9rem;
}

.category-stats {
  display: flex;
  justify-content: center;
  gap: 15px;
}

.stat-item {
  font-size: 0.8rem;
  color: #28a745;
  font-weight: 600;
}

.category-btn {
  border-radius: 25px;
  padding: 10px 20px;
  font-weight: 600;
  transition: all 0.3s ease;
}

/* Fresh Today Section */
.special-deal-card {
  background: white;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 15px 35px rgba(0,0,0,0.1);
  height: 100%;
}

.deal-image-wrapper {
  position: relative;
  height: 250px;
}

.deal-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.deal-badge {
  position: absolute;
  top: 20px;
  left: 20px;
  background: linear-gradient(135deg, #dc3545, #c82333);
  color: white;
  padding: 10px 15px;
  border-radius: 10px;
  text-align: center;
}

.badge-text {
  display: block;
  font-size: 0.7rem;
  font-weight: 600;
  margin-bottom: 5px;
}

.badge-discount {
  display: block;
  font-size: 1.2rem;
  font-weight: 700;
}

.deal-content {
  padding: 25px;
}

.deal-title {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 15px;
  color: #2c3e50;
}

.deal-description {
  color: #6c757d;
  margin-bottom: 20px;
}

.price-section {
  margin-bottom: 20px;
}

.original-price {
  text-decoration: line-through;
  color: #6c757d;
  margin-right: 10px;
}

.sale-price {
  font-size: 1.5rem;
  font-weight: 700;
  color: #28a745;
  margin-right: 10px;
}

.savings {
  background: #d4edda;
  color: #155724;
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 600;
}

/* Mini Product Cards */
.fresh-picks-grid {
  height: 100%;
}

.mini-product-card {
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  height: 100%;
}

.mini-product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.mini-product-image {
  width: 100%;
  height: 100px;
  object-fit: cover;
}

.mini-product-content {
  padding: 15px;
  text-align: center;
}

.mini-product-title {
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #2c3e50;
}

.mini-product-price {
  color: #28a745;
  font-weight: 600;
  font-size: 0.8rem;
  margin-bottom: 10px;
  display: block;
}

/* Category Banners */
.large-category-banner, .small-category-banner {
  position: relative;
  border-radius: 15px;
  overflow: hidden;
  height: 300px;
  margin-bottom: 0;
}

.small-category-banner {
  height: 140px;
}

.banner-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: all 0.3s ease;
}

.large-category-banner:hover .banner-image,
.small-category-banner:hover .banner-image {
  transform: scale(1.05);
}

.banner-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(0,0,0,0.6), rgba(0,0,0,0.2));
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 30px;
}

.banner-content {
  text-align: center;
  color: white;
}

.banner-icon {
  font-size: 3rem;
  margin-bottom: 15px;
}

.banner-title {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 10px;
}

.small-category-banner .banner-title {
  font-size: 1.3rem;
}

.banner-subtitle {
  margin-bottom: 20px;
  opacity: 0.9;
}

.banner-offer {
  margin-bottom: 20px;
}

.offer-text {
  display: block;
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 5px;
}

.offer-discount {
  display: block;
  font-size: 2rem;
  font-weight: 700;
  color: #ffc107;
}

/* Featured Products */
.product-card {
  background: white;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  margin: 10px;
}

.product-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.product-image-wrapper {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.product-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: all 0.3s ease;
}

.product-card:hover .product-image {
  transform: scale(1.1);
}

.product-badges {
  position: absolute;
  top: 10px;
  left: 10px;
}

.product-badges .badge {
  margin-right: 5px;
  margin-bottom: 5px;
}

.product-actions {
  position: absolute;
  top: 10px;
  right: 10px;
  opacity: 0;
  transition: all 0.3s ease;
}

.product-card:hover .product-actions {
  opacity: 1;
}

.product-actions .btn {
  margin-left: 5px;
  border-radius: 50%;
  width: 35px;
  height: 35px;
  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.product-content {
  padding: 20px;
}

.product-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #2c3e50;
}

.product-description {
  color: #6c757d;
  font-size: 0.9rem;
  margin-bottom: 10px;
}

.product-rating {
  margin-bottom: 15px;
}

.rating-text {
  color: #6c757d;
  font-size: 0.8rem;
  margin-left: 5px;
}

.product-price {
  display: flex;
  align-items: center;
  gap: 10px;
}

.current-price {
  font-size: 1.2rem;
  font-weight: 700;
  color: #28a745;
}

.original-price {
  text-decoration: line-through;
  color: #6c757d;
  font-size: 0.9rem;
}

/* Why Choose Us */
.feature-box {
  padding: 20px;
}

.feature-icon {
  color: rgba(255,255,255,0.8);
}

.feature-title {
  font-weight: 600;
  margin-bottom: 15px;
}

.feature-description {
  opacity: 0.9;
}

/* Newsletter */
.newsletter-title {
  font-size: 2rem;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 15px;
}

.newsletter-description {
  color: #6c757d;
  font-size: 1.1rem;
}

.newsletter-form input {
  border-radius: 25px;
  padding: 12px 20px;
  border: 2px solid #e9ecef;
}

.newsletter-form button {
  border-radius: 25px;
  padding: 12px 30px;
  font-weight: 600;
}

/* Responsive Design */
@media (max-width: 991px) {
  .hero-section {
    min-height: auto;
  }
  
  .hero-slide {
    height: 400px;
  }
  
  .section-title {
    font-size: 2rem;
  }
  
  .banner-title {
    font-size: 1.5rem;
  }
  
  .deal-title {
    font-size: 1.2rem;
  }
  
  .large-category-banner,
  .small-category-banner {
    height: 250px;
  }
}

@media (max-width: 767px) {
  .hero-content {
    text-align: center;
    padding: 30px 0;
  }
  
  .display-3 {
    font-size: 2.5rem;
  }
  
  .hero-actions {
    flex-direction: column;
    gap: 15px;
  }
  
  .hero-btn {
    width: 100%;
  }
  
  .delivery-promise {
    justify-content: center;
    text-align: center;
  }
  
  .newsletter-form {
    margin-top: 20px;
  }
  
  .newsletter-form .d-flex {
    flex-direction: column;
    gap: 15px;
  }
}
</style>

<script>
$(document).ready(function() {
    // Initialize hero carousel
    $('.hero-carousel').owlCarousel({
        loop: true,
        margin: 0,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });
    
    // Initialize products carousel
    $('.products-carousel').owlCarousel({
        loop: true,
        margin: 20,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 4000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 4
            }
        }
    });
    
    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if( target.length ) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });
    
    // Add to cart functionality (placeholder)
    $('.add-to-cart-btn').on('click', function() {
        // Add your cart logic here
        $(this).html('<i class="fas fa-check"></i>').removeClass('btn-success').addClass('btn-primary');
        setTimeout(() => {
            $(this).html('<i class="fas fa-cart-plus"></i>').removeClass('btn-primary').addClass('btn-success');
        }, 2000);
    });
    
    // Quick view functionality (placeholder)
    $('.quick-view-btn').on('click', function() {
        // Add your quick view modal logic here
        alert('Quick view feature coming soon!');
    });
    
    // Newsletter subscription
    $('.newsletter-form form').on('submit', function(e) {
        e.preventDefault();
        // Add your newsletter subscription logic here
        alert('Thank you for subscribing to our newsletter!');
    });
    
    // Animate elements on scroll
    $(window).scroll(function() {
        $('.section-header').each(function() {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();
            
            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('animate__animated animate__fadeInUp');
            }
        });
    });
});
</script>
