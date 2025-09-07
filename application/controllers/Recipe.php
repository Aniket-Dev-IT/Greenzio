<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Recipe extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('recipe');
        $this->load->model('products');
        $this->load->model('shopping_list');
        $this->load->model('nutrition');
    }

    /**
     * Display all recipes
     */
    public function index() {
        $filters = [];
        
        // Get filter parameters
        if ($this->input->get('cuisine_type')) {
            $filters['cuisine_type'] = $this->input->get('cuisine_type');
        }
        if ($this->input->get('difficulty')) {
            $filters['difficulty'] = $this->input->get('difficulty');
        }
        if ($this->input->get('meal_type')) {
            $filters['meal_type'] = $this->input->get('meal_type');
        }
        if ($this->input->get('max_time')) {
            $filters['max_time'] = $this->input->get('max_time');
        }
        
        $data['recipes'] = $this->recipe->getAllRecipes($filters);
        $data['categories'] = $this->recipe->getRecipeCategories();
        $data['current_filters'] = $filters;
        $data['popular_recipes'] = $this->recipe->getPopularRecipes(6);
        
        $this->load->view('main/header');
        $this->load->view('pages/recipes/index', $data);
        $this->load->view('main/footer');
    }

    /**
     * Display single recipe with ingredients
     */
    public function view($recipe_id) {
        $data['recipe'] = $this->recipe->getRecipeWithIngredients($recipe_id);
        
        if (!$data['recipe']) {
            show_404();
            return;
        }
        
        // Get recipe suggestions based on current recipe ingredients
        $ingredient_ids = array_column($data['recipe']['ingredients'], 'product_id');
        $data['suggested_recipes'] = $this->recipe->getRecipesByProducts($ingredient_ids);
        
        // Remove current recipe from suggestions
        $data['suggested_recipes'] = array_filter($data['suggested_recipes'], function($r) use ($recipe_id) {
            return $r['recipe_id'] != $recipe_id;
        });
        
        $data['suggested_recipes'] = array_slice($data['suggested_recipes'], 0, 4);
        
        $this->load->view('main/header');
        $this->load->view('pages/recipes/detail', $data);
        $this->load->view('main/footer');
    }

    /**
     * Search recipes
     */
    public function search() {
        $query = $this->input->get('q');
        $filters = [
            'difficulty' => $this->input->get('difficulty'),
            'cuisine_type' => $this->input->get('cuisine_type'),
            'meal_type' => $this->input->get('meal_type')
        ];
        
        $data['recipes'] = $this->recipe->searchRecipes($query, $filters);
        $data['search_query'] = $query;
        $data['categories'] = $this->recipe->getRecipeCategories();
        $data['current_filters'] = $filters;
        
        $this->load->view('main/header');
        $this->load->view('pages/recipes/search_results', $data);
        $this->load->view('main/footer');
    }

    /**
     * Get recipe suggestions based on products (AJAX)
     */
    public function getSuggestions() {
        $product_ids = $this->input->post('product_ids');
        
        if (empty($product_ids) || !is_array($product_ids)) {
            echo json_encode(['success' => false, 'message' => 'No products provided']);
            return;
        }
        
        $suggestions = $this->recipe->getRecipeSuggestions($product_ids, 8);
        
        echo json_encode([
            'success' => true,
            'suggestions' => $suggestions,
            'count' => count($suggestions)
        ]);
    }

    /**
     * Add recipe ingredients to shopping list (AJAX)
     */
    public function addToShoppingList() {
        $recipe_id = $this->input->post('recipe_id');
        $servings = $this->input->post('servings');
        
        if (!$recipe_id) {
            echo json_encode(['success' => false, 'message' => 'Recipe ID required']);
            return;
        }
        
        // Get user identification
        $user_id = $this->session->userdata('user_id');
        $guest_token = $this->session->userdata('guest_token');
        
        if (!$user_id && !$guest_token) {
            $guest_token = bin2hex(random_bytes(16));
            $this->session->set_userdata('guest_token', $guest_token);
        }
        
        try {
            $list_id = $this->recipe->createShoppingListFromRecipe($recipe_id, $user_id, $guest_token);
            
            if ($list_id) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Ingredients added to shopping list successfully!',
                    'list_id' => $list_id
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create shopping list']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Rate a recipe (AJAX)
     */
    public function rate() {
        $recipe_id = $this->input->post('recipe_id');
        $rating = $this->input->post('rating');
        
        if (!$recipe_id || !$rating || $rating < 1 || $rating > 5) {
            echo json_encode(['success' => false, 'message' => 'Invalid rating data']);
            return;
        }
        
        $result = $this->recipe->rateRecipe($recipe_id, $rating);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Thank you for rating this recipe!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save rating']);
        }
    }

    /**
     * Get recipes by category (AJAX)
     */
    public function getByCategory() {
        $category_type = $this->input->get('type'); // cuisine_type, meal_type, difficulty
        $category_value = $this->input->get('value');
        
        $filters = [];
        if ($category_type && $category_value) {
            $filters[$category_type] = $category_value;
        }
        
        $recipes = $this->recipe->getAllRecipes($filters);
        
        $output = '';
        if (!empty($recipes)) {
            foreach ($recipes as $recipe) {
                $output .= $this->generateRecipeCard($recipe);
            }
        } else {
            $output = '<div class="col-12 text-center py-5">
                        <h4>No recipes found</h4>
                        <p class="text-muted">Try adjusting your filters</p>
                       </div>';
        }
        
        echo json_encode([
            'success' => true,
            'html' => $output,
            'count' => count($recipes)
        ]);
    }

    /**
     * Get recipe ingredients availability (AJAX)
     */
    public function checkIngredients() {
        $recipe_id = $this->input->get('recipe_id');
        
        if (!$recipe_id) {
            echo json_encode(['success' => false, 'message' => 'Recipe ID required']);
            return;
        }
        
        $availability = $this->recipe->checkIngredientAvailability($recipe_id);
        $missing = $this->recipe->getMissingIngredients($recipe_id);
        $cost = $this->recipe->calculateRecipeCost($recipe_id);
        
        echo json_encode([
            'success' => true,
            'availability' => $availability,
            'missing_ingredients' => $missing,
            'total_cost' => $cost
        ]);
    }

    /**
     * Generate recipe card HTML
     */
    private function generateRecipeCard($recipe) {
        $dietary_tags = json_decode($recipe['dietary_tags'], true) ?? [];
        $tags_html = '';
        
        foreach ($dietary_tags as $tag) {
            $tags_html .= '<span class="badge badge-success mr-1">' . ucfirst(str_replace('_', ' ', $tag)) . '</span>';
        }
        
        $total_time = ($recipe['prep_time'] ?? 0) + ($recipe['cook_time'] ?? 0);
        
        return '
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="recipe-card card h-100">
                <div class="recipe-image">
                    <img src="' . base_url($recipe['image']) . '" class="card-img-top" alt="' . htmlspecialchars($recipe['title']) . '">
                    <div class="recipe-overlay">
                        <div class="recipe-rating">
                            <i class="fas fa-star text-warning"></i>
                            <span>' . number_format($recipe['rating'], 1) . '</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">' . htmlspecialchars($recipe['title']) . '</h5>
                    <p class="card-text text-muted">' . substr(htmlspecialchars($recipe['description']), 0, 100) . '...</p>
                    
                    <div class="recipe-meta mb-3">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="far fa-clock mr-1"></i>
                                    ' . $total_time . ' mins
                                </small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="fas fa-users mr-1"></i>
                                    ' . $recipe['servings'] . ' servings
                                </small>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="fas fa-signal mr-1"></i>
                                    ' . $recipe['difficulty'] . '
                                </small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="fas fa-utensils mr-1"></i>
                                    ' . $recipe['cuisine_type'] . '
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dietary-tags mb-3">
                        ' . $tags_html . '
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="btn-group w-100">
                        <a href="' . base_url('recipe/view/' . $recipe['recipe_id']) . '" class="btn btn-primary">
                            <i class="fas fa-eye mr-1"></i>View Recipe
                        </a>
                        <button class="btn btn-outline-success add-to-shopping-list" data-recipe-id="' . $recipe['recipe_id'] . '">
                            <i class="fas fa-shopping-list mr-1"></i>Add to List
                        </button>
                    </div>
                </div>
            </div>
        </div>';
    }

    /**
     * Recipe suggestions widget (for homepage/product pages)
     */
    public function widget() {
        $product_ids = $this->input->get('product_ids');
        $limit = $this->input->get('limit') ?? 4;
        
        if ($product_ids) {
            $product_ids = explode(',', $product_ids);
            $data['recipes'] = $this->recipe->getRecipeSuggestions($product_ids, $limit);
        } else {
            $data['recipes'] = $this->recipe->getPopularRecipes($limit);
        }
        
        $this->load->view('partials/recipe_widget', $data);
    }
}
