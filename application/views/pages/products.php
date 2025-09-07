<?php

// echo "<pre>";
// print_r($productDetail);
// echo "</pre>";
foreach ($productDetail['productDetail'] as $product) {

  // Extract grocery-specific data
  $weight = $product['weight'];
  $unit = $product['unit'];
  $brand = $product['brand'];
  $expiryDate = $product['expiry_date'];
  $stockQuantity = $product['stock_quantity'];
  $discount = $product['discount'];
  $description = $product['description'] ?? '';
  $category = $product['category'];
  $subcategory = $product['subcategory'] ?? '';
  
  // Calculate discounted price if applicable
  $originalPrice = $product['price'];
  $finalPrice = $discount > 0 ? $originalPrice * (1 - $discount / 100) : $originalPrice;
  
  // Mock nutritional information - in real implementation, this would come from database
  $nutritionalInfo = [
    'calories' => $product['calories'] ?? '120',
    'protein' => $product['protein'] ?? '2.5g',
    'carbs' => $product['carbs'] ?? '25g',
    'fat' => $product['fat'] ?? '1.2g',
    'fiber' => $product['fiber'] ?? '3.1g',
    'sodium' => $product['sodium'] ?? '15mg'
  ];
  
  // Mock storage instructions - in real implementation, this would come from database
  $storageInstructions = $product['storage_instructions'] ?? 'Store in a cool, dry place away from direct sunlight.';
  
  // Product rating data (mock)
  $averageRating = $product['rating'] ?? 4.2;
  $totalReviews = $product['total_reviews'] ?? 127;
  
  ?>
  <section class="mt-8 mb-5">
    <div class="container">
      <ol class="breadcrumb justify-content-center">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url() . 'product/category/' . urlencode($product['category']); ?>"><?php echo ucfirst($product['category']); ?></a></li>
        <?php if (!empty($product['subcategory'])): ?>
        <li class="breadcrumb-item"><a href="<?php echo base_url() . 'product/category/' . urlencode($product['category']) . '/' . urlencode($product['subcategory']); ?>"><?php echo ucfirst($product['subcategory']); ?></a></li>
        <?php endif; ?>
        <li class="breadcrumb-item active"><?php echo $product['pname']; ?></li>
      </ol>
    </div>
  </section>

  <section>

    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <!-- Image Zoom Container -->
          <div class="product-image-container position-relative">
            <div class="product-main-image mb-3">
              <img src="<?php echo base_url() . $product['pimage']; ?>" 
                   class="img-fluid product-zoom-image" 
                   id="mainProductImage"
                   alt="<?php echo htmlspecialchars($product['pname']); ?>">
              <div class="image-zoom-overlay" id="imageZoomOverlay">
                <div class="zoom-lens" id="zoomLens"></div>
              </div>
            </div>
            
            <!-- Image Thumbnails -->
            <div class="product-thumbnails d-flex">
              <div class="thumbnail-item active mr-2">
                <img src="<?php echo base_url() . $product['pimage']; ?>" 
                     class="img-thumbnail thumbnail-img" 
                     data-large="<?php echo base_url() . $product['pimage']; ?>">
              </div>
              <div class="thumbnail-item mr-2">
                <img src="<?php echo base_url() . $product['pimage']; ?>" 
                     class="img-thumbnail thumbnail-img" 
                     data-large="<?php echo base_url() . $product['pimage']; ?>">
              </div>
              <div class="thumbnail-item mr-2">
                <img src="<?php echo base_url() . $product['pimage']; ?>" 
                     class="img-thumbnail thumbnail-img" 
                     data-large="<?php echo base_url() . $product['pimage']; ?>">
              </div>
            </div>
            
            <!-- Magnified Image Container -->
            <div class="magnified-image-container" id="magnifiedImageContainer">
              <img src="<?php echo base_url() . $product['pimage']; ?>" 
                   class="magnified-image" id="magnifiedImage">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div>
            <!-- Product Name -->
            <p class="h3 workFont"><?php echo $product['pname']; ?></p>
            <!-- Brand -->
            <?php if (!empty($brand)): ?>
            <p class="text-muted mb-1">by <strong><?php echo $brand; ?></strong></p>
            <?php endif; ?>
            <!-- Weight/Unit -->
            <p class="text-info font-weight-bold"><?php echo $weight . ' ' . $unit; ?></p>
          </div>
          
          <!-- Product Information Cards -->
          <div class="row mt-3">
            <!-- Stock Status -->
            <div class="col-6">
              <div class="card border-0 bg-light">
                <div class="card-body p-2 text-center">
                  <small class="text-muted">Availability</small>
                  <?php if (isset($stockDetails) && $stockDetails): ?>
                  <div class="<?php echo $stockDetails['css_class']; ?> font-weight-bold">
                    <?php echo $stockDetails['message']; ?>
                  </div>
                  <?php if ($stockDetails['status'] === 'very_low_stock' || $stockDetails['status'] === 'low_stock'): ?>
                  <small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Limited Stock</small>
                  <?php endif; ?>
                  <?php else: ?>
                  <div class="<?php echo $stockQuantity > 0 ? 'text-success' : 'text-danger'; ?> font-weight-bold">
                    <?php echo $stockQuantity > 0 ? 'In Stock: ' . $stockQuantity . ' units' : 'Out of Stock'; ?>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            
            <!-- Expiry Date -->
            <?php if (!empty($expiryDate) || (isset($expiryDetails) && $expiryDetails)): ?>
            <div class="col-6">
              <div class="card border-0 bg-light">
                <div class="card-body p-2 text-center">
                  <small class="text-muted">Best Before</small>
                  <?php if (isset($expiryDetails) && $expiryDetails): ?>
                  <div class="<?php echo $expiryDetails['css_class']; ?> font-weight-bold">
                    <?php echo $expiryDetails['formatted_date']; ?>
                  </div>
                  <?php if ($expiryDetails['status'] === 'expiring_urgent' || $expiryDetails['status'] === 'expiring_soon'): ?>
                  <small class="<?php echo $expiryDetails['css_class']; ?>">
                    <i class="fas fa-clock"></i> <?php echo $expiryDetails['message']; ?>
                  </small>
                  <?php endif; ?>
                  <?php elseif (!empty($expiryDate)): ?>
                  <div class="<?php echo (strtotime($expiryDate) - time()) < (7 * 24 * 60 * 60) ? 'text-warning' : 'text-success'; ?> font-weight-bold">
                    <?php echo date('d M Y', strtotime($expiryDate)); ?>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php endif; ?>
          </div>
          
          <!-- Stock and Expiry Alerts -->
          <?php if (isset($stockDetails) && ($stockDetails['status'] === 'very_low_stock' || $stockDetails['status'] === 'low_stock')): ?>
          <div class="alert alert-warning mt-3" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Limited Stock!</strong> Hurry up! Only <?php echo $stockDetails['stock_quantity']; ?> units left in stock.
          </div>
          <?php endif; ?>
          
          <?php if (isset($expiryDetails) && ($expiryDetails['status'] === 'expiring_urgent' || $expiryDetails['status'] === 'expiring_soon')): ?>
          <div class="alert <?php echo $expiryDetails['status'] === 'expiring_urgent' ? 'alert-danger' : 'alert-warning'; ?> mt-3" role="alert">
            <i class="fas fa-clock"></i>
            <strong>Expires Soon!</strong> This product expires in <?php echo $expiryDetails['days_to_expiry']; ?> days. Order now for maximum freshness!
          </div>
          <?php endif; ?>
          
          <!-- Price Section -->
          <div class="mt-4 mb-3">
            <div class="d-flex align-items-center">
              <h1 class="mb-0"><i class="fas fa-rupee-sign"></i><?php echo number_format($finalPrice, 2); ?></h1>
              <?php if ($discount > 0): ?>
              <span class="ml-3">
                <s class="text-muted h5">₹<?php echo number_format($originalPrice, 2); ?></s>
                <span class="text-success font-weight-bold ml-2"><?php echo $discount; ?>% OFF</span>
              </span>
              <?php endif; ?>
            </div>
            <p class="text-muted small">Price per <?php echo $unit; ?> | Inclusive of all taxes</p>
            <?php if ($weight > 1 && in_array($unit, ['kg', 'litre'])): ?>
            <p class="text-info small">₹<?php echo number_format($finalPrice / $weight, 2); ?> per <?php echo $unit == 'kg' ? 'kg' : 'litre'; ?></p>
            <?php endif; ?>
          </div>
          
          <!-- Add to Cart Form -->
          <?php 
          $canAddToCart = $stockQuantity > 0;
          if (isset($stockDetails)) {
              $canAddToCart = $stockDetails['stock_quantity'] > 0;
          }
          ?>
          <?php if ($canAddToCart): ?>
          <form action="<?php echo base_url() . 'shopping/addToCart/'; ?>" method="POST" id="addToCartForm">
            <div class="row">
              <div class="col-sm-12 col-lg-12 detail-option mt-3">
                <h5 class="detail-option-heading">Quantity</h5>
                <div class="d-flex align-items-center mb-3">
                  <label for="quantity" class="sr-only">Quantity</label>
                  <button type="button" class="btn btn-outline-secondary" id="decreaseQty">-</button>
                  <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $stockQuantity; ?>" class="form-control text-center mx-2" style="width: 80px;">
                  <button type="button" class="btn btn-outline-secondary" id="increaseQty">+</button>
                  <span class="ml-3 text-muted">Max: <?php echo $stockQuantity; ?></span>
                </div>
                
                <input type="hidden" name="pid" value="<?php echo $product['pid']; ?>" id="pid">
                <input type="hidden" name="price" value="<?php echo $finalPrice; ?>" id="price">

                <div class="mt-4">
                  <h6 class="font-weight-light">Check COD Availability</h6>
                  <label for="pincode">
                    <input type="number" class="form-control mt-2" placeholder="Enter Pincode" name ="pincode" id="pincode" minlength="6" maxlength="6">
                  </label>
                 <div id ="cod"></div>
                </div>
              </div>
            </div>

            <div>
              <button type="submit" class="btn btn-lg btn-primary text-uppercase mt-5 btn-block" id="addToCart">
                <i class="fa fa-shopping-cart mr-2"></i>Add to Cart
              </button>
            </div>
          </form>
          <?php else: ?>
          <div class="mt-5">
            <button class="btn btn-lg btn-secondary text-uppercase btn-block" disabled>
              Out of Stock
            </button>
            <p class="text-center mt-2 text-muted small">This product is currently unavailable</p>
          </div>
          <?php endif; ?>
        </div>

      </div>
      
      <!-- Product Details Tabs -->
      <div class="row mt-5">
        <div class="col-12">
          <nav>
            <div class="nav nav-tabs" id="product-tabs" role="tablist">
              <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">
                <i class="fas fa-info-circle mr-2"></i>Description
              </a>
              <a class="nav-link" id="nutrition-tab" data-toggle="tab" href="#nutrition" role="tab" aria-controls="nutrition" aria-selected="false">
                <i class="fas fa-apple-alt mr-2"></i>Nutrition Facts
              </a>
              <a class="nav-link" id="storage-tab" data-toggle="tab" href="#storage" role="tab" aria-controls="storage" aria-selected="false">
                <i class="fas fa-box mr-2"></i>Storage
              </a>
              <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">
                <i class="fas fa-star mr-2"></i>Reviews (<?php echo $totalReviews; ?>)
              </a>
            </div>
          </nav>
          
          <div class="tab-content" id="product-tab-content">
            <!-- Description Tab -->
            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
              <div class="p-4">
                <h5>Product Description</h5>
                <p class="text-muted">
                  <?php echo !empty($description) ? $description : 'Premium quality ' . $product['pname'] . ' sourced from the finest producers. Perfect for your daily needs with guaranteed freshness and quality.'; ?>
                </p>
                
                <div class="row mt-4">
                  <div class="col-md-6">
                    <h6>Product Details</h6>
                    <ul class="list-unstyled">
                      <li><strong>Category:</strong> <?php echo ucfirst($category); ?></li>
                      <?php if (!empty($subcategory)): ?>
                      <li><strong>Subcategory:</strong> <?php echo ucfirst($subcategory); ?></li>
                      <?php endif; ?>
                      <?php if (!empty($brand)): ?>
                      <li><strong>Brand:</strong> <?php echo $brand; ?></li>
                      <?php endif; ?>
                      <li><strong>Weight/Quantity:</strong> <?php echo $weight . ' ' . $unit; ?></li>
                      <li><strong>Shelf Life:</strong> <?php echo !empty($expiryDate) ? 'Best before ' . date('M d, Y', strtotime($expiryDate)) : 'Check packaging'; ?></li>
                    </ul>
                  </div>
                  <div class="col-md-6">
                    <h6>Quality Assurance</h6>
                    <div class="quality-badges">
                      <span class="badge badge-success mr-2 mb-2"><i class="fas fa-check"></i> Fresh</span>
                      <span class="badge badge-primary mr-2 mb-2"><i class="fas fa-award"></i> Premium Quality</span>
                      <span class="badge badge-info mr-2 mb-2"><i class="fas fa-truck"></i> Fast Delivery</span>
                      <span class="badge badge-warning mr-2 mb-2"><i class="fas fa-undo"></i> Easy Returns</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Nutrition Facts Tab -->
            <div class="tab-pane fade" id="nutrition" role="tabpanel" aria-labelledby="nutrition-tab">
              <div class="p-4">
                <h5>Nutrition Facts</h5>
                <p class="text-muted small">Per <?php echo $weight . ' ' . $unit; ?> serving</p>
                
                <div class="nutrition-label bg-white border p-3 mt-3">
                  <div class="nutrition-title h6 font-weight-bold border-bottom pb-2 mb-3">Nutrition Facts</div>
                  
                  <div class="nutrition-row d-flex justify-content-between border-bottom py-2">
                    <span class="font-weight-bold">Calories</span>
                    <span><?php echo $nutritionalInfo['calories']; ?></span>
                  </div>
                  
                  <div class="nutrition-section mt-3">
                    <div class="nutrition-row d-flex justify-content-between py-1">
                      <span>Total Fat</span>
                      <span><?php echo $nutritionalInfo['fat']; ?></span>
                    </div>
                    <div class="nutrition-row d-flex justify-content-between py-1">
                      <span>Protein</span>
                      <span><?php echo $nutritionalInfo['protein']; ?></span>
                    </div>
                    <div class="nutrition-row d-flex justify-content-between py-1">
                      <span>Total Carbohydrates</span>
                      <span><?php echo $nutritionalInfo['carbs']; ?></span>
                    </div>
                    <div class="nutrition-row d-flex justify-content-between py-1 ml-3">
                      <span>Dietary Fiber</span>
                      <span><?php echo $nutritionalInfo['fiber']; ?></span>
                    </div>
                    <div class="nutrition-row d-flex justify-content-between py-1">
                      <span>Sodium</span>
                      <span><?php echo $nutritionalInfo['sodium']; ?></span>
                    </div>
                  </div>
                </div>
                
                <div class="mt-3">
                  <small class="text-muted">*Percent Daily Values are based on a 2,000 calorie diet. Your daily values may be higher or lower depending on your calorie needs.</small>
                </div>
              </div>
            </div>
            
            <!-- Storage Instructions Tab -->
            <div class="tab-pane fade" id="storage" role="tabpanel" aria-labelledby="storage-tab">
              <div class="p-4">
                <h5>Storage Instructions</h5>
                
                <div class="row mt-3">
                  <div class="col-md-6">
                    <div class="card border-0 bg-light mb-3">
                      <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-thermometer-half text-primary mr-2"></i>Storage Temperature</h6>
                        <p class="card-text text-muted">
                          <?php 
                          $isPerishable = in_array(strtolower($category), ['fruits-vegetables', 'dairy-products', 'meat-seafood']);
                          echo $isPerishable ? 'Store in refrigerator at 2-8°C (36-46°F)' : 'Store at room temperature (15-25°C)'; 
                          ?>
                        </p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="card border-0 bg-light mb-3">
                      <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-clock text-info mr-2"></i>Shelf Life</h6>
                        <p class="card-text text-muted">
                          <?php if (!empty($expiryDate)): ?>
                          Best before <?php echo date('M d, Y', strtotime($expiryDate)); ?>
                          <?php else: ?>
                          Check packaging for expiry date
                          <?php endif; ?>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="storage-instructions">
                  <h6>Detailed Storage Guidelines</h6>
                  <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    <?php echo $storageInstructions; ?>
                  </div>
                  
                  <ul class="list-unstyled mt-3">
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Keep in original packaging until use</li>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Avoid exposure to direct sunlight</li>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Keep away from strong odors</li>
                    <?php if ($isPerishable): ?>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Use within recommended period after opening</li>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Do not freeze unless specified</li>
                    <?php endif; ?>
                  </ul>
                </div>
              </div>
            </div>
            
            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
              <div class="p-4">
                <!-- Rating Summary -->
                <div class="row mb-4">
                  <div class="col-md-4">
                    <div class="text-center">
                      <div class="rating-average h2 text-warning mb-2">
                        <?php echo number_format($averageRating, 1); ?>
                        <i class="fas fa-star"></i>
                      </div>
                      <div class="rating-stars mb-2">
                        <?php 
                        for ($i = 1; $i <= 5; $i++) {
                          if ($i <= floor($averageRating)) {
                            echo '<i class="fas fa-star text-warning"></i>';
                          } elseif ($i == ceil($averageRating) && $averageRating > floor($averageRating)) {
                            echo '<i class="fas fa-star-half-alt text-warning"></i>';
                          } else {
                            echo '<i class="far fa-star text-muted"></i>';
                          }
                        }
                        ?>
                      </div>
                      <small class="text-muted">Based on <?php echo $totalReviews; ?> reviews</small>
                    </div>
                  </div>
                  
                  <div class="col-md-8">
                    <div class="rating-breakdown">
                      <?php 
                      // Mock rating distribution
                      $ratingDist = [5 => 68, 4 => 24, 3 => 5, 2 => 2, 1 => 1];
                      foreach ($ratingDist as $stars => $percentage): 
                      ?>
                      <div class="d-flex align-items-center mb-2">
                        <span class="rating-star-count"><?php echo $stars; ?></span>
                        <i class="fas fa-star text-warning mx-2"></i>
                        <div class="progress flex-grow-1 mx-2" style="height: 8px;">
                          <div class="progress-bar bg-warning" style="width: <?php echo $percentage; ?>%"></div>
                        </div>
                        <small class="text-muted"><?php echo $percentage; ?>%</small>
                      </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>
                
                <!-- Individual Reviews -->
                <div class="reviews-list">
                  <h6>Customer Reviews</h6>
                  
                  <?php 
                  // Mock reviews data
                  $mockReviews = [
                    [
                      'name' => 'Priya Sharma',
                      'rating' => 5,
                      'date' => '2025-01-15',
                      'title' => 'Excellent quality!',
                      'comment' => 'Very fresh and good quality. Delivered on time and packaging was perfect.'
                    ],
                    [
                      'name' => 'Rajesh Kumar',
                      'rating' => 4,
                      'date' => '2025-01-12',
                      'title' => 'Good value for money',
                      'comment' => 'Good product at reasonable price. Will order again.'
                    ],
                    [
                      'name' => 'Anita Verma',
                      'rating' => 5,
                      'date' => '2025-01-10',
                      'title' => 'Highly recommended',
                      'comment' => 'Best quality I have found online. Fast delivery and great customer service.'
                    ]
                  ];
                  
                  foreach ($mockReviews as $review): 
                  ?>
                  <div class="review-item border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <div>
                        <h6 class="mb-1"><?php echo $review['name']; ?></h6>
                        <div class="review-rating">
                          <?php 
                          for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $review['rating']) {
                              echo '<i class="fas fa-star text-warning"></i>';
                            } else {
                              echo '<i class="far fa-star text-muted"></i>';
                            }
                          }
                          ?>
                        </div>
                      </div>
                      <small class="text-muted"><?php echo date('M d, Y', strtotime($review['date'])); ?></small>
                    </div>
                    <h6 class="review-title"><?php echo $review['title']; ?></h6>
                    <p class="review-comment text-muted mb-2"><?php echo $review['comment']; ?></p>
                    <div class="review-actions">
                      <small class="text-muted mr-3">
                        <i class="fas fa-thumbs-up mr-1"></i> Helpful (<?php echo rand(1, 10); ?>)
                      </small>
                      <small class="text-muted">
                        <i class="fas fa-reply mr-1"></i> Reply
                      </small>
                    </div>
                  </div>
                  <?php endforeach; ?>
                  
                  <!-- Write Review Button -->
                  <div class="text-center mt-4">
                    <button class="btn btn-outline-primary" data-toggle="modal" data-target="#writeReviewModal">
                      <i class="fas fa-edit mr-2"></i>Write a Review
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Similar Products Section -->
  <?php if (isset($relatedProducts) && !empty($relatedProducts)): ?>
  <section class="py-5 bg-light">
    <div class="container">
      <h3 class="text-center mb-5">Similar Products</h3>
      <div class="row">
        <?php 
        $similarProducts = array_slice($relatedProducts, 0, 4); // Show only 4 similar products
        foreach ($similarProducts as $similar): 
          if ($similar['pid'] != $product['pid']): // Don't show the same product
        ?>
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="card product-card h-100 border-0 shadow-sm">
            <div class="product-image-container position-relative">
              <img src="<?php echo base_url() . $similar['pimage']; ?>" 
                   class="card-img-top product-image" 
                   alt="<?php echo htmlspecialchars($similar['pname']); ?>">
              <?php if (!empty($similar['discount']) && $similar['discount'] > 0): ?>
              <span class="discount-badge"><?php echo $similar['discount']; ?>% OFF</span>
              <?php endif; ?>
              <div class="product-overlay">
                <a href="<?php echo base_url('product/index/' . $similar['pid']); ?>" class="btn btn-primary btn-sm">
                  <i class="fas fa-eye mr-1"></i> View
                </a>
              </div>
            </div>
            <div class="card-body">
              <h6 class="card-title"><?php echo $similar['pname']; ?></h6>
              <p class="text-muted small mb-2">
                <?php echo $similar['weight'] . ' ' . $similar['unit']; ?>
                <?php if (!empty($similar['brand'])): ?>
                • <?php echo $similar['brand']; ?>
                <?php endif; ?>
              </p>
              <div class="price-section">
                <?php if (!empty($similar['discount']) && $similar['discount'] > 0): ?>
                <span class="current-price font-weight-bold text-primary">
                  ₹<?php echo number_format($similar['price'] * (1 - $similar['discount'] / 100), 2); ?>
                </span>
                <span class="original-price text-muted small ml-2">
                  <s>₹<?php echo number_format($similar['price'], 2); ?></s>
                </span>
                <?php else: ?>
                <span class="current-price font-weight-bold text-primary">
                  ₹<?php echo number_format($similar['price'], 2); ?>
                </span>
                <?php endif; ?>
              </div>
              <div class="stock-info mt-2">
                <?php if ($similar['stock_quantity'] > 0): ?>
                <small class="text-success"><i class="fas fa-check"></i> In Stock</small>
                <?php else: ?>
                <small class="text-danger"><i class="fas fa-times"></i> Out of Stock</small>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <?php 
          endif;
        endforeach; 
        ?>
      </div>
    </div>
  </section>
  <?php endif; ?>
<?php
}
//  echo $abc;
// echo "<br>";
// $arr = implode($abc);
// print_r($size);

//print_r($arr);

?>



<script>
// Quantity controls
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const maxStock = <?php echo $stockQuantity; ?>;
    
    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updatePriceDisplay();
            }
        });
    }
    
    if (increaseBtn) {
        increaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < maxStock) {
                quantityInput.value = currentValue + 1;
                updatePriceDisplay();
            }
        });
    }
    
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            if (value < 1) {
                this.value = 1;
            } else if (value > maxStock) {
                this.value = maxStock;
                alert('Maximum available quantity is ' + maxStock);
            }
            updatePriceDisplay();
        });
    }
    
    function updatePriceDisplay() {
        // This function can be enhanced to show total price for selected quantity
        const quantity = parseInt(quantityInput.value);
        const basePrice = <?php echo $finalPrice; ?>;
        const totalPrice = (basePrice * quantity).toFixed(2);
        
        // Update any price display elements if needed
        console.log('Total price for ' + quantity + ' items: ₹' + totalPrice);
    }
});

// Pincode checking
let pincode = document.querySelector('#pincode');

function checkPinCode()
{
    let pincodeValue = pincode.value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url().'shopping/checkPinCode/'; ?>',
        dataType: "JSON",
        data: {
            pincode : pincodeValue
        },
        success: function(data) {
            JSON.stringify(data);
            $('#cod').html(data.text);
        },
        error: function(jqXhr, textStatus, errorMessage) {
            console.log("Error: ", errorMessage);
        }
    });
}      

if (pincode) {
    pincode.addEventListener('keyup', checkPinCode);
}

// Simple and reliable cart functionality using jQuery
$(document).ready(function() {
    $('#addToCartForm').on('submit', function(e) {
        e.preventDefault();
        
        const stockAvailable = <?php echo $stockQuantity; ?>;
        const quantity = parseInt($('#quantity').val());
        
        if (quantity > stockAvailable) {
            alert('Insufficient stock! Only ' + stockAvailable + ' items available.');
            return;
        }
        
        // Show loading state
        const submitBtn = $('#addToCart');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-2"></i>Adding...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: {
                product_id: $('#pid').val(),
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message - simple alert for now
                    alert('✓ ' + response.message);
                    
                    // Update cart count if function exists
                    if (typeof window.refreshCartCount === 'function') {
                        window.refreshCartCount();
                    }
                } else {
                    alert('✗ ' + (response.message || 'Failed to add product to cart'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Add to cart error:', error);
                alert('An error occurred. Please try again.');
            },
            complete: function() {
                // Reset button state
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});

// Function to update cart count (if you have a cart counter in header)
function updateCartCount() {
    $.get('<?php echo base_url("shopping/getCartItemCount"); ?>', function(data) {
        if (data.count !== undefined) {
            $('.cart-count').text(data.count); // Update cart counter element
        }
    }, 'json');
}

// Image Zoom Functionality
const productZoom = {
    init: function() {
        const mainImage = document.getElementById('mainProductImage');
        const zoomOverlay = document.getElementById('imageZoomOverlay');
        const zoomLens = document.getElementById('zoomLens');
        const magnifiedContainer = document.getElementById('magnifiedImageContainer');
        const magnifiedImage = document.getElementById('magnifiedImage');
        const thumbnails = document.querySelectorAll('.thumbnail-img');
        
        if (!mainImage || !zoomOverlay || !zoomLens || !magnifiedContainer || !magnifiedImage) {
            return;
        }
        
        // Thumbnail switching
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                const newImageSrc = this.dataset.large;
                mainImage.src = newImageSrc;
                magnifiedImage.src = newImageSrc;
                
                // Update active thumbnail
                document.querySelector('.thumbnail-item.active').classList.remove('active');
                this.parentElement.classList.add('active');
            });
        });
        
        // Zoom functionality
        let isZooming = false;
        
        mainImage.addEventListener('mouseenter', function() {
            isZooming = true;
            zoomOverlay.style.display = 'block';
            magnifiedContainer.style.display = 'block';
        });
        
        mainImage.addEventListener('mouseleave', function() {
            isZooming = false;
            zoomOverlay.style.display = 'none';
            magnifiedContainer.style.display = 'none';
        });
        
        mainImage.addEventListener('mousemove', function(e) {
            if (!isZooming) return;
            
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            // Calculate lens position
            const lensSize = 100;
            const lensX = Math.max(0, Math.min(x - lensSize/2, rect.width - lensSize));
            const lensY = Math.max(0, Math.min(y - lensSize/2, rect.height - lensSize));
            
            zoomLens.style.left = lensX + 'px';
            zoomLens.style.top = lensY + 'px';
            
            // Calculate magnified image position
            const zoomLevel = 2;
            const magnifiedX = -(x * zoomLevel - magnifiedContainer.offsetWidth / 2);
            const magnifiedY = -(y * zoomLevel - magnifiedContainer.offsetHeight / 2);
            
            magnifiedImage.style.transform = `scale(${zoomLevel}) translate(${magnifiedX/zoomLevel}px, ${magnifiedY/zoomLevel}px)`;
        });
    }
};

// Initialize zoom functionality
productZoom.init();

</script>

<!-- Write Review Modal -->
<div class="modal fade" id="writeReviewModal" tabindex="-1" role="dialog" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="writeReviewModalLabel">
          <i class="fas fa-star mr-2"></i>Write a Review
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="reviewForm">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="reviewerName">Your Name *</label>
                <input type="text" class="form-control" id="reviewerName" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="reviewerEmail">Email Address *</label>
                <input type="email" class="form-control" id="reviewerEmail" required>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <label>Rating *</label>
            <div class="star-rating" id="starRating">
              <i class="far fa-star" data-rating="1"></i>
              <i class="far fa-star" data-rating="2"></i>
              <i class="far fa-star" data-rating="3"></i>
              <i class="far fa-star" data-rating="4"></i>
              <i class="far fa-star" data-rating="5"></i>
              <input type="hidden" id="ratingValue" name="rating" value="0">
            </div>
            <small class="form-text text-muted">Click on stars to rate this product</small>
          </div>
          
          <div class="form-group">
            <label for="reviewTitle">Review Title *</label>
            <input type="text" class="form-control" id="reviewTitle" placeholder="Give your review a title" required>
          </div>
          
          <div class="form-group">
            <label for="reviewComment">Your Review *</label>
            <textarea class="form-control" id="reviewComment" rows="4" placeholder="Share your experience with this product" required></textarea>
            <small class="form-text text-muted">Minimum 10 characters</small>
          </div>
          
          <div class="form-group">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="recommendProduct">
              <label class="form-check-label" for="recommendProduct">
                I would recommend this product to others
              </label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="submitReview">
          <i class="fas fa-paper-plane mr-2"></i>Submit Review
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// Star Rating System for Review Modal
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('#starRating i');
    const ratingValue = document.getElementById('ratingValue');
    
    stars.forEach((star, index) => {
        star.addEventListener('mouseenter', function() {
            highlightStars(index + 1);
        });
        
        star.addEventListener('mouseleave', function() {
            const currentRating = parseInt(ratingValue.value);
            highlightStars(currentRating);
        });
        
        star.addEventListener('click', function() {
            const rating = index + 1;
            ratingValue.value = rating;
            highlightStars(rating);
        });
    });
    
    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('far');
                star.classList.add('fas', 'text-warning');
            } else {
                star.classList.remove('fas', 'text-warning');
                star.classList.add('far');
            }
        });
    }
    
    // Submit Review
    document.getElementById('submitReview').addEventListener('click', function() {
        const form = document.getElementById('reviewForm');
        const formData = new FormData(form);
        
        // Basic validation
        const name = document.getElementById('reviewerName').value.trim();
        const email = document.getElementById('reviewerEmail').value.trim();
        const rating = document.getElementById('ratingValue').value;
        const title = document.getElementById('reviewTitle').value.trim();
        const comment = document.getElementById('reviewComment').value.trim();
        
        if (!name || !email || !rating || rating === '0' || !title || !comment) {
            alert('Please fill in all required fields and provide a rating.');
            return;
        }
        
        if (comment.length < 10) {
            alert('Review must be at least 10 characters long.');
            return;
        }
        
        // Simulate review submission
        const submitBtn = this;
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
        
        setTimeout(() => {
            alert('Thank you for your review! It will be published after moderation.');
            $('#writeReviewModal').modal('hide');
            form.reset();
            ratingValue.value = '0';
            highlightStars(0);
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }, 2000);
        
        // In real implementation, you would submit this via AJAX to your backend
        // $.ajax({
        //     url: '<?php echo base_url("product/submitReview"); ?>',
        //     method: 'POST',
        //     data: {
        //         product_id: <?php echo $product['pid']; ?>,
        //         name: name,
        //         email: email,
        //         rating: rating,
        //         title: title,
        //         comment: comment,
        //         recommend: document.getElementById('recommendProduct').checked
        //     },
        //     success: function(response) {
        //         // Handle success
        //     },
        //     error: function() {
        //         // Handle error
        //     }
        // });
    });
});
</script>
