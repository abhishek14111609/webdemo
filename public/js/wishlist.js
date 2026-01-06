$(document).ready(function() {
    // Add to wishlist functionality
    $(document).on('click', '.add-to-wishlist', function(e) {
        e.preventDefault();
        const button = $(this);
        const productId = button.data('product-id');
        const url = button.data('url') || `/wishlist/add/${productId}`;
        
        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(data) {
                if (data.success) {
                    // Show success message
                    showNotification(data.message, 'success');
                    
                    // Update wishlist count if available
                    if (data.wishlist_count !== undefined) {
                        $('.wishlist-count').text(data.wishlist_count);
                    }
                    
                    // Change button appearance
                    button.addClass('added');
                    const icon = button.find('i');
                    if (icon.length) {
                        icon.removeClass().addClass('fas fa-heart');
                    }
                } else {
                    showNotification(data.message || 'Error adding to wishlist', 'error');
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                let errorMessage = 'Error adding to wishlist';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showNotification(errorMessage, 'error');
            }
        });
    });
    
    // Remove from wishlist functionality
    $(document).on('click', '.remove-from-wishlist', function(e) {
        e.preventDefault();
        
        const form = $(this).closest('.remove-from-wishlist-form');
        const url = form.attr('action');
        const productId = $(this).data('product-id');
        const row = $(this).closest('tr');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(data) {
                // Remove the row from the table
                row.fadeOut(300, function() {
                    $(this).remove();
                    
                    // Update wishlist count in navbar if it exists
                    if ($('.wishlist-count').length) {
                        let count = parseInt($('.wishlist-count').text()) || 0;
                        if (count > 0) {
                            $('.wishlist-count').text(count - 1);
                        }
                    }
                    
                    // Check if wishlist is now empty
                    if ($('#wishlist-table tbody tr').length === 0) {
                        // Reload the page to show empty wishlist message
                        window.location.reload();
                    }
                });
                
                // Show success message
                showNotification(data.message || 'Item removed from wishlist', 'success');
            },
            error: function(xhr) {
                let errorMessage = 'Error removing item from wishlist';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showNotification(errorMessage, 'error');
            }
        });
    });

    
    // Update notes functionality
    $(document).on('submit', '.update-notes-form', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const url = form.attr('action');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'X-HTTP-Method-Override': 'PATCH'
            },
            success: function(data) {
                // Show success message
                showNotification(data.message || 'Notes updated successfully', 'success');
            },
            error: function(xhr) {
                let errorMessage = 'Error updating notes';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showNotification(errorMessage, 'error');
            }
        });
    });
    
    // Move to cart functionality
    $(document).on('click', '.move-to-cart', function(e) {
        e.preventDefault();
        
        const form = $(this).closest('.move-to-cart-form');
        const url = form.attr('action');
        const productId = $(this).data('product-id');
        const row = $(this).closest('tr');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(data) {
                // Remove the row from the table
                row.fadeOut(300, function() {
                    $(this).remove();
                    
                    // Update wishlist count in navbar if it exists
                    if ($('.wishlist-count').length) {
                        let count = parseInt($('.wishlist-count').text()) || 0;
                        if (count > 0) {
                            $('.wishlist-count').text(count - 1);
                        }
                    }
                    
                    // Update cart count in navbar if it exists
                    if ($('.cart-count').length) {
                        let count = parseInt($('.cart-count').text()) || 0;
                        $('.cart-count').text(count + 1);
                    }
                    
                    // Check if wishlist is now empty
                    if ($('#wishlist-table tbody tr').length === 0) {
                        // Reload the page to show empty wishlist message
                        window.location.reload();
                    }
                });
                
                // Show success message
                showNotification(data.message || 'Item moved to cart', 'success');
            },
            error: function(xhr) {
                let errorMessage = 'Error moving item to cart';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showNotification(errorMessage, 'error');
            }
        });
    });

    
    // Helper function to show notifications
    function showNotification(message, type = 'success') {
        // Check if Bootstrap toast is available
        if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
            // Create toast container if it doesn't exist
            if ($('#toast-container').length === 0) {
                $('body').append(`
                    <div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
                        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header">
                                <strong class="me-auto toast-title">Notification</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body"></div>
                        </div>
                    </div>
                `);
            }
            
            // Set toast classes based on type
            const toastEl = document.getElementById('liveToast');
            $(toastEl).removeClass().addClass('toast');
            
            if (type === 'success') {
                $(toastEl).addClass('bg-success text-white');
                $('.toast-title').text('Success');
            } else if (type === 'error') {
                $(toastEl).addClass('bg-danger text-white');
                $('.toast-title').text('Error');
            } else if (type === 'info') {
                $(toastEl).addClass('bg-info text-white');
                $('.toast-title').text('Information');
            }
            
            // Set message and show toast
            $('.toast-body').text(message);
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        } else {
            // Fallback to alert notification if Bootstrap is not available
            let container = $('#notification-container');
            
            // Create container if it doesn't exist
            if (container.length === 0) {
                container = $('<div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>');
                $('body').append(container);
            }
            
            // Create notification element
            const notification = $(`<div class="alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`);
            
            // Add to container
            container.append(notification);
            
            // Auto remove after 3 seconds
            setTimeout(function() {
                notification.removeClass('show');
                setTimeout(function() {
                    notification.remove();
                }, 150);
            }, 3000);
        }
    }
});