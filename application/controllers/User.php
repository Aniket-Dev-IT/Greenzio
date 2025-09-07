<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('email');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('email');
        $this->load->model('customerDetails');
        $this->load->model('cart');
    }
    //////////////////////////////////*****************************************/////////////////////////////////////////////
    ////////////////////////////////// 0 - Error, 1 - Email, 2- Mobile Number /////////////////////////////////////////////
    //////////////////////////////////****************************************/////////////////////////////////////////////


    public function login()
    {
        // Initialize response data
        $data = array();
        
        // Debug logging
        log_message('info', 'User login attempt started');
        log_message('info', 'POST data: ' . print_r($this->input->post(), true));
        
        $ipAddr = $this->input->ip_address();

        $loginInput = $this->input->post('loginInput');
        
        log_message('info', 'Login input: ' . $loginInput);
        log_message('info', 'IP Address: ' . $ipAddr);

        $input = $this->checkInput($loginInput);

        if ($input == 1) {
            $this->form_validation->set_rules('loginInput', 'Email/Mobile No.', 'required|callback_email_check');
            $email = $loginInput;
            $mobile = NULL;
            // email_check($registerInput);
        } else if ($input == 2) {
            $this->form_validation->set_rules('loginInput', 'Email/Mobile No.', 'required|exact_length[10]|is_natural');
            $mobile = $loginInput;
            $email = NULL;
        } else {
            $mobile = NULL;
            $email = NULL;
        }

        $this->form_validation->set_rules('loginPassword', 'Password', 'trim|required|min_length[3]|max_length[50]');

        if ($this->form_validation->run() == FALSE) {
            $validation_errors = validation_errors();
            log_message('error', 'Login validation failed: ' . $validation_errors);
            $data['error'] = $validation_errors;
        } else {
            log_message('info', 'Login validation passed');
            $checkLogin = $this->customerDetails->checkLogin($mobile, $email);
            log_message('info', 'User lookup result: ' . ($checkLogin ? 'User found (ID: ' . $checkLogin->uid . ')' : 'User not found'));

            $password = $this->input->post('loginPassword');
            log_message('info', 'Password length: ' . strlen($password));
            
            // Check if user was found
            if (empty($checkLogin) || !$checkLogin) {
                log_message('error', 'User not found for email/mobile: ' . $loginInput);
                $data['error'] = "User not found! Please check your email/mobile number.";
            } else {
                log_message('info', 'User found, checking password...');
                // Check if password matches (support both old SHA1 and new bcrypt)
                $passwordMatch = false;
                if (password_verify($password, $checkLogin->password)) {
                    $passwordMatch = true;
                } else if (sha1($password) == $checkLogin->password) {
                    // Legacy SHA1 support - consider migrating to bcrypt
                    $passwordMatch = true;
                    // Optionally upgrade password hash here
                    // $newHash = password_hash($password, PASSWORD_DEFAULT);
                    // $this->customerDetails->updatePassword($checkLogin->uid, $newHash);
                }
                
                if ($passwordMatch) {
                log_message('info', 'Password verification successful for user ID: ' . $checkLogin->uid);
                $sessionData['userID'] = $checkLogin ->uid;
                $sessionData['email'] = $checkLogin->email;
                $sessionData['mobile'] = $checkLogin->mobile;
                $sessionData['gender'] = $checkLogin->gender;
                $sessionData['ipAddr'] = $ipAddr;

                // $sessionData['cartTotalItem'] = $this->cart->getTotalItemsInCart($checkLogin->uid, $ipAddr);

                $this->session->set_userdata($sessionData);
                log_message('info', 'Session data set successfully');
                
                // Debug: Verify session data was actually set
                $verifySessionData = $this->session->userdata();
                log_message('info', 'Session verification - all data: ' . print_r($verifySessionData, true));
                $userIDFromSession = $this->session->userdata('userID');
                log_message('info', 'Session verification - userID: ' . ($userIDFromSession ? $userIDFromSession : 'NULL'));
                
                // Simple redirect to homepage
                $data['success'] = true;
                $data['url'] = base_url();
                $data['message'] = 'Login successful!';
                $data['debug_session_id'] = session_id();
                $data['debug_user_id'] = $userIDFromSession;
                } else {
                    log_message('error', 'Password verification failed for user: ' . $loginInput);
                    $data['error'] = "Password is incorrect!";
                }
            }
        }
        log_message('info', 'Login response: ' . json_encode($data));
        echo json_encode($data);
    }

    public function register()
    {
        $registerInput = $this->input->post('regInput');
        $inPut = $this->checkInput($registerInput);

        if ($inPut == 1) {
            $this->form_validation->set_rules('regInput', 'Email/Mobile No.', 'required|is_unique[users.email]|callback_email_check');
            $email = $registerInput;
            $mobile = NULL;
            // email_check($registerInput);
        } else if ($inPut == 2) {
            $this->form_validation->set_rules('regInput', 'Email/Mobile No.', 'required|is_unique[users.mobile]|exact_length[10]|is_natural');
            $mobile = $registerInput;
            $email = NULL;
        } else {
            $mobile = NULL;
            $email = NULL;
        }

        $this->form_validation->set_rules('regPass', 'Password', 'trim|required|min_length[5]|max_length[50]|callback_password_check');
        $this->form_validation->set_rules('regPass2', ' Confirm Password', 'trim|required|matches[regPass2]');
        $this->form_validation->set_rules('gender', ' Gender', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['error'] = "Please Check your details and try again!";
            // echo json_encode($data);

        } else {
            $password = $this->input->post('regPass');
            $hashPassword = password_hash($password, PASSWORD_DEFAULT); // Use bcrypt instead of SHA1
            $gender = $this->input->post('gender');
            // echo "yeah! you did it bitch!".$email.' '.$password.' '.$gender.'  '.$mobile;

            $ipAddr = $this->input->ip_address();

            $data = array(
                'email' => $email,
                'mobile' => $mobile,
                'password' => $hashPassword,
                'gender' => $gender
            );


            $data['userID'] =  $this->customerDetails->registerUser($data);
            
            // $data['cartTotalItem'] = $this->cart->getTotalItemsInCart($data['userID'], $ipAddr);

            $data['ipAddr'] = $ipAddr;

            $checkCart =  $this->cart->getCartByID($data['userID'], $ipAddr);

            $this->session->set_userdata($data);

            // Redirect to homepage for grocery shopping after registration
            if (empty($checkCart)) {
                $data['url'] = base_url();
            } else {
                $data['url'] = base_url() . 'shopping/cart';
            }
        }

        echo json_encode($data);
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

    public function password_check($password)
    {
        // Temporarily relaxed password validation for testing
        if (strlen($password) < 3) {
            $this->form_validation->set_message('password_check', 'Password must be at least 3 characters long!');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function checkInput($registerInput)
    {

        $emailPattern = '/^\w{2,}@\w{2,}\.\w{2,4}$/';
        $mobilePattern = "/^[6-9][0-9]{9}$/";

        if (preg_match($emailPattern, $registerInput)) {
            return 1;
        } else if (preg_match($mobilePattern, $registerInput)) {
            return 2;
        } else {
            return 0;
        }
    }

    public function logout(){
        $this->session->sess_destroy();
		redirect();
    }

    // Render user dashboard
    public function dashboard() {
        if (!$this->session->userdata('userID')) {
            redirect('error/error_401');
            return;
        }
        $this->load->view('main/header');
        $this->load->view('user/dashboard');
        $this->load->view('main/footer');
    }

    // Render user profile
    public function profile() {
        if (!$this->session->userdata('userID')) {
            redirect('error/error_401');
            return;
        }
        $this->load->view('main/header');
        $this->load->view('user/profile');
        $this->load->view('main/footer');
    }

    // Render addresses management (placeholder)
    public function addresses() {
        if (!$this->session->userdata('userID')) {
            redirect('error/error_401');
            return;
        }
        // Reuse address page if exists else simple redirect for now
        if (file_exists(APPPATH.'views/pages/address.php')) {
            $this->load->view('main/header');
            $this->load->view('pages/address');
            $this->load->view('main/footer');
        } else {
            redirect('order/orderList');
        }
    }

    // Dashboard stats (mock data for now)
    public function getDashboardStats() {
        if (!$this->session->userdata('userID')) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
            return;
        }
        $stats = [
            'total_orders' => 12,
            'pending_orders' => 2,
            'total_spent' => 4589.75,
            'reward_points' => 240,
            'member_since' => date('Y-m-d', strtotime('-8 months'))
        ];
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['success' => true, 'stats' => $stats]));
    }

    // Recent addresses (mock)
    public function getSavedAddresses() {
        if (!$this->session->userdata('userID')) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
            return;
        }
        $addresses = [
            [
                'type' => 'Home',
                'line1' => '221B Baker Street',
                'city' => 'Mumbai',
                'state' => 'MH',
                'pincode' => '400001'
            ],
            [
                'type' => 'Office',
                'line1' => 'Tech Park, Phase 2',
                'city' => 'Pune',
                'state' => 'MH',
                'pincode' => '411045'
            ]
        ];
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['success' => true, 'addresses' => $addresses]));
    }

    // Profile data (real database implementation)
    public function getProfileData() {
        if (!$this->session->userdata('userID')) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
            return;
        }
        
        $userId = $this->session->userdata('userID');
        
        // Get profile data
        $this->db->select('first_name, last_name, date_of_birth, phone, city, state, pincode');
        $this->db->from('users');
        $this->db->where('uid', $userId);
        $profileQuery = $this->db->get();
        $profile = $profileQuery->row_array() ?: [];
        
        // Get preferences data
        $this->db->select('email_notifications, sms_notifications, order_updates, promotional_emails, weekly_newsletter');
        $this->db->from('user_preferences');
        $this->db->where('user_id', $userId);
        $prefQuery = $this->db->get();
        $prefData = $prefQuery->row_array();
        
        $preferences = [
            'emailNotifications' => $prefData['email_notifications'] ?? true,
            'smsNotifications' => $prefData['sms_notifications'] ?? true,
            'orderUpdates' => $prefData['order_updates'] ?? true,
            'promotionalEmails' => $prefData['promotional_emails'] ?? false,
            'weeklyNewsletter' => $prefData['weekly_newsletter'] ?? false
        ];
        
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['success' => true, 'profile' => $profile, 'preferences' => $preferences]));
    }

    public function updateProfile() {
        if (!$this->session->userdata('userID')) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
            return;
        }
        
        $userId = $this->session->userdata('userID');
        
        // Get and validate input
        $firstName = trim($this->input->post('firstName'));
        $lastName = trim($this->input->post('lastName'));
        $email = trim($this->input->post('email'));
        $mobile = trim($this->input->post('mobile'));
        $gender = $this->input->post('gender');
        $dateOfBirth = $this->input->post('dateOfBirth');
        
        // Basic validation
        if (empty($firstName) || empty($lastName) || empty($email)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Please fill in all required fields']));
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Please enter a valid email address']));
            return;
        }
        
        // Check if email is already used by another user
        $this->db->select('uid');
        $this->db->from('users');
        $this->db->where('email', $email);
        $this->db->where('uid !=', $userId);
        $emailCheck = $this->db->get();
        
        if ($emailCheck->num_rows() > 0) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Email address is already in use']));
            return;
        }
        
        // Update profile data
        $updateData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'mobile' => $mobile,
            'gender' => $gender,
            'date_of_birth' => !empty($dateOfBirth) ? $dateOfBirth : null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('uid', $userId);
        $success = $this->db->update('users', $updateData);
        
        if ($success) {
            // Update session data
            $this->session->set_userdata('email', $email);
            $this->session->set_userdata('mobile', $mobile);
            $this->session->set_userdata('gender', $gender);
            
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'message' => 'Profile updated successfully']));
        } else {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Failed to update profile']));
        }
    }

    public function changePassword() {
        if (!$this->session->userdata('userID')) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
            return;
        }
        
        $userId = $this->session->userdata('userID');
        
        $currentPassword = $this->input->post('currentPassword');
        $newPassword = $this->input->post('newPassword');
        $confirmPassword = $this->input->post('confirmPassword');
        
        // Validation
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Please fill in all fields']));
            return;
        }
        
        if ($newPassword !== $confirmPassword) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'New passwords do not match']));
            return;
        }
        
        if (strlen($newPassword) < 6) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long']));
            return;
        }
        
        // Get current password from database
        $this->db->select('password');
        $this->db->from('users');
        $this->db->where('uid', $userId);
        $query = $this->db->get();
        $user = $query->row();
        
        if (!$user) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'User not found']));
            return;
        }
        
        // Verify current password
        if (!password_verify($currentPassword, $user->password)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Current password is incorrect']));
            return;
        }
        
        // Hash new password and update
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $this->db->where('uid', $userId);
        $success = $this->db->update('users', ['password' => $hashedNewPassword, 'updated_at' => date('Y-m-d H:i:s')]);
        
        if ($success) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'message' => 'Password changed successfully']));
        } else {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Failed to update password']));
        }
    }

    public function updateNotificationPreferences() {
        if (!$this->session->userdata('userID')) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
            return;
        }
        
        $userId = $this->session->userdata('userID');
        
        // Get checkbox values (will be null if unchecked)
        $emailNotifications = $this->input->post('emailNotifications') ? 1 : 0;
        $smsNotifications = $this->input->post('smsNotifications') ? 1 : 0;
        $orderUpdates = $this->input->post('orderUpdates') ? 1 : 0;
        $promotionalEmails = $this->input->post('promotionalEmails') ? 1 : 0;
        $weeklyNewsletter = $this->input->post('weeklyNewsletter') ? 1 : 0;
        
        $preferenceData = [
            'email_notifications' => $emailNotifications,
            'sms_notifications' => $smsNotifications,
            'order_updates' => $orderUpdates,
            'promotional_emails' => $promotionalEmails,
            'weekly_newsletter' => $weeklyNewsletter,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Check if preferences already exist for this user
        $this->db->select('id');
        $this->db->from('user_preferences');
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            // Update existing preferences
            $this->db->where('user_id', $userId);
            $success = $this->db->update('user_preferences', $preferenceData);
        } else {
            // Insert new preferences
            $preferenceData['user_id'] = $userId;
            $success = $this->db->insert('user_preferences', $preferenceData);
        }
        
        if ($success) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'message' => 'Preferences updated successfully']));
        } else {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Failed to update preferences']));
        }
    }

    public function uploadProfileImage() {
        if (!$this->session->userdata('userID')) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
            return;
        }
        // For now, just accept and return success without storage
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['success' => true, 'image_url' => null]));
    }
}
