<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Shopping_list extends Base_model {
    
    protected $table = 'shopping_lists';
    protected $primary_key = 'list_id';
    protected $fillable = [
        'user_id', 'guest_token', 'list_name', 'is_default', 'list_type', 'recipe_id'
    ];
    protected $timestamps = true;

    function __construct() {
        parent::__construct();
    }

    /**
     * Get user's shopping lists
     */
    public function getUserShoppingLists($user_id = null, $guest_token = null) {
        $this->db->select('sl.*, COUNT(sli.item_id) as item_count, SUM(CASE WHEN sli.is_purchased = 1 THEN 1 ELSE 0 END) as purchased_count');
        $this->db->from('shopping_lists sl');
        $this->db->join('shopping_list_items sli', 'sli.list_id = sl.list_id', 'left');
        
        if ($user_id) {
            $this->db->where('sl.user_id', $user_id);
        } else if ($guest_token) {
            $this->db->where('sl.guest_token', $guest_token);
        } else {
            return [];
        }
        
        $this->db->group_by('sl.list_id');
        $this->db->order_by('sl.is_default', 'DESC');
        $this->db->order_by('sl.updated_at', 'DESC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Get shopping list with items
     */
    public function getShoppingListWithItems($list_id) {
        // Get the list details
        $list = $this->get_by_id($list_id);
        
        if ($list) {
            // Get list items
            $this->db->select('sli.*, p.pname, p.price, p.pimage, p.stock_quantity, p.unit as product_unit, p.weight');
            $this->db->from('shopping_list_items sli');
            $this->db->join('product p', 'p.pid = sli.product_id');
            $this->db->where('sli.list_id', $list_id);
            $this->db->order_by('sli.is_purchased', 'ASC');
            $this->db->order_by('sli.added_at', 'ASC');
            
            $list['items'] = $this->db->get()->result_array();
            $list['total_cost'] = $this->calculateListCost($list_id);
            $list['completion_percentage'] = $this->getCompletionPercentage($list_id);
        }
        
        return $list;
    }

    /**
     * Create new shopping list
     */
    public function createShoppingList($data) {
        $list_data = [
            'user_id' => $data['user_id'] ?? null,
            'guest_token' => $data['guest_token'] ?? null,
            'list_name' => $data['list_name'] ?? 'My Shopping List',
            'is_default' => $data['is_default'] ?? 0,
            'list_type' => $data['list_type'] ?? 'regular',
            'recipe_id' => $data['recipe_id'] ?? null
        ];
        
        $this->db->insert('shopping_lists', $list_data);
        return $this->db->insert_id();
    }

    /**
     * Get or create default shopping list
     */
    public function getOrCreateDefaultList($user_id = null, $guest_token = null) {
        // Try to get existing default list
        $this->db->select('list_id');
        $this->db->from('shopping_lists');
        $this->db->where('is_default', 1);
        
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        } else if ($guest_token) {
            $this->db->where('guest_token', $guest_token);
        }
        
        $result = $this->db->get()->row_array();
        
        if ($result) {
            return $result['list_id'];
        }
        
        // Create new default list
        return $this->createShoppingList([
            'user_id' => $user_id,
            'guest_token' => $guest_token,
            'list_name' => 'My Shopping List',
            'is_default' => 1,
            'list_type' => 'regular'
        ]);
    }

    /**
     * Add item to shopping list
     */
    public function addItemToList($list_id, $product_id, $quantity = 1, $unit = null, $notes = null) {
        // Check if item already exists in the list
        $existing = $this->db->select('*')
                            ->from('shopping_list_items')
                            ->where('list_id', $list_id)
                            ->where('product_id', $product_id)
                            ->get()->row_array();
        
        if ($existing) {
            // Update quantity
            $new_quantity = $existing['quantity'] + $quantity;
            $this->db->where('item_id', $existing['item_id']);
            $this->db->update('shopping_list_items', ['quantity' => $new_quantity]);
            return $existing['item_id'];
        } else {
            // Add new item
            $item_data = [
                'list_id' => $list_id,
                'product_id' => $product_id,
                'quantity' => $quantity,
                'unit' => $unit,
                'notes' => $notes
            ];
            
            $this->db->insert('shopping_list_items', $item_data);
            return $this->db->insert_id();
        }
    }

    /**
     * Update item quantity
     */
    public function updateItemQuantity($item_id, $quantity) {
        if ($quantity <= 0) {
            return $this->removeItemFromList($item_id);
        }
        
        $this->db->where('item_id', $item_id);
        return $this->db->update('shopping_list_items', ['quantity' => $quantity]);
    }

    /**
     * Mark item as purchased/unpurchased
     */
    public function markItemPurchased($item_id, $purchased = true) {
        $data = [
            'is_purchased' => $purchased ? 1 : 0,
            'purchased_at' => $purchased ? date('Y-m-d H:i:s') : null
        ];
        
        $this->db->where('item_id', $item_id);
        return $this->db->update('shopping_list_items', $data);
    }

    /**
     * Remove item from list
     */
    public function removeItemFromList($item_id) {
        $this->db->where('item_id', $item_id);
        return $this->db->delete('shopping_list_items');
    }

    /**
     * Clear purchased items from list
     */
    public function clearPurchasedItems($list_id) {
        $this->db->where('list_id', $list_id);
        $this->db->where('is_purchased', 1);
        return $this->db->delete('shopping_list_items');
    }

    /**
     * Calculate total cost of shopping list
     */
    public function calculateListCost($list_id) {
        $this->db->select('SUM(sli.quantity * p.price) as total_cost');
        $this->db->from('shopping_list_items sli');
        $this->db->join('product p', 'p.pid = sli.product_id');
        $this->db->where('sli.list_id', $list_id);
        $this->db->where('sli.is_purchased', 0);
        
        $result = $this->db->get()->row_array();
        return round($result['total_cost'] ?? 0, 2);
    }

    /**
     * Get completion percentage of shopping list
     */
    public function getCompletionPercentage($list_id) {
        $this->db->select('COUNT(*) as total_items, SUM(is_purchased) as purchased_items');
        $this->db->from('shopping_list_items');
        $this->db->where('list_id', $list_id);
        
        $result = $this->db->get()->row_array();
        
        if ($result['total_items'] == 0) {
            return 0;
        }
        
        return round(($result['purchased_items'] / $result['total_items']) * 100, 1);
    }

    /**
     * Create quick reorder list from previous order
     */
    public function createQuickReorderList($order_id, $user_id = null, $guest_token = null) {
        // Get order details
        $this->db->select('od.*, p.pname');
        $this->db->from('order_details od');
        $this->db->join('product p', 'p.pid = od.pid');
        $this->db->where('od.order_id', $order_id);
        $order_items = $this->db->get()->result_array();
        
        if (empty($order_items)) {
            return false;
        }
        
        // Create new shopping list
        $list_id = $this->createShoppingList([
            'user_id' => $user_id,
            'guest_token' => $guest_token,
            'list_name' => 'Quick Reorder - Order #' . $order_id,
            'list_type' => 'quick_reorder'
        ]);
        
        // Add items to shopping list
        foreach ($order_items as $item) {
            $this->addItemToList($list_id, $item['pid'], $item['quantity']);
        }
        
        return $list_id;
    }

    /**
     * Get frequently bought items for suggestions
     */
    public function getFrequentlyBoughtItems($user_id = null, $limit = 10) {
        $this->db->select('p.pid, p.pname, p.pimage, p.price, COUNT(*) as purchase_frequency');
        $this->db->from('order_details od');
        $this->db->join('orders o', 'o.order_id = od.order_id');
        $this->db->join('product p', 'p.pid = od.pid');
        
        if ($user_id) {
            $this->db->where('o.user_id', $user_id);
        }
        
        $this->db->group_by('p.pid');
        $this->db->order_by('purchase_frequency', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result_array();
    }

    /**
     * Share shopping list (generate shareable link)
     */
    public function shareShoppingList($list_id) {
        // Generate unique share token
        $share_token = bin2hex(random_bytes(16));
        
        $this->db->where('list_id', $list_id);
        $this->db->update('shopping_lists', ['share_token' => $share_token]);
        
        return $share_token;
    }

    /**
     * Get shopping list by share token
     */
    public function getSharedShoppingList($share_token) {
        $this->db->select('*');
        $this->db->from('shopping_lists');
        $this->db->where('share_token', $share_token);
        
        $list = $this->db->get()->row_array();
        
        if ($list) {
            return $this->getShoppingListWithItems($list['list_id']);
        }
        
        return null;
    }

    /**
     * Delete shopping list
     */
    public function deleteShoppingList($list_id, $user_id = null, $guest_token = null) {
        // Verify ownership
        $this->db->select('*');
        $this->db->from('shopping_lists');
        $this->db->where('list_id', $list_id);
        
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        } else if ($guest_token) {
            $this->db->where('guest_token', $guest_token);
        }
        
        $list = $this->db->get()->row_array();
        
        if ($list && !$list['is_default']) { // Don't delete default list
            $this->db->where('list_id', $list_id);
            return $this->db->delete('shopping_lists');
        }
        
        return false;
    }

    /**
     * Get shopping list statistics
     */
    public function getShoppingListStats($user_id = null, $guest_token = null) {
        $stats = [];
        
        // Total lists
        $this->db->select('COUNT(*) as total_lists');
        $this->db->from('shopping_lists');
        
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        } else if ($guest_token) {
            $this->db->where('guest_token', $guest_token);
        }
        
        $result = $this->db->get()->row_array();
        $stats['total_lists'] = $result['total_lists'];
        
        // Total items across all lists
        $this->db->select('COUNT(*) as total_items');
        $this->db->from('shopping_list_items sli');
        $this->db->join('shopping_lists sl', 'sl.list_id = sli.list_id');
        
        if ($user_id) {
            $this->db->where('sl.user_id', $user_id);
        } else if ($guest_token) {
            $this->db->where('sl.guest_token', $guest_token);
        }
        
        $result = $this->db->get()->row_array();
        $stats['total_items'] = $result['total_items'];
        
        // Total value
        $this->db->select('SUM(sli.quantity * p.price) as total_value');
        $this->db->from('shopping_list_items sli');
        $this->db->join('shopping_lists sl', 'sl.list_id = sli.list_id');
        $this->db->join('product p', 'p.pid = sli.product_id');
        
        if ($user_id) {
            $this->db->where('sl.user_id', $user_id);
        } else if ($guest_token) {
            $this->db->where('sl.guest_token', $guest_token);
        }
        
        $result = $this->db->get()->row_array();
        $stats['total_value'] = round($result['total_value'] ?? 0, 2);
        
        return $stats;
    }

    /**
     * Add multiple items to cart from shopping list
     */
    public function addListToCart($list_id, $user_id = null, $ip_address = null) {
        $this->load->model('cart');
        
        $list_items = $this->db->select('*')
                              ->from('shopping_list_items')
                              ->where('list_id', $list_id)
                              ->where('is_purchased', 0)
                              ->get()->result_array();
        
        $added_count = 0;
        
        foreach ($list_items as $item) {
            $cart_data = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $this->cart->calculateWeightBasedPrice($item['product_id'], $item['quantity']),
                'user_id' => $user_id,
                'ip_address' => $ip_address
            ];
            
            $this->cart->insertCart($cart_data);
            $added_count++;
        }
        
        return $added_count;
    }
}
