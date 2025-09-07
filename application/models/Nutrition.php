<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Nutrition extends Base_model {
    
    function __construct() {
        parent::__construct();
    }

    /**
     * Get nutritional information for a product
     */
    public function getNutritionalInfo($product_id) {
        $this->db->select('*');
        $this->db->from('nutritional_info');
        $this->db->where('product_id', $product_id);
        
        return $this->db->get()->row_array();
    }

    /**
     * Get dietary attributes for a product
     */
    public function getDietaryAttributes($product_id) {
        $this->db->select('*');
        $this->db->from('dietary_attributes');
        $this->db->where('product_id', $product_id);
        
        return $this->db->get()->row_array();
    }

    /**
     * Get products by dietary filters
     */
    public function getProductsByDietaryFilter($filters) {
        $this->db->select('DISTINCT p.*');
        $this->db->from('product p');
        $this->db->join('dietary_attributes da', 'da.product_id = p.pid', 'left');
        $this->db->join('nutritional_info ni', 'ni.product_id = p.pid', 'left');
        
        $conditions_applied = false;
        
        // Dietary filters
        if (!empty($filters['vegetarian']) && $filters['vegetarian']) {
            $this->db->where('da.is_vegetarian', 1);
            $conditions_applied = true;
        }
        
        if (!empty($filters['vegan']) && $filters['vegan']) {
            $this->db->where('da.is_vegan', 1);
            $conditions_applied = true;
        }
        
        if (!empty($filters['gluten_free']) && $filters['gluten_free']) {
            $this->db->where('da.is_gluten_free', 1);
            $conditions_applied = true;
        }
        
        if (!empty($filters['dairy_free']) && $filters['dairy_free']) {
            $this->db->where('da.is_dairy_free', 1);
            $conditions_applied = true;
        }
        
        if (!empty($filters['nut_free']) && $filters['nut_free']) {
            $this->db->where('da.is_nut_free', 1);
            $conditions_applied = true;
        }
        
        if (!empty($filters['sugar_free']) && $filters['sugar_free']) {
            $this->db->where('da.is_sugar_free', 1);
            $conditions_applied = true;
        }
        
        if (!empty($filters['organic']) && $filters['organic']) {
            $this->db->where('da.is_organic', 1);
            $conditions_applied = true;
        }
        
        if (!empty($filters['keto_friendly']) && $filters['keto_friendly']) {
            $this->db->where('da.is_keto_friendly', 1);
            $conditions_applied = true;
        }
        
        // Nutritional filters
        if (!empty($filters['max_calories'])) {
            $this->db->where('ni.calories <=', $filters['max_calories']);
            $conditions_applied = true;
        }
        
        if (!empty($filters['min_protein'])) {
            $this->db->where('ni.protein >=', $filters['min_protein']);
            $conditions_applied = true;
        }
        
        if (!empty($filters['max_sugar'])) {
            $this->db->where('ni.sugar <=', $filters['max_sugar']);
            $conditions_applied = true;
        }
        
        if (!empty($filters['low_sodium']) && $filters['low_sodium']) {
            $this->db->where('ni.sodium <=', 140); // Low sodium threshold (mg per serving)
            $conditions_applied = true;
        }
        
        if (!empty($filters['high_fiber']) && $filters['high_fiber']) {
            $this->db->where('ni.fiber >=', 3); // High fiber threshold (g per serving)
            $conditions_applied = true;
        }
        
        // Only show products with stock
        $this->db->where('p.stock_quantity >', 0);
        
        // If no dietary conditions were applied, just return all products
        if (!$conditions_applied) {
            $this->db->order_by('p.pname', 'ASC');
            return $this->db->get()->result_array();
        }
        
        $this->db->order_by('p.pname', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Get nutrition facts formatted for display
     */
    public function getFormattedNutritionFacts($product_id) {
        $nutrition = $this->getNutritionalInfo($product_id);
        
        if (!$nutrition) {
            return null;
        }
        
        $formatted = [
            'serving_size' => $nutrition['serving_size'],
            'facts' => [
                [
                    'label' => 'Calories',
                    'value' => $nutrition['calories'],
                    'unit' => '',
                    'daily_value' => $this->calculateDailyValue('calories', $nutrition['calories'])
                ],
                [
                    'label' => 'Protein',
                    'value' => $nutrition['protein'],
                    'unit' => 'g',
                    'daily_value' => $this->calculateDailyValue('protein', $nutrition['protein'])
                ],
                [
                    'label' => 'Total Carbohydrates',
                    'value' => $nutrition['carbohydrates'],
                    'unit' => 'g',
                    'daily_value' => $this->calculateDailyValue('carbohydrates', $nutrition['carbohydrates'])
                ],
                [
                    'label' => 'Total Fat',
                    'value' => $nutrition['fat'],
                    'unit' => 'g',
                    'daily_value' => $this->calculateDailyValue('fat', $nutrition['fat'])
                ],
                [
                    'label' => 'Saturated Fat',
                    'value' => $nutrition['saturated_fat'],
                    'unit' => 'g',
                    'daily_value' => $this->calculateDailyValue('saturated_fat', $nutrition['saturated_fat'])
                ],
                [
                    'label' => 'Trans Fat',
                    'value' => $nutrition['trans_fat'],
                    'unit' => 'g',
                    'daily_value' => null // No daily value for trans fat
                ],
                [
                    'label' => 'Cholesterol',
                    'value' => $nutrition['cholesterol'],
                    'unit' => 'mg',
                    'daily_value' => $this->calculateDailyValue('cholesterol', $nutrition['cholesterol'])
                ],
                [
                    'label' => 'Sodium',
                    'value' => $nutrition['sodium'],
                    'unit' => 'mg',
                    'daily_value' => $this->calculateDailyValue('sodium', $nutrition['sodium'])
                ],
                [
                    'label' => 'Dietary Fiber',
                    'value' => $nutrition['fiber'],
                    'unit' => 'g',
                    'daily_value' => $this->calculateDailyValue('fiber', $nutrition['fiber'])
                ],
                [
                    'label' => 'Total Sugars',
                    'value' => $nutrition['sugar'],
                    'unit' => 'g',
                    'daily_value' => null // No daily value established for total sugars
                ]
            ],
            'vitamins_minerals' => [
                [
                    'label' => 'Calcium',
                    'value' => $nutrition['calcium'],
                    'unit' => 'mg',
                    'daily_value' => $this->calculateDailyValue('calcium', $nutrition['calcium'])
                ],
                [
                    'label' => 'Iron',
                    'value' => $nutrition['iron'],
                    'unit' => 'mg',
                    'daily_value' => $this->calculateDailyValue('iron', $nutrition['iron'])
                ],
                [
                    'label' => 'Vitamin C',
                    'value' => $nutrition['vitamin_c'],
                    'unit' => 'mg',
                    'daily_value' => $this->calculateDailyValue('vitamin_c', $nutrition['vitamin_c'])
                ],
                [
                    'label' => 'Vitamin A',
                    'value' => $nutrition['vitamin_a'],
                    'unit' => 'mcg',
                    'daily_value' => $this->calculateDailyValue('vitamin_a', $nutrition['vitamin_a'])
                ]
            ]
        ];
        
        return $formatted;
    }

    /**
     * Calculate daily value percentage
     */
    private function calculateDailyValue($nutrient, $value) {
        if ($value === null || $value == 0) {
            return 0;
        }
        
        // Daily values based on 2000 calorie diet (FDA guidelines)
        $daily_values = [
            'calories' => 2000,
            'protein' => 50,
            'carbohydrates' => 300,
            'fat' => 65,
            'saturated_fat' => 20,
            'cholesterol' => 300,
            'sodium' => 2300,
            'fiber' => 25,
            'calcium' => 1000,
            'iron' => 18,
            'vitamin_c' => 90,
            'vitamin_a' => 900
        ];
        
        if (isset($daily_values[$nutrient])) {
            return round(($value / $daily_values[$nutrient]) * 100);
        }
        
        return null;
    }

    /**
     * Get dietary filter options with counts
     */
    public function getDietaryFilterOptions() {
        $filters = [
            'vegetarian' => $this->getFilterCount('is_vegetarian'),
            'vegan' => $this->getFilterCount('is_vegan'),
            'gluten_free' => $this->getFilterCount('is_gluten_free'),
            'dairy_free' => $this->getFilterCount('is_dairy_free'),
            'nut_free' => $this->getFilterCount('is_nut_free'),
            'sugar_free' => $this->getFilterCount('is_sugar_free'),
            'organic' => $this->getFilterCount('is_organic'),
            'keto_friendly' => $this->getFilterCount('is_keto_friendly')
        ];
        
        return $filters;
    }

    /**
     * Get count of products for a dietary filter
     */
    private function getFilterCount($filter_column) {
        $this->db->select('COUNT(DISTINCT p.pid) as count');
        $this->db->from('product p');
        $this->db->join('dietary_attributes da', 'da.product_id = p.pid');
        $this->db->where("da.{$filter_column}", 1);
        $this->db->where('p.stock_quantity >', 0);
        
        $result = $this->db->get()->row_array();
        return $result['count'];
    }

    /**
     * Get nutritional comparison between products
     */
    public function compareNutrition($product_ids) {
        if (empty($product_ids) || count($product_ids) < 2) {
            return [];
        }
        
        $this->db->select('p.pid, p.pname, p.pimage, ni.*');
        $this->db->from('product p');
        $this->db->join('nutritional_info ni', 'ni.product_id = p.pid', 'left');
        $this->db->where_in('p.pid', $product_ids);
        
        $products = $this->db->get()->result_array();
        
        $comparison = [];
        $nutrients = ['calories', 'protein', 'carbohydrates', 'fat', 'fiber', 'sugar', 'sodium'];
        
        foreach ($products as $product) {
            $comparison[$product['pid']] = [
                'name' => $product['pname'],
                'image' => $product['pimage'],
                'serving_size' => $product['serving_size'] ?? 'N/A',
                'nutrition' => []
            ];
            
            foreach ($nutrients as $nutrient) {
                $comparison[$product['pid']]['nutrition'][$nutrient] = [
                    'value' => $product[$nutrient] ?? 0,
                    'daily_value' => $this->calculateDailyValue($nutrient, $product[$nutrient] ?? 0)
                ];
            }
        }
        
        return $comparison;
    }

    /**
     * Get allergen information for products
     */
    public function getAllergenInfo($product_ids = null) {
        $this->db->select('p.pid, p.pname, da.allergens');
        $this->db->from('product p');
        $this->db->join('dietary_attributes da', 'da.product_id = p.pid', 'left');
        
        if ($product_ids) {
            $this->db->where_in('p.pid', $product_ids);
        }
        
        $this->db->where('da.allergens IS NOT NULL');
        $this->db->where("da.allergens != '[]'");
        
        $results = $this->db->get()->result_array();
        
        $allergen_info = [];
        foreach ($results as $result) {
            $allergens = json_decode($result['allergens'], true);
            if (is_array($allergens) && !empty($allergens)) {
                $allergen_info[$result['pid']] = [
                    'name' => $result['pname'],
                    'allergens' => $allergens
                ];
            }
        }
        
        return $allergen_info;
    }

    /**
     * Get products suitable for specific health conditions
     */
    public function getHealthFriendlyProducts($condition) {
        $conditions = [
            'diabetes' => [
                'max_sugar' => 5,
                'low_sodium' => true,
                'high_fiber' => true
            ],
            'heart_health' => [
                'low_sodium' => true,
                'max_cholesterol' => 20,
                'max_saturated_fat' => 3
            ],
            'weight_management' => [
                'max_calories' => 100,
                'high_fiber' => true,
                'min_protein' => 5
            ],
            'hypertension' => [
                'low_sodium' => true,
                'max_sodium' => 140
            ]
        ];
        
        if (!isset($conditions[$condition])) {
            return [];
        }
        
        $filters = $conditions[$condition];
        return $this->getProductsByDietaryFilter($filters);
    }

    /**
     * Search products by nutritional content
     */
    public function searchByNutrition($nutrient, $operator, $value) {
        $valid_nutrients = ['calories', 'protein', 'carbohydrates', 'fat', 'fiber', 'sugar', 'sodium', 'calcium', 'iron'];
        $valid_operators = ['>', '<', '>=', '<=', '='];
        
        if (!in_array($nutrient, $valid_nutrients) || !in_array($operator, $valid_operators)) {
            return [];
        }
        
        $this->db->select('DISTINCT p.*');
        $this->db->from('product p');
        $this->db->join('nutritional_info ni', 'ni.product_id = p.pid');
        $this->db->where("ni.{$nutrient} {$operator}", $value);
        $this->db->where('p.stock_quantity >', 0);
        $this->db->order_by('p.pname', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Get nutrition summary for a shopping list or cart
     */
    public function getNutritionSummary($items) {
        $summary = [
            'total_calories' => 0,
            'total_protein' => 0,
            'total_carbs' => 0,
            'total_fat' => 0,
            'total_fiber' => 0,
            'total_sodium' => 0,
            'items_with_nutrition' => 0,
            'total_items' => count($items)
        ];
        
        foreach ($items as $item) {
            $nutrition = $this->getNutritionalInfo($item['product_id']);
            if ($nutrition) {
                $quantity = $item['quantity'] ?? 1;
                
                $summary['total_calories'] += ($nutrition['calories'] ?? 0) * $quantity;
                $summary['total_protein'] += ($nutrition['protein'] ?? 0) * $quantity;
                $summary['total_carbs'] += ($nutrition['carbohydrates'] ?? 0) * $quantity;
                $summary['total_fat'] += ($nutrition['fat'] ?? 0) * $quantity;
                $summary['total_fiber'] += ($nutrition['fiber'] ?? 0) * $quantity;
                $summary['total_sodium'] += ($nutrition['sodium'] ?? 0) * $quantity;
                $summary['items_with_nutrition']++;
            }
        }
        
        // Round values
        foreach (['total_calories', 'total_protein', 'total_carbs', 'total_fat', 'total_fiber', 'total_sodium'] as $key) {
            $summary[$key] = round($summary[$key], 2);
        }
        
        return $summary;
    }
}
