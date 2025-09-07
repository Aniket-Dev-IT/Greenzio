<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('adminmodel');
        $this->load->model('ordermodel');
        $this->load->library('form_validation');
    }
    
    // Helper function to check if admin is logged in
    private function is_admin_logged_in()
    {
        return $this->session->userdata('admin_logged_in') && $this->session->userdata('admin_email');
    }

    public function index()
    {
        // If admin is already logged in, redirect to dashboard
        if ($this->session->userdata('admin_logged_in')) {
            redirect('admin/dashboard', 'refresh');
        }
        
        $this->login();
    }

    public function sidebarHeader()
    {
        $this->load->view('admin/sidebar');
        $this->load->view('admin/header');
    }

    public function footer()
    {
        $this->load->view('admin/footer');
    }

    public function dashboard()
    {
        // Check if admin is already logged in
        if ($this->session->userdata('admin_logged_in')) {
            $this->load_dashboard();
        } else {
            // Show login form
            redirect('admin/', 'refresh');
        }
    }
    
    public function login()
    {
        // If POST request, process login
        if ($this->input->post()) {
            $this->process_login();
        } else {
            // Show login form
            $this->load->view('admin/login');
        }
    }
    
    private function process_login()
    {
        $admin_email = $this->input->post('admin_email');
        $admin_password = $this->input->post('admin_password');
        
        // Basic validation
        if (empty($admin_email) || empty($admin_password)) {
            $this->session->set_flashdata('error', 'Please enter both email and password!');
            redirect('admin/', 'refresh');
            return;
        }
        
        // Check admin credentials
        $admin_data = $this->adminmodel->checkAdmin($admin_email, $admin_password);
        
        if (!empty($admin_data)) {
            // Login successful - set session
            $session_data = array(
                'admin_id' => $admin_data[0]['admin_id'],
                'admin_name' => $admin_data[0]['admin_name'],
                'admin_email' => $admin_email,
                'admin_logged_in' => TRUE
            );
            
            $this->session->set_userdata($session_data);
            redirect('admin/dashboard', 'refresh');
        } else {
            // Login failed
            $this->session->set_flashdata('error', 'Invalid email or password!');
            redirect('admin/', 'refresh');
        }
    }
    
    private function load_dashboard()
    {
        // Get dashboard content
        ob_start();
        $this->load->view('admin/dashboard');
        $dashboard_content = ob_get_clean();
        
        // Load the clean admin wrapper with dashboard content
        $data['admin_content'] = $dashboard_content;
        $this->load->view('admin/admin_wrapper', $data);
    }

    public function manageAdmin()
    {
        if (!$this->is_admin_logged_in()) {
            redirect('admin/', 'refresh');
        }
        
        $details['adminDetails'] = $this->adminmodel->getAdmins();

        // Get content
        ob_start();
        $this->load->view('admin/manageAdmin', $details);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $data);
    }

    public function addAdmin(){
        $data = array();
        $data['admin_name'] = $this->input->post('newAdminName');
        $data['admin_email'] = $this->input->post('newAdminEmail');
        $data['admin_password'] = $this->input->post('newAdminPass');
        $admin_password2 = $this->input->post('newAdminPass2');

        $this->form_validation->set_rules('newAdminEmail', 'Email', 'required|is_unique[admin.admin_email]|callback_email_check');
        $this->form_validation->set_rules('newAdminPass', 'Password', 'trim|required|min_length[5]|max_length[50]');
        $this->form_validation->set_rules('newAdminPass2', ' Confirm Password', 'trim|required|matches[newAdminPass]');
    
        if ($this->form_validation->run()) {
            $this->adminmodel->addAdmins($data);
            $message = array('message' => 'Another Admin Added', 'class' => 'text-success text-center');
            $this->session->set_flashdata("item", $message);
            redirect('admin/manageAdmin', 'refresh');
        } else {
            $message = array('message' => 'Oops! Something Went Wrong!', 'class' => 'text-danger text-center');
            $this->session->set_flashdata("item", $message);
            redirect('admin/manageAdmin', 'refresh');
        }

    }

    public function email_check($email)
    {

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('email_check', 'Please Enter a valid Email id!');
            return FALSE;
        }
    }

    public function deleteAdmin(){
        $id = $this->input->get('id');
        $this->adminmodel->deleteAdmin($id);
        redirect('admin/manageAdmin', 'refresh');
    }


    public function productlist(){
        if (!$this->is_admin_logged_in()) {
            redirect('admin/', 'refresh');
        }
        
        $details['productDetails'] = $this->adminmodel->getProducts();

        // Get content
        ob_start();
        $this->load->view('admin/productlist', $details);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $data);
    }

    public function deleteProduct(){
        $id = $this->input->get('id');
        $this->adminmodel->deleteProduct($id);
        redirect('admin/productlist', 'refresh');
    }

    public function productinsert(){
        if (!$this->is_admin_logged_in()) {
            redirect('admin/', 'refresh');
        }

        // Get content
        ob_start();
        $this->load->view('admin/insertproduct');
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $data);
    }

    public function insertProduct() //function that handles the backend of insert-product
    {
        $data = array();
         $data['pname'] =  $this->input->post('productName');
         $data['category'] =  $this->input->post('category');
         $data['subcategory'] =  $this->input->post('subcategory');
         $data['brand'] =  $this->input->post('brand');
         $data['price'] =  $this->input->post('price');
         $data['discount'] =  $this->input->post('discount');
         $data['weight'] =  $this->input->post('weight');
         $data['unit'] =  $this->input->post('unit');
         $data['stock_quantity'] =  $this->input->post('stock_quantity');
         $data['expiry_date'] =  $this->input->post('expiry_date');

         // Create upload path based on category
         $category_folder = str_replace(' ', '_', str_replace('&', 'and', $data['category']));
         $upload_path = 'assets/img/grocery/' . $category_folder;
         
         // Create directory if it doesn't exist
         if (!is_dir($upload_path)) {
             mkdir($upload_path, 0755, true);
         }
         
         $config['upload_path']          = $upload_path;
         $config['allowed_types']        = 'gif|jpg|png|jpeg';
         $config['max_size']             = 10000;
         $config['max_width']            = 10000;
         $config['max_height']           = 10000;
         $config['encrypt_name']			= TRUE;
         $config['remove_spaces']		= TRUE;
          $config['overwrite']			= FALSE;

         $this->load->library('upload', $config);

         if ( ! $this->upload->do_upload('image'))
         {
                 $error = array('error' => $this->upload->display_errors());

                 print_r($error);
                 echo "<br>";
                 echo $config['upload_path'];
                 return; // Exit if image upload fails
         }
         else
         {
                 $imagePath = $this->upload->data();
                 $data['pimage'] = 'grocery/' . $category_folder . '/' . $imagePath['file_name'];
         }

        //  print_r($data);
        $this->adminmodel->insertProduct($data);

        $this->productlist();
    }

    public function manageorders(){
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }

        $userOrders['orderList'] = $this->ordermodel->totalOrders();

        // Get content
        ob_start();
        $this->load->view('admin/orderlist_new', $userOrders);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $data);
    }

    public function userslist()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $details['usersList'] = $this->adminmodel->getUsers();
        
        // Get content
        ob_start();
        $this->load->view('admin/userslist', $details);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $data);
    }
    
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('admin/', 'refresh');
    }
    
    // ===================== NEW ADMIN PAGES WITH WRAPPER =====================
    
    /**
     * Reports Page
     */
    public function reports()
    {
        if (!$this->is_admin_logged_in()) {
            redirect('admin/', 'refresh');
        }
        
        // Get reports data
        $details['reports_data'] = $this->get_reports_data();
        
        // Get content
        ob_start();
        $this->load->view('admin/reports', $details);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $data);
    }
    
    /**
     * Settings Page
     */
    public function settings()
    {
        if (!$this->is_admin_logged_in()) {
            redirect('admin/', 'refresh');
        }
        
        // Get system settings
        $details['settings'] = $this->get_system_settings();
        
        // Get content
        ob_start();
        $this->load->view('admin/settings', $details);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $data);
    }
    
    /**
     * Expiry alerts alias method (lowercase)
     */
    public function expiryalerts()
    {
        // Redirect to proper camelCase method
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $data['expiring_products'] = $this->get_expiring_products();
        
        // Get content
        ob_start();
        $this->load->view('admin/expiryalerts', $data);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $wrapper_data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $wrapper_data);
    }
    
    // ===================== HELPER METHODS =====================
    
    /**
     * Get categories data (placeholder)
     */
    private function get_categories_data()
    {
        // This would typically come from a categories table
        return [
            ['id' => 1, 'name' => 'Fruits & Vegetables', 'products' => 45, 'status' => 'active'],
            ['id' => 2, 'name' => 'Dairy & Bakery', 'products' => 23, 'status' => 'active'],
            ['id' => 3, 'name' => 'Grains & Rice', 'products' => 18, 'status' => 'active'],
            ['id' => 4, 'name' => 'Beverages', 'products' => 12, 'status' => 'active']
        ];
    }
    
    /**
     * Get expiring products data
     */
    private function get_expiring_products()
    {
        // Get products expiring within 7 days
        $products = $this->adminmodel->getProducts();
        $expiring = [];
        
        foreach ($products as $product) {
            if (isset($product['expiry_date']) && !empty($product['expiry_date'])) {
                $expiry = strtotime($product['expiry_date']);
                $today = time();
                $days_diff = ($expiry - $today) / (60 * 60 * 24);
                
                if ($days_diff <= 7 && $days_diff >= 0) {
                    $product['days_to_expire'] = (int)$days_diff;
                    $expiring[] = $product;
                }
            }
        }
        
        return $expiring;
    }
    
    /**
     * Get contact messages data (placeholder)
     */
    private function get_contact_messages()
    {
        // This would typically come from a contacts/messages table
        return [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'subject' => 'Product Inquiry', 'message' => 'Looking for organic vegetables', 'date' => date('Y-m-d H:i:s'), 'status' => 'unread'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'subject' => 'Delivery Issue', 'message' => 'Order was delivered late', 'date' => date('Y-m-d H:i:s', strtotime('-1 day')), 'status' => 'read']
        ];
    }
    
    /**
     * Get reports data
     */
    private function get_reports_data()
    {
        $products = $this->adminmodel->getProducts();
        $users = $this->adminmodel->getUsers();
        
        return [
            'total_products' => count($products),
            'total_users' => count($users),
            'total_orders' => 0, // Would get from orders table
            'monthly_revenue' => 0, // Would calculate from orders
            'top_products' => array_slice($products, 0, 5),
            'recent_signups' => array_slice($users, -5)
        ];
    }
    
    /**
     * Get system settings (placeholder)
     */
    private function get_system_settings()
    {
        return [
            'site_name' => 'Greenzio',
            'site_email' => 'admin@greenzio.com',
            'currency' => 'INR',
            'timezone' => 'Asia/Kolkata',
            'maintenance_mode' => false,
            'email_notifications' => true
        ];
    }
    
    // ===================== ADMIN PROFILE MANAGEMENT =====================
    
    /**
     * Admin Profile Page
     */
    public function profile()
    {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/', 'refresh');
        }
        
        $admin_id = $this->session->userdata('admin_id');
        $admin_data = $this->adminmodel->getAdminById($admin_id);
        
        if (empty($admin_data)) {
            // Fallback to session data if admin not found in database
            $session_data = $this->session->userdata();
            $admin_data = [
                'admin_id' => $session_data['admin_id'] ?? null,
                'admin_name' => $session_data['admin_name'] ?? 'Administrator',
                'admin_email' => $session_data['admin_email'] ?? '',
                'admin_role' => 'Super Admin',
                'status' => 'Active',
                'last_login' => date('Y-m-d H:i:s')
            ];
        } else {
            // Add missing fields to database data
            $admin_data['admin_role'] = $admin_data['admin_role'] ?? 'Super Admin';
            $admin_data['status'] = 'Active';
            $admin_data['last_login'] = date('Y-m-d H:i:s');
        }
        
        // Get profile content
        ob_start();
        $this->load->view('admin/profile', ['admin_details' => $admin_data]);
        $profile_content = ob_get_clean();
        
        // Load the clean admin wrapper with profile content
        $data['admin_content'] = $profile_content;
        $this->load->view('admin/admin_wrapper', $data);
    }
    
    /**
     * Update Admin Profile
     */
    public function updateProfile()
    {
        if (!$this->session->userdata('admin_logged_in')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $admin_id = $this->session->userdata('admin_id');
        $admin_name = $this->input->post('admin_name');
        $admin_email = $this->input->post('admin_email');
        $current_password = $this->input->post('current_password');
        $new_password = $this->input->post('new_password');
        $confirm_password = $this->input->post('confirm_password');
        
        // Validate required fields
        if (empty($admin_name) || empty($admin_email)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => 'Name and email are required']);
                return;
            }
            $this->session->set_flashdata('error', 'Name and email are required!');
            redirect('admin/profile', 'refresh');
            return;
        }
        
        $update_data = array(
            'admin_name' => $admin_name,
            'admin_email' => $admin_email
        );
        
        // Handle password change with proper current password verification
        if (!empty($new_password)) {
            if (empty($current_password)) {
                $this->session->set_flashdata('error', 'Current password is required to change password!');
                redirect('admin/profile', 'refresh');
                return;
            }
            
            if ($new_password !== $confirm_password) {
                $this->session->set_flashdata('error', 'New passwords do not match!');
                redirect('admin/profile', 'refresh');
                return;
            }
            
            if (strlen($new_password) < 6) {
                $this->session->set_flashdata('error', 'Password must be at least 6 characters long!');
                redirect('admin/profile', 'refresh');
                return;
            }
            
            // Verify current password
            $current_admin = $this->adminmodel->getAdminById($admin_id);
            if (!password_verify($current_password, $current_admin['admin_password']) && $current_password !== $current_admin['admin_password']) {
                $this->session->set_flashdata('error', 'Current password is incorrect!');
                redirect('admin/profile', 'refresh');
                return;
            }
            
            $update_data['admin_password'] = $new_password; // Will be hashed in model
        }
        
        // Update profile
        if ($this->adminmodel->updateAdmin($admin_id, $update_data)) {
            // Update session data
            $this->session->set_userdata('admin_name', $admin_name);
            $this->session->set_userdata('admin_email', $admin_email);
            
            $this->session->set_flashdata('success', 'Profile updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update profile. Please try again.');
        }
        
        redirect('admin/profile', 'refresh');
    }
    
    // ===================== USER MANAGEMENT FUNCTIONS =====================
    
    /**
     * Delete a user and all associated data
     */
    public function deleteUser()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $user_id = $this->input->get('id');
        if ($user_id) {
            $this->adminmodel->deleteUser($user_id);
            $this->session->set_flashdata("item", array('message' => 'User deleted successfully', 'class' => 'text-success'));
        } else {
            $this->session->set_flashdata("item", array('message' => 'Invalid user ID', 'class' => 'text-danger'));
        }
        redirect('admin/userslist', 'refresh');
    }
    
    /**
     * Edit user details form
     */
    public function editUser()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $user_id = $this->input->get('id');
        if (!$user_id) {
            $this->session->set_flashdata("item", array('message' => 'Invalid user ID', 'class' => 'text-danger'));
            redirect('admin/userslist', 'refresh');
        }
        
        $data['userDetails'] = $this->adminmodel->getUserById($user_id);
        if (empty($data['userDetails'])) {
            $this->session->set_flashdata("item", array('message' => 'User not found', 'class' => 'text-danger'));
            redirect('admin/userslist', 'refresh');
        }
        
        // Get content
        ob_start();
        $this->load->view('admin/edituser', $data);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $wrapper_data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $wrapper_data);
    }
    
    /**
     * Update user details
     */
    public function updateUser()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $user_id = $this->input->post('user_id');
        $email = $this->input->post('email');
        $mobile = $this->input->post('mobile');
        $password = $this->input->post('password');
        $gender = $this->input->post('gender');
        
        // Validation
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('mobile', 'Mobile', 'numeric|exact_length[10]');
        $this->form_validation->set_rules('gender', 'Gender', 'required|in_list[m,f]');
        
        if ($this->form_validation->run()) {
            $updateData = array(
                'email' => $email,
                'mobile' => !empty($mobile) ? $mobile : null,
                'gender' => $gender
            );
            
            // Update password if provided
            if (!empty($password)) {
                $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            
            $this->adminmodel->updateUser($user_id, $updateData);
            $this->session->set_flashdata("item", array('message' => 'User updated successfully', 'class' => 'text-success'));
        } else {
            $this->session->set_flashdata("item", array('message' => validation_errors(), 'class' => 'text-danger'));
        }
        
        redirect('admin/userslist', 'refresh');
    }
    
    // ===================== ORDER MANAGEMENT FUNCTIONS =====================
    
    /**
     * Update order status with comprehensive workflow management
     */
    public function updateOrderStatus()
    {
        if (!$this->session->userdata('admin_email')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }
        
        $order_id = $this->input->post('order_id');
        $status = $this->input->post('status');
        $admin_notes = $this->input->post('admin_notes') ?: '';
        
        // Valid status transitions
        $valid_statuses = ['pending', 'accepted', 'rejected', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        if (!$order_id || !in_array($status, $valid_statuses)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => 'Invalid order ID or status']);
                return;
            }
            $this->session->set_flashdata("item", array('message' => 'Invalid order ID or status', 'class' => 'text-danger'));
            redirect('admin/manageorders', 'refresh');
            return;
        }
        
        // Get current order details
        $current_order = $this->adminmodel->getOrderDetails($order_id);
        if (!$current_order) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => 'Order not found']);
                return;
            }
            $this->session->set_flashdata("item", array('message' => 'Order not found', 'class' => 'text-danger'));
            redirect('admin/manageorders', 'refresh');
            return;
        }
        
        $current_status = $current_order['order_status'];
        
        // Validate status transition
        if (!$this->isValidStatusTransition($current_status, $status)) {
            $message = "Cannot change status from {$current_status} to {$status}";
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => $message]);
                return;
            }
            $this->session->set_flashdata("item", array('message' => $message, 'class' => 'text-danger'));
            redirect('admin/manageorders', 'refresh');
            return;
        }
        
        // Update order status
        $update_data = [
            'order_status' => $status,
            'admin_notes' => $admin_notes,
            'status_updated_at' => date('Y-m-d H:i:s'),
            'updated_by_admin' => $this->session->userdata('admin_id')
        ];
        
        $result = $this->adminmodel->updateOrderStatusComplete($order_id, $update_data);
        
        if ($result) {
            // Log the status change
            $this->adminmodel->logOrderStatusChange($order_id, $current_status, $status, $this->session->userdata('admin_id'), $admin_notes);
            
            $message = "Order #{$order_id} status updated to {$status} successfully";
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => true, 'message' => $message, 'new_status' => $status]);
                return;
            }
            $this->session->set_flashdata("item", array('message' => $message, 'class' => 'text-success'));
        } else {
            $message = 'Failed to update order status';
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => $message]);
                return;
            }
            $this->session->set_flashdata("item", array('message' => $message, 'class' => 'text-danger'));
        }
        
        redirect('admin/manageorders', 'refresh');
    }
    
    /**
     * Validate if status transition is allowed
     */
    private function isValidStatusTransition($current_status, $new_status) {
        $valid_transitions = [
            'pending' => ['accepted', 'rejected', 'cancelled'],
            'accepted' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => [], // Final status
            'rejected' => [], // Final status  
            'cancelled' => [] // Final status
        ];
        
        return isset($valid_transitions[$current_status]) && 
               in_array($new_status, $valid_transitions[$current_status]);
    }
    
    /**
     * View detailed order information
     */
    public function viewOrder()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $order_id = $this->input->get('id');
        if (!$order_id) {
            $this->session->set_flashdata("item", array('message' => 'Invalid order ID', 'class' => 'text-danger'));
            redirect('admin/manageorders', 'refresh');
        }
        
        $data['orderDetails'] = $this->adminmodel->getOrderDetails($order_id);
        $data['orderItems'] = $this->adminmodel->getOrderItems($order_id);
        $data['billingDetails'] = $this->adminmodel->getBillingDetails($order_id);
        $data['orderHistory'] = $this->adminmodel->getOrderStatusHistory($order_id);
        
        if (empty($data['orderDetails'])) {
            $this->session->set_flashdata("item", array('message' => 'Order not found', 'class' => 'text-danger'));
            redirect('admin/manageorders', 'refresh');
        }
        
        // Get content
        ob_start();
        $this->load->view('admin/vieworder', $data);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $wrapper_data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $wrapper_data);
    }
    
    /**
     * Quick accept order (AJAX)
     */
    public function acceptOrder()
    {
        if (!$this->session->userdata('admin_email')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }
        
        $order_id = $this->input->post('order_id');
        $admin_notes = $this->input->post('admin_notes') ?: 'Order accepted by admin';
        
        if (!$order_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
            return;
        }
        
        // Check if order is pending
        $order = $this->adminmodel->getOrderDetails($order_id);
        if (!$order || $order['order_status'] !== 'pending') {
            echo json_encode(['success' => false, 'message' => 'Order cannot be accepted']);
            return;
        }
        
        $update_data = [
            'order_status' => 'accepted',
            'admin_notes' => $admin_notes,
            'status_updated_at' => date('Y-m-d H:i:s'),
            'updated_by_admin' => $this->session->userdata('admin_id')
        ];
        
        $result = $this->adminmodel->updateOrderStatusComplete($order_id, $update_data);
        
        if ($result) {
            $this->adminmodel->logOrderStatusChange($order_id, 'pending', 'accepted', $this->session->userdata('admin_id'), $admin_notes);
            echo json_encode(['success' => true, 'message' => 'Order accepted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to accept order']);
        }
    }
    
    /**
     * Quick reject order (AJAX)
     */
    public function rejectOrder()
    {
        if (!$this->session->userdata('admin_email')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }
        
        $order_id = $this->input->post('order_id');
        $rejection_reason = $this->input->post('rejection_reason') ?: 'Order rejected by admin';
        
        if (!$order_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
            return;
        }
        
        // Check if order is pending
        $order = $this->adminmodel->getOrderDetails($order_id);
        if (!$order || $order['order_status'] !== 'pending') {
            echo json_encode(['success' => false, 'message' => 'Order cannot be rejected']);
            return;
        }
        
        $update_data = [
            'order_status' => 'rejected',
            'admin_notes' => $rejection_reason,
            'status_updated_at' => date('Y-m-d H:i:s'),
            'updated_by_admin' => $this->session->userdata('admin_id')
        ];
        
        $result = $this->adminmodel->updateOrderStatusComplete($order_id, $update_data);
        
        if ($result) {
            // Restore product stock when order is rejected
            $orderItems = $this->adminmodel->getOrderItems($order_id);
            foreach ($orderItems as $item) {
                $this->adminmodel->updateProductStock($item['product_id'], $item['product_quantity'], 'add');
            }
            
            $this->adminmodel->logOrderStatusChange($order_id, 'pending', 'rejected', $this->session->userdata('admin_id'), $rejection_reason);
            echo json_encode(['success' => true, 'message' => 'Order rejected successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to reject order']);
        }
    }
    
    /**
     * Get orders by status (AJAX)
     */
    public function getOrdersByStatus()
    {
        if (!$this->session->userdata('admin_email')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }
        
        $status = $this->input->get('status');
        $limit = $this->input->get('limit') ?: 50;
        $offset = $this->input->get('offset') ?: 0;
        
        if ($status === 'all') {
            $orders = $this->adminmodel->getAllOrdersPaginated($limit, $offset);
        } else {
            $orders = $this->adminmodel->getOrdersByStatus($status, $limit, $offset);
        }
        
        echo json_encode(['success' => true, 'orders' => $orders, 'count' => count($orders)]);
    }
    
    // ===================== CATEGORY MANAGEMENT FUNCTIONS =====================
    
    /**
     * Manage product categories
     */
    public function manageCategories()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $data['categories'] = $this->get_categories_data();
        
        // Get content
        ob_start();
        $this->load->view('admin/categories', $data);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $wrapper_data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $wrapper_data);
    }
    
    // ===================== GROCERY-SPECIFIC FEATURES =====================
    
    
    /**
     * Perishable Inventory Management
     */
    public function perishableInventory()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $data['perishableProducts'] = $this->adminmodel->getPerishableProducts();
        $data['rotationAlerts'] = $this->adminmodel->getStockRotationAlerts();
        
        // Get content
        ob_start();
        $this->load->view('admin/perishableinventory', $data);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $wrapper_data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $wrapper_data);
    }
    
    /**
     * Update product stock (AJAX)
     */
    public function updateStock()
    {
        if (!$this->session->userdata('admin_email')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $product_id = $this->input->post('product_id');
        $new_stock = $this->input->post('stock_quantity');
        $operation = $this->input->post('operation'); // 'set', 'add', 'subtract'
        
        if (!$product_id || !is_numeric($new_stock)) {
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
            return;
        }
        
        $result = $this->adminmodel->updateProductStock($product_id, $new_stock, $operation);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Stock updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update stock']);
        }
    }
    
    /**
     * Batch update products (for bulk operations)
     */
    public function batchUpdate()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $data['categories'] = [
            ['id' => 1, 'name' => 'Fruits & Vegetables', 'products' => 45, 'status' => 'active'],
            ['id' => 2, 'name' => 'Dairy & Bakery', 'products' => 23, 'status' => 'active'],
            ['id' => 3, 'name' => 'Grains & Rice', 'products' => 18, 'status' => 'active'],
            ['id' => 4, 'name' => 'Beverages', 'products' => 12, 'status' => 'active']
        ];
        
        // Use new admin wrapper for consistency
        ob_start();
        $this->load->view('admin/categories', $data);
        $content = ob_get_clean();
        
        $wrapper_data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $wrapper_data);
    }
    
    /**
     * Process batch update (AJAX)
     */
    public function processBatchUpdate()
    {
        if (!$this->session->userdata('admin_email')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $update_type = $this->input->post('update_type');
        $category = $this->input->post('category');
        $value = $this->input->post('value');
        $product_ids = $this->input->post('product_ids'); // Array of IDs
        
        $success_count = 0;
        $total_count = 0;
        
        if ($update_type === 'discount' && is_array($product_ids)) {
            foreach ($product_ids as $pid) {
                $total_count++;
                if ($this->adminmodel->updateProductDiscount($pid, $value)) {
                    $success_count++;
                }
            }
        } elseif ($update_type === 'price_increase' && !empty($category)) {
            $result = $this->adminmodel->batchUpdateCategoryPrices($category, $value, 'increase');
            $success_count = $result['updated'];
            $total_count = $result['total'];
        } elseif ($update_type === 'price_decrease' && !empty($category)) {
            $result = $this->adminmodel->batchUpdateCategoryPrices($category, $value, 'decrease');
            $success_count = $result['updated'];
            $total_count = $result['total'];
        }
        
        echo json_encode([
            'success' => true,
            'message' => "Updated {$success_count} out of {$total_count} products",
            'success_count' => $success_count,
            'total_count' => $total_count
        ]);
    }
    
    /**
     * Generate inventory report
     */
    public function inventoryReport()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $report_type = $this->input->get('type') ?: 'summary';
        $data['report_type'] = $report_type;
        
        switch ($report_type) {
            case 'low_stock':
                $data['report_data'] = $this->adminmodel->getLowStockProducts(20); // Top 20
                break;
            case 'expiry':
                $data['report_data'] = $this->adminmodel->getExpiringSoonProducts(14); // 2 weeks
                break;
            case 'category_wise':
                $data['report_data'] = $this->adminmodel->getCategoryWiseStock();
                break;
            default:
                $data['report_data'] = $this->adminmodel->getInventorySummary();
        }
        
        // Get content
        ob_start();
        $this->load->view('admin/inventory_report', $data);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $wrapper_data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $wrapper_data);
    }
    
    /**
     * Add new category (Note: For grocery e-commerce, categories are managed through products)
     */
    public function addCategory()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $category_name = $this->input->post('category_name');
        
        $this->form_validation->set_rules('category_name', 'Category Name', 'required|trim');
        
        if ($this->form_validation->run()) {
            // For grocery e-commerce, categories are managed through product entries
            // This is more of a placeholder - in practice, categories are added when products are added
            $data = array(
                'category_name' => $category_name
            );
            
            $this->adminmodel->addCategory($data);
            $this->session->set_flashdata("item", array('message' => 'Category noted (categories are managed through products)', 'class' => 'text-info'));
        } else {
            $this->session->set_flashdata("item", array('message' => validation_errors(), 'class' => 'text-danger'));
        }
        
        redirect('admin/manageCategories', 'refresh');
    }
    
    /**
     * Delete category
     */
    public function deleteCategory()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $category_id = $this->input->get('id');
        if ($category_id) {
            $this->adminmodel->deleteCategory($category_id);
            $this->session->set_flashdata("item", array('message' => 'Category deleted successfully', 'class' => 'text-success'));
        } else {
            $this->session->set_flashdata("item", array('message' => 'Invalid category ID', 'class' => 'text-danger'));
        }
        
        redirect('admin/manageCategories', 'refresh');
    }
    
    // ===================== STOCK MANAGEMENT =====================
    
    /**
     * Stock management dashboard
     */
    public function stockmanagement()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $data['productDetails'] = $this->adminmodel->getProducts();
        
        // Get content
        ob_start();
        $this->load->view('admin/stockmanagement', $data);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $wrapper_data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $wrapper_data);
    }
    
    /**
     * Bulk update stock quantities
     */
    public function bulkUpdateStock()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $product_id = $this->input->post('product_id');
        $operation = $this->input->post('operation');
        $quantity = $this->input->post('quantity');
        
        if ($product_id && $operation && $quantity !== '') {
            $this->load->model('products');
            
            if ($operation === 'set') {
                // Set exact stock quantity
                $this->db->where('pid', $product_id);
                $this->db->update('product', array('stock_quantity' => $quantity));
                $message = 'Stock quantity set to ' . $quantity;
            } else {
                // Add or subtract stock
                $result = $this->products->updateStock($product_id, $quantity, $operation);
                if ($result !== false) {
                    $action = ($operation === 'add') ? 'added to' : 'removed from';
                    $message = $quantity . ' units ' . $action . ' stock. New quantity: ' . $result;
                } else {
                    $message = 'Failed to update stock';
                }
            }
            
            $this->session->set_flashdata("item", array('message' => $message, 'class' => 'text-success'));
        } else {
            $this->session->set_flashdata("item", array('message' => 'Invalid input parameters', 'class' => 'text-danger'));
        }
        
        redirect('admin/stockmanagement', 'refresh');
    }
    
    /**
     * Update product expiry date
     */
    public function updateExpiryDate()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $product_id = $this->input->post('product_id');
        $expiry_date = $this->input->post('expiry_date');
        
        if ($product_id && $expiry_date) {
            $this->load->model('products');
            
            $result = $this->products->updateExpiryDate($product_id, $expiry_date);
            
            if ($result) {
                $message = 'Expiry date updated successfully';
                $this->session->set_flashdata("item", array('message' => $message, 'class' => 'text-success'));
            } else {
                $message = 'Failed to update expiry date';
                $this->session->set_flashdata("item", array('message' => $message, 'class' => 'text-danger'));
            }
        } else {
            $this->session->set_flashdata("item", array('message' => 'Invalid input parameters', 'class' => 'text-danger'));
        }
        
        redirect('admin/stockmanagement', 'refresh');
    }
    
    /**
     * AJAX endpoint for getting stock and expiry alerts
     */
    public function getStockAlerts()
    {
        if (!$this->session->userdata('admin_email')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $this->load->model('products');
        
        $alerts = [
            'low_stock' => $this->products->getLowStockProducts(),
            'out_of_stock' => $this->products->getOutOfStockProducts(),
            'expiring_soon' => $this->products->getExpiringProducts()
        ];
        
        echo json_encode(['success' => true, 'alerts' => $alerts]);
    }
    
    // ===================== CONTACT MANAGEMENT =====================
    
    /**
     * Manage contact messages
     */
    public function manageContacts()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $data['messages'] = $this->get_contact_messages();
        
        // Get content
        ob_start();
        $this->load->view('admin/messages', $data);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $wrapper_data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $wrapper_data);
    }
    
    /**
     * Delete contact message
     */
    public function deleteContact()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $contact_id = $this->input->get('id');
        if ($contact_id) {
            $this->adminmodel->deleteContact($contact_id);
            $this->session->set_flashdata("item", array('message' => 'Contact message deleted successfully', 'class' => 'text-success'));
        } else {
            $this->session->set_flashdata("item", array('message' => 'Invalid contact ID', 'class' => 'text-danger'));
        }
        
        redirect('admin/manageContacts', 'refresh');
    }
    
    // ===================== SUPPLIER MANAGEMENT =====================
    
    /**
     * Supplier Management Page
     */
    public function suppliermanagement()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        // For now, use dummy data since suppliers table may not exist
        $data['suppliers'] = $this->getDummySuppliers();
        
        // Get content
        ob_start();
        $this->load->view('admin/suppliermanagement', $data);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $wrapper_data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $wrapper_data);
    }
    
    /**
     * Get dummy supplier data for testing
     */
    private function getDummySuppliers()
    {
        return [
            [
                'supplier_id' => 1,
                'supplier_name' => 'Fresh Farms Ltd',
                'contact_person' => 'John Smith',
                'phone' => '9876543210',
                'email' => 'contact@freshfarms.com',
                'address' => '123 Farm Road, Village',
                'specialization' => 'Fruits & Vegetables',
                'payment_terms' => 'net_30',
                'status' => 'active',
                'orders_count' => 25,
                'rating' => 4.5
            ],
            [
                'supplier_id' => 2,
                'supplier_name' => 'Dairy Direct Co',
                'contact_person' => 'Mary Johnson',
                'phone' => '9876543211',
                'email' => 'orders@dairydirect.com',
                'address' => '456 Milk Street, City',
                'specialization' => 'Dairy & Bakery',
                'payment_terms' => 'cash_on_delivery',
                'status' => 'active',
                'orders_count' => 18,
                'rating' => 4.0
            ],
            [
                'supplier_id' => 3,
                'supplier_name' => 'Grain & Rice Co',
                'contact_person' => 'Robert Brown',
                'phone' => '9876543212',
                'email' => 'info@grainrice.com',
                'address' => '789 Grain Avenue, Town',
                'specialization' => 'Grains & Rice',
                'payment_terms' => 'net_15',
                'status' => 'inactive',
                'orders_count' => 12,
                'rating' => 3.8
            ]
        ];
    }
    
    /**
     * Add new supplier
     */
    public function addSupplier()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        // For now, just show success message since suppliers table may not exist
        $this->session->set_flashdata("item", array('message' => 'Supplier functionality is ready for implementation', 'class' => 'text-info'));
        redirect('admin/suppliermanagement', 'refresh');
    }
    
    // ===================== NOTIFICATION SYSTEM =====================
    
    /**
     * Get admin notifications for the header dropdown (AJAX)
     */
    public function getNotifications()
    {
        if (!$this->session->userdata('admin_email')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $this->load->model('products');
        
        $notifications = [];
        
        // Get expiry alerts
        $expiringProducts = $this->adminmodel->getExpiringSoonProducts(7);
        if (!empty($expiringProducts)) {
            $notifications[] = [
                'id' => 'expiry-alert',
                'type' => 'warning',
                'icon' => 'fas fa-exclamation-triangle',
                'title' => 'Expiry Alerts',
                'message' => count($expiringProducts) . ' products expiring soon',
                'time' => '2 minutes ago',
                'link' => base_url('admin/expiryalerts'),
                'priority' => 'high'
            ];
        }
        
        // Get low stock alerts
        $lowStockProducts = $this->adminmodel->getLowStockProducts(20);
        if (!empty($lowStockProducts)) {
            $notifications[] = [
                'id' => 'stock-alert',
                'type' => 'danger',
                'icon' => 'fas fa-box',
                'title' => 'Low Stock Alert',
                'message' => count($lowStockProducts) . ' products low in stock',
                'time' => '15 minutes ago',
                'link' => base_url('admin/stockmanagement'),
                'priority' => 'high'
            ];
        }
        
        // Get new orders
        $recentOrders = $this->adminmodel->getRecentOrders(24); // Last 24 hours
        if (!empty($recentOrders)) {
            $notifications[] = [
                'id' => 'new-orders',
                'type' => 'success',
                'icon' => 'fas fa-shopping-cart',
                'title' => 'New Orders',
                'message' => count($recentOrders) . ' new orders received',
                'time' => '1 hour ago',
                'link' => base_url('admin/manageorders'),
                'priority' => 'medium'
            ];
        }
        
        // Get pending user registrations (if applicable)
        $recentUsers = $this->adminmodel->getRecentUsers(24);
        if (!empty($recentUsers)) {
            $notifications[] = [
                'id' => 'new-users',
                'type' => 'info',
                'icon' => 'fas fa-users',
                'title' => 'New Users',
                'message' => count($recentUsers) . ' users registered today',
                'time' => '3 hours ago',
                'link' => base_url('admin/userslist'),
                'priority' => 'low'
            ];
        }
        
        // Get contact messages
        $recentContacts = $this->adminmodel->getRecentContacts(7); // Last 7 days
        if (!empty($recentContacts)) {
            $notifications[] = [
                'id' => 'contact-messages',
                'type' => 'info',
                'icon' => 'fas fa-envelope',
                'title' => 'Contact Messages',
                'message' => count($recentContacts) . ' new messages this week',
                'time' => '4 hours ago',
                'link' => base_url('admin/manageContacts'),
                'priority' => 'low'
            ];
        }
        
        // Sort notifications by priority
        usort($notifications, function($a, $b) {
            $priority_order = ['high' => 1, 'medium' => 2, 'low' => 3];
            return $priority_order[$a['priority']] <=> $priority_order[$b['priority']];
        });
        
        // Limit to top 10 notifications
        $notifications = array_slice($notifications, 0, 10);
        
        echo json_encode([
            'success' => true,
            'notifications' => $notifications,
            'count' => count($notifications)
        ]);
    }
    
    /**
     * Mark notification as read (AJAX)
     */
    public function markNotificationRead()
    {
        if (!$this->session->userdata('admin_email')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $notification_id = $this->input->post('notification_id');
        
        // For now, just return success since we're using dynamic notifications
        // In a full implementation, you'd store read status in the database
        echo json_encode(['success' => true]);
    }
    
    /**
     * Get admin dashboard statistics for mobile header
     */
    public function getDashboardStats()
    {
        if (!$this->session->userdata('admin_email')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $stats = [
            'notifications' => 3, // This could be dynamic based on actual alerts
            'new_orders' => count($this->adminmodel->getRecentOrders(24)),
            'alerts' => count($this->adminmodel->getExpiringSoonProducts(7)) + count($this->adminmodel->getLowStockProducts(10))
        ];
        
        echo json_encode(['success' => true, 'stats' => $stats]);
    }
    
    // ===================== ADMIN PROFILE AND SETTINGS =====================
    
    
    /**
     * Admin activity log (placeholder)
     */
    public function activitylog()
    {
        if (!$this->session->userdata('admin_email')) {
            redirect('admin/', 'refresh');
        }
        
        $data['activities'] = [
            [
                'timestamp' => date('Y-m-d H:i:s'),
                'admin_name' => $this->session->userdata('admin_name'),
                'action' => 'Login',
                'description' => 'Admin logged into the system',
                'ip_address' => $this->input->ip_address()
            ],
            [
                'timestamp' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'admin_name' => $this->session->userdata('admin_name'),
                'action' => 'Product Update',
                'description' => 'Updated product stock levels',
                'ip_address' => $this->input->ip_address()
            ]
        ];
        
        // Get content
        ob_start();
        $this->load->view('admin/activitylog', $data);
        $content = ob_get_clean();
        
        // Load with admin wrapper
        $wrapper_data['admin_content'] = $content;
        $this->load->view('admin/admin_wrapper', $wrapper_data);
    }
    
    /**
     * Emergency password reset (for development/testing)
     * Remove this method in production!
     */
    public function resetPassword()
    {
        // Only allow in development environment
        if (ENVIRONMENT === 'production') {
            show_404();
            return;
        }
        
        $email = $this->input->get('email');
        $new_password = $this->input->get('password') ?: 'admin123';
        
        if ($email) {
            $result = $this->adminmodel->resetAdminPassword($email, $new_password);
            if ($result) {
                echo "Password reset successful for: {$email}<br>";
                echo "New password: {$new_password}<br>";
                echo "<a href='" . base_url('admin/') . "'>Go to Admin Login</a>";
            } else {
                echo "Password reset failed. Admin not found.";
            }
        } else {
            echo "Usage: /admin/resetPassword?email=youremail@example.com&password=newpassword";
        }
    }
}
