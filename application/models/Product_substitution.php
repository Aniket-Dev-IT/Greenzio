<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_substitution extends Base_model {
    
    protected $table = 'product_substitutions';
    protected $primary_key = 'id';
    protected $fillable = [
        'original_product_id', 'substitute_product_id', 'substitution_ratio', 
        'notes', 'priority', 'is_active'
    ];
    protected $timestamps = false;

    function __construct() {
        parent::__construct();
        $this->load->model('products');
        $this->load->model('nutrition');
    }

    /**
     * Get substitutions for a product
     */
    public function getSubstitutions($product_id, $limit = 5) {
        $this->db->select('ps.*, p.pname, p.price, p.pimage, p.stock_quantity, p.unit, p.weight, p.brand, p.category');
        $this->db->from('product_substitutions ps');
        $this->db->join('product p', 'p.pid = ps.substitute_product_id');
        $this->db->where('ps.original_product_id', $product_id);
        $this->db->where('ps.is_active', 1);
        $this->db->where('p.stock_quantity >', 0); // Only suggest available products
        $this->db->order_by('ps.priority', 'ASC');
        $this->db->order_by('p.stock_quantity', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result_array();
    }

    /**
     * Get smart substitutions based on category, brand, and attributes
     */
    public function getSmartSubstitutions($product_id, $limit = 5) {
        $original_product = $this->products->getProductByID($product_id);
        
        if (empty($original_product)) {
            return [];
        }
        
        $original = $original_product[0];
        $substitutions = [];
        
        // First, get manual substitutions
        $manual_subs = $this->getSubstitutions($product_id, $limit);
        $substitutions = array_merge($substitutions, $manual_subs);
        
        $remaining_slots = $limit - count($substitutions);
        if ($remaining_slots <= 0) {
            return $substitutions;
        }
        
        // Get used substitute IDs to avoid duplicates
        $used_ids = array_column($substitutions, 'substitute_product_id');
        $used_ids[] = $product_id; // Exclude original product
        
        // Smart substitutions based on similarity
        $smart_subs = $this->findSimilarProducts($original, $used_ids, $remaining_slots);
        $substitutions = array_merge($substitutions, $smart_subs);
        
        return $substitutions;
    }

    /**
     * Find similar products based on category, brand, attributes
     */
    private function findSimilarProducts($original_product, $exclude_ids, $limit) {
        $this->db->select('p.*, "auto" as substitution_type, 1.0 as substitution_ratio, "Suggested based on similar attributes" as notes, 10 as priority');
        $this->db->from('product p');
        
        // Same category first
        $this->db->where('p.category', $original_product['category']);
        
        // Exclude already used products
        if (!empty($exclude_ids)) {
            $this->db->where_not_in('p.pid', $exclude_ids);
        }
        
        $this->db->where('p.stock_quantity >', 0);
        
        // Prefer same brand
        $this->db->order_by("CASE WHEN p.brand = '{$original_product['brand']}' THEN 0 ELSE 1 END", 'ASC');
        
        // Prefer similar price range
        $price_lower = $original_product['price'] * 0.8;
        $price_upper = $original_product['price'] * 1.2;
        $this->db->order_by("CASE WHEN p.price BETWEEN {$price_lower} AND {$price_upper} THEN 0 ELSE 1 END", 'ASC');
        
        $this->db->order_by('p.stock_quantity', 'DESC');
        $this->db->limit($limit);
        
        $similar_products = $this->db->get()->result_array();
        
        // If we don't have enough from same category, try same subcategory from different categories
        if (count($similar_products) < $limit && !empty($original_product['subcategory'])) {
            $remaining = $limit - count($similar_products);
            $used_ids_updated = array_merge($exclude_ids, array_column($similar_products, 'pid'));
            
            $this->db->select('p.*, "auto" as substitution_type, 1.0 as substitution_ratio, "Suggested based on product type" as notes, 11 as priority');
            $this->db->from('product p');
            $this->db->where('p.subcategory', $original_product['subcategory']);
            $this->db->where('p.category !=', $original_product['category']);
            
            if (!empty($used_ids_updated)) {
                $this->db->where_not_in('p.pid', $used_ids_updated);
            }
            
            $this->db->where('p.stock_quantity >', 0);
            $this->db->order_by('p.stock_quantity', 'DESC');
            $this->db->limit($remaining);
            
            $additional_products = $this->db->get()->result_array();
            $similar_products = array_merge($similar_products, $additional_products);
        }
        
        return $similar_products;
    }

    /**
     * Get substitutions for multiple products (for cart/shopping list)
     */
    public function getBulkSubstitutions($product_ids) {
        $substitutions = [];
        
        foreach ($product_ids as $product_id) {
            $product_subs = $this->getSmartSubstitutions($product_id, 3);
            if (!empty($product_subs)) {
                $substitutions[$product_id] = $product_subs;
            }
        }
        
        return $substitutions;
    }

    /**
     * Add manual substitution
     */
    public function addSubstitution($original_id, $substitute_id, $ratio = 1.0, $notes = '', $priority = 1) {
        // Check if substitution already exists
        $existing = $this->db->select('id')
                            ->from('product_substitutions')
                            ->where('original_product_id', $original_id)
                            ->where('substitute_product_id', $substitute_id)
                            ->get()->row_array();
        
        if ($existing) {
            return false; // Already exists
        }
        
        $data = [
            'original_product_id' => $original_id,
            'substitute_product_id' => $substitute_id,
            'substitution_ratio' => $ratio,
            'notes' => $notes,
            'priority' => $priority,
            'is_active' => 1
        ];
        
        $this->db->insert('product_substitutions', $data);
        return $this->db->insert_id();
    }

    /**
     * Update substitution
     */
    public function updateSubstitution($substitution_id, $data) {
        $allowed_fields = ['substitution_ratio', 'notes', 'priority', 'is_active'];
        $update_data = [];
        
        foreach ($allowed_fields as $field) {
            if (isset($data[$field])) {
                $update_data[$field] = $data[$field];
            }
        }
        
        if (empty($update_data)) {
            return false;
        }
        
        $this->db->where('id', $substitution_id);
        return $this->db->update('product_substitutions', $update_data);
    }

    /**
     * Remove substitution
     */
    public function removeSubstitution($substitution_id) {
        $this->db->where('id', $substitution_id);
        return $this->db->delete('product_substitutions');
    }

    /**
     * Get substitution suggestions based on cart analysis
     */
    public function getCartSubstitutions($cart_items) {
        $out_of_stock_items = [];
        $suggestions = [];
        
        foreach ($cart_items as $item) {
            $stock = $this->products->checkStock($item['product_id']);
            if ($stock <= 0 || $stock < $item['quantity']) {
                $out_of_stock_items[] = $item['product_id'];
            }
        }
        
        if (!empty($out_of_stock_items)) {
            $suggestions = $this->getBulkSubstitutions($out_of_stock_items);
        }
        
        return $suggestions;
    }

    /**
     * Get substitution with nutritional comparison
     */
    public function getSubstitutionWithNutrition($original_id, $substitute_id) {
        $substitution = $this->db->select('ps.*, p.pname, p.price, p.pimage, p.stock_quantity')
                                ->from('product_substitutions ps')
                                ->join('product p', 'p.pid = ps.substitute_product_id')
                                ->where('ps.original_product_id', $original_id)
                                ->where('ps.substitute_product_id', $substitute_id)
                                ->get()->row_array();
        
        if ($substitution) {
            $nutritional_comparison = $this->nutrition->compareNutrition([$original_id, $substitute_id]);
            $substitution['nutritional_comparison'] = $nutritional_comparison;
        }
        
        return $substitution;
    }

    /**
     * Get popular substitutions (frequently used)
     */
    public function getPopularSubstitutions($limit = 10) {
        // This would require tracking substitution usage
        // For now, return top substitutions by priority
        $this->db->select('ps.*, po.pname as original_name, ps.pname as substitute_name');
        $this->db->from('product_substitutions ps');
        $this->db->join('product po', 'po.pid = ps.original_product_id');
        $this->db->join('product ps', 'ps.pid = ps.substitute_product_id');
        $this->db->where('ps.is_active', 1);
        $this->db->order_by('ps.priority', 'ASC');
        $this->db->limit($limit);
        
        return $this->db->get()->result_array();
    }

    /**
     * Get substitution statistics
     */
    public function getSubstitutionStats() {
        $stats = [];
        
        // Total substitutions
        $total = $this->db->select('COUNT(*) as total')
                         ->from('product_substitutions')
                         ->where('is_active', 1)
                         ->get()->row_array();
        $stats['total_substitutions'] = $total['total'];
        
        // Products with substitutions
        $products_with_subs = $this->db->select('COUNT(DISTINCT original_product_id) as count')
                                      ->from('product_substitutions')
                                      ->where('is_active', 1)
                                      ->get()->row_array();
        $stats['products_with_substitutions'] = $products_with_subs['count'];
        
        // Categories with most substitutions
        $category_stats = $this->db->select('p.category, COUNT(*) as substitution_count')
                                  ->from('product_substitutions ps')
                                  ->join('product p', 'p.pid = ps.original_product_id')
                                  ->where('ps.is_active', 1)
                                  ->group_by('p.category')
                                  ->order_by('substitution_count', 'DESC')
                                  ->limit(5)
                                  ->get()->result_array();
        $stats['top_categories'] = $category_stats;
        
        return $stats;
    }

    /**
     * Auto-generate substitutions for products without any
     */
    public function autoGenerateSubstitutions($limit = 100) {
        // Get products without manual substitutions
        $products_without_subs = $this->db->select('p.*')
                                         ->from('product p')
                                         ->where('p.stock_quantity >', 0)
                                         ->where('p.pid NOT IN (SELECT DISTINCT original_product_id FROM product_substitutions WHERE is_active = 1)')
                                         ->limit($limit)
                                         ->get()->result_array();
        
        $generated_count = 0;
        
        foreach ($products_without_subs as $product) {
            $similar_products = $this->findSimilarProducts($product, [$product['pid']], 3);
            
            foreach ($similar_products as $similar) {
                $this->addSubstitution(
                    $product['pid'], 
                    $similar['pid'], 
                    1.0, 
                    'Auto-generated based on product similarity', 
                    10
                );
                $generated_count++;
            }
        }
        
        return $generated_count;
    }

    /**
     * Get seasonal substitutions
     */
    public function getSeasonalSubstitutions($season = null) {
        if (!$season) {
            $season = $this->getCurrentSeason();
        }
        
        // This could be enhanced with seasonal product data
        // For now, return substitutions for products that might be seasonal
        $seasonal_categories = ['Fruits & Vegetables', 'Fruits', 'Vegetables'];
        
        $this->db->select('ps.*, po.pname as original_name, ps.pname as substitute_name, po.category');
        $this->db->from('product_substitutions ps');
        $this->db->join('product po', 'po.pid = ps.original_product_id');
        $this->db->join('product ps', 'ps.pid = ps.substitute_product_id');
        $this->db->where('ps.is_active', 1);
        $this->db->where_in('po.category', $seasonal_categories);
        $this->db->order_by('ps.priority', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Get current season
     */
    private function getCurrentSeason() {
        $month = date('n');
        
        if ($month >= 3 && $month <= 5) {
            return 'spring';
        } elseif ($month >= 6 && $month <= 8) {
            return 'summer';
        } elseif ($month >= 9 && $month <= 11) {
            return 'autumn';
        } else {
            return 'winter';
        }
    }

    /**
     * Get recipe-based substitutions (for recipe ingredients)
     */
    public function getRecipeSubstitutions($recipe_id) {
        $this->load->model('recipe');
        
        $ingredients = $this->recipe->getRecipeIngredients($recipe_id);
        $substitutions = [];
        
        foreach ($ingredients as $ingredient) {
            if ($ingredient['stock_quantity'] <= 0) {
                $ingredient_subs = $this->getSmartSubstitutions($ingredient['product_id'], 3);
                if (!empty($ingredient_subs)) {
                    $substitutions[$ingredient['product_id']] = [
                        'ingredient' => $ingredient,
                        'substitutions' => $ingredient_subs
                    ];
                }
            }
        }
        
        return $substitutions;
    }
}
