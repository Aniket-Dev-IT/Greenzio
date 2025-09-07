<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Check if user is logged in
$userData = $this->session->userdata();
if (!isset($userData['userID'])) {
    redirect('user/login');
}
?>

<section style="margin-top: 120px;" class="mb-5">
    <div class="container">
        <div class="text-center">
            <h1 class="display-4 letter-spacing-5 text-uppercase font-weight-bold">My Profile</h1>
            <p class="lead text-muted">Manage your account information and preferences</p>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <!-- Alert Messages -->
        <div id="alertContainer"></div>

        <div class="row mb-5">
            <div class="col-lg-8 mx-auto">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                    
                        <form id="profileForm">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" 
                                           placeholder="First Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" name="lastName" 
                                           placeholder="Last Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo $userData['email'] ?? ''; ?>" 
                                           placeholder="abc@gmail.com" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="mobile" class="form-label">Mobile Number</label>
                                    <input type="tel" class="form-control" id="mobile" name="mobile" 
                                           value="<?php echo $userData['mobile'] ?? ''; ?>" 
                                           placeholder="Phone Number" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="m" <?php echo ($userData['gender'] ?? '') == 'm' ? 'selected' : ''; ?>>Male</option>
                                        <option value="f" <?php echo ($userData['gender'] ?? '') == 'f' ? 'selected' : ''; ?>>Female</option>
                                        <option value="o">Other</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="dateOfBirth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth">
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Security Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Security Settings</h5>
                    </div>
                    <div class="card-body">
                        
                        <form id="passwordForm">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="currentPassword" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="currentPassword" 
                                           name="currentPassword" placeholder="Current Password" required>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="newPassword" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="newPassword" 
                                           name="newPassword" placeholder="New Password" required>
                                    <small class="text-muted" id="strengthText">Enter a password to see strength</small>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirmPassword" 
                                           name="confirmPassword" placeholder="Confirm Password" required>
                                    <div class="invalid-feedback" id="confirmPasswordFeedback"></div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key mr-2"></i>
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Notification Preferences -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Notification Preferences</h5>
                    </div>
                    <div class="card-body">
                        <form id="notificationForm">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="emailNotifications" 
                                           name="emailNotifications" checked>
                                    <label class="custom-control-label" for="emailNotifications">Email Notifications</label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="smsNotifications" 
                                           name="smsNotifications" checked>
                                    <label class="custom-control-label" for="smsNotifications">SMS Notifications</label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="orderUpdates" 
                                           name="orderUpdates" checked>
                                    <label class="custom-control-label" for="orderUpdates">Order Status Updates</label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="promotionalEmails" 
                                           name="promotionalEmails">
                                    <label class="custom-control-label" for="promotionalEmails">Promotional Emails</label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="weeklyNewsletter" 
                                           name="weeklyNewsletter">
                                    <label class="custom-control-label" for="weeklyNewsletter">Weekly Newsletter</label>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Save Preferences
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="<?php echo base_url('order/orderList'); ?>" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            View Orders
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="<?php echo base_url('user/addresses'); ?>" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Manage Addresses
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="<?php echo base_url('shopping/cart'); ?>" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Go to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load user profile data
    loadProfileData();
    
    // Setup form submissions
    setupFormHandlers();
    
    // Setup password strength checker
    setupPasswordStrength();
    
    // Setup password confirmation checker
    setupPasswordConfirmation();
});

function loadProfileData() {
    // Load user profile data from server
    fetch('<?php echo base_url(); ?>user/getProfileData')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.profile) {
                const profile = data.profile;
                
                // Populate form fields
                document.getElementById('firstName').value = profile.first_name || '';
                document.getElementById('lastName').value = profile.last_name || '';
                document.getElementById('dateOfBirth').value = profile.date_of_birth || '';
                
                // Load notification preferences
                if (data.preferences) {
                    Object.keys(data.preferences).forEach(key => {
                        const checkbox = document.getElementById(key);
                        if (checkbox) {
                            checkbox.checked = data.preferences[key];
                        }
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error loading profile data:', error);
        });
}

function setupFormHandlers() {
    // Profile form submission
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateProfile();
    });
    
    // Password form submission
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        changePassword();
    });
    
    // Notification form submission
    document.getElementById('notificationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateNotificationPreferences();
    });
}

function setupPasswordStrength() {
    const passwordInput = document.getElementById('newPassword');
    const strengthText = document.getElementById('strengthText');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = calculatePasswordStrength(password);
        
        // Update strength text with simple color coding
        strengthText.textContent = strength.message;
        strengthText.className = 'text-' + (strength.level === 'weak' ? 'danger' : 
                                           strength.level === 'fair' ? 'warning' : 
                                           strength.level === 'good' ? 'info' : 'success');
    });
}

function setupPasswordConfirmation() {
    const confirmInput = document.getElementById('confirmPassword');
    const feedback = document.getElementById('confirmPasswordFeedback');
    
    confirmInput.addEventListener('input', function() {
        const password = document.getElementById('newPassword').value;
        const confirm = this.value;
        
        if (confirm.length > 0) {
            if (password === confirm) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                feedback.textContent = '';
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
                feedback.textContent = 'Passwords do not match';
            }
        }
    });
}

function calculatePasswordStrength(password) {
    let score = 0;
    let level = 'weak';
    let message = 'Very weak password';
    
    if (password.length >= 8) score += 20;
    if (password.length >= 12) score += 10;
    if (/[a-z]/.test(password)) score += 20;
    if (/[A-Z]/.test(password)) score += 20;
    if (/[0-9]/.test(password)) score += 20;
    if (/[^A-Za-z0-9]/.test(password)) score += 10;
    
    if (score < 40) {
        level = 'weak';
        message = 'Weak password';
    } else if (score < 60) {
        level = 'fair';
        message = 'Fair password';
    } else if (score < 80) {
        level = 'good';
        message = 'Good password';
    } else {
        level = 'strong';
        message = 'Strong password';
    }
    
    return { score, level, message };
}

function updateProfile() {
    const formData = new FormData(document.getElementById('profileForm'));
    
    fetch('<?php echo base_url(); ?>user/updateProfile', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Profile updated successfully!', 'success');
        } else {
            showAlert(data.message || 'Failed to update profile', 'danger');
        }
    })
    .catch(error => {
        console.error('Error updating profile:', error);
        showAlert('An error occurred while updating your profile', 'danger');
    });
}

function changePassword() {
    const formData = new FormData(document.getElementById('passwordForm'));
    
    fetch('<?php echo base_url(); ?>user/changePassword', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Password changed successfully!', 'success');
            document.getElementById('passwordForm').reset();
        } else {
            showAlert(data.message || 'Failed to change password', 'danger');
        }
    })
    .catch(error => {
        console.error('Error changing password:', error);
        showAlert('An error occurred while changing your password', 'danger');
    });
}

function updateNotificationPreferences() {
    const formData = new FormData(document.getElementById('notificationForm'));
    
    fetch('<?php echo base_url(); ?>user/updateNotificationPreferences', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Notification preferences updated!', 'success');
        } else {
            showAlert(data.message || 'Failed to update preferences', 'danger');
        }
    })
    .catch(error => {
        console.error('Error updating preferences:', error);
        showAlert('An error occurred while updating your preferences', 'danger');
    });
}

function handleImageUpload(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            showAlert('Please select a valid image file', 'danger');
            return;
        }
        
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showAlert('Image size should be less than 5MB', 'danger');
            return;
        }
        
        const formData = new FormData();
        formData.append('profile_image', file);
        
        fetch('<?php echo base_url(); ?>user/uploadProfileImage', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Profile image updated!', 'success');
                // Update avatar display
                if (data.image_url) {
                    document.querySelector('.profile-avatar').style.backgroundImage = `url(${data.image_url})`;
                    document.querySelector('.profile-avatar').style.backgroundSize = 'cover';
                    document.querySelector('.profile-avatar').textContent = '';
                }
            } else {
                showAlert(data.message || 'Failed to upload image', 'danger');
            }
        })
        .catch(error => {
            console.error('Error uploading image:', error);
            showAlert('An error occurred while uploading your image', 'danger');
        });
    }
}

function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;
    
    alertContainer.innerHTML = alertHtml;
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alert = alertContainer.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }
    }, 5000);
}

function confirmAccountDeletion() {
    if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
        if (confirm('This will permanently delete all your data including orders, addresses, and preferences. Are you absolutely sure?')) {
            // Implement account deletion
            showAlert('Account deletion feature will be implemented soon', 'info');
        }
    }
}
</script>
