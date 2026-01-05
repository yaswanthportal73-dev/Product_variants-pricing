<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('partials/title-meta'); ?>
    <?php $this->load->view('partials/head-css'); ?>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

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
        #brandTable.table {
            border: none !important;
        }
        #brandTable thead th {
            background-color: #f9fafb;
            color: #6c757d;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            border-top: none;
            border-bottom: 2px solid #e5e7eb;
            padding: 1rem;
        }
        #brandTable tbody td {
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem;
        }
        #brandTable tbody tr:last-child td {
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

        /* --- Custom Badge Styling for Status Column --- */
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
            transition: all 0.2s ease-in-out;
        }

        /* Style for Active */
        .status-active {
            color: #057a55;
            background-color: #def7ec;
            border: 1px solid #10b981;
        }

        /* Style for Inactive */
        .status-inactive {
            color: #6c757d;
            background-color: #e9ecef;
            border: 1px solid #adb5bd;
        }

        /* --- Action Buttons (Table Cells) --- */
        .btn-action {
            font-size: 0.85rem;
            padding: 0.3rem 0.6rem;
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
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

        .page-header h4 {
            margin-bottom: 0.25rem;
        }
        .page-header small {
            font-size: 0.875rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
<?php $this->load->view('partials/body'); ?>
<div id="global-loader">
    <div class="whirly-loader"></div>
</div>	

<div class="main-wrapper">
<?php $this->load->view('partials/menu'); ?>

<div class="page-wrapper">
<div class="content container-fluid">

    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">Brands Management</h4>
            <small class="text-muted">Efficiently manage all product brand listings and statuses.</small>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-1"></i> Add New Brand
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="brandTable" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    <th width="50">SL.NO</th>
                    <th>Brand Name</th>
                    <th width="120" class="text-center">Status</th>
                    <th width="100" class="text-center">Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
</div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<form id="addForm">
<div class="modal-header">
    <h5 class="modal-title">Create New Brand</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <label class="form-label small text-muted">Brand Name</label>
        <input type="text" name="name" class="form-control" required placeholder="e.g., Apple, Samsung, Nike">
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="status" id="addStatusSwitch" checked>
        <label class="form-check-label" for="addStatusSwitch">Active Status</label>
        <small class="text-muted d-block mt-1">If checked, the brand will be visible immediately.</small>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary">Save Brand</button>
</div>
</form>
</div>
</div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<form id="editForm">
<input type="hidden" id="edit_id">
<div class="modal-header">
    <h5 class="modal-title">Edit Brand Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <label class="form-label small text-muted">Brand Name</label>
        <input type="text" id="edit_name" name="name" class="form-control" required>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="edit_status" name="status">
        <label class="form-check-label" for="edit_status">Active Status</label>
        <small class="text-muted d-block mt-1">Toggle the status to activate or deactivate the brand.</small>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary">Update Brand</button>
</div>
</form>
</div>
</div>
</div>

<?php $this->load->view('partials/vendor-scripts'); ?>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
$(function(){
    let csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    let csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

    let table = $('#brandTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?= base_url("brands/data"); ?>',
            type: 'POST',
            data: d => { d[csrfName] = csrfHash; },
            dataSrc: r => { 
                csrfHash = r.csrf_token; 
                return r.brands; 
            }
        },
        columns: [
            { data: null, className: 'text-center' },
            { data: 'name' },
            {
                data: 'status',
                className: 'text-center',
                render: (d, t, r) => {
                    if(d == 1) {
                        return `<span class="status-badge status-active">Active</span>`;
                    } else {
                        return `<span class="status-badge status-inactive">Inactive</span>`;
                    }
                }
            },
            {
                data: null,
                className: 'text-center',
                render: r => `
                <div class="action-cell justify-content-center">
                    <button class="btn btn-sm btn-info btn-action edit" title="Edit Brand"
                        data-id="${r.id}"
                        data-name="${r.name}"
                        data-status="${r.status}">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-action delete" title="Delete Brand"
                        data-id="${r.id}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>`
            }
        ],
        columnDefs: [
            {
                targets: 0,
                render: (d, t, r, m) => m.row + 1
            },
            { targets: 3, orderable: false, searchable: false }
        ],
        language: {
            search: "Search:",
            searchPlaceholder: "Search brands...",
            paginate: {
                next: '<i class="fas fa-chevron-right"></i>',
                previous: '<i class="fas fa-chevron-left"></i>'
            }
        }
    });

    /* ADD */
    $('#addForm').submit(function(e){
        e.preventDefault();
        $.post('<?= base_url("brands/add"); ?>',
            $(this).serialize() + `&${csrfName}=${csrfHash}`,
            r => {
                csrfHash = r.csrf_token;
                alert(r.message);
                if(r.success){
                    $('#addModal').modal('hide');
                    $('#addForm')[0].reset();
                    table.ajax.reload();
                }
            }, 'json');
    });

    /* EDIT OPEN */
    $(document).on('click', '.edit', function(){
        $('#edit_id').val($(this).data('id'));
        $('#edit_name').val($(this).data('name'));
        $('#editModal .modal-body input[name="status"]').prop('checked', $(this).data('status') == 1);
        $('#editModal').modal('show');
    });

    /* EDIT SAVE */
    $('#editForm').submit(function(e){
        e.preventDefault();
        let id = $('#edit_id').val();
        $.post('<?= base_url("brands/edit/"); ?>' + id,
            $(this).serialize() + `&${csrfName}=${csrfHash}`,
            r => {
                csrfHash = r.csrf_token;
                alert(r.message);
                if(r.success){
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                }
            }, 'json');
    });

    /* DELETE */
    $(document).on('click', '.delete', function(){
        if(confirm('Are you sure you want to delete this brand?')){
            $.post('<?= base_url("brands/delete/"); ?>' + $(this).data('id'),
                {[csrfName]: csrfHash},
                r => {
                    csrfHash = r.csrf_token;
                    alert(r.message);
                    table.ajax.reload();
                }, 'json');
        }
    });
});
</script>

</body>
</html>