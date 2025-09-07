<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Custom Exception Handler for Greenzio
 * Handles errors gracefully with user-friendly messages and logging
 */
class MY_Exceptions extends CI_Exceptions {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * General Error Page
     * 
     * Takes an error message as input (either as a string or an array)
     * and displays it using the specified template.
     */
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500) {
        // Log the error for debugging
        $this->log_error_details($heading, $message, $status_code);
        
        // Set appropriate HTTP status code
        $this->set_status_header($status_code);
        
        // Check if this is an AJAX request
        if ($this->is_ajax_request()) {
            return $this->show_ajax_error($heading, $message, $status_code);
        }
        
        // Determine which template to use based on error type
        $template = $this->determine_error_template($status_code);
        
        // Prepare error data
        $error_data = $this->prepare_error_data($heading, $message, $status_code);
        
        echo $this->show_error_page($error_data, $template, $status_code);
        exit(1);
    }
    
    /**
     * 404 Error Page
     */
    public function show_404($page = '', $log_error = TRUE) {
        if ($log_error) {
            $this->log_error_details('404 Page Not Found', "Page: {$page}", 404);
        }
        
        $this->set_status_header(404);
        
        if ($this->is_ajax_request()) {
            return $this->show_ajax_error('Page Not Found', 'The requested page could not be found.', 404);
        }
        
        $error_data = [
            'heading' => 'Page Not Found',
            'message' => $page ? "The page '{$page}' you requested could not be found." : 'The page you requested could not be found.',
            'status_code' => 404,
            'page' => $page
        ];
        
        echo $this->show_error_page($error_data, 'error_404', 404);
        exit(3);
    }
    
    /**
     * Database Error Page
     */
    public function show_php_error($severity, $message, $filepath, $line) {
        // Only show detailed errors in development
        if (ENVIRONMENT !== 'development') {
            $generic_message = 'We encountered a temporary issue. Please try again later.';
            return $this->show_error('System Error', $generic_message, 'error_general', 500);
        }
        
        // Log the PHP error
        $this->log_error_details("PHP Error (Severity: {$severity})", 
                               "Message: {$message} in {$filepath} on line {$line}", 500);
        
        // Show detailed error in development
        return parent::show_php_error($severity, $message, $filepath, $line);
    }
    
    /**
     * Determine appropriate error template
     */
    private function determine_error_template($status_code) {
        $templates = [
            404 => 'error_404',
            403 => 'error_403',
            500 => 'error_500',
            503 => 'error_503'
        ];
        
        return isset($templates[$status_code]) ? $templates[$status_code] : 'error_general';
    }
    
    /**
     * Prepare error data for template
     */
    private function prepare_error_data($heading, $message, $status_code) {
        $error_titles = [
            400 => 'Bad Request',
            401 => 'Unauthorized Access',
            403 => 'Access Forbidden',
            404 => 'Page Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout'
        ];
        
        $user_friendly_messages = [
            400 => 'Your request could not be processed. Please check your input and try again.',
            401 => 'You need to log in to access this page.',
            403 => 'You don\'t have permission to access this page.',
            404 => 'The page you\'re looking for couldn\'t be found.',
            405 => 'This action is not allowed.',
            500 => 'Something went wrong on our end. We\'re working to fix it.',
            502 => 'We\'re experiencing connectivity issues. Please try again.',
            503 => 'Our grocery service is temporarily unavailable. Please check back soon.',
            504 => 'The request took too long to process. Please try again.'
        ];
        
        return [
            'heading' => isset($error_titles[$status_code]) ? $error_titles[$status_code] : $heading,
            'message' => ENVIRONMENT === 'production' && isset($user_friendly_messages[$status_code]) 
                        ? $user_friendly_messages[$status_code] : $message,
            'original_message' => $message,
            'status_code' => $status_code,
            'timestamp' => date('Y-m-d H:i:s'),
            'environment' => ENVIRONMENT
        ];
    }
    
    /**
     * Generate error page HTML
     */
    private function show_error_page($error_data, $template, $status_code) {
        // Extract data for template
        extract($error_data);
        
        // Start output buffering
        ob_start();
        
        // Include template if it exists, otherwise use default
        $template_path = APPPATH.'views/errors/html/'.$template.'.php';
        if (file_exists($template_path)) {
            include($template_path);
        } else {
            // Fallback to built-in grocery-themed error page
            include($this->get_default_error_template($error_data));
        }
        
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
    
    /**
     * Handle AJAX errors
     */
    private function show_ajax_error($heading, $message, $status_code) {
        header('Content-Type: application/json');
        
        $error_response = [
            'success' => false,
            'error' => true,
            'message' => ENVIRONMENT === 'production' ? 'An error occurred. Please try again.' : $message,
            'status_code' => $status_code,
            'timestamp' => date('c')
        ];
        
        // Add debug info in development
        if (ENVIRONMENT === 'development') {
            $error_response['debug'] = [
                'heading' => $heading,
                'original_message' => $message
            ];
        }
        
        echo json_encode($error_response);
        exit;
    }
    
    /**
     * Check if request is AJAX
     */
    private function is_ajax_request() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') ||
               (isset($_SERVER['CONTENT_TYPE']) && 
                strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false);
    }
    
    /**
     * Log error details
     */
    private function log_error_details($heading, $message, $status_code) {
        if (!function_exists('log_message')) {
            return;
        }
        
        $log_data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'status_code' => $status_code,
            'heading' => $heading,
            'message' => $message,
            'url' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'Unknown',
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown',
            'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unknown'
        ];
        
        $log_message = "Greenzio Error [{$status_code}]: {$heading} | {$message} | URL: {$log_data['url']} | IP: {$log_data['ip']}";
        
        // Log based on severity
        if ($status_code >= 500) {
            log_message('error', $log_message);
        } elseif ($status_code >= 400) {
            log_message('info', $log_message);
        }
        
        // Optional: Save to custom error log file
        if (defined('CUSTOM_ERROR_LOG') && CUSTOM_ERROR_LOG) {
            $this->save_to_custom_log($log_data);
        }
    }
    
    /**
     * Save to custom error log
     */
    private function save_to_custom_log($log_data) {
        $log_file = APPPATH.'logs/greenzio_errors_'.date('Y-m-d').'.log';
        $log_entry = date('Y-m-d H:i:s').' - '.json_encode($log_data).PHP_EOL;
        
        // Ensure logs directory exists
        $log_dir = dirname($log_file);
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0755, true);
        }
        
        file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Get default error template
     */
    private function get_default_error_template($error_data) {
        $template = tempnam(sys_get_temp_dir(), 'greenzio_error_');
        
        $html = $this->get_grocery_error_html($error_data);
        file_put_contents($template, $html);
        
        return $template;
    }
    
    /**
     * Generate grocery-themed error HTML
     */
    private function get_grocery_error_html($error_data) {
        $status_code = $error_data['status_code'];
        $heading = htmlspecialchars($error_data['heading']);
        $message = htmlspecialchars($error_data['message']);
        
        // Different icons for different error types
        $icons = [
            404 => 'fa-shopping-basket',
            403 => 'fa-ban',
            500 => 'fa-exclamation-triangle',
            503 => 'fa-tools'
        ];
        $icon = isset($icons[$status_code]) ? $icons[$status_code] : 'fa-exclamation-circle';
        
        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>'.$heading.' - Greenzio</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0; padding: 0; min-height: 100vh; 
            display: flex; align-items: center; justify-content: center;
        }
        .error-container { 
            background: white; border-radius: 15px; padding: 3rem; 
            text-align: center; max-width: 500px; margin: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }
        .error-icon { font-size: 5rem; color: #28a745; margin-bottom: 1.5rem; }
        .error-title { font-size: 2.5rem; color: #333; margin-bottom: 1rem; font-weight: 700; }
        .error-message { font-size: 1.1rem; color: #666; margin-bottom: 2rem; line-height: 1.6; }
        .btn-home { 
            background: linear-gradient(135deg, #28a745, #20c997); 
            color: white; padding: 1rem 2rem; border: none; border-radius: 50px; 
            text-decoration: none; font-size: 1.1rem; display: inline-block;
            transition: transform 0.3s ease;
        }
        .btn-home:hover { transform: translateY(-2px); text-decoration: none; color: white; }
        .error-code { margin-top: 2rem; color: #999; font-size: 0.9rem; }
        @media (max-width: 768px) {
            .error-container { padding: 2rem 1rem; margin: 1rem; }
            .error-title { font-size: 2rem; }
            .error-icon { font-size: 4rem; }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fas '.$icon.'"></i>
        </div>
        <h1 class="error-title">'.$heading.'</h1>
        <p class="error-message">'.$message.'</p>
        <a href="/" class="btn-home">
            <i class="fas fa-home" style="margin-right: 0.5rem;"></i>
            Back to Home
        </a>
        <div class="error-code">Error '.$status_code.' | Greenzio Fresh Groceries</div>
    </div>
</body>
</html>';
    }
}

/* End of file MY_Exceptions.php */
/* Location: ./application/core/MY_Exceptions.php */
