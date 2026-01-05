<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('partials/title-meta'); ?>
    <?php $this->load->view('partials/head-css'); ?>
    <style>
        /* --- General UI Polish --- */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #344767;
        }
        
        /* --- Card Styling --- */
        .card {
            border: none;
            box-shadow: 0 0.5rem 1.25rem rgba(18, 20, 23, 0.05);
            border-radius: 12px;
        }

        /* --- Page Header --- */
        .page-header {
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 1.5rem !important;
        }
        .page-header h4 {
            font-weight: 700;
            color: #1a1a1a;
        }
        .page-header .btn-primary {
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
        }
        .page-header .btn-primary:hover {
            opacity: 0.9;
        }

        /* --- Table Styling --- */
        #shippingMethodsTable.table {
            border: none !important;
        }
        #shippingMethodsTable thead th {
            background-color: #f9fafb;
            color: #6c757d;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            border-top: none;
            border-bottom: 2px solid #e5e7eb;
            padding: 1rem;
        }
        #shippingMethodsTable tbody td {
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem;
        }
        #shippingMethodsTable tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* DataTable Filter and Pagination */
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.375rem 0.75rem;
            box-shadow: none;
        }
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 15px;
        }
        .dataTables_wrapper .pagination .page-item .page-link {
            border-radius: 8px;
            margin: 0 3px;
            color: #495057;
        }
        .dataTables_wrapper .pagination .page-item.active .page-link {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        /* --- Custom Badge Styling --- */
        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.7rem;
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .status-badge:hover {
            opacity: 0.8;
        }
        .status-active {
            color: #057a55;
            background-color: #def7ec;
            border: 1px solid #10b981;
        }
        .status-inactive {
            color: #6c757d;
            background-color: #e9ecef;
            border: 1px solid #adb5bd;
        }
        
        /* --- Action Buttons --- */
        .btn-action {
            font-size: 0.85rem;
            padding: 0.3rem 0.6rem;
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            margin: 0 2px;
        }
        .btn-info {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .btn-info:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        .btn-danger {
            background-color: #ef4444;
            border-color: #ef4444;
        }
        .btn-danger:hover {
            background-color: #dc2626;
            border-color: #dc2626;
        }
        .action-cell {
            white-space: nowrap;
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        /* --- Modals --- */
        .modal-content {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,.1);
        }
        .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 1.5rem;
        }
        .modal-header h5 {
            font-weight: 600;
        }
        .modal-footer {
            border-top: 1px solid #f1f5f9;
            background-color: #f9fafb;
            border-radius: 0 0 12px 12px;
        }
        .modal-body .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
        .modal-body .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
        }
        .modal-body .form-select {
            border-radius: 8px;
            padding: 0.75rem 2.25rem 0.75rem 1rem;
        }
        
        /* --- Charge display --- */
        .charge-amount {
            font-weight: 600;
            color: #1a1a1a;
        }
        .min-order {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .delivery-time {
            color: #4b5563;
            font-weight: 500;
        }
        
        /* --- Loading Spinner --- */
        .dataTables_processing {
            background: rgba(255, 255, 255, 0.9) !important;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 20px !important;
            font-weight: 600;
            color: #4f46e5;
        }
        
        /* Conditional info */
        .conditional-info {
            background-color: #f8f9fa;
            border-left: 3px solid #4f46e5;
            padding: 0.75rem;
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
<div class="main-wrapper">
<?php $this->load->view('partials/menu'); ?>

<div class="page-wrapper">
<div class="content container-fluid">

    <div class="page-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Shipping Methods</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addShippingModal">
            <i class="fas fa-plus me-1"></i> Add Shipping Method
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="shippingMethodsTable" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    
                    <th>Shipping Name</th>
                    <th width="100" class="text-center">Charge</th>
                    <th width="120" class="text-center">Min Order</th>
                    <th width="100" class="text-center">Delivery Time</th>
                    <th width="100" class="text-center">Status</th>
                    <th width="120" class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
</div>

<!-- Add Shipping Method Modal -->
<div class="modal fade" id="addShippingModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<form id="addShippingForm">
<div class="modal-header">
    <h5 class="modal-title">Add New Shipping Method</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <label class="form-label small text-muted">Shipping Name *</label>
        <input type="text" class="form-control" name="name" placeholder="e.g. Standard Shipping, Express" required>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label small text-muted">Delivery Time</label>
            <input type="text" class="form-control" name="delivery_time" placeholder="e.g. 3-5 business days">
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label small text-muted">Shipping Charge (₹)</label>
            <input type="number" class="form-control" name="charge" id="add_shipping_charge" placeholder="0.00" step="0.01" min="0" value="0">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label small text-muted">Minimum Order Amount (₹)</label>
            <input type="number" class="form-control" name="min_order_amount" id="add_min_order" placeholder="0.00" step="0.01" min="0" value="0">
        </div>
    </div>
    
    <div class="form-check mt-3">
        <input type="checkbox" class="form-check-input" name="status" id="add_shipping_status" checked>
        <label class="form-check-label" for="add_shipping_status">Active</label>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary">Save Shipping Method</button>
</div>
</form>
</div>
</div>
</div>

<!-- Edit Shipping Method Modal -->
<div class="modal fade" id="editShippingModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<form id="editShippingForm">
<input type="hidden" id="edit_shipping_id" name="id">
<div class="modal-header">
    <h5 class="modal-title">Edit Shipping Method</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <label class="form-label small text-muted">Shipping Name *</label>
        <input type="text" class="form-control" id="edit_shipping_name" name="name" required>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label small text-muted">Delivery Time</label>
            <input type="text" class="form-control" id="edit_delivery_time" name="delivery_time">
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label small text-muted">Shipping Charge (₹)</label>
            <input type="number" class="form-control" id="edit_shipping_charge" name="charge" step="0.01" min="0">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label small text-muted">Minimum Order Amount (₹)</label>
            <input type="number" class="form-control" id="edit_min_order" name="min_order_amount" step="0.01" min="0">
        </div>
    </div>
    
    <div class="form-check mt-3">
        <input type="checkbox" class="form-check-input" id="edit_shipping_status" name="status">
        <label class="form-check-label" for="edit_shipping_status">Active</label>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary">Update Shipping Method</button>
</div>
</form>
</div>
</div>
</div>

<?php $this->load->view('partials/theme-settings') ?>
<?php $this->load->view('partials/vendor-scripts') ?>

<script>
let csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
let csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

// Initialize DataTable for Shipping Methods
let shippingTable = $('#shippingMethodsTable').DataTable({
    processing: true,
    serverSide: false,
    ajax: {
        url: '<?= site_url("home/get_shipping_methods_data"); ?>',
        type: 'POST',
        data: function(d) {
            d[csrfName] = csrfHash;
        },
        dataSrc: function(json) {
            console.log('Server Response:', json);
            
            if(json && json.csrf_token) {
                csrfHash = json.csrf_token;
            }
            
            if(json && json.shipping_methods) {
                return json.shipping_methods;
            } else if(json && Array.isArray(json)) {
                return json;
            } else if(json && json.data) {
                return json.data;
            } else {
                console.error('Unexpected response format:', json);
                return [];
            }
        },
        error: function(xhr, error, thrown) {
            console.error('AJAX Error Details:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                error: error,
                thrown: thrown
            });
            
            Swal.fire({
                icon: 'error',
                title: 'Data Load Error',
                html: 'Failed to load shipping methods data.<br><br>' +
                      'Status: ' + xhr.status + ' ' + xhr.statusText + '<br>' +
                      'Please check the browser console for more details.',
                timer: 5000
            });
        }
    },
    columns: [
        
        { 
            data: 'name'
        },
        { 
            data: 'charge',
            className: 'text-center',
            render: function(data) {
                return '<span class="charge-amount">₹' + parseFloat(data).toFixed(2) + '</span>';
            }
        },
        { 
            data: 'min_order_amount',
            className: 'text-center',
            render: function(data) {
                return data ? '<span class="min-order">₹' + parseFloat(data).toFixed(2) + '+</span>' : '<span class="text-muted">-</span>';
            }
        },
        { 
            data: 'delivery_time',
            className: 'text-center',
            render: function(data) {
                return data ? '<span class="delivery-time">' + data + '</span>' : '<span class="text-muted">-</span>';
            }
        },
        { 
            data: 'status',
            className: 'text-center',
            render: function(data, type, row) {
                if(data == 1) {
                    return '<span class="status-badge status-active toggle-shipping-status" data-id="' + row.id + '">Active</span>';
                } else {
                    return '<span class="status-badge status-inactive toggle-shipping-status" data-id="' + row.id + '">Inactive</span>';
                }
            }
        },
        { 
            data: null,
            className: 'text-center',
            render: function(data) {
                return `
                <div class="action-cell justify-content-center">
                    <button class="btn btn-sm btn-info btn-action edit-shipping" 
                        data-id="${data.id}"
                        data-name="${data.name}"
                        data-charge="${data.charge}"
                        data-min-order="${data.min_order_amount}"
                        data-delivery-time="${data.delivery_time}"
                        data-status="${data.status}">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-action delete-shipping" 
                        data-id="${data.id}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>`;
            }
        }
    ],
    order: [[0, 'asc']],
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
    language: {
        search: "Search:",
        searchPlaceholder: "Search shipping methods...",
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "Showing 0 to 0 of 0 entries",
        infoFiltered: "(filtered from _MAX_ total entries)",
        emptyTable: "No shipping methods found",
        zeroRecords: "No matching shipping methods found",
        paginate: {
            next: '<i class="fas fa-chevron-right"></i>',
            previous: '<i class="fas fa-chevron-left"></i>'
        }
    },
    drawCallback: function(settings) {
        if(settings.json && settings.json.csrf_token) {
            csrfHash = settings.json.csrf_token;
        }
    }
});

// Add Shipping Method
$('#addShippingForm').submit(function(e) {
    e.preventDefault();
    
    $.post('<?= site_url("home/add_shipping_method"); ?>',
        $(this).serialize() + `&${csrfName}=${csrfHash}`,
        function(r) {
            if(r.csrf_token) {
                csrfHash = r.csrf_token;
            }
            
            if(r.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: r.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                $('#addShippingModal').modal('hide');
                $('#addShippingForm')[0].reset();
                shippingTable.ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: r.message || 'An error occurred. Please try again.'
                });
            }
        }, 'json').fail(function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: 'Failed to save shipping method. Status: ' + xhr.status
            });
        });
});

// Open Edit Modal
$(document).on('click', '.edit-shipping', function() {
    const $this = $(this);
    
    $('#edit_shipping_id').val($this.data('id'));
    $('#edit_shipping_name').val($this.data('name'));
    $('#edit_shipping_charge').val($this.data('charge'));
    $('#edit_min_order').val($this.data('minOrder'));
    $('#edit_delivery_time').val($this.data('deliveryTime'));
    $('#edit_shipping_status').prop('checked', $this.data('status') == 1);
    
    $('#editShippingModal').modal('show');
});

// Edit Shipping Method
$('#editShippingForm').submit(function(e) {
    e.preventDefault();
    let id = $('#edit_shipping_id').val();
    
    $.post('<?= site_url("home/edit_shipping_method/"); ?>' + id,
        $(this).serialize() + `&${csrfName}=${csrfHash}`,
        function(r) {
            if(r.csrf_token) {
                csrfHash = r.csrf_token;
            }
            
            if(r.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: r.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                $('#editShippingModal').modal('hide');
                shippingTable.ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: r.message || 'An error occurred. Please try again.'
                });
            }
        }, 'json').fail(function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: 'Failed to update shipping method. Status: ' + xhr.status
            });
        });
});

// Toggle Shipping Status
$(document).on('click', '.toggle-shipping-status', function() {
    let id = $(this).data('id');
    let currentText = $(this).text();
    let newStatus = currentText === 'Active' ? 0 : 1;
    let statusText = newStatus ? 'Active' : 'Inactive';
    
    Swal.fire({
        title: 'Change Status?',
        text: 'Change status to ' + statusText + '?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, change it!',
        cancelButtonText: 'No, keep it',
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= site_url("home/toggle_shipping_status/"); ?>' + id,
                {
                    status: newStatus,
                    [csrfName]: csrfHash
                },
                function(r) {
                    if(r.csrf_token) {
                        csrfHash = r.csrf_token;
                    }
                    
                    if(r.success) {
                        shippingTable.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated!',
                            text: r.message || 'Shipping method status has been updated.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: r.message || 'Failed to update status.'
                        });
                    }
                }, 'json').fail(function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Request Failed',
                        text: 'Failed to update status. Status: ' + xhr.status
                    });
                });
        }
    });
});

// Delete Shipping Method
$(document).on('click', '.delete-shipping', function() {
    let id = $(this).data('id');
    
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= site_url("home/delete_shipping_method/"); ?>' + id,
                {[csrfName]: csrfHash},
                function(r) {
                    if(r.csrf_token) {
                        csrfHash = r.csrf_token;
                    }
                    
                    if(r.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: r.message || 'Shipping method has been deleted.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        shippingTable.ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: r.message || 'Failed to delete shipping method.'
                        });
                    }
                }, 'json').fail(function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Request Failed',
                        text: 'Failed to delete shipping method. Status: ' + xhr.status
                    });
                });
        }
    });
});

// Reload table on modal close
$('#addShippingModal, #editShippingModal').on('hidden.bs.modal', function() {
    $(this).find('form')[0].reset();
});

// Initial debug check
$(document).ready(function() {
    console.log('Shipping Methods Page Loaded');
    console.log('CSRF Token Name:', csrfName);
    console.log('CSRF Token Hash:', csrfHash);
    console.log('AJAX URL:', '<?= site_url("home/get_shipping_methods_data"); ?>');
});
</script>
</body>
</html>