<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Controller Class
 * Contains common functionality for all controllers
 */
class Base_Controller extends CI_Controller {

    protected $data = [];
    protected $view_data = [];
    protected $user_data = [];
    protected $admin_data = [];
    
    public function __construct() {
        parent::__construct();
        
        // Load common helpers
        $this->load->helper(['url', 'form', 'html', 'common', 'validation']);
        
        // Load common libraries
        $this->load->library(['session', 'form_validation', 'user_agent']);
        
        // Initialize common data
        $this->_init_common_data();
        
        // Load user session data
        $this->_load_user_session();
        
        // Set common view data
        $this->_set_common_view_data();
    }
    
    /**
     * Initialize common data
     */
    private function _init_common_data() {
        $this->data['base_url'] = base_url();
        $this->data['site_name'] = 'Greenzio';
        $this->data['current_url'] = current_url();
        $this->data['user_agent'] = $this->agent->browser() . ' ' . $this->agent->version();
        $this->data['is_mobile'] = $this->agent->is_mobile();
        $this->data['ip_address'] = $this->input->ip_address();
    }
    
    /**
     * Load user session data
     */
    private function _load_user_session() {
        $this->user_data = $this->session->userdata();
        $this->data['is_logged_in'] = isset($this->user_data['userID']);
        $this->data['is_admin'] = isset($this->user_data['admin_id']);
        $this->data['user_data'] = $this->user_data;
    }
    
    /**
     * Set common view data
     */
    private function _set_common_view_data() {
        $this->view_data = array_merge($this->view_data, $this->data);
    }
    
    /**
     * Render view with common data
     * @param string $view
     * @param array $data
     * @param bool $return
     */
    protected function render_view($view, $data = [], $return = false) {
        $final_data = array_merge($this->view_data, $data);
        return $this->load->view($view, $final_data, $return);
    }
    
    /**
     * Load page with header and footer
     * @param string|array $views
     * @param array $data
     */
    protected function load_page($views, $data = []) {
        $this->render_view('main/header', $data);
        
        if (is_array($views)) {
            foreach ($views as $view) {
                $this->render_view($view, $data);
            }
        } else {
            $this->render_view($views, $data);
        }
        
        $this->render_view('main/footer', $data);
    }
    
    /**
     * Load admin page with sidebar, header and footer
     * @param string|array $views
     * @param array $data
     */
    protected function load_admin_page($views, $data = []) {
        $this->render_view('admin/sidebar', $data);
        $this->render_view('admin/header', $data);
        
        if (is_array($views)) {
            foreach ($views as $view) {
                $this->render_view($view, $data);
            }
        } else {
            $this->render_view($views, $data);
        }
        
        $this->render_view('admin/footer', $data);
    }
    
    /**
     * Check if user is logged in
     * @param string $redirect_url
     */
    protected function require_login($redirect_url = '') {
        if (!$this->data['is_logged_in']) {
            if (empty($redirect_url)) {
                $redirect_url = $this->agent->is_referrer() ? $this->agent->referrer() : base_url();
            }
            redirect($redirect_url);
        }
    }
    
    /**
     * Check if admin is logged in
     * @param string $redirect_url
     */
    protected function require_admin($redirect_url = 'admin/') {
        if (!$this->data['is_admin']) {
            redirect($redirect_url);
        }
    }
    
    /**
     * Set flash message
     * @param string $message
     * @param string $type
     */
    protected function set_message($message, $type = 'success') {
        $this->session->set_flashdata('message', [
            'text' => $message,
            'type' => $type
        ]);
    }
    
    /**
     * Get flash message
     * @return array|null
     */
    protected function get_message() {
        return $this->session->flashdata('message');
    }
    
    /**
     * Return JSON response
     * @param array $data
     * @param int $status_code
     */
    protected function json_response($data, $status_code = 200) {
        $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
    
    /**
     * Handle AJAX request validation
     */
    protected function validate_ajax_request() {
        if (!$this->input->is_ajax_request()) {
            $this->json_response(['success' => false, 'message' => 'Invalid request'], 400);
            return false;
        }
        return true;
    }
    
    /**
     * Standard error handling
     * @param string $message
     * @param string $redirect_url
     */
    protected function handle_error($message, $redirect_url = '') {
        log_message('error', $message);
        
        if ($this->input->is_ajax_request()) {
            $this->json_response(['success' => false, 'message' => $message]);
        } else {
            $this->set_message($message, 'error');
            if (!empty($redirect_url)) {
                redirect($redirect_url);
            }
        }
    }
    
    /**
     * Standard success handling
     * @param string $message
     * @param array $data
     * @param string $redirect_url
     */
    protected function handle_success($message, $data = [], $redirect_url = '') {
        if ($this->input->is_ajax_request()) {
            $response = array_merge(['success' => true, 'message' => $message], $data);
            $this->json_response($response);
        } else {
            $this->set_message($message, 'success');
            if (!empty($redirect_url)) {
                redirect($redirect_url);
            }
        }
    }
    
    /**
     * Validate form with common error handling
     * @param array $rules
     * @return bool
     */
    protected function validate_form($rules) {
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() === FALSE) {
            if ($this->input->is_ajax_request()) {
                $this->json_response([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->form_validation->error_array()
                ], 400);
            }
            return false;
        }
        
        return true;
    }
    
    /**
     * Get paginated data
     * @param string $model_method
     * @param array $params
     * @param int $per_page
     * @return array
     */
    protected function get_paginated_data($model_method, $params = [], $per_page = 20) {
        $this->load->library('pagination');
        
        $offset = (int) $this->input->get('page') * $per_page;
        
        // Get total count
        $count_method = str_replace('get_', 'count_', $model_method);
        $total_rows = call_user_func_array([$this->$params['model'], $count_method], $params['count_params'] ?? []);
        
        // Configure pagination
        $config = [
            'base_url' => current_url(),
            'total_rows' => $total_rows,
            'per_page' => $per_page,
            'page_query_string' => TRUE,
            'query_string_segment' => 'page',
            'use_page_numbers' => TRUE,
            'first_link' => 'First',
            'last_link' => 'Last',
            'next_link' => 'Next',
            'prev_link' => 'Prev'
        ];
        
        $this->pagination->initialize($config);
        
        // Get data with limit and offset
        $data_params = array_merge($params['data_params'] ?? [], [$per_page, $offset]);
        $data = call_user_func_array([$this->$params['model'], $model_method], $data_params);
        
        return [
            'data' => $data,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $total_rows,
            'current_page' => ((int) $this->input->get('page')) + 1,
            'per_page' => $per_page
        ];
    }
    
    /**
     * Log activity
     * @param string $action
     * @param string $description
     * @param array $data
     */
    protected function log_activity($action, $description, $data = []) {
        log_activity($action, $description, array_merge($data, [
            'controller' => $this->router->class,
            'method' => $this->router->method,
            'url' => current_url()
        ]));
    }
    
    /**
     * Upload file with validation
     * @param string $field_name
     * @param string $upload_path
     * @param array $config
     * @return array
     */
    protected function upload_file($field_name, $upload_path, $config = []) {
        $default_config = [
            'upload_path' => $upload_path,
            'allowed_types' => 'gif|jpg|png|jpeg',
            'max_size' => 2048,
            'max_width' => 2000,
            'max_height' => 2000,
            'encrypt_name' => TRUE,
            'remove_spaces' => TRUE,
            'overwrite' => FALSE
        ];
        
        $config = array_merge($default_config, $config);
        
        // Create directory if it doesn't exist
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }
        
        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload($field_name)) {
            return [
                'success' => false,
                'message' => 'Upload failed',
                'errors' => $this->upload->display_errors('', '')
            ];
        }
        
        $upload_data = $this->upload->data();
        
        return [
            'success' => true,
            'message' => 'File uploaded successfully',
            'data' => $upload_data,
            'file_path' => $upload_data['full_path'],
            'file_name' => $upload_data['file_name']
        ];
    }
}
