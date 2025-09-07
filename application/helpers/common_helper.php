<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Common Helper Functions
 * Contains reusable functions for common operations
 */

if (!function_exists('format_price')) {
    /**
     * Format price with currency symbol
     * @param float $price
     * @param string $currency
     * @return string
     */
    function format_price($price, $currency = '₹') {
        return $currency . number_format($price, 2);
    }
}

if (!function_exists('calculate_discount_price')) {
    /**
     * Calculate discounted price
     * @param float $original_price
     * @param float $discount_percent
     * @return array
     */
    function calculate_discount_price($original_price, $discount_percent) {
        $discount_amount = $original_price * ($discount_percent / 100);
        $final_price = $original_price - $discount_amount;
        
        return [
            'original_price' => $original_price,
            'discount_amount' => $discount_amount,
            'final_price' => $final_price,
            'discount_percent' => $discount_percent,
            'has_discount' => $discount_percent > 0
        ];
    }
}

if (!function_exists('format_weight_unit')) {
    /**
     * Format weight with unit display
     * @param float $weight
     * @param string $unit
     * @return string
     */
    function format_weight_unit($weight, $unit) {
        if (empty($weight) || empty($unit)) {
            return '';
        }
        return $weight . ' ' . $unit;
    }
}

if (!function_exists('get_stock_status')) {
    /**
     * Get stock status information
     * @param int $stock_quantity
     * @param int $low_stock_threshold
     * @param int $very_low_threshold
     * @return array
     */
    function get_stock_status($stock_quantity, $low_stock_threshold = 10, $very_low_threshold = 3) {
        if ($stock_quantity <= 0) {
            return [
                'status' => 'out_of_stock',
                'message' => 'Out of Stock',
                'css_class' => 'text-danger',
                'badge_class' => 'badge-danger',
                'available' => false
            ];
        } elseif ($stock_quantity <= $very_low_threshold) {
            return [
                'status' => 'very_low_stock',
                'message' => "Only {$stock_quantity} left!",
                'css_class' => 'text-danger',
                'badge_class' => 'badge-warning',
                'available' => true
            ];
        } elseif ($stock_quantity <= $low_stock_threshold) {
            return [
                'status' => 'low_stock',
                'message' => "Only {$stock_quantity} left",
                'css_class' => 'text-warning',
                'badge_class' => 'badge-warning',
                'available' => true
            ];
        } else {
            return [
                'status' => 'in_stock',
                'message' => "In Stock: {$stock_quantity} units",
                'css_class' => 'text-success',
                'badge_class' => 'badge-success',
                'available' => true
            ];
        }
    }
}

if (!function_exists('get_expiry_status')) {
    /**
     * Get expiry status information
     * @param string $expiry_date
     * @param int $warning_days
     * @return array|null
     */
    function get_expiry_status($expiry_date, $warning_days = 7) {
        if (empty($expiry_date)) {
            return null;
        }
        
        $days_to_expiry = floor((strtotime($expiry_date) - time()) / 86400);
        
        if ($days_to_expiry < 0) {
            return [
                'status' => 'expired',
                'message' => 'Expired',
                'css_class' => 'text-danger',
                'badge_class' => 'badge-danger',
                'days_to_expiry' => $days_to_expiry,
                'formatted_date' => date('M d, Y', strtotime($expiry_date))
            ];
        } elseif ($days_to_expiry <= 3) {
            return [
                'status' => 'expiring_urgent',
                'message' => "Expires in {$days_to_expiry} days",
                'css_class' => 'text-danger',
                'badge_class' => 'badge-danger',
                'days_to_expiry' => $days_to_expiry,
                'formatted_date' => date('M d, Y', strtotime($expiry_date))
            ];
        } elseif ($days_to_expiry <= $warning_days) {
            return [
                'status' => 'expiring_soon',
                'message' => "Expires in {$days_to_expiry} days",
                'css_class' => 'text-warning',
                'badge_class' => 'badge-warning',
                'days_to_expiry' => $days_to_expiry,
                'formatted_date' => date('M d, Y', strtotime($expiry_date))
            ];
        } else {
            return [
                'status' => 'fresh',
                'message' => 'Fresh',
                'css_class' => 'text-success',
                'badge_class' => 'badge-success',
                'days_to_expiry' => $days_to_expiry,
                'formatted_date' => date('M d, Y', strtotime($expiry_date))
            ];
        }
    }
}

if (!function_exists('sanitize_filename')) {
    /**
     * Sanitize filename for file uploads
     * @param string $filename
     * @return string
     */
    function sanitize_filename($filename) {
        // Remove special characters and replace spaces with underscores
        $filename = preg_replace('/[^a-zA-Z0-9\._-]/', '_', $filename);
        $filename = preg_replace('/_{2,}/', '_', $filename); // Replace multiple underscores with single
        return trim($filename, '_');
    }
}

if (!function_exists('generate_breadcrumbs')) {
    /**
     * Generate breadcrumbs array
     * @param array $items
     * @return array
     */
    function generate_breadcrumbs($items = []) {
        $breadcrumbs = [
            ['title' => 'Home', 'url' => base_url(), 'active' => false]
        ];
        
        foreach ($items as $item) {
            $breadcrumbs[] = [
                'title' => $item['title'] ?? '',
                'url' => $item['url'] ?? '#',
                'active' => $item['active'] ?? false
            ];
        }
        
        // Mark last item as active
        if (!empty($breadcrumbs)) {
            $breadcrumbs[count($breadcrumbs) - 1]['active'] = true;
        }
        
        return $breadcrumbs;
    }
}

if (!function_exists('generate_product_url')) {
    /**
     * Generate product detail URL
     * @param int $product_id
     * @param string $product_name
     * @return string
     */
    function generate_product_url($product_id, $product_name = '') {
        return base_url('product/index/' . $product_id);
    }
}

if (!function_exists('generate_category_url')) {
    /**
     * Generate category URL
     * @param string $category
     * @param string $subcategory
     * @return string
     */
    function generate_category_url($category, $subcategory = '') {
        $url = base_url('product/category/' . urlencode($category));
        if (!empty($subcategory)) {
            $url .= '/' . urlencode($subcategory);
        }
        return $url;
    }
}

if (!function_exists('truncate_text')) {
    /**
     * Truncate text with ellipsis
     * @param string $text
     * @param int $length
     * @param string $suffix
     * @return string
     */
    function truncate_text($text, $length = 100, $suffix = '...') {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . $suffix;
    }
}

if (!function_exists('is_mobile_request')) {
    /**
     * Check if request is from mobile device
     * @return bool
     */
    function is_mobile_request() {
        $CI =& get_instance();
        $CI->load->library('user_agent');
        return $CI->agent->is_mobile();
    }
}

if (!function_exists('log_activity')) {
    /**
     * Log user/admin activity
     * @param string $action
     * @param string $description
     * @param array $data
     * @return bool
     */
    function log_activity($action, $description, $data = []) {
        $CI =& get_instance();
        
        $log_data = [
            'action' => $action,
            'description' => $description,
            'user_id' => $CI->session->userdata('userID') ?: null,
            'admin_id' => $CI->session->userdata('admin_id') ?: null,
            'ip_address' => $CI->input->ip_address(),
            'user_agent' => $CI->input->user_agent(),
            'data' => json_encode($data),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // In real implementation, you would save this to a logs table
        // $CI->db->insert('activity_logs', $log_data);
        
        return true;
    }
}

if (!function_exists('get_image_placeholder')) {
    /**
     * Get placeholder image URL
     * @param int $width
     * @param int $height
     * @return string
     */
    function get_image_placeholder($width = 300, $height = 300) {
        return "https://via.placeholder.com/{$width}x{$height}/cccccc/666666?text=No+Image";
    }
}

if (!function_exists('format_date_time')) {
    /**
     * Format date time for display
     * @param string $datetime
     * @param string $format
     * @return string
     */
    function format_date_time($datetime, $format = 'M d, Y g:i A') {
        if (empty($datetime)) {
            return 'N/A';
        }
        return date($format, strtotime($datetime));
    }
}

if (!function_exists('get_grocery_type_filters')) {
    /**
     * Get available grocery type filters
     * @return array
     */
    function get_grocery_type_filters() {
        return [
            'organic' => 'Organic',
            'fresh' => 'Fresh',
            'frozen' => 'Frozen',
            'diet' => 'Diet/Low Fat',
            'gluten_free' => 'Gluten Free',
            'vegan' => 'Vegan',
            'sugar_free' => 'Sugar Free',
            'low_sodium' => 'Low Sodium',
            'high_protein' => 'High Protein'
        ];
    }
}

if (!function_exists('get_storage_types')) {
    /**
     * Get available storage types
     * @return array
     */
    function get_storage_types() {
        return [
            'dry' => 'Dry Storage',
            'refrigerated' => 'Refrigerated',
            'frozen' => 'Frozen',
            'ambient' => 'Room Temperature'
        ];
    }
}

if (!function_exists('format_storage_type')) {
    /**
     * Format storage type for display
     * @param string $storage_type
     * @return string
     */
    function format_storage_type($storage_type) {
        $types = get_storage_types();
        return isset($types[$storage_type]) ? $types[$storage_type] : ucfirst($storage_type);
    }
}

if (!function_exists('calculate_nutrition_percentage')) {
    /**
     * Calculate percentage of daily nutrition value
     * @param float $amount
     * @param float $daily_value
     * @return float
     */
    function calculate_nutrition_percentage($amount, $daily_value) {
        if ($daily_value == 0) return 0;
        return round(($amount / $daily_value) * 100, 1);
    }
}

if (!function_exists('get_unit_conversions')) {
    /**
     * Get unit conversion rates to base units
     * @return array
     */
    function get_unit_conversions() {
        return [
            // Weight conversions to grams
            'g' => 1,
            'gram' => 1,
            'grams' => 1,
            'kg' => 1000,
            'kilogram' => 1000,
            'kilograms' => 1000,
            'lb' => 453.592,
            'pound' => 453.592,
            'pounds' => 453.592,
            'oz' => 28.3495,
            'ounce' => 28.3495,
            'ounces' => 28.3495,
            
            // Volume conversions to milliliters
            'ml' => 1,
            'milliliter' => 1,
            'milliliters' => 1,
            'l' => 1000,
            'liter' => 1000,
            'liters' => 1000,
            'litre' => 1000,
            'litres' => 1000,
            'cup' => 236.588,
            'cups' => 236.588,
            'fl oz' => 29.5735,
            'fluid ounce' => 29.5735,
            'fluid ounces' => 29.5735,
            
            // Count units
            'piece' => 1,
            'pieces' => 1,
            'pc' => 1,
            'pcs' => 1,
            'dozen' => 12,
            'pair' => 2,
            'pairs' => 2
        ];
    }
}

if (!function_exists('normalize_unit')) {
    /**
     * Normalize unit names for consistent comparison
     * @param string $unit
     * @return string
     */
    function normalize_unit($unit) {
        $unit = strtolower(trim($unit));
        $conversions = get_unit_conversions();
        
        // Direct match
        if (isset($conversions[$unit])) {
            return $unit;
        }
        
        // Try to find closest match
        foreach ($conversions as $standard_unit => $conversion) {
            if (strpos($unit, $standard_unit) !== false || strpos($standard_unit, $unit) !== false) {
                return $standard_unit;
            }
        }
        
        return $unit; // Return as-is if no match found
    }
}

if (!function_exists('calculate_unit_price')) {
    /**
     * Calculate price per standard unit (per kg, per liter, etc.)
     * @param float $price
     * @param float $weight
     * @param string $unit
     * @return array
     */
    function calculate_unit_price($price, $weight, $unit) {
        $conversions = get_unit_conversions();
        $normalized_unit = normalize_unit($unit);
        
        if (!isset($conversions[$normalized_unit]) || $weight <= 0) {
            return [
                'unit_price' => $price,
                'standard_unit' => $unit,
                'display' => format_price($price) . ' per ' . $unit
            ];
        }
        
        // Convert to base unit (grams or ml)
        $base_amount = $weight * $conversions[$normalized_unit];
        
        // Determine appropriate display unit
        $display_unit = $normalized_unit;
        $display_amount = $weight;
        
        // For weight: if >= 1000g, show per kg
        if (in_array($normalized_unit, ['g', 'gram', 'grams']) && $base_amount >= 1000) {
            $display_unit = 'kg';
            $display_amount = $base_amount / 1000;
        }
        // For volume: if >= 1000ml, show per liter
        elseif (in_array($normalized_unit, ['ml', 'milliliter', 'milliliters']) && $base_amount >= 1000) {
            $display_unit = 'liter';
            $display_amount = $base_amount / 1000;
        }
        
        $unit_price = $price / $display_amount;
        
        return [
            'unit_price' => $unit_price,
            'standard_unit' => $display_unit,
            'display' => format_price($unit_price) . ' per ' . $display_unit
        ];
    }
}
