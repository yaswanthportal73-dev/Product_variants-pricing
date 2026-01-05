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
            background-color: #f8f9fa; /* Lighter background */
            color: #344767;
        }
        
        /* --- Card Styling --- */
        .card {
            border: none;
            box-shadow: 0 0.5rem 1.25rem rgba(18, 20, 23, 0.05); /* Soft shadow */
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
             /* Make the Add Unit button stand out slightly more */
            padding: 0.5rem 1.25rem; 
            border-radius: 8px;
            font-weight: 600;
        }
        .page-header .btn-primary:hover {
            opacity: 0.9;
        }

        /* --- Table Styling --- */
        #unitTable.table {
            border: none !important;
        }
        #unitTable thead th {
            background-color: #f9fafb;
            color: #6c757d;
            font-size: 0.8rem; /* Slightly smaller font for headers */
            font-weight: 600;
            text-transform: uppercase;
            border-top: none;
            border-bottom: 2px solid #e5e7eb; /* Thicker bottom border for header */
            padding: 1rem;
        }
        #unitTable tbody td {
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem;
        }
        #unitTable tbody tr:last-child td {
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

        /* --- Custom Badge Styling for Status Column (New UI) --- */
        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.7rem;
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 50px; /* Pill shape */
            transition: all 0.2s ease-in-out;
        }

        /* Style for Active (Green - like the requested image) */
        .status-active {
            color: #057a55; /* Darker green text */
            background-color: #def7ec; /* Light green background */
            border: 1px solid #10b981;
        }

        /* Style for Inactive (Gray) */
        .status-inactive {
            color: #6c757d; /* Gray text */
            background-color: #e9ecef; /* Light gray background */
            border: 1px solid #adb5bd;
        }
        /* --- Action Buttons (Table Cells) --- */
        .btn-action {
            font-size: 0.85rem;
            padding: 0.3rem 0.6rem;
            width: 30px; /* Uniform width for icon buttons */
            height: 30px; /* Uniform height for icon buttons */
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
    </style>
</head>

<body>
<?php $this->load->view('partials/body'); ?>
<!-- <div id="global-loader" >
            <div class="whirly-loader"> </div>
        </div>	 -->

<div class="main-wrapper">
<?php $this->load->view('partials/menu'); ?>

<div class="page-wrapper">
<div class="content container-fluid">

    <div class="page-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Product Units</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-1"></i> Add Unit
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="unitTable" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    <th width="50">SL.No</th>
                    <th>Symbol</th>
                    <th>Name</th>
                    <th width="100" class="text-center">Status</th>
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
    <h5 class="modal-title">Add New Unit</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <label class="form-label small text-muted">Symbol (Short form)</label>
        <input type="text" class="form-control" name="name" placeholder="e.g. Kilogram" required>
    </div>
    <div class="mb-3">
        <label class="form-label small text-muted">Unit Name</label>
        <input type="text" class="form-control" name="symbol" placeholder="e.g. kg, pcs, litre">
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="status" id="add_modal_status" checked>
        <label class="form-check-label" for="add_modal_status">Active Unit</label>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary">Save Unit</button>
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
    <h5 class="modal-title">Edit Unit Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <label class="form-label small text-muted">Unit Name</label>
        <input type="text" id="edit_name" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label small text-muted">Symbol (Short form)</label>
        <input type="text" id="edit_symbol" name="symbol" class="form-control">
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="edit_status" name="status">
        <label class="form-check-label" for="edit_status">Active Unit</label>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary">Update Unit</button>
</div>
</form>
</div>
</div>
</div>

<?php $this->load->view('partials/vendor-scripts'); ?>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
let csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
let csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

let table = $('#unitTable').DataTable({
    processing: true,
    serverSide: false,
    ajax:{
        url:'<?= site_url("home/get_units_data"); ?>',
        type:'POST',
        data:d=>{ d[csrfName]=csrfHash; },
        dataSrc:r=>{ csrfHash=r.csrf_token; return r.units; }
    },
    columns:[
        {data:null, className: 'text-center'}, // #
        {data:'name'},
        {data:'symbol'},
        { // Status column - UI CORRECTION: Changed rendering to use the status badge
            data:'status',
            className: 'text-center',
            render:(d,t,r)=>{
                if(d == 1){
                    // Active Status Badge (Green)
                    return `<span class="status-badge status-active">Active</span>`;
                } else {
                    // Inactive Status Badge (Gray)
                    return `<span class="status-badge status-inactive">Inactive</span>`;
                }
            }
        },
        { // Action column
            data:null,
            className: 'text-center',
            render:r=>`
            <div class="action-cell justify-content-center">
                <button class="btn btn-sm btn-info btn-action edit" title="Edit Unit"
                    data-id="${r.id}"
                    data-name="${r.name}"
                    data-symbol="${r.symbol}"
                    data-status="${r.status}"><i class="fas fa-pen"></i></button>
                <button class="btn btn-sm btn-danger btn-action delete" title="Delete Unit"
                    data-id="${r.id}"><i class="fas fa-trash-alt"></i></button>
            </div>`
        }
    ],
    columnDefs:[
        {
            targets:0,
            render:(d,t,r,m)=>m.row+1
        },
        { targets: 4, orderable: false, searchable: false }
    ],
    language: {
        search: "Search:",
        searchPlaceholder: "Search units...",
        paginate: {
            next: '<i class="fas fa-chevron-right"></i>',
            previous: '<i class="fas fa-chevron-left"></i>'
        }
    }
});

/* ADD */
$('#addForm').submit(function(e){
e.preventDefault();
$.post('<?= site_url("home/add_unit"); ?>',
$(this).serialize()+`&${csrfName}=${csrfHash}`,
r=>{
csrfHash=r.csrf_token;
alert(r.message);
if(r.success){
    $('#addModal').modal('hide');
    // Clear form fields on successful submission
    $('#addForm')[0].reset(); 
    table.ajax.reload();
}
},'json');
});

/* EDIT OPEN */
$(document).on('click','.edit',function(){
$('#edit_id').val($(this).data('id'));
$('#edit_name').val($(this).data('name'));
$('#edit_symbol').val($(this).data('symbol'));
// Update standard checkbox in modal
$('#editModal .modal-body input[name="status"]').prop('checked',$(this).data('status')==1);
$('#editModal').modal('show');
});

/* EDIT SAVE */
$('#editForm').submit(function(e){
e.preventDefault();
let id = $('#edit_id').val();
$.post('<?= site_url("home/edit_unit/"); ?>'+id,
$(this).serialize()+`&${csrfName}=${csrfHash}`,
r=>{
csrfHash=r.csrf_token;
alert(r.message);
if(r.success){
    $('#editModal').modal('hide');
    table.ajax.reload();
}
},'json');
});

/* STATUS TOGGLE - UI CORRECTION: Removed this event listener as the status is now a static badge. */
/*
$(document).on('change','.status-toggle',function(){
$.post('<?= site_url("home/toggle_unit_status/"); ?>'+$(this).data('id'),
{status:this.checked?1:0,[csrfName]:csrfHash},
r=>{ csrfHash=r.csrf_token; },'json');
});
*/

/* DELETE */
$(document).on('click','.delete',function(){
if(confirm('Are you sure you want to delete this unit?')){
$.post('<?= site_url("home/delete_unit/"); ?>'+$(this).data('id'),
{[csrfName]:csrfHash},
r=>{
csrfHash=r.csrf_token;
alert(r.message);
table.ajax.reload();
},'json');
}
});
</script>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>
</html>