@extends('admin.layout')

@section('title', 'Products Management')

@section('content')
<style>
.products-table th,
.products-table td {
    vertical-align: middle;
}

.products-table th {
    background: #554c2a;
    color: #ffe082;
    font-weight: 600;
    border: none;
}

.products-table td {
    background: #222;
    color: #fff;
    border: none;
}

.product-image {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 6px;
    margin-right: 8px;
}

.image-preview {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.search-bar {
    background: #222;
    color: #ffe082;
    border: 2px solid #554c2a;
    border-radius: 2em;
    padding: 0.75em 1.5em;
    width: 100%;
    font-size: 1.1em;
    margin-bottom: 1.5em;
}

.category-filter {
    background: #222;
    color: #ffe082;
    border: 2px solid #554c2a;
    border-radius: 2em;
    padding: 0.75em 1.5em;
    font-size: 1.1em;
    width: 100%;
}

.btn-add-product {
    background: #388e3c;
    color: #fff;
    border-radius: 2em;
    font-weight: 600;
    font-size: 1.1em;
    padding: 0.5em 1.5em;
    margin-bottom: 1.5em;
    float: right;
}

.badge-status {
    font-size: 0.9em;
    padding: 0.4em 1em;
    border-radius: 2em;
    font-weight: 600;
}

.badge-active {
    background: #388e3c;
    color: #fff;
}

.badge-inactive {
    background: #d32f2f;
    color: #fff;
}

.action-btn {
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1em;
    margin-right: 0.3em;
    border: none;
    transition: all 0.2s;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.action-edit {
    background: #fbc02d;
    color: #222;
}

.action-delete {
    background: #b71c1c;
    color: #fff;
}

.action-view {
    background: #1976d2;
    color: #fff;
}

.product-name {
    font-weight: 600;
    color: #ffe082;
    margin-bottom: 0.25rem;
}

.product-category {
    font-size: 0.85em;
    color: #aaa;
}

.product-card-image {
    height: 200px;
    object-fit: cover;
    border-bottom: 1px solid #333;
}

.product-category-badge {
    position: absolute;
    bottom: 10px;
    right: 10px;
}

.card {
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
}

/* Status toggle switch styling */
.form-check-input.status-toggle {
    width: 3em;
    height: 1.5em;
    margin-top: 0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.form-check-input.status-toggle:checked {
    background-color: #198754;
    border-color: #198754;
}

.form-check-input.status-toggle:not(:checked) {
    background-color: #dc3545;
    border-color: #dc3545;
}

.form-check-input.status-toggle:disabled {
    opacity: 0.6;
    cursor: wait;
}

.status-toggle-form,
.featured-toggle-form {
    margin-bottom: 0;
}

/* Featured toggle switch styling */
.form-check-input.featured-toggle {
    width: 3em;
    height: 1.5em;
    margin-top: 0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.form-check-input.featured-toggle:checked {
    background-color: #ffc107;
    border-color: #ffc107;
}

.form-check-input.featured-toggle:not(:checked) {
    background-color: #6c757d;
    border-color: #6c757d;
}

.form-check-input.featured-toggle:disabled {
    opacity: 0.6;
    cursor: wait;
}

/* Loading spinner animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.fa-spinner {
    animation: spin 1s linear infinite;
}

/* Product selection checkbox styling */
.product-select-checkbox {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 10;
}

.product-select-checkbox .form-check-input {
    width: 1.5em;
    height: 1.5em;
    background-color: rgba(0, 0, 0, 0.5);
    border: 2px solid #fff;
    cursor: pointer;
}

.product-select-checkbox .form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

tr.table-primary {
    background-color: rgba(13, 110, 253, 0.15) !important;
}

tr.table-primary td {
    background-color: rgba(13, 110, 253, 0.15) !important;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-add-product">
            <i class="fas fa-plus me-2"></i> Add New Product
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12 mb-3">

    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@include('admin.products.partials.products_table', ['products' => $products])

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
        tooltipTriggerEl));

    // Handle status toggle changes
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.checked) {
                label.textContent = 'Active';
                label.classList.remove('text-danger');
                label.classList.add('text-success');
            } else {
                label.textContent = 'Inactive';
                label.classList.remove('text-success');
                label.classList.add('text-danger');
            }
        });
    });

    // Handle featured toggle changes
    document.querySelectorAll('.featured-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.checked) {
                label.textContent = 'Featured';
                label.classList.remove('text-secondary');
                label.classList.add('text-warning');
            } else {
                label.textContent = 'Not Featured';
                label.classList.remove('text-warning');
                label.classList.add('text-secondary');
            }
        });
    });

    // Handle product selection
    document.querySelectorAll('.product-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const row = this.closest('tr');
            if (this.checked) {
                row.classList.add('table-primary');
            } else {
                row.classList.remove('table-primary');
            }
            updateBulkActionStatus();
        });
    });

    // Handle bulk actions
    // document.querySelectorAll('.bulk-action').forEach(action => {
    //     action.addEventListener('click', function() {
    //         const selectedProducts = getSelectedProducts();
    //         if (selectedProducts.length === 0) {
    //             alert('Please select at least one product to perform this action.');
    //             return;
    //         }

    //         const actionType = this.dataset.action;
    //         let confirmMessage = '';

    //         switch (actionType) {
    //             case 'activate':
    //                 confirmMessage = 'Are you sure you want to activate the selected products?';
    //                 break;
    //             case 'deactivate':
    //                 confirmMessage =
    //                     'Are you sure you want to deactivate the selected products?';
    //                 break;
    //             case 'feature':
    //                 confirmMessage =
    //                     'Are you sure you want to mark the selected products as featured?';
    //                 break;
    //             case 'unfeature':
    //                 confirmMessage =
    //                     'Are you sure you want to remove the selected products from featured?';
    //                 break;
    //             case 'delete':
    //                 confirmMessage =
    //                     'Are you sure you want to delete the selected products? This action cannot be undone.';
    //                 break;
    //         }

    //         if (confirm(confirmMessage)) {
    //             // Create a form to submit the bulk action
    //             const form = document.createElement('form');
    //             form.method = 'POST';
    //             form.action = '{{ route("admin.products.bulk-action") }}';

    //             // Add CSRF token
    //             const csrfToken = document.createElement('input');
    //             csrfToken.type = 'hidden';
    //             csrfToken.name = '_token';
    //             csrfToken.value = '{{ csrf_token() }}';
    //             form.appendChild(csrfToken);

    //             // Add action type
    //             const actionInput = document.createElement('input');
    //             actionInput.type = 'hidden';
    //             actionInput.name = 'action';
    //             actionInput.value = actionType;
    //             form.appendChild(actionInput);

    //             // Add selected product IDs
    //             selectedProducts.forEach(productId => {
    //                 const productInput = document.createElement('input');
    //                 productInput.type = 'hidden';
    //                 productInput.name = 'products[]';
    //                 productInput.value = productId;
    //                 form.appendChild(productInput);
    //             });

    //             // Submit the form
    //             document.body.appendChild(form);
    //             form.submit();
    //         }
    //     });
    // });

    // Function to get selected product IDs
    // function getSelectedProducts() {
    //     const selectedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
    //     return Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
    // }

    // Function to update bulk action status
    // function updateBulkActionStatus() {
    //     const selectedCount = document.querySelectorAll('.product-checkbox:checked').length;
    //     const bulkActionsDropdown = document.getElementById('bulkActionsDropdown');

    //     if (selectedCount > 0) {
    //         bulkActionsDropdown.textContent = `Bulk Actions (${selectedCount})`;
    //         bulkActionsDropdown.classList.add('btn-primary');
    //         bulkActionsDropdown.classList.remove('btn-secondary');
    //     } else {
    //         bulkActionsDropdown.textContent = 'Bulk Actions';
    //         bulkActionsDropdown.classList.add('btn-secondary');
    //         bulkActionsDropdown.classList.remove('btn-primary');
    //     }
    // }

    // Add select all functionality for table view
    const selectAllTableCheckbox = document.getElementById('select-all-table');
    if (selectAllTableCheckbox) {
        selectAllTableCheckbox.addEventListener('change', function() {
            const productCheckboxes = document.querySelectorAll('.product-checkbox');
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                const row = checkbox.closest('tr');
                if (this.checked) {
                    row.classList.add('table-primary');
                } else {
                    row.classList.remove('table-primary');
                }
            });
            updateBulkActionStatus();
        });
    }

    // Handle status toggle switches with AJAX
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const productId = this.dataset.productId;
            const isActive = this.checked ? 1 : 0;
            const label = document.getElementById(`status-label-${productId}`);
            const toggle = this;

            // Show loading state
            toggle.disabled = true;
            label.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

            // Send AJAX request
            fetch(`/admin/products/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    is_active: isActive
                })
            })
            .then(response => response.json())
            .then(data => {
                // Update UI
                toggle.disabled = false;
                if (data.success) {
                    label.textContent = isActive ? 'Active' : 'Inactive';
                    label.className = `form-check-label text-${isActive ? 'success' : 'danger'}`;
                } else {
                    // Revert toggle if there was an error
                    toggle.checked = !toggle.checked;
                    label.textContent = !isActive ? 'Active' : 'Inactive';
                    label.className = `form-check-label text-${!isActive ? 'success' : 'danger'}`;
                    alert('Failed to update product status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toggle.disabled = false;
                toggle.checked = !toggle.checked;
                label.textContent = !isActive ? 'Active' : 'Inactive';
                label.className = `form-check-label text-${!isActive ? 'success' : 'danger'}`;
                alert('An error occurred while updating the product status.');
            });
        });
    });

    // Handle featured toggle switches with AJAX
    document.querySelectorAll('.featured-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const productId = this.dataset.productId;
            const isFeatured = this.checked ? 1 : 0;
            const label = document.getElementById(`featured-label-${productId}`);
            const toggle = this;

            // Show loading state
            toggle.disabled = true;
            label.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

            // Send AJAX request
            fetch(`/admin/products/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    is_featured: isFeatured
                })
            })
            .then(response => response.json())
            .then(data => {
                // Update UI
                toggle.disabled = false;
                if (data.success) {
                    label.textContent = isFeatured ? 'Featured' : 'Not Featured';
                    label.className = `form-check-label text-${isFeatured ? 'warning' : 'secondary'}`;
                } else {
                    // Revert toggle if there was an error
                    toggle.checked = !toggle.checked;
                    label.textContent = !isFeatured ? 'Featured' : 'Not Featured';
                    label.className = `form-check-label text-${!isFeatured ? 'warning' : 'secondary'}`;
                    alert('Failed to update product featured status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toggle.disabled = false;
                toggle.checked = !toggle.checked;
                label.textContent = !isFeatured ? 'Featured' : 'Not Featured';
                label.className = `form-check-label text-${!isFeatured ? 'warning' : 'secondary'}`;
                alert('An error occurred while updating the product featured status.');
            });
        });
    });
});
</script>
@endpush

@endsection
