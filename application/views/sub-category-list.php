<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('partials/title-meta'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <?php $this->load->view('partials/head-css'); ?>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        /* --- General UI Polish --- */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #344767;
        }

        /* --- Page Header --- */
        .page-header { margin-bottom: 1.5rem; }
        .page-title h4 { font-weight: 700; color: #1a1a1a; letter-spacing: -0.5px; }
        .page-title small { font-size: 0.875rem; color: #6c757d; }

        /* --- Card & Table Styling --- */
        .card.table-list-card {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
        }
        .table-responsive { padding: 0; }
        .table { margin-bottom: 0; width: 100%; }
        .table thead th {
            background-color: #f9fafb;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.9rem;
            color: #374151;
        }
        .table tbody tr:hover { background-color: #f9fafb; }
        
        /* DataTables overrides for clean UI */
        table.dataTable.no-footer { border-bottom: 1px solid #e5e7eb; }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.4rem 0.8rem;
            outline: none;
        }

        /* --- Badges --- */
        .badge {
            padding: 0.35em 0.8em;
            border-radius: 50rem;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
        }
        .badge-linesuccess { background-color: #d1fae5; color: #059669; }
        .badge-linedanger { background-color: #fee2e2; color: #dc2626; }

        /* --- Action Buttons --- */
        .action-btns { display: flex; gap: 8px; justify-content: flex-end; }
        .action-btns a {
            width: 34px; height: 34px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 8px; color: #64748b;
            transition: all 0.2s ease; background: #f8fafc; border: 1px solid #e2e8f0;
        }
        .action-btns a.edit-item:hover { background-color: #eff6ff; color: #3b82f6; border-color: #bfdbfe; }
        .action-btns a.delete-item:hover { background-color: #fef2f2; color: #ef4444; border-color: #fecaca; }

        /* --- Modals & Forms --- */
        .modal-content {
            border: none;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
        }
        .modal-header { border-bottom: 1px solid #f3f4f6; padding: 1.5rem; border-radius: 16px 16px 0 0; }
        .modal-body { padding: 1.5rem; }
        .modal-footer { border-top: 1px solid #f3f4f6; padding: 1.25rem 1.5rem; background-color: #f9fafb; border-radius: 0 0 16px 16px; }
        
        .form-control, .form-select {
            border: 1px solid #e5e7eb; padding: 0.625rem 1rem; border-radius: 8px; font-size: 0.9rem;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); border-color: #3b82f6;
        }
        .form-label { font-weight: 500; margin-bottom: 0.5rem; color: #374151; font-size: 0.875rem; }
        
        /* --- Buttons --- */
        .btn { padding: 0.6rem 1.2rem; font-weight: 500; border-radius: 8px; display: inline-flex; align-items: center; }
        .btn-primary { background-color: #4f46e5; border-color: #4f46e5; box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2); }
        .btn-primary:hover { background-color: #4338ca; border-color: #4338ca; transform: translateY(-1px); }

        /* --- Toast --- */
        .toast-container { z-index: 1080; }
        .toast { border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    </style>
</head>

<body>
<?php $this->load->view('partials/body'); ?>
<div id="global-loader" >
            <div class="whirly-loader"> </div>
        </div>	

<div class="main-wrapper">
<?php $this->load->view('partials/menu'); ?>

<div class="page-wrapper">
<div class="content">

    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="page-title">
            <h4 class="mb-1">Sub Categories</h4>
            <small class="text-muted d-block">Manage product sub-categories</small>
        </div>
        <div class="page-btn d-flex gap-2">
            <button type="button" class="btn btn-white border shadow-sm refresh-page" title="Refresh">
                <i data-feather="rotate-ccw" class="me-2" style="width:16px;"></i>Refresh
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i data-feather="plus" class="me-2" style="width:18px;"></i>Add Sub Category
            </button>
        </div>
    </div>

    <div class="card table-list-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="subCategoryTable" class="table align-middle">
                    <thead>
                        <tr>
                            <th style="width:50px; padding-left: 1.5rem;">SL.No</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th class="text-end" style="padding-right: 1.5rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="addForm">
                <div class="modal-header">
                    <h5 class="modal-title">Add Sub-Category</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Parent Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Sub Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Mobile Phones" required>
                    </div>
                    <div class="form-check form-switch d-flex align-items-center gap-2 ps-0">
                        <label class="form-check-label me-3">Status:</label>
                        <input class="form-check-input ms-0" type="checkbox" name="status" value="1" checked style="width: 3em; height: 1.5em; cursor: pointer;">
                        <label class="form-check-label text-muted small">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editForm">
                <input type="hidden" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Sub-Category</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Parent Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="edit_category" class="form-select" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Sub Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-check form-switch d-flex align-items-center gap-2 ps-0">
                        <label class="form-check-label me-3">Status:</label>
                        <input class="form-check-input ms-0" type="checkbox" name="status" id="edit_status" value="1" style="width: 3em; height: 1.5em; cursor: pointer;">
                        <label class="form-check-label text-muted small">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="appToast" class="toast align-items-center text-white bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<?php $this->load->view('partials/vendor-scripts'); ?>
<script src="https://unpkg.com/feather-icons"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(function(){
    // --- CSRF & Toast Setup ---
    let csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    let csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

    const toastEl = document.getElementById('appToast');
    const toast = new bootstrap.Toast(toastEl);
    const notify = (msg) => { $('.toast-body').text(msg); toast.show(); };

    // --- DataTable Initialization ---
    let table = $('#subCategoryTable').DataTable({
        ajax: {
            url: '<?= base_url("get-sub-categories"); ?>',
            type: 'POST',
            data: function(d){
                d[csrfName] = csrfHash;
            },
            dataSrc: function(json){
                // Update CSRF hash for next request
                csrfHash = json.csrf_token;
                return json.categories;
            }
        },
        pageLength: 10,
        ordering: true,
        searching: true,
        lengthChange: false,
        language: {
            search: "",
            searchPlaceholder: "Search sub-categories...",
            paginate: {
                previous: "<i data-feather='chevron-left'></i>",
                next: "<i data-feather='chevron-right'></i>"
            }
        },
        drawCallback: function() {
            feather.replace();
            $('.dataTables_filter input').addClass('form-control');
        },
        columns: [
            { data: null, className: "ps-4" }, // Padding left wrapper
            { 
                data: 'category_name',
                render: function(data) {
                    return `<span class="fw-medium text-dark">${data}</span>`;
                }
            },
            { data: 'name' },
            { 
                data: 'slug',
                render: function(data) {
                    return `<span class="text-muted small font-monospace">${data}</span>`;
                }
            },
            {
                data: 'status',
                render: function(data) {
                    return `<span class="badge ${data==1 ? 'badge-linesuccess' : 'badge-linedanger'}">
                                ${data==1 ? 'Active' : 'Inactive'}
                            </span>`;
                }
            },
            {
                data: null,
                className: 'text-end pe-4',
                render: function(data) {
                    return `
                        <div class="action-btns">
                            <a href="javascript:void(0)" class="edit-item" title="Edit"
                               data-id="${data.id}"
                               data-name="${data.name}"
                               data-category="${data.category_id}"
                               data-status="${data.status}">
                               <i data-feather="edit-2" style="width:16px;"></i>
                            </a>
                            <a href="javascript:void(0)" class="delete-item" title="Delete"
                               data-id="${data.id}">
                               <i data-feather="trash-2" style="width:16px;"></i>
                            </a>
                        </div>`;
                }
            }
        ],
        columnDefs: [
            { 
                targets: 0, 
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { targets: 5, orderable: false }
        ]
    });

    // Refresh Button Logic
    $('.refresh-page').on('click', function(){
        table.ajax.reload();
    });

    // --- ADD SUB CATEGORY ---
    $('#addForm').on('submit', function(e){
        e.preventDefault();
        // Append current CSRF to form data
        let formData = $(this).serialize() + `&${csrfName}=${csrfHash}`;
        
        $.post('<?= base_url("add-sub-category"); ?>', formData, function(res){
            notify(res.message);
            csrfHash = res.csrf_token; // Update token
            
            if(res.success){
                $('#addModal').modal('hide');
                $('#addForm')[0].reset();
                table.ajax.reload();
            }
        }, 'json');
    });

    // --- EDIT CLICK HANDLER ---
    $(document).on('click', '.edit-item', function(){
        $('#edit_id').val($(this).data('id'));
        $('#edit_name').val($(this).data('name'));
        $('#edit_category').val($(this).data('category'));
        $('#edit_status').prop('checked', $(this).data('status') == 1);
        $('#editModal').modal('show');
    });

    // --- UPDATE SUB CATEGORY ---
    $('#editForm').on('submit', function(e){
        e.preventDefault();
        let id = $('#edit_id').val();
        let formData = $(this).serialize() + `&${csrfName}=${csrfHash}`;

        $.post('<?= base_url("edit-sub-category/"); ?>' + id, formData, function(res){
            notify(res.message);
            csrfHash = res.csrf_token;
            
            if(res.success){
                $('#editModal').modal('hide');
                table.ajax.reload();
            }
        }, 'json');
    });

    // --- DELETE SUB CATEGORY ---
    $(document).on('click', '.delete-item', function(){
        let id = $(this).data('id');
        if(confirm('Are you sure you want to delete this sub-category?')){
            $.post('<?= base_url("delete-sub-category/"); ?>' + id, { [csrfName]: csrfHash }, function(res){
                notify(res.message);
                csrfHash = res.csrf_token;
                table.ajax.reload();
            }, 'json');
        }
    });

    // Initial icon replacement
    feather.replace();
});
</script>
</body>
</html>