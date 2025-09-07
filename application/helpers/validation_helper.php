<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Validation Helper Functions
 * Contains reusable validation functions
 */

if (!function_exists('validate_email_or_mobile')) {
    /**
     * Validate if input is either email or mobile number
     * @param string $input
     * @return array
     */
    function validate_email_or_mobile($input) {
        $emailPattern = '/^\\w{2,}@\\w{2,}\\.\\w{2,4}$/';
        $mobilePattern = "/^[6-9][0-9]{9}$/";
        
        if (preg_match($emailPattern, $input)) {
            return ['type' => 'email', 'valid' => true, 'value' => $input];
        } elseif (preg_match($mobilePattern, $input)) {
            return ['type' => 'mobile', 'valid' => true, 'value' => $input];
        } else {
            return ['type' => 'unknown', 'valid' => false, 'value' => $input];
        }
    }
}

if (!function_exists('validate_password_strength')) {
    /**
     * Validate password strength
     * @param string $password
     * @return array
     */
    function validate_password_strength($password) {
        $errors = [];
        $score = 0;
        
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        } else {
            $score += 1;
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        } else {
            $score += 1;
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        } else {
            $score += 1;
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number';
        } else {
            $score += 1;
        }
        
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $errors[] = 'Password must contain at least one special character';
        } else {
            $score += 1;
        }
        
        $strength = 'weak';
        if ($score >= 4) {
            $strength = 'strong';
        } elseif ($score >= 3) {
            $strength = 'medium';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'score' => $score,
            'strength' => $strength
        ];
    }
}

if (!function_exists('validate_indian_mobile')) {
    /**
     * Validate Indian mobile number
     * @param string $mobile
     * @return bool
     */
    function validate_indian_mobile($mobile) {
        $pattern = "/^[6-9][0-9]{9}$/";
        return preg_match($pattern, $mobile);
    }
}

if (!function_exists('validate_pincode')) {
    /**
     * Validate Indian PIN code
     * @param string $pincode
     * @return bool
     */
    function validate_pincode($pincode) {
        $pattern = "/^[1-9][0-9]{5}$/";
        return preg_match($pattern, $pincode);
    }
}

if (!function_exists('sanitize_input')) {
    /**
     * Sanitize input data
     * @param mixed $input
     * @param string $type
     * @return mixed
     */
    function sanitize_input($input, $type = 'string') {
        switch ($type) {
            case 'email':
                return filter_var($input, FILTER_SANITIZE_EMAIL);
            case 'url':
                return filter_var($input, FILTER_SANITIZE_URL);
            case 'int':
                return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'string':
            default:
                return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
        }
    }
}

if (!function_exists('validate_file_upload')) {
    /**
     * Validate file upload
     * @param array $file
     * @param array $config
     * @return array
     */
    function validate_file_upload($file, $config = []) {
        $default_config = [
            'max_size' => 2048, // KB
            'allowed_types' => ['jpg', 'jpeg', 'png', 'gif'],
            'max_width' => 2000,
            'max_height' => 2000
        ];
        
        $config = array_merge($default_config, $config);
        $errors = [];
        
        if (!isset($file['name']) || empty($file['name'])) {
            $errors[] = 'No file selected';
            return ['valid' => false, 'errors' => $errors];
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload error occurred';
        }
        
        if ($file['size'] > $config['max_size'] * 1024) {
            $errors[] = 'File size exceeds maximum allowed size of ' . $config['max_size'] . 'KB';
        }
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $config['allowed_types'])) {
            $errors[] = 'File type not allowed. Allowed types: ' . implode(', ', $config['allowed_types']);
        }
        
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $image_info = getimagesize($file['tmp_name']);
            if ($image_info) {
                if ($image_info[0] > $config['max_width'] || $image_info[1] > $config['max_height']) {
                    $errors[] = "Image dimensions exceed maximum allowed size of {$config['max_width']}x{$config['max_height']}";
                }
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'extension' => $extension,
            'size' => $file['size']
        ];
    }
}

if (!function_exists('validate_quantity')) {
    /**
     * Validate product quantity
     * @param mixed $quantity
     * @param int $max_quantity
     * @param int $min_quantity
     * @return array
     */
    function validate_quantity($quantity, $max_quantity = 100, $min_quantity = 1) {
        $quantity = (int) $quantity;
        $errors = [];
        
        if ($quantity < $min_quantity) {
            $errors[] = "Minimum quantity is {$min_quantity}";
        }
        
        if ($quantity > $max_quantity) {
            $errors[] = "Maximum quantity is {$max_quantity}";
        }
        
        if (!is_numeric($quantity) || $quantity <= 0) {
            $errors[] = "Quantity must be a positive number";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'quantity' => $quantity
        ];
    }
}

if (!function_exists('validate_price')) {
    /**
     * Validate price value
     * @param mixed $price
     * @param float $min_price
     * @param float $max_price
     * @return array
     */
    function validate_price($price, $min_price = 0.01, $max_price = 999999.99) {
        $price = (float) $price;
        $errors = [];
        
        if ($price < $min_price) {
            $errors[] = "Minimum price is {$min_price}";
        }
        
        if ($price > $max_price) {
            $errors[] = "Maximum price is {$max_price}";
        }
        
        if (!is_numeric($price) || $price <= 0) {
            $errors[] = "Price must be a positive number";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'price' => number_format($price, 2, '.', '')
        ];
    }
}

if (!function_exists('validate_date')) {
    /**
     * Validate date format and range
     * @param string $date
     * @param string $format
     * @param string $min_date
     * @param string $max_date
     * @return array
     */
    function validate_date($date, $format = 'Y-m-d', $min_date = null, $max_date = null) {
        $errors = [];
        
        $date_obj = DateTime::createFromFormat($format, $date);
        if (!$date_obj || $date_obj->format($format) !== $date) {
            $errors[] = "Invalid date format. Expected format: {$format}";
            return ['valid' => false, 'errors' => $errors];
        }
        
        if ($min_date && $date_obj < DateTime::createFromFormat($format, $min_date)) {
            $errors[] = "Date cannot be earlier than {$min_date}";
        }
        
        if ($max_date && $date_obj > DateTime::createFromFormat($format, $max_date)) {
            $errors[] = "Date cannot be later than {$max_date}";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'date' => $date_obj->format('Y-m-d')
        ];
    }
}
