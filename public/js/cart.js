document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const url = form.action;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showToast(data.message || 'Product added to cart!', 'success');
                    
                    // Update cart count if available
                    if (data.cart_count !== undefined) {
                        const cartCountElement = document.querySelector('.cart-count');
                        if (cartCountElement) {
                            cartCountElement.textContent = data.cart_count;
                        }
                    }
                    
                    // Reset quantity input if exists
                    const quantityInput = form.querySelector('input[name="quantity"]');
                    if (quantityInput) {
                        quantityInput.value = 1;
                    }
                    
                    // Reset options if any
                    const optionSelects = form.querySelectorAll('select[name^="options"]');
                    optionSelects.forEach(select => {
                        select.selectedIndex = 0;
                    });
                } else {
                    showToast(data.message || 'Error adding to cart', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error adding to cart', 'danger');
            });
        });
    });
    
    // Update quantity buttons
    document.querySelectorAll('.quantity-update').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const input = this.closest('.quantity-controls').querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            const maxStock = parseInt(input.getAttribute('max')) || 999;
            
            if (this.classList.contains('quantity-increment')) {
                if (currentValue < maxStock) {
                    input.value = currentValue + 1;
                } else {
                    alert('Maximum available quantity reached');
                }
            } else if (this.classList.contains('quantity-decrement') && currentValue > 1) {
                input.value = currentValue - 1;
            }
        });
    });

    // Update cart item quantity
    document.querySelectorAll('.update-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const url = form.action;
            const quantity = form.querySelector('.quantity-input').value;
            const itemId = form.dataset.itemId;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the subtotal and total
                    const subtotalElement = document.querySelector(`.subtotal-${itemId}`);
                    const totalElement = document.querySelector('.cart-total');
                    
                    if (subtotalElement) subtotalElement.textContent = data.subtotal;
                    if (totalElement) totalElement.textContent = data.total;
                    
                    // Show success message
                    showToast('Cart updated successfully!', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error updating cart. Please try again.', 'error');
            });
        });
    });

    // Remove item from cart
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                const url = this.href;
                const itemId = this.dataset.itemId;
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'DELETE'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the item row from the table
                        const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
                        if (row) row.remove();
                        
                        // Update the total
                        const totalElement = document.querySelector('.cart-total');
                        if (totalElement) totalElement.textContent = data.total;
                        
                        // Show success message
                        showToast('Item removed from cart', 'success');
                        
                        // If cart is empty, show empty cart message
                        if (document.querySelectorAll('tbody tr').length === 0) {
                            window.location.reload();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error removing item. Please try again.', 'error');
                });
            }
        });
    });

    // Clear cart
    const clearCartBtn = document.querySelector('.clear-cart');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to clear your cart?')) {
                const url = this.href;
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'DELETE'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error clearing cart. Please try again.', 'error');
                });
            }
        });
    }
});

// Helper function to show toast messages
function showToast(message, type = 'success') {
    // You can replace this with your preferred toast/notification library
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast after it's hidden
    toast.addEventListener('hidden.bs.toast', function() {
        document.body.removeChild(toast);
    });
}
