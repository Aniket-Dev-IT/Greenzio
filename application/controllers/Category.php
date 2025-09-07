<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('products');
    }
    
    /**
     * Get category mapping for URL-friendly names to database categories
     */
    private function getCategoryMap()
    {
        return [
            // URL slug => Database category name (exact match)
            'fruits-vegetables' => 'Fruits & Vegetables',
            'dairy-products' => 'Dairy & Bakery',  // Fixed: was 'dairy-bakery'
            'dairy-bakery' => 'Dairy & Bakery',    // Keep both for compatibility
            'grains-rice' => 'Grains & Rice', 
            'grains-pulses' => 'Grains & Rice',    // Alternative URL
            'spices-condiments' => 'Spices & Seasonings',  // Fixed: was 'Spices & Condiments'
            'spices-seasonings' => 'Spices & Seasonings',  // Alternative URL
            'snacks-beverages' => 'Snacks & Instant Food', // Fixed: was 'Snacks & Beverages'
            'snacks-instant-food' => 'Snacks & Instant Food', // Alternative URL
            'beverages' => 'Beverages',
            'personal-care' => 'Personal Care',
            'household-items' => 'Household Items',
            'cooking-oils' => 'Cooking Oils',
            'oils-ghee' => 'Cooking Oils',         // Alternative for oils
            'meat-seafood' => 'Meat & Seafood'
        ];
    }
    
    /**
     * Convert URL-friendly category name to display name
     */
    private function getDisplayCategory($urlCategory)
    {
        $categoryMap = $this->getCategoryMap();
        return isset($categoryMap[$urlCategory]) ? $categoryMap[$urlCategory] : $urlCategory;
    }
    
    /**
     * Convert display category name to URL-friendly name
     */
    private function getCategorySlug($displayCategory)
    {
        $categoryMap = array_flip($this->getCategoryMap());
        return isset($categoryMap[$displayCategory]) ? $categoryMap[$displayCategory] : strtolower(str_replace([' ', '&'], ['-', ''], $displayCategory));
    }

    public function index($category = null, $subcategory = null)
    {
        // Get category from URI segments
        $category = $this->uri->segment(2) ?: $category;
        $subcategory = $this->uri->segment(3) ?: $subcategory;
        
        if (!empty($category)) {
            // Convert URL-friendly category name to display name
            $displayCategory = $this->getDisplayCategory($category);
            
            // Debug logging (remove in production)
            if (ENVIRONMENT === 'development') {
                log_message('debug', 'Category Debug: URL=' . $category . ', Display=' . $displayCategory);
            }
            
            // Get products for the specific category
            if (!empty($subcategory)) {
                $list['products'] = $this->products->productsByCategory($displayCategory, $subcategory);
            } else {
                $list['products'] = $this->products->productsByCategory($displayCategory);
            }
            
            // Debug: Check if products were found
            if (ENVIRONMENT === 'development') {
                $product_count = is_array($list['products']) ? count($list['products']) : 0;
                log_message('debug', 'Products found for category "' . $displayCategory . '": ' . $product_count);
            }
            
            // Get subcategories and other filter options for this category
            try {
                $list['categories'] = $this->products->getSubcategories($displayCategory);
            } catch (Exception $e) {
                log_message('error', 'Error getting subcategories: ' . $e->getMessage());
                $list['categories'] = [];
            }
            
            try {
                $list['brands'] = $this->products->getBrands($displayCategory);
            } catch (Exception $e) {
                log_message('error', 'Error getting brands: ' . $e->getMessage());
                $list['brands'] = [];
            }
            
            $list['color'] = $this->getColorsForCategory($displayCategory);
            $list['category'] = $displayCategory;
            $list['category_slug'] = $category;
            
            // Ensure all arrays are initialized
            if (!isset($list['products']) || !is_array($list['products'])) {
                $list['products'] = [];
            }
            if (!isset($list['categories']) || !is_array($list['categories'])) {
                $list['categories'] = [];
            }
            if (!isset($list['brands']) || !is_array($list['brands'])) {
                $list['brands'] = [];
            }
            if (!isset($list['color']) || !is_array($list['color'])) {
                $list['color'] = [];
            }
            
            // Add debug info for development
            if (ENVIRONMENT === 'development') {
                $list['debug_info'] = [
                    'url_category' => $category,
                    'display_category' => $displayCategory,
                    'product_count' => count($list['products']),
                    'categories_found' => count($list['categories'] ?? []),
                    'brands_found' => count($list['brands'] ?? [])
                ];
            }
            
            // Load the view with products (if any found or show empty state)
            $this->load->view('main/header');
            $this->load->view('pages/category', $list);
            $this->load->view('main/footer');
        } else {
            // If no category specified, redirect to homepage or show all categories
            redirect(base_url());
        }
    }
    
    /**
     * Get colors for a specific category
     */
    private function getColorsForCategory($category)
    {
        // For grocery categories, we might not need color filters
        // but keeping this for compatibility with the view
        return [
            ['color' => '#FF6B6B'], // Red for fresh items
            ['color' => '#4ECDC4'], // Teal for organic items
            ['color' => '#45B7D1'], // Blue for packaged items
            ['color' => '#96CEB4'], // Green for natural items
            ['color' => '#FFEAA7'], // Yellow for premium items
        ];
    }
}
