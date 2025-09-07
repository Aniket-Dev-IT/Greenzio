<div class="row">
    <div class="col-xl-8 offset-xl-2 text-center mb-5">
        <p class="lead text-muted">Choose your delivery method.</p>
    </div>
</div>


<section>
    <div class="container">
        <div class="row mb-5">   <!-- Closed at the end -->
       
        <div class="col-lg-8">

        <ul class="custom-nav nav nav-pills mb-5">
              <li class="nav-item w-25"><a href="<?php echo base_url('shopping/getAddress'); ?>" class="nav-link text-sm">Address</a></li>
              <li class="nav-item w-25"><a href="#" class="nav-link text-sm active">Delivery Method</a></li>
              <li class="nav-item w-25"><a href="#" class="nav-link text-sm disabled">Payment Method </a></li>
              <li class="nav-item w-25"><a href="#" class="nav-link text-sm disabled">Order Review</a></li>
            </ul>

            <form action="<?php echo base_url('shopping/deliveryMethodInput'); ?>" method="POST">
            <div class="block my-5">
              <div class="block-body">
                <div class="row">
                  <div class="form-group col-md-12 d-flex align-items-center">
                    <input type="radio" name="shipping" id="normalDelivery" value="normal" required>
                    <label for="option0" class="ml-3"><strong class="d-block text-uppercase mb-2">Normal Delivery</strong><span class="text-muted text-sm">Get it in 7 - 10 Business Days.</span></label>
                  </div>
                  <div class="form-group col-md-12 d-flex align-items-center">
                    <input type="radio" name="shipping" id="expressDelivery" value="express" required>
                    <label for="option2" class="ml-3"><strong class="d-block text-uppercase mb-2">Express Delivery</strong><span class="text-muted text-sm">Get it in 2 - 3 Business Days. Extra Charges Applicable.</span></label>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Delivery Time Slot Selection -->
            <div class="block my-5">
              <div class="block-header">
                <h6 class="text-uppercase mb-0">Select Delivery Time Slot</h6>
              </div>
              <div class="block-body">
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="delivery_date" class="form-label">Preferred Delivery Date <span class="text-danger">*</span></label>
                    <input type="date" name="delivery_date" id="delivery_date" class="form-control" 
                           min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" 
                           max="<?php echo date('Y-m-d', strtotime('+15 days')); ?>" required>
                    <small class="text-muted">Next day delivery available for orders placed before 6 PM</small>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="delivery_time_slot" class="form-label">Preferred Time Slot <span class="text-danger">*</span></label>
                    <select name="delivery_time_slot" id="delivery_time_slot" class="form-control" required>
                      <option value="">Select Time Slot</option>
                      <option value="morning" data-recommended="true">Morning (9:00 AM - 12:00 PM) - Recommended for fresh items</option>
                      <option value="afternoon">Afternoon (12:00 PM - 4:00 PM)</option>
                      <option value="evening">Evening (4:00 PM - 8:00 PM)</option>
                      <option value="any_time">Any Time (9:00 AM - 8:00 PM)</option>
                    </select>
                    <small class="text-info d-none" id="perishable_note">
                      <i class="fas fa-leaf"></i> Morning delivery recommended for perishable items
                    </small>
                  </div>
                </div>
                
                <!-- Special Handling Instructions -->
                <div class="row">
                  <div class="form-group col-md-12">
                    <label class="form-label">Special Handling Requirements</label>
                    <div class="form-check-list">
                      <div class="form-check">
                        <input type="checkbox" name="handle_fragile" id="handle_fragile" class="form-check-input" value="1">
                        <label for="handle_fragile" class="form-check-label">
                          <i class="fas fa-exclamation-triangle text-warning"></i> 
                          Handle fragile items with extra care (eggs, bread, chips, etc.)
                        </label>
                      </div>
                      <div class="form-check">
                        <input type="checkbox" name="keep_cold" id="keep_cold" class="form-check-input" value="1">
                        <label for="keep_cold" class="form-check-label">
                          <i class="fas fa-thermometer-quarter text-info"></i> 
                          Keep perishable items cold during delivery
                        </label>
                      </div>
                      <div class="form-check">
                        <input type="checkbox" name="contact_before" id="contact_before" class="form-check-input" value="1">
                        <label for="contact_before" class="form-check-label">
                          <i class="fas fa-phone text-success"></i> 
                          Contact me before delivery
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="form-group col-md-12">
                    <label for="delivery_instructions" class="form-label">Additional Delivery Instructions (Optional)</label>
                    <textarea name="delivery_instructions" id="delivery_instructions" class="form-control" rows="3" 
                              placeholder="e.g., Leave at door, Ring bell twice, Gate code: 1234, etc."></textarea>
                    <small class="text-muted">Help our delivery team find you and deliver your order safely</small>
                  </div>
                </div>
                
                <!-- Delivery Guarantees -->
                <div class="alert alert-info mt-3">
                  <h6><i class="fas fa-shield-alt"></i> Our Delivery Promise</h6>
                  <ul class="mb-0 pl-3">
                    <li>Fresh guarantee - We ensure quality at delivery</li>
                    <li>Temperature controlled transport for perishables</li>
                    <li>Careful handling for fragile items</li>
                    <li>Real-time delivery tracking</li>
                    <li>Contact-free delivery available</li>
                  </ul>
                </div>
              </div>
            </div>
 
        <div class=" mb-5 d-flex justify-content-between flex-column flex-lg-row">
                  <a href="<?php echo base_url('shopping/getAddress');  ?>" class="btn btn-link text-muted text-capitalize">
                      <i class="fas fa-arrow-left"></i>&nbsp;Back to the address
                    </a>
                      <!-- <a href="<?php echo base_url('shopping/paymentMethod');  ?>" class="btn btn-outline-dark text-capitalize">Choose payment method&nbsp;<i class="fas fa-arrow-right"></i></a> -->
                      <button type="submit" class="btn btn-outline-dark">Choose payment method&nbsp;<i class="fas fa-arrow-right"></i name="paymentButton"></button>  
                    
                    </form>
        </div>


        </div>
        
<script>
// Auto-suggest special handling based on potential cart contents
document.addEventListener('DOMContentLoaded', function() {
    // Get preferred delivery time from cart page (if set)
    const savedDeliveryTime = sessionStorage.getItem('preferred_delivery_time');
    if (savedDeliveryTime) {
        const timeSlotSelect = document.getElementById('delivery_time_slot');
        timeSlotSelect.value = savedDeliveryTime;
        
        // Show perishable note if morning is selected
        if (savedDeliveryTime === 'morning') {
            document.getElementById('perishable_note').classList.remove('d-none');
        }
    }
    
    // Auto-check special handling if common keywords are detected in localStorage
    const cartData = sessionStorage.getItem('cart_requirements');
    if (cartData) {
        const requirements = JSON.parse(cartData);
        if (requirements.hasFragileItems) {
            document.getElementById('handle_fragile').checked = true;
        }
        if (requirements.hasPerishableItems) {
            document.getElementById('keep_cold').checked = true;
        }
    }
    
    // Show/hide perishable note based on time slot selection
    document.getElementById('delivery_time_slot').addEventListener('change', function() {
        const perishableNote = document.getElementById('perishable_note');
        if (this.value === 'morning') {
            perishableNote.classList.remove('d-none');
        } else {
            perishableNote.classList.add('d-none');
        }
    });
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const deliveryDate = document.getElementById('delivery_date').value;
        const timeSlot = document.getElementById('delivery_time_slot').value;
        const shippingMethod = document.querySelector('input[name="shipping"]:checked');
        
        if (!shippingMethod) {
            e.preventDefault();
            alert('Please select a delivery method.');
            return;
        }
        
        if (!deliveryDate) {
            e.preventDefault();
            alert('Please select a delivery date.');
            return;
        }
        
        if (!timeSlot) {
            e.preventDefault();
            alert('Please select a delivery time slot.');
            return;
        }
        
        // Show confirmation for special handling
        const specialHandling = [];
        if (document.getElementById('handle_fragile').checked) {
            specialHandling.push('Handle fragile items with care');
        }
        if (document.getElementById('keep_cold').checked) {
            specialHandling.push('Keep perishable items cold');
        }
        if (document.getElementById('contact_before').checked) {
            specialHandling.push('Contact before delivery');
        }
        
        if (specialHandling.length > 0) {
            const message = 'Special handling requested:\n- ' + specialHandling.join('\n- ');
            if (!confirm(message + '\n\nProceed with these requirements?')) {
                e.preventDefault();
                return;
            }
        }
    });
});
</script>
