<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Error Handler Helper Functions
 * Standardized error handling across all controllers
 */

if (!function_exists('handle_error')) {
    /**
     * Standard error handler
     * @param string $message
     * @param array $data
     * @param int $status_code
     * @param string $redirect_url
     * @return void
     */
    function handle_error($message, $data = [], $status_code = 500, $redirect_url = '') {
        $CI =& get_instance();
        
        // Log the error
        log_message('error', $message . ' | Data: ' . json_encode($data));
        
        // Add to activity log
        log_activity('error', $message, $data);
        
        if ($CI->input->is_ajax_request()) {
            $CI->output
                ->set_status_header($status_code)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => $message,
                    'error_code' => $status_code,
                    'data' => $data
                ]));
        } else {
            $CI->session->set_flashdata('error_message', $message);
            
            if (!empty($redirect_url)) {
                redirect($redirect_url);
            } else {
                redirect($_SERVER['HTTP_REFERER'] ?? base_url());
            }
        }
    }
}

if (!function_exists('handle_success')) {
    /**
     * Standard success handler
     * @param string $message
     * @param array $data
     * @param string $redirect_url
     * @return void
     */
    function handle_success($message, $data = [], $redirect_url = '') {
        $CI =& get_instance();
        
        // Add to activity log
        log_activity('success', $message, $data);
        
        if ($CI->input->is_ajax_request()) {
            $CI->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'message' => $message,
                    'data' => $data
                ]));
        } else {
            $CI->session->set_flashdata('success_message', $message);
            
            if (!empty($redirect_url)) {
                redirect($redirect_url);
            }
        }
    }
}

if (!function_exists('handle_validation_errors')) {
    /**
     * Handle form validation errors
     * @param object $form_validation
     * @param string $redirect_url
     * @return void
     */
    function handle_validation_errors($form_validation, $redirect_url = '') {
        $CI =& get_instance();
        
        $errors = $form_validation->error_array();
        $message = 'Please correct the following errors: ' . implode(', ', $errors);
        
        if ($CI->input->is_ajax_request()) {
            $CI->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors,
                    'error_string' => validation_errors()
                ]));
        } else {
            $CI->session->set_flashdata('validation_errors', $errors);
            $CI->session->set_flashdata('error_message', $message);
            
            if (!empty($redirect_url)) {
                redirect($redirect_url);
            } else {
                redirect($_SERVER['HTTP_REFERER'] ?? base_url());
            }
        }
    }
}

if (!function_exists('handle_database_error')) {
    /**
     * Handle database errors
     * @param array $error
     * @param string $operation
     * @return void
     */
    function handle_database_error($error, $operation = 'database operation') {
        $message = "Database error during {$operation}: " . $error['message'];
        
        handle_error($message, [
            'error_code' => $error['code'],
            'operation' => $operation
        ], 500);
    }
}

if (!function_exists('handle_file_upload_error')) {
    /**
     * Handle file upload errors
     * @param array $upload_errors
     * @param string $redirect_url
     * @return void
     */
    function handle_file_upload_error($upload_errors, $redirect_url = '') {
        $message = 'File upload failed: ' . implode(', ', $upload_errors);
        
        handle_error($message, ['upload_errors' => $upload_errors], 400, $redirect_url);
    }
}

if (!function_exists('handle_permission_error')) {
    /**
     * Handle permission/authorization errors
     * @param string $action
     * @param string $redirect_url
     * @return void
     */
    function handle_permission_error($action = 'access this resource', $redirect_url = 'login') {
        $message = "You don't have permission to {$action}";
        
        handle_error($message, ['action' => $action], 403, $redirect_url);
    }
}

if (!function_exists('handle_not_found_error')) {
    /**
     * Handle resource not found errors
     * @param string $resource
     * @param string $redirect_url
     * @return void
     */
    function handle_not_found_error($resource = 'resource', $redirect_url = '') {
        $message = ucfirst($resource) . ' not found';
        
        handle_error($message, ['resource' => $resource], 404, $redirect_url);
    }
}

if (!function_exists('handle_rate_limit_error')) {
    /**
     * Handle rate limiting errors
     * @param int $retry_after
     * @return void
     */
    function handle_rate_limit_error($retry_after = 60) {
        $CI =& get_instance();
        
        $message = "Too many requests. Please try again in {$retry_after} seconds.";
        
        $CI->output->set_header("Retry-After: {$retry_after}");
        
        handle_error($message, ['retry_after' => $retry_after], 429);
    }
}

if (!function_exists('get_error_messages')) {
    /**
     * Get all error messages from session
     * @return array
     */
    function get_error_messages() {
        $CI =& get_instance();
        
        return [
            'error' => $CI->session->flashdata('error_message'),
            'success' => $CI->session->flashdata('success_message'),
            'validation_errors' => $CI->session->flashdata('validation_errors'),
            'warning' => $CI->session->flashdata('warning_message'),
            'info' => $CI->session->flashdata('info_message')
        ];
    }
}

if (!function_exists('display_flash_messages')) {
    /**
     * Display flash messages in HTML format
     * @return string
     */
    function display_flash_messages() {
        $CI =& get_instance();
        $messages = get_error_messages();
        $html = '';
        
        foreach ($messages as $type => $message) {
            if (!empty($message)) {
                $alert_class = '';
                $icon = '';
                
                switch ($type) {
                    case 'error':
                        $alert_class = 'alert-danger';
                        $icon = 'fas fa-exclamation-triangle';
                        break;
                    case 'success':
                        $alert_class = 'alert-success';
                        $icon = 'fas fa-check-circle';
                        break;
                    case 'warning':
                        $alert_class = 'alert-warning';
                        $icon = 'fas fa-exclamation-circle';
                        break;
                    case 'info':
                        $alert_class = 'alert-info';
                        $icon = 'fas fa-info-circle';
                        break;
                    case 'validation_errors':
                        $alert_class = 'alert-danger';
                        $icon = 'fas fa-exclamation-triangle';
                        if (is_array($message)) {
                            $message = '<ul><li>' . implode('</li><li>', $message) . '</li></ul>';
                        }
                        break;
                }
                
                if (!empty($message)) {
                    $html .= "
                    <div class=\"alert {$alert_class} alert-dismissible fade show\" role=\"alert\">
                        <i class=\"{$icon} mr-2\"></i>
                        {$message}
                        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                            <span aria-hidden=\"true\">&times;</span>
                        </button>
                    </div>";
                }
            }
        }
        
        return $html;
    }
}
