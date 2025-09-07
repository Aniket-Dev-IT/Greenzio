/**
 * GREENZIO - PRODUCT CART HANDLER
 * Enhanced add to cart functionality for product pages
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        // Handle add to cart form submission
        const addToCartForm = document.getElementById('addToCartForm');
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(addToCartForm);
                const productId = formData.get('pid');
                const quantity = formData.get('quantity') || 1;
                
                // Disable the submit button to prevent double clicks
                const submitBtn = document.getElementById('addToCart');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Adding...';
                
                // Call the global add to cart function
                if (typeof window.addProductToCart === 'function') {
                    window.addProductToCart(productId, quantity)
                        .finally(() => {
                            // Re-enable the button
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        });
                } else {
                    // Fallback if global function is not available
                    fetch(window.location.origin + '/Greenzio/shopping/addToCart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `product_id=${productId}&quantity=${quantity}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message, 'success');
                            } else {
                                alert(data.message);
                            }
                            // Update cart count if function exists
                            if (typeof window.refreshCartCount === 'function') {
                                window.refreshCartCount();
                            }
                        } else {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message, 'error');
                            } else {
                                alert(data.message);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Add to cart error:', error);
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('An error occurred while adding to cart. Please try again.', 'error');
                        } else {
                            alert('An error occurred while adding to cart. Please try again.');
                        }
                    })
                    .finally(() => {
                        // Re-enable the button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
                }
            });
        }

        // Handle quantity increase/decrease buttons
        const increaseBtn = document.getElementById('increaseQty');
        const decreaseBtn = document.getElementById('decreaseQty');
        const quantityInput = document.getElementById('quantity');

        if (increaseBtn && quantityInput) {
            increaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value) || 1;
                const maxValue = parseInt(quantityInput.getAttribute('max')) || 999;
                if (currentValue < maxValue) {
                    quantityInput.value = currentValue + 1;
                }
            });
        }

        if (decreaseBtn && quantityInput) {
            decreaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value) || 1;
                const minValue = parseInt(quantityInput.getAttribute('min')) || 1;
                if (currentValue > minValue) {
                    quantityInput.value = currentValue - 1;
                }
            });
        }

        // Handle pincode check
        const pincodeInput = document.getElementById('pincode');
        if (pincodeInput) {
            pincodeInput.addEventListener('input', function() {
                const pincode = this.value;
                if (pincode.length === 6) {
                    fetch(window.location.origin + '/Greenzio/shopping/checkPinCode', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `pincode=${pincode}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        const codContainer = document.getElementById('cod');
                        if (codContainer) {
                            codContainer.innerHTML = data.text || '';
                        }
                    })
                    .catch(error => {
                        console.error('Pincode check error:', error);
                    });
                } else {
                    const codContainer = document.getElementById('cod');
                    if (codContainer) {
                        codContainer.innerHTML = '';
                    }
                }
            });
        }
    });

})();
