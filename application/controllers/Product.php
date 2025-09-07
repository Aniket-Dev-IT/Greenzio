<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('products');
        $this->load->model('nutrition');
        $this->load->model('product_substitution');
        $this->load->model('cart');
    }

    public function index($id){
        $pid = $this->uri->segment(3);
        
        $productDetail['productDetail'] = $this->products->getProductByID($pid);
        
        if (!empty($productDetail['productDetail'])) {
            $product = $productDetail['productDetail'][0];
            
            // Add enhanced grocery-specific data
            $productDetail['weightDisplay'] = $this->products->getProductWeight($pid);
            $productDetail['stockDetails'] = $this->products->getStockDetails($pid);
            $productDetail['expiryDetails'] = $this->products->getExpiryDetails($pid);
            $productDetail['pricePerUnit'] = $this->calculateDisplayPrice($product);
            
            // Get nutritional information (mock data for demo)
            $productDetail['nutritionalInfo'] = $this->getNutritionalInfo($pid);
            
            // Get storage instructions based on product category
            $productDetail['storageInstructions'] = $this->getStorageInstructions($product['category']);
            
            // Get customer reviews and ratings (mock data for demo)
            $productDetail['reviews'] = $this->getProductReviews($pid);
            $productDetail['averageRating'] = $this->getAverageRating($pid);
            $productDetail['totalReviews'] = $this->getTotalReviews($pid);
            
            // Legacy compatibility
            $productDetail['stockStatus'] = $this->products->checkStock($pid);
            $productDetail['isExpiringSoon'] = $this->checkIfExpiringSoon($product['expiry_date']);
            
            // Get related products by category or brand
            $category = $product['category'];
            $productDetail['relatedProducts'] = $this->products->productsByCategory($category);
            
            $this->load->view('main/header');
            $this->load->view('product/detail', $productDetail);
            $this->load->view('main/footer');
        } else {
            // Product not found, redirect to home or show 404
            redirect('/');
        }
    }
    
    /**
     * Show all products with enhanced grocery filtering
     */
    public function listing(){
        $category = $this->input->get('category');
        $subcategory = $this->input->get('subcategory');
        $search_term = $this->input->get('search');
        
        // Get products based on filters
        if (!empty($category)) {
            $data['products'] = $this->products->productsByCategory($category, $subcategory);
        } elseif (!empty($search_term)) {
            $data['products'] = $this->products->searchProducts($search_term);
        } else {
            $data['products'] = $this->products->getAllProducts();
        }
        
        // Prepare filter data
        $data['categories'] = $this->products->getAllCategories();
        $data['brands'] = $this->products->getBrands();
        $data['category'] = $category;
        $data['subcategory'] = $subcategory;
        $data['search_term'] = $search_term;
        
        $this->load->view('main/header');
        $this->load->view('product/index', $data);
        $this->load->view('main/footer');
    }
    
    // New grocery-specific methods
    public function category($category, $subcategory = null){
        $data['products'] = $this->products->productsByCategory($category, $subcategory);
        $data['category'] = $category;
        $data['subcategory'] = $subcategory;
        $data['subcategories'] = $this->products->getSubcategories($category);
        $data['brands'] = $this->products->getBrands($category);
        
        $this->load->view('main/header');
        $this->load->view('product/index', $data);
        $this->load->view('main/footer');
    }
    
    public function search(){
        $searchTerm = $this->input->get('q');
        $category = $this->input->get('category');
        $brand = $this->input->get('brand');
        $sort = $this->input->get('sort');
        $priceRange = null;
        
        if ($this->input->get('min_price') || $this->input->get('max_price')) {
            $priceRange = [
                'min' => $this->input->get('min_price'),
                'max' => $this->input->get('max_price')
            ];
        }
        
        $data['products'] = $this->products->searchProducts($searchTerm, $category, $brand, $priceRange, $sort);
        $data['searchTerm'] = $searchTerm;
        $data['categories'] = $this->products->getAllCategories();
        $data['brands'] = $this->products->getBrands();
        
        $this->load->view('main/header');
        $this->load->view('pages/search_results', $data);
        $this->load->view('main/footer');
    }
    
    /**
     * AJAX endpoint for search filtering
     */
    public function searchFilter(){
        $searchTerm = $this->input->post('search_term');
        $categories = $this->input->post('categories');
        $brands = $this->input->post('brands');
        $units = $this->input->post('units');
        $stockFilters = $this->input->post('stock_filters');
        $sortBy = $this->input->post('sort_by');
        $minPrice = $this->input->post('min_price');
        $maxPrice = $this->input->post('max_price');
        
        $filters = [
            'search_term' => $searchTerm,
            'categories' => $categories,
            'brands' => $brands,
            'units' => $units,
            'stock_filters' => $stockFilters,
            'sort_by' => $sortBy,
            'price_range' => [
                'min' => $minPrice,
                'max' => $maxPrice
            ]
        ];
        
        $products = $this->products->advancedSearchFilter($filters);
        
        $output = '';
        $i = 0;
        
        if (!empty($products)) {
            foreach ($products as $product) {
                $i++;
                $id = $product['pid'];
                $stockBadge = '';
                
                // Generate stock badge
                if (isset($product['stock_quantity'])) {
                    $stock = $product['stock_quantity'];
                    if ($stock <= 0) {
                        $stockBadge = '<span class="stock-badge out-of-stock">Out of Stock</span>';
                    } elseif ($stock <= 5) {
                        $stockBadge = '<span class="stock-badge low-stock">Only ' . $stock . ' left</span>';
                    } elseif ($stock <= 10) {
                        $stockBadge = '<span class="stock-badge medium-stock">Limited Stock</span>';
                    }
                }
                
                $output .= '
                <div class="col-lg-4 my-4 product-item" 
                     data-price="' . $product['price'] . '" 
                     data-name="' . strtolower($product['pname']) . '"
                     data-category="' . $product['category'] . '"
                     data-brand="' . ($product['brand'] ?? '') . '"
                     data-stock="' . ($product['stock_quantity'] ?? 0) . '"
                     data-unit="' . ($product['unit'] ?? '') . '">
                    <div class="product-image">
                        <img src="' . base_url() . $product['pimage'] . '" 
                             class="pimage img-fluid" 
                             alt="' . htmlspecialchars($product['pname']) . '" 
                             loading="lazy">
                        ' . $stockBadge . '
                        <div class="product-hover-overlay">
                            <a href="' . base_url() . 'product/index/' . $id . '" class="product-hover-overlay-link"></a>
                            <div class="product-hover-overlay-buttons">
                                <a href="' . base_url() . 'product/index/' . $id . '" class="btn btn-outline-dark btn-buy">
                                    <i class="fa-search fa"></i>
                                    <span>View</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="py-2">
                        <p class="text-muted text-sm mb-1">
                            ' . htmlspecialchars($product['category'] ?? '') . '';
                            
                if (!empty($product['brand'])) {
                    $output .= ' • ' . htmlspecialchars($product['brand']);
                }
                
                $output .= '
                        </p>
                        <h3 class="h6 text-uppercase mb-1">
                            <a href="' . base_url() . 'product/index/' . $id . '" class="text-dark">
                                ' . htmlspecialchars($product['pname']) . '
                            </a>
                        </h3>
                        <div class="product-price">
                            <span class="text-dark font-weight-bold">
                                <i class="fas fa-rupee-sign"></i>
                                ' . number_format($product['price'], 2) . '
                            </span>';
                            
                if (!empty($product['weight']) && !empty($product['unit'])) {
                    $output .= '<small class="text-muted ml-2">per ' . $product['weight'] . ' ' . $product['unit'] . '</small>';
                }
                
                $output .= '
                        </div>
                    </div>
                </div>';
            }
        } else {
            $output = '
            <div class="col-12 text-center py-5">
                <div class="no-results-found">
                    <i class="fas fa-search fa-5x text-muted mb-4"></i>
                    <h3 class="h4 text-muted mb-3">No products found</h3>
                    <p class="text-muted mb-4">
                        We couldn\'t find any products matching your criteria.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <p class="text-muted mb-3">Try:</p>
                            <ul class="list-unstyled text-muted">
                                <li>• Using different keywords</li>
                                <li>• Checking the spelling</li>
                                <li>• Using more general terms</li>
                                <li>• Removing some filters</li>
                            </ul>
                        </div>
                    </div>
                    <a href="' . base_url() . '" class="btn btn-primary mt-3">
                        <i class="fas fa-home mr-2"></i>Back to Home
                    </a>
                </div>
            </div>';
        }
        
        $data = array('row' => $i, 'products' => $output);
        echo json_encode($data);
    }
    
    /**
     * AJAX endpoint for search suggestions
     */
    public function searchSuggestions(){
        $query = $this->input->get('q');
        
        if (strlen($query) < 2) {
            echo json_encode([]);
            return;
        }
        
        $suggestions = $this->products->getSearchSuggestions($query);
        echo json_encode($suggestions);
    }
    
    public function brand($brand){
        $data['products'] = $this->products->getProductsByBrand($brand);
        $data['brand'] = $brand;
        
        $this->load->view('main/header');
        $this->load->view('pages/brand_products', $data);
        $this->load->view('main/footer');
    }
    
    public function getProductInfo(){
        $pid = $this->input->post('pid');
        $quantity = $this->input->post('quantity', TRUE);
        
        if (!$quantity) $quantity = 1;
        
        $product = $this->products->getProductByID($pid);
        
        if (!empty($product)) {
            $productData = $product[0];
            
            $response = [
                'success' => true,
                'product' => $productData,
                'weight_display' => $this->products->getProductWeight($pid),
                'stock_status' => $this->products->checkStock($pid),
                'total_price' => $this->products->calculatePricePerUnit($pid, $quantity),
                'is_expiring_soon' => $this->checkIfExpiringSoon($productData['expiry_date'])
            ];
        } else {
            $response = ['success' => false, 'message' => 'Product not found'];
        }
        
        echo json_encode($response);
    }
    
    // Helper methods
    private function checkIfExpiringSoon($expiryDate, $days = 7){
        if (!$expiryDate) return false;
        
        $expiryTimestamp = strtotime($expiryDate);
        $warningTimestamp = strtotime("+{$days} days");
        
        return $expiryTimestamp <= $warningTimestamp;
    }
    
    private function calculateDisplayPrice($product){
        $price = $product['price'];
        $weight = $product['weight'];
        $unit = $product['unit'];
        $discount = $product['discount'];
        
        // Apply discount if available
        if ($discount > 0) {
            $price = $price * (1 - $discount / 100);
        }
        
        // Calculate per unit price display
        $displayPrice = [
            'original_price' => $product['price'],
            'discounted_price' => $price,
            'discount_percent' => $discount,
            'per_unit_price' => $weight > 0 ? $price / $weight : $price,
            'unit' => $unit,
            'weight' => $weight
        ];
        
        return $displayPrice;
    }
    
    /**
     * Get nutritional information for a product
     * In real implementation, this would come from database
     */
    private function getNutritionalInfo($pid){
        // Mock nutritional data - in real implementation, fetch from database
        // This could be stored in a separate nutrition table linked to products
        return [
            'calories' => '120',
            'protein' => '2.5g',
            'carbs' => '25g',
            'fat' => '1.2g',
            'fiber' => '3.1g',
            'sodium' => '15mg',
            'sugar' => '8g',
            'calcium' => '45mg',
            'iron' => '1.2mg',
            'vitamin_c' => '12mg'
        ];
    }
    
    /**
     * Get storage instructions based on product category
     */
    private function getStorageInstructions($category){
        $instructions = [
            'fruits-vegetables' => 'Store in refrigerator. Keep in original packaging or breathable bags. Best consumed within 3-7 days.',
            'dairy-products' => 'Keep refrigerated at 2-4°C. Use within expiry date. Do not freeze unless specified.',
            'grains-pulses' => 'Store in a cool, dry place in airtight containers. Keep away from moisture and pests.',
            'spices-condiments' => 'Store in a cool, dry place away from direct sunlight. Keep containers tightly sealed.',
            'snacks-beverages' => 'Store in a cool, dry place. Check individual product labels for specific storage requirements.',
            'personal-care' => 'Store in a cool, dry place. Keep away from direct sunlight and heat.',
            'household-items' => 'Store in a dry place away from children. Follow product-specific storage instructions.',
            'meat-seafood' => 'Keep frozen or refrigerated. Use within recommended timeframes. Thaw safely before cooking.'
        ];
        
        return isset($instructions[strtolower($category)]) ? 
               $instructions[strtolower($category)] : 
               'Store in a cool, dry place away from direct sunlight.';
    }
    
    /**
     * Get product reviews (mock data)
     * In real implementation, this would fetch from reviews table
     */
    private function getProductReviews($pid){
        // Mock review data - in real implementation, fetch from database
        return [
            [
                'id' => 1,
                'user_name' => 'Priya Sharma',
                'rating' => 5,
                'title' => 'Excellent quality!',
                'comment' => 'Very fresh and good quality. Delivered on time and packaging was perfect.',
                'date' => '2025-01-15',
                'helpful_count' => 8
            ],
            [
                'id' => 2,
                'user_name' => 'Rajesh Kumar',
                'rating' => 4,
                'title' => 'Good value for money',
                'comment' => 'Good product at reasonable price. Will order again.',
                'date' => '2025-01-12',
                'helpful_count' => 5
            ],
            [
                'id' => 3,
                'user_name' => 'Anita Verma',
                'rating' => 5,
                'title' => 'Highly recommended',
                'comment' => 'Best quality I have found online. Fast delivery and great customer service.',
                'date' => '2025-01-10',
                'helpful_count' => 12
            ]
        ];
    }
    
    /**
     * Get average rating for a product (mock data)
     */
    private function getAverageRating($pid){
        // Mock data - in real implementation, calculate from reviews table
        return 4.2;
    }
    
    /**
     * Get total number of reviews for a product (mock data)
     */
    private function getTotalReviews($pid){
        // Mock data - in real implementation, count from reviews table
        return 127;
    }
    
    /**
     * Submit a new review (AJAX endpoint)
     * This would be implemented to handle review submissions
     */
    public function submitReview(){
        $productId = $this->input->post('product_id');
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $rating = $this->input->post('rating');
        $title = $this->input->post('title');
        $comment = $this->input->post('comment');
        $recommend = $this->input->post('recommend');
        
        // Validation
        if (empty($productId) || empty($name) || empty($email) || empty($rating) || empty($title) || empty($comment)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            return;
        }
        
        if ($rating < 1 || $rating > 5) {
            echo json_encode(['success' => false, 'message' => 'Invalid rating']);
            return;
        }
        
        // In real implementation, save to database
        // $reviewData = [
        //     'product_id' => $productId,
        //     'user_name' => $name,
        //     'user_email' => $email,
        //     'rating' => $rating,
        //     'title' => $title,
        //     'comment' => $comment,
        //     'recommend' => $recommend ? 1 : 0,
        //     'status' => 'pending', // For moderation
        //     'created_at' => date('Y-m-d H:i:s')
        // ];
        // 
        // $this->db->insert('product_reviews', $reviewData);
        
        echo json_encode(['success' => true, 'message' => 'Review submitted successfully! It will be published after moderation.']);
    }
    
    /**
     * Get nutritional information for product (AJAX)
     */
    public function getNutrition() {
        $product_id = $this->input->get('product_id');
        
        if (!$product_id) {
            echo json_encode(['success' => false, 'message' => 'Product ID required']);
            return;
        }
        
        $nutrition = $this->nutrition->getFormattedNutritionFacts($product_id);
        $dietary_attributes = $this->nutrition->getDietaryAttributes($product_id);
        
        echo json_encode([
            'success' => true,
            'nutrition' => $nutrition,
            'dietary_attributes' => $dietary_attributes
        ]);
    }
    
    /**
     * Get product substitutions (AJAX)
     */
    public function getSubstitutions() {
        $product_id = $this->input->get('product_id');
        $limit = $this->input->get('limit') ?: 5;
        
        if (!$product_id) {
            echo json_encode(['success' => false, 'message' => 'Product ID required']);
            return;
        }
        
        $substitutions = $this->product_substitution->getSmartSubstitutions($product_id, $limit);
        
        echo json_encode([
            'success' => true,
            'substitutions' => $substitutions,
            'count' => count($substitutions)
        ]);
    }
    
    /**
     * Get bulk pricing information (AJAX)
     */
    public function getBulkPricing() {
        $product_id = $this->input->get('product_id');
        $quantity = $this->input->get('quantity') ?: 1;
        
        if (!$product_id) {
            echo json_encode(['success' => false, 'message' => 'Product ID required']);
            return;
        }
        
        $bulk_pricing = $this->cart->getBulkDiscountPrice($product_id, $quantity);
        $bulk_tiers = $this->cart->getBulkPricingTiers($product_id);
        
        echo json_encode([
            'success' => true,
            'bulk_pricing' => $bulk_pricing,
            'tiers' => $bulk_tiers
        ]);
    }
    
    /**
     * Compare products nutritionally
     */
    public function compare() {
        $product_ids = $this->input->get('products');
        
        if (!$product_ids) {
            redirect('product/listing');
            return;
        }
        
        $product_ids = is_array($product_ids) ? $product_ids : explode(',', $product_ids);
        
        if (count($product_ids) < 2) {
            $this->session->set_flashdata('error', 'Please select at least 2 products to compare');
            redirect('product/listing');
            return;
        }
        
        $data['comparison'] = $this->nutrition->compareNutrition($product_ids);
        $data['product_ids'] = $product_ids;
        
        $this->load->view('main/header');
        $this->load->view('pages/nutrition_compare', $data);
        $this->load->view('main/footer');
    }
}
