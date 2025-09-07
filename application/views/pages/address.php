<?php
$email = $this->session->userdata('email');

// Initialize default values
$name = '';
$street = '';
$city = '';
$zip = '';
$state = '';
$mobile = '';

// If billing details exist, use them
if (!empty($billDetails)) {
    foreach ($billDetails as $details) {
        $name = $details['name'] ?? '';
        $street = $details['street'] ?? '';
        $city = $details['city'] ?? '';
        $zip = $details['zip'] ?? '';
        $state = $details['state'] ?? '';
        $mobile = $details['mobile'] ?? '';
        break; // Only use first record
    }
}
?>

<div class="row">
  <div class="col-xl-8 offset-xl-2 text-center mb-5">
    <p class="lead text-muted">Please fill in your address.</p>
  </div>
</div>

<section>
  <div class="container">
    <div class="row mb-5">
      <div class="col-lg-8">
        <ul class="custom-nav nav nav-pills mb-5">
          <li class="nav-item w-25"><a href="#" class="nav-link text-sm active">Address</a></li>
          <li class="nav-item w-25"><a href="#" class="nav-link text-sm disabled">Delivery Method</a></li>
          <li class="nav-item w-25"><a href="#" class="nav-link text-sm disabled">Payment Method </a></li>
          <li class="nav-item w-25"><a href="#" class="nav-link text-sm disabled">Order Review</a></li>
        </ul>
        
        <form action="<?php echo base_url('shopping/getAddressInput'); ?>" method="POST">
          <div class="block">
            <!-- Invoice Address-->
            <div class="block-header">
              <h6 class="text-uppercase mb-0">Invoice address</h6>
            </div>
            <div class="block-body">
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="fullname_invoice" class="form-label">Full Name</label>
                  <input type="text" name="fullname_invoice" placeholder="Full Name" id="fullname_invoice" class="form-control" required 
                  value="<?php echo htmlspecialchars($name); ?>" >
                </div>
                <div class="form-group col-md-6">
                  <label for="emailaddress_invoice" class="form-label">Email Address</label>
                  <input type="email" name="email_invoice" placeholder="abc@gmail.com" id="email_invoice" class="form-control" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>
                <div class="form-group col-md-6">
                  <label for="street_invoice" class="form-label">Street</label>
                  <input type="text" name="street_invoice" placeholder="123 Main St." id="street_invoice" class="form-control" required
                  value="<?php echo htmlspecialchars($street); ?>" >
                </div>
                <div class="form-group col-md-6">
                  <label for="city_invoice" class="form-label">City</label>
                  <input type="text" name="city_invoice" placeholder="City" id="city_invoice" class="form-control" required
                  value="<?php echo htmlspecialchars($city); ?>">
                </div>
                <div class="form-group col-md-6">
                  <label for="zip_invoice" class="form-label">ZIP</label>
                  <input type="number" name="zip_invoice" placeholder="Postal code" id="zip_invoice" class="form-control" required minlength="6" maxlength="6"  value="<?php echo htmlspecialchars($zip); ?>">
                </div>
                <div class="form-group col-md-6">
                  <label for="state_invoice" class="form-label">State</label>
                  <input type="text" name="state_invoice" placeholder="State" id="state_invoice" class="form-control" required
                  value="<?php echo htmlspecialchars($state); ?>">
                </div>
                <div class="form-group col-md-6">
                  <label for="phone_invoice" class="form-label">Phone Number</label>
                  <input type="number" name="phone_invoice" placeholder="Phone Number" id="phone_invoice" class="form-control" required minlength="10" maxlength="12"  value="<?php echo htmlspecialchars($mobile); ?>">
                </div>
                <div class="form-group col-md-6">
                  <label for="alt_phone" class="form-label">Alternate Phone Number (Optional)</label>
                  <input type="number" name="alt_phone" placeholder="Alternate Phone Number" id="alt_phone" class="form-control" minlength="10" maxlength="12">
                </div>
                <div class="form-group col-md-12">
                  <label for="landmark" class="form-label">Landmark (Optional)</label>
                  <input type="text" name="landmark" placeholder="e.g., Near XYZ Mall, Behind ABC Hospital" id="landmark" class="form-control">
                </div>
                <div class="form-group col-md-12">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="home_delivery_instructions" name="home_delivery_instructions">
                    <label class="custom-control-label" for="home_delivery_instructions">
                      I will be available to receive the delivery at the specified address
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <!-- /Invoice Address-->
          </div>
          
          <div class=" mb-5 d-flex justify-content-between flex-column flex-lg-row">
            <a href="<?php echo base_url('shopping/cart');  ?>" class="btn btn-link text-muted">
              <i class="fas fa-arrow-left"></i>&nbsp;Back
            </a>
            <button type="submit" class="btn btn-outline-dark">Choose delivery method&nbsp;<i class="fas fa-arrow-right"></i></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
              <!-- /Invoice Address-->
            </div>
          </div>
          <div class=" mb-5 d-flex justify-content-between flex-column flex-lg-row">
            <a href="<?php echo base_url('shopping/cart');  ?>" class="btn btn-link text-muted">
              <i class="fas fa-arrow-left"></i>&nbsp;Back
            </a>
            <!-- <a href="<?php echo base_url('shopping/deliveryMethod');  ?>" class="btn btn-outline-dark">Choose delivery method&nbsp;<i class="fas fa-arrow-right"></i></a> -->
            <button type="submit" class="btn btn-outline-dark">Choose delivery method&nbsp;<i class="fas fa-arrow-right"></i name="deliveryButton"></button>
          </div>
        </form>

      </div>