

              <?php
              
            $subTotal = 0;
            $totalSavings = 0;
            $shipping = 0.0;
            $grandTotal = 0.0;
            $itemCount = 0;

            // Get cart items to calculate savings
            $userId = $this->session->userdata('userID');
            $ipAddr = $this->input->ip_address();
            
            if($userId) {
                $cartItems = $this->cart->showCartByID($userId);
            } else {
                $cartItems = $this->cart->showCart($ipAddr);
            }
            
            // Calculate subtotal and savings
            if(!empty($subtotal)){
                foreach($subtotal as $value){
                  (int) $subTotal = $value['price'];
                } 
                
                // Calculate total savings from bulk discounts
                if(!empty($cartItems)) {
                    foreach($cartItems as $item) {
                        $itemCount++;
                        $originalPrice = $this->cart->calculateWeightBasedPrice($item['product_id'], $item['quantity']);
                        $actualPrice = $item['price'];
                        if($originalPrice > $actualPrice) {
                            $totalSavings += ($originalPrice - $actualPrice);
                        }
                    }
                }
                
                // Minimum order amount validation
                $minimumOrderAmount = 200;
                $isMinimumMet = $subTotal >= $minimumOrderAmount;
                
                // Calculate shipping
                if($subTotal <= 500 && $subTotal > 0) {
                    $shipping = 50;
                } else {
                    $shipping = 0; // Free shipping for orders above 500
                }

                $grandTotal = $subTotal + floatval($shipping);
                $total['total'] = $grandTotal;
                $this->session->set_userdata($total);
            }
              ?>

            <div class="col-lg-4">
            <div class="block mb-5">
              <div class="block-header">
                <h6 class="text-uppercase mb-0">Order Summary</h6>
              </div>
              <div class="block-body bg-light pt-1">
                <p class="text-sm">Shipping and additional costs are calculated based on values you have entered.</p>
                <ul class="order-summary mb-0 list-unstyled">
                  <li class="order-summary-item d-flex justify-content-between">
                    <span>Subtotal (<?php echo $itemCount; ?> items)</span>
                    <span>₹<?php echo number_format($subTotal, 2); ?></span>
                  </li>
                  
                  <?php if($totalSavings > 0): ?>
                  <li class="order-summary-item d-flex justify-content-between text-success">
                    <span><i class="fas fa-tag"></i> Your Savings</span>
                    <span>-₹<?php echo number_format($totalSavings, 2); ?></span>
                  </li>
                  <?php endif; ?>
                  
                  <li class="order-summary-item d-flex justify-content-between">
                    <span>Delivery Charges</span>
                    <span>
                      <?php if($shipping > 0): ?>
                        ₹<?php echo number_format($shipping, 2); ?>
                      <?php else: ?>
                        <span class="text-success">FREE</span>
                      <?php endif; ?>
                    </span>
                  </li>
                  
                  <li class="order-summary-item d-flex justify-content-between">
                    <span>Tax</span>
                    <span>₹0.00</span>
                  </li>
                  
                  <li class="order-summary-item border-0 d-flex justify-content-between">
                    <span class="font-weight-bold">Total</span>
                    <strong class="order-summary-total">₹<?php echo number_format($grandTotal, 2); ?></strong>
                  </li>
                </ul>
                
                <?php if($totalSavings > 0): ?>
                <div class="alert alert-success mt-3">
                    <small><i class="fas fa-piggy-bank"></i> You saved ₹<?php echo number_format($totalSavings, 2); ?> on this order with bulk discounts!</small>
                </div>
                <?php endif; ?>
                
                <?php if($shipping == 0 && $subTotal >= 500): ?>
                <div class="alert alert-info mt-3">
                    <small><i class="fas fa-truck"></i> Congratulations! You qualify for FREE delivery.</small>
                </div>
                <?php endif; ?>
                
                <?php if (!$isMinimumMet && $subTotal > 0): ?>
                <div class="alert alert-warning mt-3">
                    <small><i class="fas fa-info-circle"></i> Minimum order amount is <i class="fas fa-rupee-sign"></i><?php echo $minimumOrderAmount; ?>. Add <i class="fas fa-rupee-sign"></i><?php echo ($minimumOrderAmount - $subTotal); ?> more to proceed.</small>
                </div>
                <?php elseif ($isMinimumMet): ?>
                <div class="alert alert-success mt-3">
                    <small><i class="fas fa-check-circle"></i> Minimum order requirement met!</small>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

        </div>
    </div>

