<?php 

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

    class Cart extends CI_Model{

        function __construct()
        {
            parent::__construct();

            $this->db = $this->load->database('default', true);

        }
        
        // Grocery-specific cart methods (quantity-based instead of size-based)
        function checkCartProductQuantity($pid, $userId){
            $this->db->select("*");
            $this->db->from('cart');
            $this->db->where('product_id', $pid);
            $this->db->where('user_id', $userId);
            $cartChecked = $this->db->get()->result_array();
            return $cartChecked;
        }
        
        function checkCartProductQuantityByIP($pid, $ipAddr){
            $this->db->select("*");
            $this->db->from('cart');
            $this->db->where('product_id', $pid);
            $this->db->where('ip_address', $ipAddr);
            $this->db->where('user_id IS NULL');
            $cartChecked = $this->db->get()->result_array();
            return $cartChecked;
        }
        
        function updateCartQuantity($pid, $userIdentifier, $quantity, $byUserId = true){
            log_message('debug', 'Updating cart quantity - PID: ' . $pid . ', Quantity: ' . $quantity . ', Identifier: ' . $userIdentifier . ', ByUserId: ' . ($byUserId ? 'true' : 'false'));
            
            $basePrice = $this->getBasePrice($pid);
            if (!$basePrice) {
                log_message('error', 'Cannot update cart quantity: Unable to get base price for product ID ' . $pid);
                return false;
            }
            
            $data = [
                'quantity' => $quantity,
                'price' => $quantity * $basePrice
            ];
            
            if ($byUserId) {
                $this->db->where('product_id', $pid);
                $this->db->where('user_id', $userIdentifier);
            } else {
                $this->db->where('product_id', $pid);
                $this->db->where('ip_address', $userIdentifier);
                $this->db->where('user_id IS NULL');
            }
            
            $result = $this->db->update('cart', $data);
            
            if (!$result) {
                $error = $this->db->error();
                log_message('error', 'Cart quantity update failed - Code: ' . $error['code'] . ', Message: ' . $error['message']);
                log_message('error', 'Update data: ' . json_encode($data));
            } else {
                log_message('debug', 'Cart quantity updated successfully');
            }
            
            return $result;
        }
        
        function deleteCartItemByUser($pid, $userId){
            $this->db->where('product_id', $pid);
            $this->db->where('user_id', $userId);
            $this->db->delete('cart');
        }
        
        function deleteCartItemByIP($pid, $ipAddr){
            $this->db->where('product_id', $pid);
            $this->db->where('ip_address', $ipAddr);
            $this->db->where('user_id IS NULL');
            $this->db->delete('cart');
        }
        
        function getCartItemCountByUser($userId){
            $this->db->select_sum('quantity');
            $this->db->from('cart');
            $this->db->where('user_id', $userId);
            $result = $this->db->get()->row_array();
            return $result['quantity'] ?: 0;
        }
        
        function getCartItemCountByIP($ipAddr){
            $this->db->select_sum('quantity');
            $this->db->from('cart');
            $this->db->where('ip_address', $ipAddr);
            $this->db->where('user_id IS NULL');
            $result = $this->db->get()->row_array();
            return $result['quantity'] ?: 0;
        }
        
        private function getBasePrice($pid){
            $this->db->select('price, weight, unit');
            $this->db->from('product');
            $this->db->where('pid', $pid);
            $result = $this->db->get()->row_array();
            
            if (!$result) {
                log_message('error', 'getBasePrice failed: Product not found for PID ' . $pid);
                return 0;
            }
            
            if (!isset($result['price']) || $result['price'] <= 0) {
                log_message('error', 'getBasePrice failed: Invalid price for PID ' . $pid . ', Price: ' . ($result['price'] ?? 'NULL'));
                return 0;
            }
            
            return $result['price'];
        }
        
        function getProductWithWeightInfo($pid){
            $this->db->select('pid, pname, price, weight, unit, brand, stock_quantity');
            $this->db->from('product');
            $this->db->where('pid', $pid);
            $result = $this->db->get()->row_array();
            return $result;
        }
        
        function calculateWeightBasedPrice($pid, $requestedQuantity){
            $product = $this->getProductWithWeightInfo($pid);
            
            if ($product) {
                $basePrice = $product['price'];
                $baseWeight = $product['weight'];
                $unit = $product['unit'];
                
                // For weight-based products, calculate price per unit
                if (in_array($unit, ['kg', 'grams', 'litre', 'ml'])) {
                    // Price is per weight/volume, multiply by quantity
                    return $basePrice * $requestedQuantity;
                } elseif (in_array($unit, ['piece', 'dozen'])) {
                    // Price is per piece/dozen
                    if ($unit === 'dozen' && $requestedQuantity > 1) {
                        return $basePrice * $requestedQuantity;
                    } else {
                        return $basePrice * $requestedQuantity;
                    }
                }
            }
            
            return 0;
        }
        
        function getBulkDiscountPrice($pid, $quantity) {
            try {
                $product = $this->getProductWithWeightInfo($pid);
                
                if ($product) {
                    $totalPrice = $this->calculateWeightBasedPrice($pid, $quantity);
                    
                    // If no bulk pricing or table doesn't exist, return simple price
                    if (!$totalPrice || $totalPrice <= 0) {
                        $totalPrice = $product['price'] * $quantity;
                    }
                    
                    // Get bulk tiers (will return empty array if table doesn't exist)
                    $bulkTiers = $this->getBulkPricingTiers($pid);
                    
                    $bestPrice = $totalPrice;
                    $appliedDiscount = 0;
                    $tierName = '';
                    
                    if (!empty($bulkTiers)) {
                        foreach ($bulkTiers as $tier) {
                            if ($quantity >= $tier['min_quantity']) {
                                // Check if quantity falls within tier range
                                if ($tier['max_quantity'] === null || $quantity <= $tier['max_quantity']) {
                                    $discountAmount = 0;
                                    
                                    if ($tier['discount_type'] === 'percentage') {
                                        $discountAmount = ($totalPrice * $tier['discount_value']) / 100;
                                    } elseif ($tier['discount_type'] === 'fixed_amount') {
                                        $discountAmount = $tier['discount_value'] * $quantity;
                                    }
                                    
                                    $tierPrice = $totalPrice - $discountAmount;
                                    
                                    if ($tierPrice < $bestPrice) {
                                        $bestPrice = $tierPrice;
                                        $appliedDiscount = $tier['discount_value'];
                                        $tierName = $tier['tier_name'];
                                    }
                                }
                            }
                        }
                    }
                    
                    return $bestPrice; // Return just the final price for simplicity
                }
            } catch (Exception $e) {
                // Log error and continue with basic pricing
                log_message('error', 'Bulk pricing error: ' . $e->getMessage());
            }
            
            // Fallback: return basic price calculation
            $product = $this->getProductWithWeightInfo($pid);
            return $product ? ($product['price'] * $quantity) : 0;
        }

        function checkCart($pid, $ipAddr, $size){
            $this->db->select("*");
            $this->db->from('cart');
            $this->db->where('product_id', $pid);
            $this->db->where('ip_address', $ipAddr);
            $this->db->where('size', $size);
            $cartChecked = $this->db->get()->result_array();
            return $cartChecked;
        }

        function checkCartProduct($pid, $userId, $size){
            $this->db->select("*");
            $this->db->from('cart');
            $this->db->where('product_id', $pid);
            $this->db->where('user_id', $userId);
            $this->db->where('size', $size);
            $cartChecked = $this->db->get()->result_array();
            return $cartChecked;
        }

        function checkCartById($userid){
            $this->db->select("*");
            $this->db->from('cart');
            $this->db->where('user_id', $userid);
            $cartChecked = $this->db->get()->result_array();
            return $cartChecked;
        }

        function insertCart($cartData){
            // Log the cart data being inserted for debugging
            log_message('debug', 'Attempting to insert cart data: ' . json_encode($cartData));
            
            // Check if required fields are present
            if (empty($cartData['product_id'])) {
                log_message('error', 'Cart insertion failed: product_id is missing');
                return false;
            }
            
            if ($this->db->insert('cart', $cartData)) {
                $insertId = $this->db->insert_id();
                log_message('debug', 'Cart insertion successful with ID: ' . $insertId);
                return $insertId;
            } else {
                $error = $this->db->error();
                log_message('error', 'Cart insertion failed - Code: ' . $error['code'] . ', Message: ' . $error['message']);
                log_message('error', 'Failed cart data: ' . json_encode($cartData));
                return false;
            }
        }

        function updateCartID($userID, $ipAddr){
            $this->db->set('user_id', $userID);
            $this->db->where('ip_address', $ipAddr);
            $this->db->update('cart');
        }

        function showCart($ipAddr){
            $this->db->select("*");
            $this->db->from('cart');
            $this->db->where('ip_address', $ipAddr);
            $cartItems = $this->db->get()->result_array();
            return $cartItems;
        }

        function showCartByID($userID){
            $this->db->select("*");
            $this->db->from('cart');
            $this->db->where('user_id', $userID);
            $cartItems = $this->db->get()->result_array();
            return $cartItems;
        }

        function getProductsForCart($implodedValue){
         
            if (!empty($implodedValue)) {
                $data = explode(',', $implodedValue);
            }
            $this->db->select("*");
            $this->db->from('product');
            $this->db->where_in('pid', $data);
            $this->db->join('cart', 'cart.product_id = product.pid');
                  
            $productDetail = $this->db->get()->result_array();
            return $productDetail;
        }

        function deleteCartItem($id, $size){
            $this->db->where('product_id', $id);
            $this->db->where('size', $size);
            $this->db->delete('cart');
        }

        function getCartByID($id, $ipAddr){
            $this->db->select("*");
            $this->db->from('cart');
            $this->db->where_in('product_id', $id);
            $this->db->or_where('ip_address', $ipAddr);
            $cartItems = $this->db->get()->result_array();
            return $cartItems;
        }

        function getTotalItemsInCart($id, $ipAddr){
            $this->db->select("*");
            $this->db->from('cart');
            $this->db->where_in('product_id', $id);
            $this->db->or_where('ip_address', $ipAddr);
            $cartItems = $this->db->count_all_results();
            return $cartItems;
        }

        function getCartTotalPrice($id){
            $this->db->select_sum("price");
            $this->db->where('user_id', $id);
            $this->db->from('cart');
           $result = $this->db->get()->result_array();
           return $result;
        }

       function getCartTotalPriceByIP($ipAddr){
        $this->db->select_sum("price");
        $this->db->where('ip_address', $ipAddr);
        $this->db->from('cart');
       $result = $this->db->get()->result_array();
       return $result;
       }

       public function placeOrder($order){
           $this->db->insert('orders', $order);
           $orderID = $this->db->insert_id();
           return $orderID;
       }

       public function insertOrderDetails($orderDetails)
       {
       $this->db->insert('order_details', $orderDetails);
           $orderID = $this->db->insert_id();
           return $orderID;
       }

       function deleteCartByUserId($id){
        $this->db->where('user_id', $id);
        $this->db->delete('cart');
    }
    
    function clearCartByIP($ipAddr){
        $this->db->where('ip_address', $ipAddr);
        $this->db->where('user_id IS NULL');
        $this->db->delete('cart');
    }
    
    /**
     * Get bulk pricing tiers for a product
     * Returns mock data since bulk_pricing_tiers table doesn't exist
     */
    function getBulkPricingTiers($pid) {
        // Check if bulk_pricing_tiers table exists
        if (!$this->db->table_exists('bulk_pricing_tiers')) {
            // Return default bulk pricing tiers (mock data)
            return [
                [
                    'tier_name' => 'Buy 5+ Save 5%',
                    'min_quantity' => 5,
                    'max_quantity' => 9,
                    'discount_type' => 'percentage',
                    'discount_value' => 5
                ],
                [
                    'tier_name' => 'Buy 10+ Save 10%',
                    'min_quantity' => 10,
                    'max_quantity' => null,
                    'discount_type' => 'percentage',
                    'discount_value' => 10
                ]
            ];
        }
        
        // If table exists, use original query
        $this->db->select('*');
        $this->db->from('bulk_pricing_tiers');
        $this->db->where('product_id', $pid);
        $this->db->where('is_active', 1);
        $this->db->order_by('min_quantity', 'ASC');
        
        return $this->db->get()->result_array();
    }
    
    /**
     * Get cart with bulk pricing applied
     */
    function getCartWithBulkPricing($userId = null, $ipAddr = null) {
        $cartItems = [];
        
        if ($userId) {
            $cartItems = $this->showCartByID($userId);
        } elseif ($ipAddr) {
            $cartItems = $this->showCart($ipAddr);
        }
        
        $enhancedCart = [];
        $totalSavings = 0;
        
        foreach ($cartItems as $item) {
            $bulkPricing = $this->getBulkDiscountPrice($item['product_id'], $item['quantity']);
            
            $item['bulk_pricing'] = $bulkPricing;
            $item['final_price'] = $bulkPricing['discounted_price'];
            $item['savings'] = $bulkPricing['savings'];
            $totalSavings += $bulkPricing['savings'];
            
            $enhancedCart[] = $item;
        }
        
        return [
            'items' => $enhancedCart,
            'total_savings' => $totalSavings,
            'bulk_discounts_applied' => $totalSavings > 0
        ];
    }


    }
