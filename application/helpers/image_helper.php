<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Image Helper for Greenzio
 * Handles consistent image paths and fallbacks
 */

if (!function_exists('get_product_image_url')) {
    /**
     * Get proper product image URL with fallbacks
     * 
     * @param string $image_path The stored image path from database
     * @param string $product_name Product name for alt text
     * @param string $category Product category for fallback
     * @return string Complete image URL
     */
    function get_product_image_url($image_path, $product_name = '', $category = '') {
        $CI =& get_instance();
        
        // If no image path provided, return placeholder
        if (empty($image_path)) {
            return get_product_placeholder($category);
        }
        
        // Clean up the image path
        $image_path = trim($image_path, '/');
        
        // Handle different possible path formats
        $possible_paths = [
            $image_path, // Original path as stored
            'assets/img/' . $image_path, // Add assets/img/ prefix
            'assets/images/' . $image_path, // Add assets/images/ prefix
            str_replace('assets/img/', 'assets/images/', $image_path), // Convert img to images
            str_replace('assets/images/', 'assets/img/', $image_path), // Convert images to img
        ];
        
        // Try each path to see if file exists
        foreach ($possible_paths as $path) {
            $full_path = FCPATH . $path;
            if (file_exists($full_path)) {
                return base_url($path);
            }
        }
        
        // If no file found, return placeholder
        return get_product_placeholder($category);
    }
}

if (!function_exists('get_product_placeholder')) {
    /**
     * Get placeholder image based on category
     * 
     * @param string $category Product category
     * @return string Placeholder image URL
     */
    function get_product_placeholder($category = '') {
        $category = strtolower(trim($category));
        
        // Category-specific placeholders
        $category_placeholders = [
            'fruits' => 'assets/img/placeholders/fruits-placeholder.jpg',
            'vegetables' => 'assets/img/placeholders/vegetables-placeholder.jpg',
            'dairy' => 'assets/img/placeholders/dairy-placeholder.jpg',
            'grains' => 'assets/img/placeholders/grains-placeholder.jpg',
            'spices' => 'assets/img/placeholders/spices-placeholder.jpg',
            'snacks' => 'assets/img/placeholders/snacks-placeholder.jpg',
            'beverages' => 'assets/img/placeholders/beverages-placeholder.jpg',
            'personal' => 'assets/img/placeholders/personal-care-placeholder.jpg',
            'household' => 'assets/img/placeholders/household-placeholder.jpg',
        ];
        
        // Try to find category-specific placeholder
        foreach ($category_placeholders as $cat => $placeholder) {
            if (strpos($category, $cat) !== false) {
                $full_path = FCPATH . $placeholder;
                if (file_exists($full_path)) {
                    return base_url($placeholder);
                }
            }
        }
        
        // Default SVG placeholder
        return 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300"%3E%3Crect fill="%23f8f9fa" width="300" height="300"/%3E%3Cg fill="%23dee2e6"%3E%3Cpath d="M100 100h100v100H100z"/%3E%3Ccircle cx="125" cy="125" r="15"/%3E%3Cpath d="M100 175l25-25 25 25 25-25v25H100z"/%3E%3C/g%3E%3Ctext fill="%236c757d" x="50%" y="230" text-anchor="middle" font-family="Arial" font-size="16"%3ENo Image%3C/text%3E%3C/svg%3E';
    }
}

if (!function_exists('optimize_image_url')) {
    /**
     * Generate optimized image URL with size parameters
     * 
     * @param string $image_url Base image URL
     * @param array $options Image optimization options
     * @return string Optimized image URL
     */
    function optimize_image_url($image_url, $options = []) {
        // Default options
        $default_options = [
            'width' => null,
            'height' => null,
            'quality' => 85,
            'format' => 'auto',
            'crop' => 'fill'
        ];
        
        $options = array_merge($default_options, $options);
        
        // For now, just return the original URL
        // This could be extended to work with image optimization services
        return $image_url;
    }
}

if (!function_exists('get_responsive_image_srcset')) {
    /**
     * Generate responsive image srcset
     * 
     * @param string $image_url Base image URL
     * @param array $sizes Array of sizes [width => descriptor]
     * @return string srcset attribute value
     */
    function get_responsive_image_srcset($image_url, $sizes = []) {
        if (empty($sizes)) {
            $sizes = [
                300 => '300w',
                600 => '600w',
                900 => '900w',
                1200 => '1200w'
            ];
        }
        
        $srcset = [];
        foreach ($sizes as $width => $descriptor) {
            $optimized_url = optimize_image_url($image_url, ['width' => $width]);
            $srcset[] = $optimized_url . ' ' . $descriptor;
        }
        
        return implode(', ', $srcset);
    }
}

if (!function_exists('create_placeholder_images')) {
    /**
     * Create placeholder images if they don't exist
     */
    function create_placeholder_images() {
        $placeholder_dir = FCPATH . 'assets/img/placeholders/';
        
        if (!is_dir($placeholder_dir)) {
            mkdir($placeholder_dir, 0755, true);
        }
        
        $placeholders = [
            'fruits-placeholder.jpg' => 'Fruits & Vegetables',
            'vegetables-placeholder.jpg' => 'Vegetables',
            'dairy-placeholder.jpg' => 'Dairy Products',
            'grains-placeholder.jpg' => 'Grains & Rice',
            'spices-placeholder.jpg' => 'Spices & Seasonings',
            'snacks-placeholder.jpg' => 'Snacks & Food',
            'beverages-placeholder.jpg' => 'Beverages',
            'personal-care-placeholder.jpg' => 'Personal Care',
            'household-placeholder.jpg' => 'Household Items',
        ];
        
        foreach ($placeholders as $filename => $category) {
            $file_path = $placeholder_dir . $filename;
            if (!file_exists($file_path)) {
                // Create a simple placeholder image (this would need GD library)
                // For now, we'll just create empty files
                file_put_contents($file_path, '');
            }
        }
    }
}

/* End of file image_helper.php */
/* Location: ./application/helpers/image_helper.php */
