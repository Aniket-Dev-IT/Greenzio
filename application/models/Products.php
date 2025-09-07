<?php 

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

    class Products extends Base_model{

        protected $table = 'product';
        protected $primary_key = 'pid';
        protected $fillable = [
            'pname', 'category', 'subcategory', 'brand', 'price', 'discount',
            'weight', 'unit', 'stock_quantity', 'expiry_date', 'pimage', 'description'
        ];
        protected $timestamps = false;

        function __construct()
        {
            parent::__construct();
        }

        // Grocery-based category methods using Base_model functionality
        function productsByCategory($category, $subcategory = null, $limit = 0){
            $conditions = ['category' => $category, 'stock_quantity >' => 0];
            
            if (!empty($subcategory)) {
                $conditions['subcategory'] = $subcategory;
            }
            
            return $this->get_all($conditions, 'pname ASC', $limit);
        }

        function getSubcategories($category){
            $this->db->distinct();
            $this->db->select('subcategory');
            $this->db->from('product');
            $this->db->where('category', $category);
            $this->db->where('subcategory IS NOT NULL');
            $this->db->where('subcategory !=', '');
            $query = $this->db->get();
            return $query->result_array(); // This returns array with 'subcategory' key
        }

        function getBrands($category = null){
            $this->db->distinct();
            $this->db->select('brand');
            $this->db->from('product');
            if (!empty($category)) {
                $this->db->where('category', $category);
            }
            $this->db->where('brand IS NOT NULL');
            $this->db->where('brand !=', '');
            $query = $this->db->get();
            return $query->result_array(); // This returns array with 'brand' key
        }
        
        function getAllCategories(){
            $this->db->distinct();
            $this->db->select('category');
            $this->db->from('product');
            $this->db->where('category IS NOT NULL');
            $this->db->where('category !=', '');
            $query = $this->db->get();
            return $query->result_array(); // This returns array with 'category' key
        }

        // Weight and unit handling methods
        function getProductWeight($pid){
            $this->db->select('weight, unit');
            $this->db->from('product');
            $this->db->where('pid', $pid);
            $result = $this->db->get()->row_array();
            
            if ($result) {
                return $result['weight'] . ' ' . $result['unit'];
            }
            return null;
        }

        function convertWeight($weight, $fromUnit, $toUnit){
            // Basic weight conversion for common units
            $weights = [
                'grams' => 1,
                'kg' => 1000,
                'ml' => 1,
                'litre' => 1000,
                'dozen' => 12,
                'piece' => 1
            ];
            
            if (isset($weights[$fromUnit]) && isset($weights[$toUnit])) {
                return ($weight * $weights[$fromUnit]) / $weights[$toUnit];
            }
            
            return $weight; // Return original if conversion not possible
        }

        function calculatePricePerUnit($pid, $requestedQuantity = 1){
            $this->db->select('price, weight, unit');
            $this->db->from('product');
            $this->db->where('pid', $pid);
            $product = $this->db->get()->row_array();
            
            if ($product) {
                $basePrice = $product['price'];
                $baseWeight = $product['weight'];
                
                // Calculate price per unit weight/quantity
                if ($baseWeight > 0) {
                    $pricePerUnit = $basePrice / $baseWeight;
                    return $pricePerUnit * $requestedQuantity;
                }
            }
            
            return 0;
        }

        // Enhanced search and filtering methods
        function searchProducts($searchTerm, $category = null, $brand = null, $priceRange = null, $sort = null){
            $this->db->select("*");
            $this->db->from('product');
            
            // Search in product name, category, subcategory, and brand
            if (!empty($searchTerm)) {
                $this->db->group_start();
                $this->db->like('pname', $searchTerm);
                $this->db->or_like('category', $searchTerm);
                $this->db->or_like('subcategory', $searchTerm);
                $this->db->or_like('brand', $searchTerm);
                $this->db->group_end();
            }
            
            if (!empty($category)) {
                $this->db->where('category', $category);
            }
            
            if (!empty($brand)) {
                $this->db->where('brand', $brand);
            }
            
            if (!empty($priceRange)) {
                if (isset($priceRange['min'])) {
                    $this->db->where('price >=', $priceRange['min']);
                }
                if (isset($priceRange['max'])) {
                    $this->db->where('price <=', $priceRange['max']);
                }
            }
            
            $this->db->where('stock_quantity >', 0); // Only show products in stock
            
            // Apply sorting
            $this->applySorting($sort);
            
            $searchResults = $this->db->get()->result_array();
            return $searchResults;
        }
        
        /**
         * Advanced search filter for AJAX requests
         */
        function advancedSearchFilter($filters){
            $this->db->select("*");
            $this->db->from('product');
            
            // Search term filter
            if (!empty($filters['search_term'])) {
                $this->db->group_start();
                $this->db->like('pname', $filters['search_term']);
                $this->db->or_like('category', $filters['search_term']);
                $this->db->or_like('subcategory', $filters['search_term']);
                $this->db->or_like('brand', $filters['search_term']);
                $this->db->or_like('description', $filters['search_term']);
                $this->db->group_end();
            }
            
            // Category filter
            if (!empty($filters['categories']) && is_array($filters['categories'])) {
                $this->db->where_in('category', $filters['categories']);
            }
            
            // Brand filter
            if (!empty($filters['brands']) && is_array($filters['brands'])) {
                $this->db->where_in('brand', $filters['brands']);
            }
            
            // Unit filter
            if (!empty($filters['units']) && is_array($filters['units'])) {
                $this->db->where_in('unit', $filters['units']);
            }
            
            // Price range filter
            if (!empty($filters['price_range'])) {
                if (isset($filters['price_range']['min']) && $filters['price_range']['min'] !== '') {
                    $this->db->where('price >=', $filters['price_range']['min']);
                }
                if (isset($filters['price_range']['max']) && $filters['price_range']['max'] !== '') {
                    $this->db->where('price <=', $filters['price_range']['max']);
                }
            }
            
            // Stock filters
            if (!empty($filters['stock_filters']) && is_array($filters['stock_filters'])) {
                $this->db->group_start();
                foreach ($filters['stock_filters'] as $stockFilter) {
                    switch ($stockFilter) {
                        case 'in_stock':
                            $this->db->or_where('stock_quantity >', 0);
                            break;
                        case 'low_stock':
                            $this->db->or_where('stock_quantity <=', 10);
                            $this->db->where('stock_quantity >', 0);
                            break;
                    }
                }
                $this->db->group_end();
            } else {
                // Default: only show products in stock
                $this->db->where('stock_quantity >', 0);
            }
            
            // Apply sorting
            $this->applySorting($filters['sort_by'] ?? null);
            
            $searchResults = $this->db->get()->result_array();
            return $searchResults;
        }
        
        /**
         * Apply sorting to query
         */
        private function applySorting($sort){
            switch ($sort) {
                case 'price_low':
                    $this->db->order_by('price', 'ASC');
                    break;
                case 'price_high':
                    $this->db->order_by('price', 'DESC');
                    break;
                case 'name_asc':
                    $this->db->order_by('pname', 'ASC');
                    break;
                case 'name_desc':
                    $this->db->order_by('pname', 'DESC');
                    break;
                case 'newest':
                    $this->db->order_by('pid', 'DESC'); // Assuming higher ID = newer
                    break;
                case 'stock_high':
                    $this->db->order_by('stock_quantity', 'DESC');
                    break;
                case 'expiry_soon':
                    $this->db->where('expiry_date IS NOT NULL');
                    $this->db->where('expiry_date >=', date('Y-m-d'));
                    $this->db->order_by('expiry_date', 'ASC');
                    break;
                case 'relevance':
                default:
                    // Default sorting by stock quantity then by name
                    $this->db->order_by('stock_quantity', 'DESC');
                    $this->db->order_by('pname', 'ASC');
                    break;
            }
        }
        
        /**
         * Get search suggestions
         */
        function getSearchSuggestions($query, $limit = 10){
            $suggestions = [];
            
            if (strlen($query) < 2) {
                return $suggestions;
            }
            
            // Get product name suggestions (5 max)
            $this->db->distinct();
            $this->db->select('pname as suggestion, "product" as type, pid');
            $this->db->from('product');
            $this->db->like('pname', $query);
            $this->db->where('stock_quantity >', 0);
            $this->db->limit(5);
            $this->db->order_by('pname', 'ASC');
            $productSuggestions = $this->db->get()->result_array();
            $suggestions = array_merge($suggestions, $productSuggestions);
            
            // Get brand suggestions (2 max)
            $this->db->distinct();
            $this->db->select('brand as suggestion, "brand" as type');
            $this->db->from('product');
            $this->db->like('brand', $query);
            $this->db->where('brand IS NOT NULL');
            $this->db->where('brand !=', '');
            $this->db->where('stock_quantity >', 0);
            $this->db->limit(2);
            $this->db->order_by('brand', 'ASC');
            $brandSuggestions = $this->db->get()->result_array();
            $suggestions = array_merge($suggestions, $brandSuggestions);
            
            // Get category suggestions (3 max)
            $this->db->distinct();
            $this->db->select('category as suggestion, "category" as type');
            $this->db->from('product');
            $this->db->like('category', $query);
            $this->db->where('stock_quantity >', 0);
            $this->db->limit(3);
            $this->db->order_by('category', 'ASC');
            $categorySuggestions = $this->db->get()->result_array();
            $suggestions = array_merge($suggestions, $categorySuggestions);
            
            // Limit total results and add additional info
            $finalSuggestions = array_slice($suggestions, 0, $limit);
            
            // Format suggestions for frontend
            $formattedSuggestions = [];
            foreach ($finalSuggestions as $suggestion) {
                $formattedSuggestions[] = [
                    'text' => $suggestion['suggestion'],
                    'type' => $suggestion['type'],
                    'pid' => isset($suggestion['pid']) ? $suggestion['pid'] : null
                ];
            }
            
            return $formattedSuggestions;
        }

        function filterByExpiry($daysFromNow = 30){
            $this->db->select("*");
            $this->db->from('product');
            $this->db->where('expiry_date >=', date('Y-m-d'));
            $this->db->where('expiry_date <=', date('Y-m-d', strtotime("+{$daysFromNow} days")));
            $this->db->where('stock_quantity >', 0);
            $this->db->order_by('expiry_date', 'ASC');
            
            $expiringProducts = $this->db->get()->result_array();
            return $expiringProducts;
        }

        function getProductsByBrand($brand){
            $this->db->select("*");
            $this->db->from('product');
            $this->db->where('brand', $brand);
            $this->db->where('stock_quantity >', 0);
            
            $brandProducts = $this->db->get()->result_array();
            return $brandProducts;
        }

        // Stock management methods
        function updateStock($pid, $quantity, $operation = 'subtract'){
            $this->db->select('stock_quantity');
            $this->db->from('product');
            $this->db->where('pid', $pid);
            $currentStock = $this->db->get()->row_array();
            
            if ($currentStock) {
                $newStock = $currentStock['stock_quantity'];
                
                if ($operation === 'subtract') {
                    $newStock -= $quantity;
                } elseif ($operation === 'add') {
                    $newStock += $quantity;
                }
                
                // Ensure stock doesn't go negative
                $newStock = max(0, $newStock);
                
                $this->db->where('pid', $pid);
                $this->db->update('product', ['stock_quantity' => $newStock]);
                
                return $newStock;
            }
            
            return false;
        }

        function checkStock($pid){
            $this->db->select('stock_quantity');
            $this->db->from('product');
            $this->db->where('pid', $pid);
            $result = $this->db->get()->row_array();
            
            return $result ? $result['stock_quantity'] : 0;
        }
        
        /**
         * Get detailed stock information including status
         */
        function getStockDetails($pid){
            $this->db->select('stock_quantity, expiry_date, pname');
            $this->db->from('product');
            $this->db->where('pid', $pid);
            $result = $this->db->get()->row_array();
            
            if ($result) {
                $stock = $result['stock_quantity'];
                $status = 'in_stock';
                $message = "In Stock: {$stock} units";
                $css_class = 'text-success';
                
                if ($stock <= 0) {
                    $status = 'out_of_stock';
                    $message = 'Out of Stock';
                    $css_class = 'text-danger';
                } elseif ($stock <= 3) {
                    $status = 'very_low_stock';
                    $message = "Only {$stock} left!";
                    $css_class = 'text-danger';
                } elseif ($stock <= 10) {
                    $status = 'low_stock';
                    $message = "Only {$stock} left";
                    $css_class = 'text-warning';
                }
                
                return [
                    'stock_quantity' => $stock,
                    'status' => $status,
                    'message' => $message,
                    'css_class' => $css_class,
                    'expiry_date' => $result['expiry_date'],
                    'product_name' => $result['pname']
                ];
            }
            
            return null;
        }
        
        /**
         * Check if product is expiring soon and get expiry details
         */
        function getExpiryDetails($pid, $warning_days = 7){
            $this->db->select('expiry_date, pname');
            $this->db->from('product');
            $this->db->where('pid', $pid);
            $result = $this->db->get()->row_array();
            
            if ($result && !empty($result['expiry_date'])) {
                $expiry_date = $result['expiry_date'];
                $days_to_expiry = floor((strtotime($expiry_date) - time()) / 86400);
                
                $status = 'fresh';
                $message = 'Fresh';
                $css_class = 'text-success';
                
                if ($days_to_expiry < 0) {
                    $status = 'expired';
                    $message = 'Expired';
                    $css_class = 'text-danger';
                } elseif ($days_to_expiry <= 3) {
                    $status = 'expiring_urgent';
                    $message = "Expires in {$days_to_expiry} days";
                    $css_class = 'text-danger';
                } elseif ($days_to_expiry <= $warning_days) {
                    $status = 'expiring_soon';
                    $message = "Expires in {$days_to_expiry} days";
                    $css_class = 'text-warning';
                }
                
                return [
                    'expiry_date' => $expiry_date,
                    'days_to_expiry' => $days_to_expiry,
                    'status' => $status,
                    'message' => $message,
                    'css_class' => $css_class,
                    'formatted_date' => date('M d, Y', strtotime($expiry_date))
                ];
            }
            
            return null;
        }
        
        /**
         * Get products with low stock for admin alerts
         */
        function getLowStockProducts($threshold = 10){
            $this->db->select('*');
            $this->db->from('product');
            $this->db->where('stock_quantity <=', $threshold);
            $this->db->where('stock_quantity >', 0);
            $this->db->order_by('stock_quantity', 'ASC');
            
            return $this->db->get()->result_array();
        }
        
        /**
         * Get out of stock products
         */
        function getOutOfStockProducts(){
            $this->db->select('*');
            $this->db->from('product');
            $this->db->where('stock_quantity', 0);
            $this->db->order_by('pname', 'ASC');
            
            return $this->db->get()->result_array();
        }
        
        /**
         * Get products expiring soon
         */
        function getExpiringProducts($days = 7){
            $this->db->select('*');
            $this->db->from('product');
            $this->db->where('expiry_date IS NOT NULL');
            $this->db->where('expiry_date >=', date('Y-m-d'));
            $this->db->where('expiry_date <=', date('Y-m-d', strtotime("+{$days} days")));
            $this->db->where('stock_quantity >', 0);
            $this->db->order_by('expiry_date', 'ASC');
            
            return $this->db->get()->result_array();
        }
        
        /**
         * Bulk update stock quantities
         */
        function bulkUpdateStock($updates){
            $this->db->trans_start();
            
            foreach ($updates as $pid => $quantity) {
                $this->db->where('pid', $pid);
                $this->db->update('product', ['stock_quantity' => $quantity]);
            }
            
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
        
        /**
         * Update product expiry date
         */
        function updateExpiryDate($pid, $expiry_date){
            $this->db->where('pid', $pid);
            return $this->db->update('product', ['expiry_date' => $expiry_date]);
        }
        
        /**
         * Get stock movement history (if you want to implement stock tracking)
         */
        function logStockMovement($pid, $movement_type, $quantity, $reason = '', $admin_id = null){
            $log_data = [
                'product_id' => $pid,
                'movement_type' => $movement_type, // 'in', 'out', 'adjustment'
                'quantity' => $quantity,
                'reason' => $reason,
                'admin_id' => $admin_id,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Note: You'll need to create a stock_movements table for this
            // $this->db->insert('stock_movements', $log_data);
            
            return true; // Placeholder for now
        }
        
        // Legacy methods (keep for backward compatibility during migration)
        function productsList($gender, $category){
            $this->db->select("*");
            $this->db->from('product');
            $this->db->where('gender', $gender);
            $this->db->where('category', $category);
            $categorizedList = $this->db->get()->result_array();
            return $categorizedList;
        }

        function allproducts($gender){
            $this->db->select("*");
            $this->db->from('product');
            $this->db->where('gender', $gender);
            $productList = $this->db->get()->result_array();
            return $productList;
        }

        function subCategory($gender){
            $this->db->distinct();
            $this->db->select('subcategory');
            $this->db->from('product');
            $this->db->where('gender', $gender);
            $subCategories = $this->db->get()->result_array();
            return $subCategories;
        }

        function colorList($gender){
            $this->db->distinct();
            $this->db->select('color');
            $this->db->from('product');
            $this->db->where('gender', $gender);
            $colorList = $this->db->get()->result_array();
            return $colorList;
        }

        // Consolidated method using Base_model
        function getProductByID($id){
            $product = $this->get_by_id($id);
            return $product ? [$product] : []; // Return as array for backward compatibility
        }
        
        // Additional consolidated methods
        function getProductsByBrandOptimized($brand){
            return $this->get_all(['brand' => $brand, 'stock_quantity >' => 0], 'pname ASC');
        }
        
        function getProductsInStock($limit = 0){
            return $this->get_all(['stock_quantity >' => 0], 'stock_quantity DESC, pname ASC', $limit);
        }
        
        function getProductsByPriceRange($min_price, $max_price){
            $this->db->where('price >=', $min_price);
            $this->db->where('price <=', $max_price);
            $this->db->where('stock_quantity >', 0);
            return $this->get_all([], 'price ASC');
        }
    }
