<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shopping extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products');
        $this->load->model('cart');
        $this->load->model('biling');
    }



    public function checkCart()
    {
        try {
            $ipAddr = $this->input->ip_address();
            $pid = $this->input->post('pid');
            $quantity = $this->input->post('quantity', TRUE);
            $price = $this->input->post('price');
            $userId = $this->session->userdata('userID');
            
            // Validate required fields
            if (!$pid) {
                $response = [
                    'success' => false,
                    'message' => 'Product ID is required.'
                ];
                
                if ($this->input->is_ajax_request()) {
                    echo json_encode($response);
                    return;
                } else {
                    $this->session->set_flashdata('cart_error', $response['message']);
                    redirect($_SERVER['HTTP_REFERER']);
                    return;
                }
            }
            
            // Set default quantity if not provided
            if (!$quantity || $quantity < 1) {
                $quantity = 1;
            }
            
            // Check stock availability first
            $availableStock = $this->products->checkStock($pid);
            
            if ($availableStock < $quantity) {
                $response = [
                    'success' => false,
                    'message' => 'Insufficient stock. Only ' . $availableStock . ' units available.',
                    'available_stock' => $availableStock
                ];
                
                if ($this->input->is_ajax_request()) {
                    echo json_encode($response);
                    return;
                } else {
                    $this->session->set_flashdata('cart_error', $response['message']);
                    redirect($_SERVER['HTTP_REFERER']);
                    return;
                }
            }
            
            // Check if product already exists in cart
            if (isset($userId)) {
                $checkCart = $this->cart->checkCartProductQuantity($pid, $userId);
            } else {
                $checkCart = $this->cart->checkCartProductQuantityByIP($pid, $ipAddr);
            }
            
            // Calculate total price based on quantity with bulk discount
            $totalPrice = 0;
            try {
                $totalPrice = $this->cart->getBulkDiscountPrice($pid, $quantity);
                // If getBulkDiscountPrice returns 0 or fails, use simple calculation
                if (!$totalPrice || $totalPrice <= 0) {
                    $productInfo = $this->products->getProductByID($pid);
                    if ($productInfo && !empty($productInfo)) {
                        $totalPrice = $productInfo[0]['price'] * $quantity;
                    } else {
                        $totalPrice = $price * $quantity;
                    }
                }
            } catch (Exception $e) {
                // Log error and use fallback pricing
                log_message('error', 'Bulk pricing calculation failed: ' . $e->getMessage());
                $productInfo = $this->products->getProductByID($pid);
                if ($productInfo && !empty($productInfo)) {
                    $totalPrice = $productInfo[0]['price'] * $quantity;
                } else {
                    $totalPrice = $price * $quantity;
                }
            }
            
            $cartData = [
                'product_id' => $pid,
                'ip_address' => $ipAddr,
                'quantity' => $quantity,
                'price' => $totalPrice,
                'user_id' => $userId
            ];
            
            if (empty($checkCart)) {
                // Add new item to cart
                $insertResult = $this->cart->insertCart($cartData);
                if ($insertResult) {
                    $response = ['success' => true, 'message' => 'Product added to cart successfully'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to add product to cart. Please try again.'];
                }
            } else {
                // Update existing cart item quantity
                $newQuantity = $checkCart[0]['quantity'] + $quantity;
                
                // Check if new total quantity exceeds stock
                if ($availableStock < $newQuantity) {
                    $response = [
                        'success' => false,
                        'message' => 'Cannot add more items. Total would exceed available stock (' . $availableStock . ' units).',
                        'available_stock' => $availableStock,
                        'current_in_cart' => $checkCart[0]['quantity']
                    ];
                } else {
                    $updateResult = $this->cart->updateCartQuantity($pid, $userId ?: $ipAddr, $newQuantity, isset($userId));
                    if ($updateResult) {
                        $response = ['success' => true, 'message' => 'Cart updated successfully'];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to update cart. Please try again.'];
                    }
                }
            }
            
            // Send response
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                if ($response['success']) {
                    $this->session->set_flashdata('cart_success', $response['message']);
                } else {
                    $this->session->set_flashdata('cart_error', $response['message']);
                }
                $this->cart();
            }
            
        } catch (Exception $e) {
            log_message('error', 'Add to cart error: ' . $e->getMessage() . ' at line ' . $e->getLine() . ' in file ' . $e->getFile());
            
            $response = [
                'success' => false,
                'message' => 'An error occurred while adding to cart. Please try again.',
                'debug' => ENVIRONMENT === 'development' ? $e->getMessage() : null
            ];
            
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                $this->session->set_flashdata('cart_error', $response['message']);
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }



    public function orderSummary()
    {
        $id = $this->session->userdata('userID');
        $ipAddr = $this->input->ip_address();
        if (isset($id))
            // $cartSubTotal['userID'] = $id;
        {
            $cartSubTotal['subtotal'] = $this->cart->getCartTotalPrice($id);
        } else {
            $cartSubTotal['subtotal'] =  $this->cart->getCartTotalPriceByIP($ipAddr);
        }
        // print_r($cartSubTotal);
      
        $this->load->view('pages/order_summary', $cartSubTotal);
    }



    public function cart()
    { //add to cart
        $ipAddr = $this->input->ip_address();

        $userId = $this->session->userdata('userID');

        $sessionIPAddr = $this->session->userdata('ipAddr');

        if ($sessionIPAddr === $ipAddr) {

            //if session ip address == local ipaddress update cart by entering the user id in the cart
            $this->cart->updateCartID($userId, $ipAddr);
            // $this->cart();
        }

        if (!isset($userId)) {
            $showCart = $this->cart->showCart($ipAddr);
        } else {
            $showCart = $this->cart->showCartByID($userId);
        }

        if (!empty($showCart)) {
            //   print_r($showCart);

            foreach ($showCart as $values) {
                $implodedValue[] = $values['product_id'];
            }
            $implodedValue = implode(',', $implodedValue);
            //echo $implodedValue;
            $productDetail['productDetail'] = $this->cart->getProductsForCart($implodedValue);
        } else {
            $productDetail['productDetail'] = NULL;
        }
        //  print_r($productDetail);
        $this->load->view('main/header');
        $this->load->view('cart/index', $productDetail);
        $this->load->view('main/footer');
    }

    public function deleteItem()
    {
        $id = $this->input->get('id');
        $userId = $this->session->userdata('userID');
        $ipAddr = $this->input->ip_address();
        
        if (isset($userId)) {
            $this->cart->deleteCartItemByUser($id, $userId);
        } else {
            $this->cart->deleteCartItemByIP($id, $ipAddr);
        }
        
        $this->session->set_flashdata('cart_success', 'Item removed from cart successfully');
        redirect('shopping/cart/');
    }
    
    public function updateQuantity()
    {
        $pid = $this->input->post('pid');
        $quantity = $this->input->post('quantity', TRUE);
        $userId = $this->session->userdata('userID');
        $ipAddr = $this->input->ip_address();
        
        if (!$quantity || $quantity < 1) {
            $response = ['success' => false, 'message' => 'Invalid quantity'];
        } else {
            // Check stock availability
            $availableStock = $this->products->checkStock($pid);
            
            if ($availableStock < $quantity) {
                $response = [
                    'success' => false,
                    'message' => 'Insufficient stock. Only ' . $availableStock . ' units available.',
                    'available_stock' => $availableStock
                ];
            } else {
                // Update cart quantity
                if (isset($userId)) {
                    $updated = $this->cart->updateCartQuantity($pid, $userId, $quantity, true);
                } else {
                    $updated = $this->cart->updateCartQuantity($pid, $ipAddr, $quantity, false);
                }
                
                if ($updated) {
                    // Calculate new total price
                    $newPrice = $this->products->calculatePricePerUnit($pid, $quantity);
                    $response = [
                        'success' => true,
                        'message' => 'Quantity updated successfully',
                        'new_price' => $newPrice,
                        'quantity' => $quantity
                    ];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to update quantity'];
                }
            }
        }
        
        echo json_encode($response);
    }
    
    public function getCartItemCount()
    {
        $userId = $this->session->userdata('userID');
        $ipAddr = $this->input->ip_address();
        
        if (isset($userId)) {
            $count = $this->cart->getCartItemCountByUser($userId);
        } else {
            $count = $this->cart->getCartItemCountByIP($ipAddr);
        }
        
        echo json_encode(['count' => $count]);
    }

    public function checkPinCode()
    {
        $pincode =  $this->input->post('pincode');

        if (strlen($pincode) < 6 || strlen($pincode) > 6) {  //$text = "Wrong Pincode";
            $completeMessage =  '<p class="workFont mt-2 text-danger"> Wrong Pincode </p>';
        }
        //   else{

        // $apiResults = file_get_contents(base_url('/assets/json/all-india-pincode-json-array.json'));

        // $result = json_decode($apiResults, true);
        //   print_r($result);
        //   $completeMessage = $result;
        //   }
        //   $message = $result['Message'];
        //     // $text = "Expected Delivery 10 - 15 days";

        //     if($message === 'No records found'){
        //         // $text = "Expected Delivery 10 - 15 days";
        //         $completeMessage =  '<p class="workFont mt-2 text-success">Expected Delivery 10 - 15 days. </p>';
        //     }
        //     else {
        //     //    $text = "No COD Available";
        //         $completeMessage =  '<p class="workFont mt-2 text-danger">No COD Available</p>';
        //     }
        //   }

        $data = array('text' => $completeMessage);
        echo json_encode($data);
    }



    public function getAddressInput()
    {
        $userId = $this->session->userdata('userID');
    //  $deliveryButton = $this->input->post('deliveryButton');
        $data = array();

         $checkDetailResult = $this->biling->checkDetails($userId);

        //  print_r($checkDetailResult);

        if (!empty($checkDetailResult)) {

           
            $dataToUpdate = array();    
            $dataToUpdate['name'] = $this->input->post('fullname_invoice');
            $dataToUpdate['email'] = $this->input->post('email_invoice');
            $dataToUpdate['street'] = $this->input->post('street_invoice');
            $dataToUpdate['city'] = $this->input->post('city_invoice');
            $dataToUpdate['zip'] = $this->input->post('zip_invoice');
            $dataToUpdate['state'] = $this->input->post('state_invoice');
            $dataToUpdate['mobile'] = $this->input->post('phone_invoice');

            // print_r($dataToUpdate);
             $this->biling->updateDetail($dataToUpdate, $userId);

        //    echo $result;
             $this->session->set_userdata($dataToUpdate);
        }
         else {

            $data['user_id'] = $this->session->userdata('userID');
            $data['name'] = $this->input->post('fullname_invoice');
            $data['email'] = $this->input->post('email_invoice');
            $data['street'] = $this->input->post('street_invoice');
            $data['city'] = $this->input->post('city_invoice');
            $data['zip'] = $this->input->post('zip_invoice');
            $data['state'] = $this->input->post('state_invoice');
            $data['mobile'] = $this->input->post('phone_invoice');
    
            $data =  $this->biling->insertAddress($data);
           
            $this->session->set_userdata($data);
        }

    $this->deliveryMethod();

    }
    public function getAddress(){

        $userId = $this->session->userdata('userID'); //to check if cart is empty or not. if not empty then don't redirect
    
        // If user is not logged in, redirect to login
        if (!$userId) {
            $this->session->set_flashdata('login_required', 'Please login to proceed with checkout.');
            redirect(base_url() . '?login_required=1');
            return;
        }
    
        $checkCart = $this->cart->checkCartByID($userId);

        if(!empty($checkCart)){

            $checkAddress['billDetails'] = $this->biling->checkDetails($userId);
        
        $this->load->view('main/header');
        $this->load->view('pages/checkout-heading');
        $this->load->view('pages/address', $checkAddress);
        $this->orderSummary();
        // $this->load->view('pages/order_summary');
        $this->load->view('main/footer');
            } 
            else {
                $this->cart();
            }
        }
    


    public function deliveryMethod()
    {
        $this->load->view('main/header');
        $this->load->view('pages/checkout-heading');
        $this->load->view('pages/deliveryMethod');
        $this->orderSummary();
        // $this->load->view('pages/order_summary');
        $this->load->view('main/footer');
    }

    public function deliveryMethodInput(){
      $deliveryMethod =  $this->input->post('shipping');
      $deliveryDate = $this->input->post('delivery_date');
      $deliveryTimeSlot = $this->input->post('delivery_time_slot');
      $deliveryInstructions = $this->input->post('delivery_instructions');
      $handleFragile = $this->input->post('handle_fragile') ? 1 : 0;
      $keepCold = $this->input->post('keep_cold') ? 1 : 0;
      $contactBefore = $this->input->post('contact_before') ? 1 : 0;
      $userId = $this->session->userdata('userID');

      // Store delivery details in session for order confirmation
      $deliveryData = [
          'delivery_method' => $deliveryMethod,
          'delivery_date' => $deliveryDate,
          'delivery_time_slot' => $deliveryTimeSlot,
          'delivery_instructions' => $deliveryInstructions,
          'handle_fragile' => $handleFragile,
          'keep_cold' => $keepCold,
          'contact_before' => $contactBefore
      ];
      $this->session->set_userdata($deliveryData);

      $updated = $this->biling->updateDeliveryMethod($deliveryMethod, $userId);
      
      // Also update delivery date and time slot in billing details
      $this->biling->updateDeliveryDetails($userId, $deliveryDate, $deliveryTimeSlot, $deliveryInstructions);
      
      // Update special handling requirements
      $specialHandling = [
          'handle_fragile' => $handleFragile,
          'keep_cold' => $keepCold,
          'contact_before' => $contactBefore
      ];
      $this->biling->updateSpecialHandling($userId, $specialHandling);

      $this->paymentmethod();
    }

    public function paymentmethod()
    {
        $this->load->view('main/header');
        $this->load->view('pages/checkout-heading');
        $this->load->view('pages/paymentMethod');
        $this->orderSummary();
        // $this->load->view('pages/order_summary');
        $this->load->view('main/footer');
    }

    public function paymentMethodInput(){
        $paymentMethod =  $this->input->post('paymentMethod');
        $userId = $this->session->userdata('userID');

        // echo $deliveryMethod;  
        // echo $userId;
           $updated = $this->biling->updatePaymentMethod($paymentMethod, $userId);

           $this->orderReview();
        
    }

    public function orderReview()

    {   $userId = $this->session->userdata('userID');

        $showCart = $this->cart->showCartByID($userId);

        if (!empty($showCart)) {
            //   print_r($showCart);

            foreach ($showCart as $values) {
                $implodedValue[] = $values['product_id'];
            }
            $implodedValue = implode(',', $implodedValue);
            //echo $implodedValue;
            $productDetail['productDetail'] = $this->cart->getProductsForCart($implodedValue);
        } else {
            $productDetail['productDetail'] = NULL;
        }

        $this->load->view('main/header');
        $this->load->view('pages/checkout-heading');
        $this->load->view('pages/order_review', $productDetail);
        $this->orderSummary();
        // $this->load->view('pages/order_summary');
        $this->load->view('main/footer');
    }

    public function confirmOrder(){
        //shift all the data in order and order details table, and empty the cart. 
        date_default_timezone_set('Asia/Kolkata');// change according timezone
       
        $currentDate = date('Y-m-d H:i:s', time());

            $userId = $this->session->userdata('userID');
            $total = $this->session->userdata('total');
            $showCart = $this->cart->showCartByID($userId);
            $order = array();
            $order['user_id'] = $userId;
            $order['order_total'] = $total;
            $order['order_status'] = 'pending'; // Changed from 'confirm' to 'pending'
            $order['order_date'] = $currentDate;

           $orderId =  $this->cart->placeOrder($order);
           
        //    $orderDetail = array();
        //    $orderDetails['order_id'] = $orderId;
            foreach ($showCart as $cart){
    
                 $orderDetails['order_id'] = $orderId;
              $orderDetails['product_id'] = $cart['product_id'];
              $orderDetails['product_price'] = $cart['price'];
              $orderDetails['product_quantity'] = $cart['quantity'];
              
              // Update product stock when order is confirmed
              $this->products->updateStock($cart['product_id'], $cart['quantity'], 'subtract');

              $orderDetailId = $this->cart->insertOrderDetails($orderDetails);

            }

            // print_r($orderDetails);

       
        if(!empty($orderDetailId)){
            
            $deleteCart = $this->cart->deleteCartByUserId($userId);
            
            $this->load->view('main/header');            
            $this->load->view('pages/order_confirmed');
            $this->load->view('main/footer');
        }
        else redirect();



    }
    
    /**
     * Add item to cart via AJAX
     */
    public function addToCart() {
        try {
            $ipAddr = $this->input->ip_address();
            $pid = $this->input->post('product_id');
            $quantity = $this->input->post('quantity', TRUE);
            $userId = $this->session->userdata('userID');
            
            // Validate required fields
            if (!$pid) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Product ID is required.'
                ]);
                return;
            }
            
            // Set default quantity if not provided
            if (!$quantity || $quantity < 1) {
                $quantity = 1;
            }
            
            // Check stock availability first
            $availableStock = $this->products->checkStock($pid);
            
            if ($availableStock < $quantity) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Insufficient stock. Only ' . $availableStock . ' units available.',
                    'available_stock' => $availableStock
                ]);
                return;
            }
            
            // Check if product already exists in cart
            if (isset($userId)) {
                $checkCart = $this->cart->checkCartProductQuantity($pid, $userId);
            } else {
                $checkCart = $this->cart->checkCartProductQuantityByIP($pid, $ipAddr);
            }
            
            // Get product info for pricing
            $productInfo = $this->products->getProductByID($pid);
            if (!$productInfo) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Product not found.'
                ]);
                return;
            }
            
            // Calculate total price
            $unitPrice = $productInfo[0]['price'];
            $totalPrice = $unitPrice * $quantity;
            
            $cartData = [
                'product_id' => $pid,
                'ip_address' => $ipAddr,
                'quantity' => $quantity,
                'price' => $totalPrice,
                'user_id' => $userId
            ];
            
            if (empty($checkCart)) {
                // Add new item to cart
                $insertResult = $this->cart->insertCart($cartData);
                if ($insertResult) {
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Product added to cart successfully!'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false, 
                        'message' => 'Failed to add product to cart. Please try again.'
                    ]);
                }
            } else {
                // Update existing cart item quantity
                $newQuantity = $checkCart[0]['quantity'] + $quantity;
                
                // Check if new total quantity exceeds stock
                if ($availableStock < $newQuantity) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Cannot add more items. Total would exceed available stock (' . $availableStock . ' units).',
                        'available_stock' => $availableStock,
                        'current_in_cart' => $checkCart[0]['quantity']
                    ]);
                } else {
                    $updateResult = $this->cart->updateCartQuantity($pid, $userId ?: $ipAddr, $newQuantity, isset($userId));
                    if ($updateResult) {
                        echo json_encode([
                            'success' => true, 
                            'message' => 'Cart updated successfully!'
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false, 
                            'message' => 'Failed to update cart. Please try again.'
                        ]);
                    }
                }
            }
            
        } catch (Exception $e) {
            log_message('error', 'Add to cart error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while adding to cart. Please try again.'
            ]);
        }
    }

    /**
     * Clear entire cart for user
     */
    public function clearCart() {
        $userId = $this->session->userdata('userID');
        $ipAddr = $this->input->ip_address();
        
        if (isset($userId)) {
            $this->cart->deleteCartByUserId($userId);
        } else {
            // Clear cart by IP address for guests
            $this->cart->clearCartByIP($ipAddr);
        }
        
        $this->session->set_flashdata('cart_success', 'Cart cleared successfully!');
        redirect('shopping/cart');
    }



}
