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
        #paymentMethodsTable.table {
            border: none !important;
        }
        #paymentMethodsTable thead th {
            background-color: #f9fafb;
            color: #6c757d;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            border-top: none;
            border-bottom: 2px solid #e5e7eb;
            padding: 1rem;
        }
        #paymentMethodsTable tbody td {
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem;
        }
        #paymentMethodsTable tbody tr:last-child td {
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
        
        /* --- Charge display --- */
        .charge-amount {
            font-weight: 600;
            color: #1a1a1a;
        }
        .code-badge {
            background-color: #f3f4f6;
            color: #4b5563;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.85rem;
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
    </style>
</head>

<body>
<div class="main-wrapper">
<?php $this->load->view('partials/menu'); ?>

<div class="page-wrapper">
<div class="content container-fluid">

    <div class="page-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Payment Methods</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
            <i class="fas fa-plus me-1"></i> Add Payment Method
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="paymentMethodsTable" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Payment Name</th>
                    <th>Code</th>
                    <th width="120" class="text-center">Extra Charge</th>
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

<!-- Add Payment Method Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<form id="addPaymentForm">
<div class="modal-header">
    <h5 class="modal-title">Add New Payment Method</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <label class="form-label small text-muted">Payment Name *</label>
        <input type="text" class="form-control" name="name" placeholder="e.g. Credit Card, PayPal" required>
    </div>
    <div class="mb-3">
        <label class="form-label small text-muted">Code *</label>
        <input type="text" class="form-control" name="code" placeholder="e.g. credit_card, paypal" required>
        <small class="text-muted">Unique identifier (no spaces, use underscores)</small>
    </div>
    <div class="mb-3">
        <label class="form-label small text-muted">Extra Charge (₹)</label>
        <input type="number" class="form-control" name="extra_charge" placeholder="0.00" step="0.01" min="0" value="0">
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="status" id="add_payment_status" checked>
        <label class="form-check-label" for="add_payment_status">Active</label>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary">Save Payment Method</button>
</div>
</form>
</div>
</div>
</div>

<!-- Edit Payment Method Modal -->
<div class="modal fade" id="editPaymentModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<form id="editPaymentForm">
<input type="hidden" id="edit_payment_id" name="id">
<div class="modal-header">
    <h5 class="modal-title">Edit Payment Method</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <label class="form-label small text-muted">Payment Name *</label>
        <input type="text" class="form-control" id="edit_payment_name" name="name" required>
    </div>
    <div class="mb-3">
        <label class="form-label small text-muted">Code *</label>
        <input type="text" class="form-control" id="edit_payment_code" name="code" required>
    </div>
    <div class="mb-3">
        <label class="form-label small text-muted">Extra Charge (₹)</label>
        <input type="number" class="form-control" id="edit_payment_charge" name="extra_charge" step="0.01" min="0">
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="edit_payment_status" name="status">
        <label class="form-check-label" for="edit_payment_status">Active</label>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary">Update Payment Method</button>
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

// Initialize DataTable for Payment Methods
let paymentTable = $('#paymentMethodsTable').DataTable({
    processing: true,
    serverSide: false,
    ajax: {
        url: '<?= site_url("home/get_payment_methods_data"); ?>',
        type: 'POST',
        data: function(d) {
            d[csrfName] = csrfHash;
        },
        dataSrc: function(json) {
            console.log('Server Response:', json); // Debug log
            
            // Handle CSRF token
            if(json && json.csrf_token) {
                csrfHash = json.csrf_token;
            }
            
            // Check response structure and return data
            if(json && json.payment_methods) {
                return json.payment_methods;
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
            
            // Check if it's a JSON parse error
            if(xhr.responseText && !xhr.responseText.startsWith('{')) {
                console.error('Response is not JSON. Response starts with:', xhr.responseText.substring(0, 100));
            }
            
            // Show user-friendly error
            Swal.fire({
                icon: 'error',
                title: 'Data Load Error',
                html: 'Failed to load payment methods data.<br><br>' +
                      'Status: ' + xhr.status + ' ' + xhr.statusText + '<br>' +
                      'Please check the browser console for more details.',
                timer: 5000
            });
        }
    },
    columns: [
        { 
            data: 'id',
            className: 'text-center'
        },
        { 
            data: 'name'
        },
        { 
            data: 'code',
            render: function(data) {
                return '<span class="code-badge">' + data + '</span>';
            }
        },
        { 
            data: 'extra_charge',
            className: 'text-center',
            render: function(data) {
                return data > 0 ? 
                    '<span class="charge-amount text-danger">+₹' + parseFloat(data).toFixed(2) + '</span>' :
                    '<span class="charge-amount text-success">₹' + parseFloat(data).toFixed(2) + '</span>';
            }
        },
        { 
            data: 'status',
            className: 'text-center',
            render: function(data, type, row) {
                if(data == 1) {
                    return '<span class="status-badge status-active toggle-payment-status" data-id="' + row.id + '">Active</span>';
                } else {
                    return '<span class="status-badge status-inactive toggle-payment-status" data-id="' + row.id + '">Inactive</span>';
                }
            }
        },
        { 
            data: null,
            className: 'text-center',
            render: function(data) {
                return `
                <div class="action-cell justify-content-center">
                    <button class="btn btn-sm btn-info btn-action edit-payment" 
                        data-id="${data.id}"
                        data-name="${data.name}"
                        data-code="${data.code}"
                        data-charge="${data.extra_charge}"
                        data-status="${data.status}">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-action delete-payment" 
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
        searchPlaceholder: "Search payment methods...",
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "Showing 0 to 0 of 0 entries",
        infoFiltered: "(filtered from _MAX_ total entries)",
        emptyTable: "No payment methods found",
        zeroRecords: "No matching payment methods found",
        paginate: {
            next: '<i class="fas fa-chevron-right"></i>',
            previous: '<i class="fas fa-chevron-left"></i>'
        }
    },
    drawCallback: function(settings) {
        // Update CSRF token after each draw if available
        if(settings.json && settings.json.csrf_token) {
            csrfHash = settings.json.csrf_token;
        }
    }
});

// Add Payment Method
$('#addPaymentForm').submit(function(e) {
    e.preventDefault();
    
    $.post('<?= site_url("home/add_payment_method"); ?>',
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
                $('#addPaymentModal').modal('hide');
                $('#addPaymentForm')[0].reset();
                paymentTable.ajax.reload(null, false); // false = keep current page
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
                text: 'Failed to save payment method. Status: ' + xhr.status
            });
        });
});

// Open Edit Modal
$(document).on('click', '.edit-payment', function() {
    $('#edit_payment_id').val($(this).data('id'));
    $('#edit_payment_name').val($(this).data('name'));
    $('#edit_payment_code').val($(this).data('code'));
    $('#edit_payment_charge').val($(this).data('charge'));
    $('#edit_payment_status').prop('checked', $(this).data('status') == 1);
    $('#editPaymentModal').modal('show');
});

// Edit Payment Method
$('#editPaymentForm').submit(function(e) {
    e.preventDefault();
    let id = $('#edit_payment_id').val();
    
    $.post('<?= site_url("home/edit_payment_method/"); ?>' + id,
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
                $('#editPaymentModal').modal('hide');
                paymentTable.ajax.reload(null, false);
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
                text: 'Failed to update payment method. Status: ' + xhr.status
            });
        });
});

// Toggle Payment Status
$(document).on('click', '.toggle-payment-status', function() {
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
            $.post('<?= site_url("home/toggle_payment_status/"); ?>' + id,
                {
                    status: newStatus,
                    [csrfName]: csrfHash
                },
                function(r) {
                    if(r.csrf_token) {
                        csrfHash = r.csrf_token;
                    }
                    
                    if(r.success) {
                        paymentTable.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated!',
                            text: r.message || 'Payment method status has been updated.',
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

// Delete Payment Method
$(document).on('click', '.delete-payment', function() {
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
            $.post('<?= site_url("home/delete_payment_method/"); ?>' + id,
                {[csrfName]: csrfHash},
                function(r) {
                    if(r.csrf_token) {
                        csrfHash = r.csrf_token;
                    }
                    
                    if(r.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: r.message || 'Payment method has been deleted.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        paymentTable.ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: r.message || 'Failed to delete payment method.'
                        });
                    }
                }, 'json').fail(function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Request Failed',
                        text: 'Failed to delete payment method. Status: ' + xhr.status
                    });
                });
        }
    });
});

// Reload table on modal close
$('#addPaymentModal, #editPaymentModal').on('hidden.bs.modal', function() {
    $(this).find('form')[0].reset();
});

// Initial debug check
$(document).ready(function() {
    console.log('CSRF Token Name:', csrfName);
    console.log('CSRF Token Hash:', csrfHash);
    console.log('AJAX URL:', '<?= site_url("home/get_payment_methods_data"); ?>');
});
</script>
</body>
</html>