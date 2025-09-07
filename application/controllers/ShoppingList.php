<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ShoppingList extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('shopping_list');
        $this->load->model('products');
        $this->load->model('cart');
        $this->load->model('nutrition');
    }

    /**
     * Display user's shopping lists
     */
    public function index() {
        $user_id = $this->session->userdata('user_id');
        $guest_token = $this->session->userdata('guest_token');
        
        if (!$user_id && !$guest_token) {
            $guest_token = bin2hex(random_bytes(16));
            $this->session->set_userdata('guest_token', $guest_token);
        }
        
        $data['shopping_lists'] = $this->shopping_list->getUserShoppingLists($user_id, $guest_token);
        $data['stats'] = $this->shopping_list->getShoppingListStats($user_id, $guest_token);
        $data['frequently_bought'] = $this->shopping_list->getFrequentlyBoughtItems($user_id, 8);
        
        $this->load->view('main/header');
        $this->load->view('pages/shopping_list/index', $data);
        $this->load->view('main/footer');
    }

    /**
     * View specific shopping list
     */
    public function view($list_id) {
        $user_id = $this->session->userdata('user_id');
        $guest_token = $this->session->userdata('guest_token');
        
        $data['list'] = $this->shopping_list->getShoppingListWithItems($list_id);
        
        if (!$data['list']) {
            show_404();
            return;
        }
        
        // Verify ownership
        if ($data['list']['user_id'] != $user_id && $data['list']['guest_token'] != $guest_token) {
            show_error('Access denied', 403);
            return;
        }
        
        // Get nutrition summary
        if (!empty($data['list']['items'])) {
            $data['nutrition_summary'] = $this->nutrition->getNutritionSummary($data['list']['items']);
        }
        
        $this->load->view('main/header');
        $this->load->view('pages/shopping_list/detail', $data);
        $this->load->view('main/footer');
    }

    /**
     * Create new shopping list
     */
    public function create() {
        $list_name = $this->input->post('list_name');
        $is_default = $this->input->post('is_default') ? 1 : 0;
        
        if (!$list_name) {
            $this->session->set_flashdata('error', 'List name is required');
            redirect('shoppinglist');
            return;
        }
        
        $user_id = $this->session->userdata('user_id');
        $guest_token = $this->session->userdata('guest_token');
        
        if (!$user_id && !$guest_token) {
            $guest_token = bin2hex(random_bytes(16));
            $this->session->set_userdata('guest_token', $guest_token);
        }
        
        $list_id = $this->shopping_list->createShoppingList([
            'user_id' => $user_id,
            'guest_token' => $guest_token,
            'list_name' => $list_name,
            'is_default' => $is_default
        ]);
        
        if ($list_id) {
            $this->session->set_flashdata('success', 'Shopping list created successfully!');
            redirect('shoppinglist/view/' . $list_id);
        } else {
            $this->session->set_flashdata('error', 'Failed to create shopping list');
            redirect('shoppinglist');
        }
    }

    /**
     * Add item to shopping list (AJAX)
     */
    public function addItem() {
        $list_id = $this->input->post('list_id');
        $product_id = $this->input->post('product_id');
        $quantity = $this->input->post('quantity') ?: 1;
        $notes = $this->input->post('notes');
        
        if (!$list_id || !$product_id) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }
        
        // Get product info for unit
        $product = $this->products->getProductByID($product_id);
        if (empty($product)) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            return;
        }
        
        $unit = $product[0]['unit'];
        
        $item_id = $this->shopping_list->addItemToList($list_id, $product_id, $quantity, $unit, $notes);
        
        if ($item_id) {
            echo json_encode([
                'success' => true, 
                'message' => 'Item added to shopping list',
                'item_id' => $item_id
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add item']);
        }
    }

    /**
     * Update item quantity (AJAX)
     */
    public function updateQuantity() {
        $item_id = $this->input->post('item_id');
        $quantity = $this->input->post('quantity');
        
        if (!$item_id || !is_numeric($quantity)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }
        
        $result = $this->shopping_list->updateItemQuantity($item_id, $quantity);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Quantity updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update quantity']);
        }
    }

    /**
     * Mark item as purchased/unpurchased (AJAX)
     */
    public function markPurchased() {
        $item_id = $this->input->post('item_id');
        $purchased = $this->input->post('purchased') === 'true';
        
        if (!$item_id) {
            echo json_encode(['success' => false, 'message' => 'Item ID required']);
            return;
        }
        
        $result = $this->shopping_list->markItemPurchased($item_id, $purchased);
        
        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => $purchased ? 'Item marked as purchased' : 'Item unmarked'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update item']);
        }
    }

    /**
     * Remove item from list (AJAX)
     */
    public function removeItem() {
        $item_id = $this->input->post('item_id');
        
        if (!$item_id) {
            echo json_encode(['success' => false, 'message' => 'Item ID required']);
            return;
        }
        
        $result = $this->shopping_list->removeItemFromList($item_id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Item removed from list']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove item']);
        }
    }

    /**
     * Clear purchased items from list
     */
    public function clearPurchased($list_id) {
        $result = $this->shopping_list->clearPurchasedItems($list_id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Purchased items cleared from list');
        } else {
            $this->session->set_flashdata('error', 'Failed to clear purchased items');
        }
        
        redirect('shoppinglist/view/' . $list_id);
    }

    /**
     * Add all list items to cart
     */
    public function addToCart($list_id) {
        $user_id = $this->session->userdata('user_id');
        $ip_address = $this->input->ip_address();
        
        $added_count = $this->shopping_list->addListToCart($list_id, $user_id, $ip_address);
        
        if ($added_count > 0) {
            $this->session->set_flashdata('success', "{$added_count} items added to cart");
            redirect('cart');
        } else {
            $this->session->set_flashdata('error', 'No items were added to cart');
            redirect('shoppinglist/view/' . $list_id);
        }
    }

    /**
     * Create quick reorder list from previous order
     */
    public function quickReorder($order_id) {
        $user_id = $this->session->userdata('user_id');
        $guest_token = $this->session->userdata('guest_token');
        
        if (!$user_id && !$guest_token) {
            $guest_token = bin2hex(random_bytes(16));
            $this->session->set_userdata('guest_token', $guest_token);
        }
        
        $list_id = $this->shopping_list->createQuickReorderList($order_id, $user_id, $guest_token);
        
        if ($list_id) {
            $this->session->set_flashdata('success', 'Quick reorder list created successfully!');
            redirect('shoppinglist/view/' . $list_id);
        } else {
            $this->session->set_flashdata('error', 'Failed to create reorder list');
            redirect('shoppinglist');
        }
    }

    /**
     * Add product to default shopping list (AJAX)
     */
    public function addToDefaultList() {
        $product_id = $this->input->post('product_id');
        $quantity = $this->input->post('quantity') ?: 1;
        
        if (!$product_id) {
            echo json_encode(['success' => false, 'message' => 'Product ID required']);
            return;
        }
        
        $user_id = $this->session->userdata('user_id');
        $guest_token = $this->session->userdata('guest_token');
        
        if (!$user_id && !$guest_token) {
            $guest_token = bin2hex(random_bytes(16));
            $this->session->set_userdata('guest_token', $guest_token);
        }
        
        $list_id = $this->shopping_list->getOrCreateDefaultList($user_id, $guest_token);
        
        // Get product info
        $product = $this->products->getProductByID($product_id);
        if (empty($product)) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            return;
        }
        
        $unit = $product[0]['unit'];
        
        $item_id = $this->shopping_list->addItemToList($list_id, $product_id, $quantity, $unit);
        
        if ($item_id) {
            echo json_encode([
                'success' => true, 
                'message' => 'Added to shopping list!',
                'list_id' => $list_id
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add to list']);
        }
    }

    /**
     * Share shopping list
     */
    public function share($list_id) {
        $share_token = $this->shopping_list->shareShoppingList($list_id);
        
        if ($share_token) {
            $share_url = base_url('shoppinglist/shared/' . $share_token);
            echo json_encode([
                'success' => true, 
                'share_url' => $share_url,
                'share_token' => $share_token
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to generate share link']);
        }
    }

    /**
     * View shared shopping list
     */
    public function shared($share_token) {
        $data['list'] = $this->shopping_list->getSharedShoppingList($share_token);
        
        if (!$data['list']) {
            show_404();
            return;
        }
        
        $data['is_shared_view'] = true;
        
        // Get nutrition summary
        if (!empty($data['list']['items'])) {
            $data['nutrition_summary'] = $this->nutrition->getNutritionSummary($data['list']['items']);
        }
        
        $this->load->view('main/header');
        $this->load->view('pages/shopping_list/detail', $data);
        $this->load->view('main/footer');
    }

    /**
     * Delete shopping list
     */
    public function delete($list_id) {
        $user_id = $this->session->userdata('user_id');
        $guest_token = $this->session->userdata('guest_token');
        
        $result = $this->shopping_list->deleteShoppingList($list_id, $user_id, $guest_token);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Shopping list deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete shopping list or cannot delete default list');
        }
        
        redirect('shoppinglist');
    }

    /**
     * Get shopping list widget for sidebar (AJAX)
     */
    public function widget() {
        $user_id = $this->session->userdata('user_id');
        $guest_token = $this->session->userdata('guest_token');
        
        if (!$user_id && !$guest_token) {
            echo json_encode(['success' => false, 'message' => 'No user session']);
            return;
        }
        
        $lists = $this->shopping_list->getUserShoppingLists($user_id, $guest_token);
        $stats = $this->shopping_list->getShoppingListStats($user_id, $guest_token);
        
        $widget_html = $this->load->view('partials/shopping_list_widget', [
            'lists' => $lists,
            'stats' => $stats
        ], true);
        
        echo json_encode([
            'success' => true,
            'html' => $widget_html,
            'stats' => $stats
        ]);
    }

    /**
     * Export shopping list (text format)
     */
    public function export($list_id) {
        $list = $this->shopping_list->getShoppingListWithItems($list_id);
        
        if (!$list) {
            show_404();
            return;
        }
        
        $filename = 'shopping-list-' . date('Y-m-d') . '.txt';
        
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        echo "SHOPPING LIST: " . strtoupper($list['list_name']) . "\n";
        echo "Generated on: " . date('F j, Y g:i A') . "\n";
        echo "Total Items: " . count($list['items']) . "\n";
        echo "Estimated Cost: ₹" . number_format($list['total_cost'], 2) . "\n";
        echo str_repeat("-", 50) . "\n\n";
        
        $categories = [];
        foreach ($list['items'] as $item) {
            $categories[$item['category']][] = $item;
        }
        
        foreach ($categories as $category => $items) {
            echo strtoupper($category) . ":\n";
            echo str_repeat("-", 20) . "\n";
            
            foreach ($items as $item) {
                $status = $item['is_purchased'] ? '[✓]' : '[ ]';
                echo sprintf("%s %s x%.1f %s - ₹%.2f\n", 
                    $status,
                    $item['pname'],
                    $item['quantity'],
                    $item['unit'] ?? $item['product_unit'],
                    $item['price'] * $item['quantity']
                );
                
                if (!empty($item['notes'])) {
                    echo "    Note: " . $item['notes'] . "\n";
                }
            }
            echo "\n";
        }
        
        echo str_repeat("-", 50) . "\n";
        echo "Happy Shopping!\n";
    }
}
