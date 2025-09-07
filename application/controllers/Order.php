<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ordermodel');
    }

    public function orderList(){
        $userId = $this->session->userdata('userID');

        $userOrders['orderList'] = $this->ordermodel->listOrders($userId);

        // print_r($userOrders);

            $this->load->view('main/header');            
            $this->load->view('pages/ordersList', $userOrders);
            $this->load->view('main/footer');

    }
    
    /**
     * Get recent orders for dashboard
     */
    public function getRecentOrders() {
        if (!$this->session->userdata('userID')) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Unauthorized']));
            return;
        }
        
        $userId = $this->session->userdata('userID');
        $limit = $this->input->get('limit') ?: 5;
        
        // Mock data - replace with actual database calls
        $orders = [
            [
                'id' => 'ORD001',
                'status' => 'Delivered',
                'total' => '1,245.50',
                'item_count' => 8,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'id' => 'ORD002',
                'status' => 'Shipped',
                'total' => '856.75',
                'item_count' => 5,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
            ],
            [
                'id' => 'ORD003',
                'status' => 'Confirmed',
                'total' => '634.25',
                'item_count' => 4,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 week'))
            ]
        ];
        
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['success' => true, 'orders' => array_slice($orders, 0, $limit)]));
    }
}
