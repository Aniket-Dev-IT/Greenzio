<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Recipe extends Base_model {
    
    protected $table = 'recipes';
    protected $primary_key = 'recipe_id';
    protected $fillable = [
        'title', 'description', 'instructions', 'prep_time', 'cook_time', 
        'servings', 'difficulty', 'cuisine_type', 'meal_type', 'dietary_tags', 
        'image', 'rating', 'total_ratings'
    ];
    protected $timestamps = true;

    function __construct() {
        parent::__construct();
        $this->load->model('products');
    }

    /**
     * Get all recipes with optional filtering
     */
    public function getAllRecipes($filters = []) {
        $this->db->select('*');
        $this->db->from('recipes');
        
        if (!empty($filters['cuisine_type'])) {
            $this->db->where('cuisine_type', $filters['cuisine_type']);
        }
        
        if (!empty($filters['difficulty'])) {
            $this->db->where('difficulty', $filters['difficulty']);
        }
        
        if (!empty($filters['meal_type'])) {
            $this->db->where('meal_type', $filters['meal_type']);
        }
        
        if (!empty($filters['dietary_tags'])) {
            foreach ($filters['dietary_tags'] as $tag) {
                $this->db->like('dietary_tags', $tag);
            }
        }
        
        if (!empty($filters['max_time'])) {
            $this->db->where('(prep_time + cook_time) <=', $filters['max_time']);
        }
        
        $this->db->order_by('rating', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Get recipe by ID with ingredients
     */
    public function getRecipeWithIngredients($recipe_id) {
        $recipe = $this->get_by_id($recipe_id);
        
        if ($recipe) {
            $recipe['ingredients'] = $this->getRecipeIngredients($recipe_id);
            $recipe['total_cost'] = $this->calculateRecipeCost($recipe_id);
            $recipe['available_ingredients'] = $this->checkIngredientAvailability($recipe_id);
            $recipe['missing_ingredients'] = $this->getMissingIngredients($recipe_id);
        }
        
        return $recipe;
    }

    /**
     * Get ingredients for a recipe
     */
    public function getRecipeIngredients($recipe_id) {
        $this->db->select('ri.*, p.pname, p.price, p.pimage, p.stock_quantity, p.unit as product_unit');
        $this->db->from('recipe_ingredients ri');
        $this->db->join('product p', 'p.pid = ri.product_id');
        $this->db->where('ri.recipe_id', $recipe_id);
        $this->db->order_by('ri.is_optional', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Calculate total cost of recipe
     */
    public function calculateRecipeCost($recipe_id) {
        $ingredients = $this->getRecipeIngredients($recipe_id);
        $total_cost = 0;
        
        foreach ($ingredients as $ingredient) {
            $cost_per_unit = $ingredient['price'];
            $quantity_needed = $ingredient['quantity'];
            
            // Simple calculation - in real scenario, you'd convert units properly
            $ingredient_cost = $cost_per_unit * $quantity_needed;
            $total_cost += $ingredient_cost;
        }
        
        return round($total_cost, 2);
    }

    /**
     * Check ingredient availability
     */
    public function checkIngredientAvailability($recipe_id) {
        $ingredients = $this->getRecipeIngredients($recipe_id);
        $availability = [];
        
        foreach ($ingredients as $ingredient) {
            $availability[$ingredient['product_id']] = [
                'available' => $ingredient['stock_quantity'] > 0,
                'stock' => $ingredient['stock_quantity'],
                'name' => $ingredient['pname']
            ];
        }
        
        return $availability;
    }

    /**
     * Get missing ingredients for a recipe
     */
    public function getMissingIngredients($recipe_id) {
        $ingredients = $this->getRecipeIngredients($recipe_id);
        $missing = [];
        
        foreach ($ingredients as $ingredient) {
            if ($ingredient['stock_quantity'] <= 0) {
                $missing[] = [
                    'product_id' => $ingredient['product_id'],
                    'name' => $ingredient['pname'],
                    'quantity' => $ingredient['quantity'],
                    'unit' => $ingredient['unit'],
                    'is_optional' => $ingredient['is_optional']
                ];
            }
        }
        
        return $missing;
    }

    /**
     * Add recipe ingredients to shopping list
     */
    public function addToShoppingList($recipe_id, $user_id = null, $guest_token = null, $servings = null) {
        $this->load->model('shopping_list');
        
        // Get or create shopping list
        $list_id = $this->shopping_list->getOrCreateRecipeList($recipe_id, $user_id, $guest_token);
        
        // Get recipe ingredients
        $ingredients = $this->getRecipeIngredients($recipe_id);
        
        if ($servings) {
            $recipe = $this->get_by_id($recipe_id);
            $multiplier = $servings / $recipe['servings'];
        } else {
            $multiplier = 1;
        }
        
        foreach ($ingredients as $ingredient) {
            $quantity = $ingredient['quantity'] * $multiplier;
            
            $this->shopping_list->addItemToList($list_id, $ingredient['product_id'], $quantity, $ingredient['unit']);
        }
        
        return $list_id;
    }

    /**
     * Search recipes
     */
    public function searchRecipes($query, $filters = []) {
        $this->db->select('*');
        $this->db->from('recipes');
        
        if (!empty($query)) {
            $this->db->group_start();
            $this->db->like('title', $query);
            $this->db->or_like('description', $query);
            $this->db->or_like('instructions', $query);
            $this->db->group_end();
        }
        
        // Apply filters
        if (!empty($filters['difficulty'])) {
            $this->db->where('difficulty', $filters['difficulty']);
        }
        
        if (!empty($filters['cuisine_type'])) {
            $this->db->where('cuisine_type', $filters['cuisine_type']);
        }
        
        if (!empty($filters['meal_type'])) {
            $this->db->where('meal_type', $filters['meal_type']);
        }
        
        $this->db->order_by('rating', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Get recipes that use specific products
     */
    public function getRecipesByProducts($product_ids) {
        $this->db->select('DISTINCT r.*');
        $this->db->from('recipes r');
        $this->db->join('recipe_ingredients ri', 'ri.recipe_id = r.recipe_id');
        $this->db->where_in('ri.product_id', $product_ids);
        $this->db->order_by('r.rating', 'DESC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Get popular recipes
     */
    public function getPopularRecipes($limit = 10) {
        $this->db->select('*');
        $this->db->from('recipes');
        $this->db->where('total_ratings >', 0);
        $this->db->order_by('rating', 'DESC');
        $this->db->order_by('total_ratings', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result_array();
    }

    /**
     * Get recipe suggestions based on available ingredients
     */
    public function getRecipeSuggestions($product_ids, $limit = 5) {
        if (empty($product_ids)) {
            return [];
        }
        
        // Get recipes that can be made with available ingredients
        $this->db->select('r.*, 
                          COUNT(ri.product_id) as total_ingredients,
                          SUM(CASE WHEN ri.product_id IN (' . implode(',', $product_ids) . ') THEN 1 ELSE 0 END) as available_ingredients');
        $this->db->from('recipes r');
        $this->db->join('recipe_ingredients ri', 'ri.recipe_id = r.recipe_id');
        $this->db->group_by('r.recipe_id');
        $this->db->having('available_ingredients >= (total_ingredients * 0.7)'); // At least 70% ingredients available
        $this->db->order_by('available_ingredients/total_ingredients', 'DESC');
        $this->db->order_by('r.rating', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result_array();
    }

    /**
     * Rate a recipe
     */
    public function rateRecipe($recipe_id, $rating) {
        $recipe = $this->get_by_id($recipe_id);
        
        if ($recipe && $rating >= 1 && $rating <= 5) {
            $current_rating = $recipe['rating'];
            $total_ratings = $recipe['total_ratings'];
            
            // Calculate new average rating
            $new_total_ratings = $total_ratings + 1;
            $new_rating = (($current_rating * $total_ratings) + $rating) / $new_total_ratings;
            
            $this->db->where('recipe_id', $recipe_id);
            $this->db->update('recipes', [
                'rating' => round($new_rating, 2),
                'total_ratings' => $new_total_ratings
            ]);
            
            return true;
        }
        
        return false;
    }

    /**
     * Get recipe categories/types for filtering
     */
    public function getRecipeCategories() {
        $cuisine_types = $this->db->select('DISTINCT cuisine_type')
                                  ->from('recipes')
                                  ->where('cuisine_type IS NOT NULL')
                                  ->get()->result_array();
        
        $meal_types = $this->db->select('DISTINCT meal_type')
                               ->from('recipes')
                               ->where('meal_type IS NOT NULL')
                               ->get()->result_array();
        
        $difficulties = $this->db->select('DISTINCT difficulty')
                                 ->from('recipes')
                                 ->get()->result_array();
        
        return [
            'cuisine_types' => array_column($cuisine_types, 'cuisine_type'),
            'meal_types' => array_column($meal_types, 'meal_type'),
            'difficulties' => array_column($difficulties, 'difficulty')
        ];
    }

    /**
     * Create shopping list from recipe
     */
    public function createShoppingListFromRecipe($recipe_id, $user_id = null, $guest_token = null) {
        $this->load->model('Shopping_list');
        
        $recipe = $this->get_by_id($recipe_id);
        if (!$recipe) {
            return false;
        }
        
        // Create new shopping list
        $list_data = [
            'user_id' => $user_id,
            'guest_token' => $guest_token,
            'list_name' => 'Recipe: ' . $recipe['title'],
            'list_type' => 'recipe_based',
            'recipe_id' => $recipe_id
        ];
        
        $list_id = $this->Shopping_list->createShoppingList($list_data);
        
        if ($list_id) {
            // Add ingredients to shopping list
            $ingredients = $this->getRecipeIngredients($recipe_id);
            foreach ($ingredients as $ingredient) {
                $this->Shopping_list->addItemToList($list_id, $ingredient['product_id'], 
                                                   $ingredient['quantity'], $ingredient['unit']);
            }
        }
        
        return $list_id;
    }
}
