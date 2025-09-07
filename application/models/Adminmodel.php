<?php 

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

    class Adminmodel extends CI_Model{

        function __construct()
        {
            parent::__construct();

            $this->db = $this->load->database('default', true);
        }
////////////////////////////////--------------------------------------Admins------------------------------/////////////////////////////////

     public function checkAdmin($admin_email, $admin_password){
            $this->db->select('*');
            $this->db->from('admin');
            $this->db->where('admin_email',$admin_email);
            $admin_data = $this->db->get()->row_array();
            
            if ($admin_data) {
                // Check if password matches (support both old plain text and new hashed passwords)
                $is_password_valid = false;
                
                // First try password_verify for hashed passwords
                if (password_verify($admin_password, $admin_data['admin_password'])) {
                    $is_password_valid = true;
                }
                // Then try plain text comparison (legacy support)
                elseif ($admin_password === $admin_data['admin_password']) {
                    $is_password_valid = true;
                    
                    // Upgrade plain text password to hash
                    $new_hash = password_hash($admin_password, PASSWORD_DEFAULT);
                    $this->db->where('admin_id', $admin_data['admin_id']);
                    $this->db->update('admin', array('admin_password' => $new_hash));
                }
                
                if ($is_password_valid) {
                    return array($admin_data); // Return as array for compatibility
                }
            }
            
            return array(); // Return empty array if no match
        }
        
        /**
         * Reset admin password (for debugging/recovery)
         */
        public function resetAdminPassword($admin_email, $new_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $this->db->where('admin_email', $admin_email);
            return $this->db->update('admin', array('admin_password' => $hashed_password));
        }
       
    public function getAdmins(){
        $this->db->select('*');
        $this->db->from('admin');
        $checkDetail =  $this->db->get()->result_array();
        return $checkDetail;
    } 
        public function addAdmins($data)
        {
            // Hash the password before storing
            if (isset($data['admin_password'])) {
                $data['admin_password'] = password_hash($data['admin_password'], PASSWORD_DEFAULT);
            }
            $this->db->insert('admin', $data);
        }

        public function deleteAdmin($id){
            $this->db->where('admin_id', $id);
            $this->db->delete('admin');

        }
        
        /**
         * Update admin profile
         */
        /**
         * Get admin by ID
         */
        public function getAdminById($admin_id){
            $this->db->select('*');
            $this->db->from('admin');
            $this->db->where('admin_id', $admin_id);
            return $this->db->get()->row_array();
        }
        
        public function updateAdmin($admin_id, $data){
            // Hash password if it's being updated
            if (isset($data['admin_password'])) {
                $data['admin_password'] = password_hash($data['admin_password'], PASSWORD_DEFAULT);
            }
            
            $this->db->where('admin_id', $admin_id);
            return $this->db->update('admin', $data);
        }
        
        public function getUsers(){
            $this->db->select('*');
            $this->db->from('users');
            $userDetails =  $this->db->get()->result_array();
            return $userDetails;
        }
/////////////////////////////////////////-------------------------Products---------------------------/////////////////////////////////////////

        public function getProducts(){
            $this->db->select('*');
            $this->db->from('product');
            $checkDetail =  $this->db->get()->result_array();
            return $checkDetail;
        }

        public function deleteProduct($id){
            $this->db->where('pid', $id);
            $this->db->delete('product');

        }

        public function insertProduct($data){
            $this->db->insert('product', $data);
        }
        
//////////////////////////////////-------------------------User Management---------------------------/////////////////////////////////////////
        
        /**
         * Get user by ID
         */
        public function getUserById($user_id){
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('uid', $user_id);
            $userDetails = $this->db->get()->row_array();
            return $userDetails;
        }
        
        /**
         * Update user details
         */
        public function updateUser($user_id, $data){
            $this->db->where('uid', $user_id);
            $this->db->update('users', $data);
        }
        
        /**
         * Delete user and all associated data
         */
        public function deleteUser($user_id){
            // Start transaction
            $this->db->trans_start();
            
            // Delete user's orders details first
            $this->db->query("DELETE od FROM order_details od 
                             INNER JOIN orders o ON od.order_id = o.order_id 
                             WHERE o.user_id = ?", array($user_id));
                             
            // Delete user's orders
            $this->db->where('user_id', $user_id);
            $this->db->delete('orders');
            
            // Delete user's billing details
            $this->db->where('user_id', $user_id);
            $this->db->delete('billing_details');
            
            // Delete user's cart items
            $this->db->where('user_id', $user_id);
            $this->db->delete('cart');
            
            // Finally delete the user
            $this->db->where('uid', $user_id);
            $this->db->delete('users');
            
            // Complete transaction
            $this->db->trans_complete();
        }

//////////////////////////////////-------------------------Order Management---------------------------/////////////////////////////////////////
        
        /**
         * Update order status (legacy - kept for compatibility)
         */
        public function updateOrderStatus($order_id, $status){
            $this->db->where('order_id', $order_id);
            $this->db->update('orders', array('order_status' => $status));
        }
        
        /**
         * Complete order status update with additional data
         */
        public function updateOrderStatusComplete($order_id, $update_data){
            $this->db->where('order_id', $order_id);
            return $this->db->update('orders', $update_data);
        }
        
        /**
         * Log order status changes for audit trail
         */
        public function logOrderStatusChange($order_id, $old_status, $new_status, $admin_id, $notes = ''){
            // Check if order_status_history table exists, if not create it
            if (!$this->db->table_exists('order_status_history')) {
                $this->createOrderStatusHistoryTable();
            }
            
            $log_data = [
                'order_id' => $order_id,
                'old_status' => $old_status,
                'new_status' => $new_status,
                'changed_by_admin' => $admin_id,
                'admin_notes' => $notes,
                'changed_at' => date('Y-m-d H:i:s')
            ];
            
            return $this->db->insert('order_status_history', $log_data);
        }
        
        /**
         * Get order status history
         */
        public function getOrderStatusHistory($order_id){
            if (!$this->db->table_exists('order_status_history')) {
                return [];
            }
            
            $this->db->select('osh.*, a.admin_name');
            $this->db->from('order_status_history osh');
            $this->db->join('admin a', 'osh.changed_by_admin = a.admin_id', 'left');
            $this->db->where('osh.order_id', $order_id);
            $this->db->order_by('osh.changed_at', 'ASC');
            
            return $this->db->get()->result_array();
        }
        
        /**
         * Get orders by status
         */
        public function getOrdersByStatus($status, $limit = 50, $offset = 0){
            $this->db->select('o.*, u.email, u.mobile');
            $this->db->from('orders o');
            $this->db->join('users u', 'o.user_id = u.uid', 'left');
            $this->db->where('o.order_status', $status);
            $this->db->order_by('o.order_date', 'DESC');
            $this->db->limit($limit, $offset);
            
            return $this->db->get()->result_array();
        }
        
        /**
         * Get all orders with pagination
         */
        public function getAllOrdersPaginated($limit = 50, $offset = 0){
            $this->db->select('o.*, u.email, u.mobile');
            $this->db->from('orders o');
            $this->db->join('users u', 'o.user_id = u.uid', 'left');
            $this->db->order_by('o.order_date', 'DESC');
            $this->db->limit($limit, $offset);
            
            return $this->db->get()->result_array();
        }
        
        /**
         * Get order counts by status for dashboard
         */
        public function getOrderStatusCounts(){
            $statuses = ['pending', 'accepted', 'processing', 'shipped', 'delivered', 'cancelled', 'rejected'];
            $counts = [];
            
            foreach ($statuses as $status) {
                $this->db->where('order_status', $status);
                $counts[$status] = $this->db->count_all_results('orders');
                $this->db->reset_query();
            }
            
            // Total orders
            $counts['total'] = $this->db->count_all('orders');
            
            // Today's orders
            $this->db->where('DATE(order_date)', date('Y-m-d'));
            $counts['today'] = $this->db->count_all_results('orders');
            
            return $counts;
        }
        
        /**
         * Create order status history table if it doesn't exist
         */
        private function createOrderStatusHistoryTable(){
            $sql = "CREATE TABLE `order_status_history` (
                `history_id` int(11) NOT NULL AUTO_INCREMENT,
                `order_id` int(11) NOT NULL,
                `old_status` varchar(50) NOT NULL,
                `new_status` varchar(50) NOT NULL,
                `changed_by_admin` int(11),
                `admin_notes` text,
                `changed_at` datetime NOT NULL,
                PRIMARY KEY (`history_id`),
                KEY `idx_order_id` (`order_id`),
                KEY `idx_changed_at` (`changed_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            
            $this->db->query($sql);
        }
        
        /**
         * Get detailed order information
         */
        public function getOrderDetails($order_id){
            $this->db->select('o.*, u.email, u.mobile');
            $this->db->from('orders o');
            $this->db->join('users u', 'o.user_id = u.uid', 'left');
            $this->db->where('o.order_id', $order_id);
            $orderDetails = $this->db->get()->row_array();
            return $orderDetails;
        }
        
        /**
         * Get order items with product details
         */
        public function getOrderItems($order_id){
            $this->db->select('od.*, p.pname, p.pimage');
            $this->db->from('order_details od');
            $this->db->join('product p', 'od.product_id = p.pid', 'left');
            $this->db->where('od.order_id', $order_id);
            $orderItems = $this->db->get()->result_array();
            return $orderItems;
        }
        
        /**
         * Get billing details for an order
         */
        public function getBillingDetails($order_id){
            $this->db->select('bd.*');
            $this->db->from('billing_details bd');
            $this->db->join('orders o', 'bd.user_id = o.user_id', 'inner');
            $this->db->where('o.order_id', $order_id);
            $this->db->order_by('bd.bill_id', 'DESC');
            $this->db->limit(1);
            $billingDetails = $this->db->get()->row_array();
            return $billingDetails;
        }

//////////////////////////////////-------------------------Category Management---------------------------/////////////////////////////////////////
        
        /**
         * Get all categories from products (unique categories)
         */
        public function getCategories(){
            $this->db->select('category, COUNT(*) as product_count');
            $this->db->from('product');
            $this->db->group_by('category');
            $this->db->order_by('category');
            $categories = $this->db->get()->result_array();
            return $categories;
        }
        
        /**
         * Add new category (This would require a categories table, for now we'll just return success)
         * Note: Since categories are stored directly in products table, this is more of a placeholder
         */
        public function addCategory($data){
            // This would require a separate categories table
            // For now, categories are managed through product entries
            return true;
        }
        
        /**
         * Delete category (removes all products in this category)
         */
        public function deleteCategory($category_name){
            // This will delete all products in this category
            $this->db->where('category', $category_name);
            $this->db->delete('product');
        }
        
//////////////////////////////////-------------------------Grocery-Specific Features---------------------------/////////////////////////////////////////
        
        /**
         * Get low stock products
         */
        public function getLowStockProducts($limit = null){
            $this->db->select('*');
            $this->db->from('product');
            $this->db->where('stock_quantity <=', 10);
            $this->db->where('stock_quantity >', 0);
            $this->db->order_by('stock_quantity', 'ASC');
            if ($limit) {
                $this->db->limit($limit);
            }
            return $this->db->get()->result_array();
        }
        
        /**
         * Get out of stock products
         */
        public function getOutOfStockProducts(){
            $this->db->select('*');
            $this->db->from('product');
            $this->db->where('stock_quantity', 0);
            $this->db->order_by('pname', 'ASC');
            return $this->db->get()->result_array();
        }
        
        /**
         * Get stock summary statistics
         */
        public function getStockSummary(){
            $summary = array();
            
            // Total products
            $this->db->select('COUNT(*) as total');
            $this->db->from('product');
            $summary['total_products'] = $this->db->get()->row_array()['total'];
            
            // In stock
            $this->db->select('COUNT(*) as in_stock');
            $this->db->from('product');
            $this->db->where('stock_quantity >', 10);
            $summary['in_stock'] = $this->db->get()->row_array()['in_stock'];
            
            // Low stock
            $this->db->select('COUNT(*) as low_stock');
            $this->db->from('product');
            $this->db->where('stock_quantity <=', 10);
            $this->db->where('stock_quantity >', 0);
            $summary['low_stock'] = $this->db->get()->row_array()['low_stock'];
            
            // Out of stock
            $this->db->select('COUNT(*) as out_of_stock');
            $this->db->from('product');
            $this->db->where('stock_quantity', 0);
            $summary['out_of_stock'] = $this->db->get()->row_array()['out_of_stock'];
            
            return $summary;
        }
        
        /**
         * Get products expiring soon
         */
        public function getExpiringSoonProducts($days = 7){
            $this->db->select('*');
            $this->db->from('product');
            $this->db->where('expiry_date IS NOT NULL');
            $this->db->where('expiry_date <=', date('Y-m-d', strtotime("+{$days} days")));
            $this->db->where('expiry_date >=', date('Y-m-d'));
            $this->db->order_by('expiry_date', 'ASC');
            return $this->db->get()->result_array();
        }
        
        /**
         * Get expired products
         */
        public function getExpiredProducts(){
            $this->db->select('*');
            $this->db->from('product');
            $this->db->where('expiry_date IS NOT NULL');
            $this->db->where('expiry_date <', date('Y-m-d'));
            $this->db->order_by('expiry_date', 'ASC');
            return $this->db->get()->result_array();
        }
        
        /**
         * Get expiry statistics
         */
        public function getExpiryStatistics(){
            $stats = array();
            
            // Products expiring in 3 days
            $this->db->select('COUNT(*) as expiring_3_days');
            $this->db->from('product');
            $this->db->where('expiry_date IS NOT NULL');
            $this->db->where('expiry_date <=', date('Y-m-d', strtotime('+3 days')));
            $this->db->where('expiry_date >=', date('Y-m-d'));
            $stats['expiring_3_days'] = $this->db->get()->row_array()['expiring_3_days'];
            
            // Products expiring in 7 days
            $this->db->select('COUNT(*) as expiring_7_days');
            $this->db->from('product');
            $this->db->where('expiry_date IS NOT NULL');
            $this->db->where('expiry_date <=', date('Y-m-d', strtotime('+7 days')));
            $this->db->where('expiry_date >=', date('Y-m-d'));
            $stats['expiring_7_days'] = $this->db->get()->row_array()['expiring_7_days'];
            
            // Already expired
            $this->db->select('COUNT(*) as expired');
            $this->db->from('product');
            $this->db->where('expiry_date IS NOT NULL');
            $this->db->where('expiry_date <', date('Y-m-d'));
            $stats['expired'] = $this->db->get()->row_array()['expired'];
            
            return $stats;
        }
        
        /**
         * Get perishable products (dairy, fruits, vegetables)
         */
        public function getPerishableProducts(){
            $this->db->select('*');
            $this->db->from('product');
            $this->db->group_start();
            $this->db->like('category', 'Fruits');
            $this->db->or_like('category', 'Vegetables');
            $this->db->or_like('category', 'Dairy');
            $this->db->or_like('category', 'Meat');
            $this->db->group_end();
            $this->db->order_by('expiry_date', 'ASC');
            return $this->db->get()->result_array();
        }
        
        /**
         * Get stock rotation alerts
         */
        public function getStockRotationAlerts(){
            // Products that haven't moved (this would require sales tracking)
            // For now, return products with high stock and approaching expiry
            $this->db->select('*');
            $this->db->from('product');
            $this->db->where('stock_quantity >', 20);
            $this->db->where('expiry_date IS NOT NULL');
            $this->db->where('expiry_date <=', date('Y-m-d', strtotime('+14 days')));
            $this->db->order_by('expiry_date', 'ASC');
            return $this->db->get()->result_array();
        }
        
        /**
         * Update product stock
         */
        public function updateProductStock($product_id, $quantity, $operation = 'set'){
            $data = array();
            
            if ($operation === 'set') {
                $data['stock_quantity'] = $quantity;
            } elseif ($operation === 'add') {
                $this->db->set('stock_quantity', 'stock_quantity + ' . intval($quantity), FALSE);
            } elseif ($operation === 'subtract') {
                $this->db->set('stock_quantity', 'GREATEST(stock_quantity - ' . intval($quantity) . ', 0)', FALSE);
            }
            
            $this->db->where('pid', $product_id);
            
            if ($operation === 'set') {
                return $this->db->update('product', $data);
            } else {
                return $this->db->update('product');
            }
        }
        
        /**
         * Update product discount
         */
        public function updateProductDiscount($product_id, $discount_percent){
            $data = array('discount' => $discount_percent);
            $this->db->where('pid', $product_id);
            return $this->db->update('product', $data);
        }
        
        /**
         * Batch update category prices
         */
        public function batchUpdateCategoryPrices($category, $percentage, $operation){
            $products = $this->db->get_where('product', array('category' => $category))->result_array();
            $total = count($products);
            $updated = 0;
            
            foreach ($products as $product) {
                $current_price = $product['price'];
                $change_amount = ($current_price * $percentage) / 100;
                
                if ($operation === 'increase') {
                    $new_price = $current_price + $change_amount;
                } else {
                    $new_price = max(0.01, $current_price - $change_amount); // Minimum price of 0.01
                }
                
                $this->db->where('pid', $product['pid']);
                if ($this->db->update('product', array('price' => $new_price))) {
                    $updated++;
                }
            }
            
            return array('total' => $total, 'updated' => $updated);
        }
        
        /**
         * Get category-wise stock information
         */
        public function getCategoryWiseStock(){
            $this->db->select('category, COUNT(*) as total_products, SUM(stock_quantity) as total_stock, AVG(stock_quantity) as avg_stock');
            $this->db->from('product');
            $this->db->group_by('category');
            $this->db->order_by('category');
            return $this->db->get()->result_array();
        }
        
        /**
         * Get inventory summary
         */
        public function getInventorySummary(){
            $summary = array();
            
            // Total inventory value
            $this->db->select('SUM(price * stock_quantity) as total_value');
            $this->db->from('product');
            $summary['total_inventory_value'] = $this->db->get()->row_array()['total_value'];
            
            // Category breakdown
            $summary['category_breakdown'] = $this->getCategoryWiseStock();
            
            // Stock alerts
            $summary['stock_alerts'] = array(
                'low_stock_count' => count($this->getLowStockProducts()),
                'out_of_stock_count' => count($this->getOutOfStockProducts()),
                'expiring_soon_count' => count($this->getExpiringSoonProducts(7))
            );
            
            return $summary;
        }

//////////////////////////////////-------------------------Contact Management---------------------------/////////////////////////////////////////
        
        /**
         * Get all contact messages
         */
        public function getContacts(){
            $this->db->select('*');
            $this->db->from('contact');
            $this->db->order_by('contact_id', 'DESC');
            $contacts = $this->db->get()->result_array();
            return $contacts;
        }
        
        /**
         * Delete contact message
         */
        public function deleteContact($contact_id){
            $this->db->where('contact_id', $contact_id);
            $this->db->delete('contact');
        }
        
//////////////////////////////////-------------------------Notification Support Methods---------------------------/////////////////////////////////////////
        
        /**
         * Get recent orders for notifications
         */
        public function getRecentOrders($hours = 24){
            $cutoff_time = date('Y-m-d H:i:s', strtotime("-{$hours} hours"));
            $this->db->select('o.*, u.email');
            $this->db->from('orders o');
            $this->db->join('users u', 'o.user_id = u.uid', 'left');
            $this->db->where('o.order_date >=', $cutoff_time);
            $this->db->order_by('o.order_date', 'DESC');
            return $this->db->get()->result_array();
        }
        
        /**
         * Get recent user registrations for notifications
         */
        public function getRecentUsers($hours = 24){
            $cutoff_time = date('Y-m-d H:i:s', strtotime("-{$hours} hours"));
            $this->db->select('*');
            $this->db->from('users');
            
            // Check if created_at column exists, otherwise use a different approach
            if ($this->db->field_exists('created_at', 'users')) {
                $this->db->where('created_at >=', $cutoff_time);
            } else {
                // If no created_at field, get recent UIDs (assuming auto-increment)
                $this->db->order_by('uid', 'DESC');
                $this->db->limit(20); // Get last 20 users as "recent"
            }
            
            $this->db->order_by('uid', 'DESC');
            return $this->db->get()->result_array();
        }
        
        /**
         * Get recent contact messages for notifications
         */
        public function getRecentContacts($days = 7){
            $cutoff_time = date('Y-m-d', strtotime("-{$days} days"));
            $this->db->select('*');
            $this->db->from('contact');
            
            // Check if date column exists
            if ($this->db->field_exists('contact_date', 'contact')) {
                $this->db->where('contact_date >=', $cutoff_time);
            } else if ($this->db->field_exists('created_at', 'contact')) {
                $this->db->where('DATE(created_at) >=', $cutoff_time);
            } else {
                // If no date field, get recent IDs (assuming auto-increment)
                $this->db->order_by('contact_id', 'DESC');
                $this->db->limit(10); // Get last 10 contacts as "recent"
            }
            
            $this->db->order_by('contact_id', 'DESC');
            return $this->db->get()->result_array();
        }
        
        /**
         * Get dashboard statistics for notifications
         */
        public function getDashboardNotificationStats(){
            $stats = array();
            
            // Count of critical alerts
            $stats['expiring_products'] = count($this->getExpiringSoonProducts(7));
            $stats['low_stock_products'] = count($this->getLowStockProducts(20));
            $stats['out_of_stock_products'] = count($this->getOutOfStockProducts());
            $stats['recent_orders'] = count($this->getRecentOrders(24));
            $stats['recent_users'] = count($this->getRecentUsers(24));
            $stats['recent_contacts'] = count($this->getRecentContacts(7));
            
            // Calculate total notification count
            $stats['total_notifications'] = 0;
            if ($stats['expiring_products'] > 0) $stats['total_notifications']++;
            if ($stats['low_stock_products'] > 0) $stats['total_notifications']++;
            if ($stats['recent_orders'] > 0) $stats['total_notifications']++;
            if ($stats['recent_users'] > 0) $stats['total_notifications']++;
            if ($stats['recent_contacts'] > 0) $stats['total_notifications']++;
            
            return $stats;
        }
        
        /**
         * Check if tables and columns exist for better error handling
         */
        public function checkTableStructure(){
            $structure = array();
            
            // Check tables exist
            $structure['tables'] = array(
                'product' => $this->db->table_exists('product'),
                'orders' => $this->db->table_exists('orders'),
                'users' => $this->db->table_exists('users'),
                'contact' => $this->db->table_exists('contact')
            );
            
            // Check important columns
            if ($structure['tables']['product']) {
                $structure['columns']['product'] = array(
                    'expiry_date' => $this->db->field_exists('expiry_date', 'product'),
                    'stock_quantity' => $this->db->field_exists('stock_quantity', 'product')
                );
            }
            
            if ($structure['tables']['orders']) {
                $structure['columns']['orders'] = array(
                    'order_date' => $this->db->field_exists('order_date', 'orders'),
                    'created_at' => $this->db->field_exists('created_at', 'orders')
                );
            }
            
            if ($structure['tables']['users']) {
                $structure['columns']['users'] = array(
                    'created_at' => $this->db->field_exists('created_at', 'users')
                );
            }
            
            return $structure;
        }

    }
