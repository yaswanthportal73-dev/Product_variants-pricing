<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Categories</title>
    <?php $this->load->view('partials/title-meta'); ?>
    <?php $this->load->view('partials/head-css'); ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        /* === YOUR UI STYLES UNCHANGED === */
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #344767; }
        .table-image { width:60px; height:60px; object-fit:cover; border-radius:6px; }
        .action-icon { 
            display: inline-block; 
            width: 32px; 
            height: 32px; 
            margin: 0 2px; 
            border-radius: 6px; 
            text-align: center; 
            line-height: 32px; 
            transition: all 0.2s ease-in-out; 
            cursor: pointer;
            text-decoration: none;
        }
        .action-icon:hover { 
            text-decoration: none; 
            opacity: 1;
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .edit-icon { 
            color: #0d6efd; 
            background-color: rgba(13, 110, 253, 0.1); 
        }
        .edit-icon:hover { 
            background-color: rgba(13, 110, 253, 0.2); 
            color: #0d6efd; 
        }
        .delete-icon { 
            color: #dc3545; 
            background-color: rgba(220, 53, 69, 0.1); 
        }
        .delete-icon:hover { 
            background-color: rgba(220, 53, 69, 0.2); 
            color: #dc3545; 
        }
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
    </style>
</head>
<body class="bg-light">
<?php $this->load->view('partials/body'); ?>
<!-- <div id="global-loader">
    <div class="whirly-loader"></div>
</div>	 -->
<div class="main-wrapper">
<?php $this->load->view('partials/menu'); ?>
<div class="page-wrapper">
<div class="content">
<div class="page-header d-flex justify-content-between align-items-center">
    <div class="page-title">
        <h4 class="mb-1">Categories</h4>
        <small class="text-muted d-block">Manage product categories</small>
    </div>
    <div class="page-btn d-flex gap-2">
        <button type="button" class="btn btn-white border shadow-sm refresh-page">
            <i data-feather="rotate-ccw" class="me-2" style="width:16px;"></i>Refresh
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i data-feather="plus" class="me-2" style="width:18px;"></i>Add Category
        </button>
    </div>
</div>
<div class="card">
<div class="card-body">
<table id="categoryTable" class="table table-bordered table-striped align-middle" aria-label="Categories management table">
<thead>
<tr>
    <th>Sl.No</th>
    <th>Image</th>
    <th>Category Name</th>
    <th>Slug</th>
    <th>Created</th>
    <th>Status</th>
    <th class="text-end">Action</th>
</tr>
</thead>
<tbody id="categoryBody"></tbody>
</table>
</div>
</div>
</div>
</div>
</div>
<!-- ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<form id="addForm" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title">Create Category</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <input type="file" name="image" id="add_image" class="form-control mb-3" accept="image/*">
    <div id="add_image_preview"></div>
    <input type="text" name="name" class="form-control mb-3" placeholder="Category name" required>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="status" checked>
        <label class="form-check-label">Active</label>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-light" data-bs-dismiss="modal" type="button">Cancel</button>
    <button class="btn btn-primary" type="submit">Create</button>
</div>
</form>
</div>
</div>
</div>
<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<form id="editForm" enctype="multipart/form-data">
<input type="hidden" name="id" id="edit_id">
<input type="hidden" name="current_image" id="edit_current_image">
<div class="modal-header">
    <h5 class="modal-title">Edit Category</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <div id="edit_image_preview"></div>
    <input type="file" name="image" id="edit_image" class="form-control mb-3" accept="image/*">
    <input type="text" name="name" id="edit_name" class="form-control mb-3" required>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="status" id="edit_status">
        <label class="form-check-label">Active</label>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-light" data-bs-dismiss="modal" type="button">Cancel</button>
    <button class="btn btn-primary" type="submit">Update</button>
</div>
</form>
</div>
</div>
</div>
<!-- TOAST -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
<div id="appToast" class="toast text-bg-dark border-0">
<div class="d-flex">
    <div class="toast-body"></div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
</div>
</div>
</div>
    <?php $this->load->view('partials/theme-settings') ?>
    <?php $this->load->view('partials/vendor-scripts') ?>
<script>
let table;
let csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
let csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
let notify = function(msg){
    const toast = new bootstrap.Toast(document.getElementById('appToast'));
    $('.toast-body').text(msg);
    toast.show();
};

// File validation helper
function validateImage(fileInput) {
    let file = fileInput.files[0];
    if (file && !file.type.startsWith('image/')) {
        notify('Please select a valid image file.');
        fileInput.value = '';
        return false;
    }
    if (file && file.size > 2 * 1024 * 1024) {
        notify('Image size must be less than 2MB.');
        fileInput.value = '';
        return false;
    }
    return true;
}

// Slugify helper (for client-side preview if needed; server generates final)
function slugify(text) {
    return text.toLowerCase().trim().replace(/[^\w\s-]/g, '').replace(/[\s_-]+/g, '-').replace(/^-+|-+$/g, '');
}

$(function(){
    loadCategories();
    $('#addForm').submit(function(e){
        e.preventDefault();
        if (!validateImage($('#add_image')[0])) return;
        let fd = new FormData(this);
        fd.append(csrfName, csrfHash);
        $.ajax({
            url:'<?= base_url("add-category"); ?>',
            type:'POST',
            data:fd,
            processData:false,
            contentType:false,
            success:function(res){
                let r = JSON.parse(res);
                notify(r.message);
                if(r.success){
                    bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                    $('#addForm')[0].reset();
                    loadCategories();
                }
                csrfHash = r.csrf_token || csrfHash;
            },
            error: function() {
                notify('An error occurred. Please try again.');
            }
        });
    });
    $('#editForm').submit(function(e){
        e.preventDefault();
        if (!validateImage($('#edit_image')[0])) return;
        let id = $('#edit_id').val();
        let fd = new FormData(this);
        fd.append(csrfName, csrfHash);
        $.ajax({
            url:'<?= base_url("edit-category/"); ?>'+id,
            type:'POST',
            data:fd,
            processData:false,
            contentType:false,
            success:function(res){
                let r = JSON.parse(res);
                notify(r.message);
                if(r.success){
                    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                    loadCategories();
                }
                csrfHash = r.csrf_token || csrfHash;
            },
            error: function() {
                notify('An error occurred. Please try again.');
            }
        });
    });
    // File change validation and preview for add
    $('#add_image').on('change', function(e) {
        let input = this;
        if (!validateImage(input)) return;
        let file = input.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#add_image_preview').html(`
                    <div class="mb-3">
                        <img src="${e.target.result}" class="img-thumbnail" width="100" alt="Preview image">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-preview mt-1">Remove Preview</button>
                    </div>
                `);
            };
            reader.readAsDataURL(file);
        } else {
            $('#add_image_preview').empty();
        }
    });
    // File change validation and preview for edit
    $('#edit_image').on('change', function(e) {
        let input = this;
        if (!validateImage(input)) return;
        let file = input.files[0];
        if (file) {
            // Remove any existing new preview to avoid duplicates
            $('#edit_image_preview .new-preview').remove();
            let reader = new FileReader();
            reader.onload = function(e) {
                let newHtml = `
                    <div class="mb-3 new-preview">
                        <strong>New Image:</strong><br>
                        <img src="${e.target.result}" class="img-thumbnail" width="100" alt="New category image">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-new mt-1">Remove New</button>
                    </div>
                `;
                $('#edit_image_preview').append(newHtml);
            };
            reader.readAsDataURL(file);
        }
    });
    // Name input for slug preview (optional)
    $('#addForm input[name="name"], #editForm input[name="name"]').on('input', function() {
        let slug = slugify($(this).val());
        // Could add a display field if desired: $(this).closest('form').find('.slug-preview').text(slug);
    });
    // Refresh button
    $(document).on('click', '.refresh-page', function() {
        loadCategories();
        notify('Categories refreshed.');
    });
    // Clear add preview on modal hide
    $('#addModal').on('hidden.bs.modal', function() {
        $('#add_image_preview').empty();
        $('#addForm')[0].reset();
    });
    // Clear edit preview on modal hide
    $('#editModal').on('hidden.bs.modal', function() {
        $('#edit_image_preview').empty();
        $('#edit_image').val('');
        $('#edit_current_image').val('');
    });
    feather.replace();
});
function loadCategories(){
    if ($.fn.DataTable.isDataTable('#categoryTable')) {
        $('#categoryTable').DataTable().destroy();
    }
    $.getJSON('<?= base_url("get-categories"); ?>', function(res){
        let tbody = $('#categoryBody').empty();
        if(res.success && res.categories.length){
            $.each(res.categories, function(i, c){
                let statusBadge = c.status == 1 
                    ? '<span class="status-badge status-active">Active</span>'
                    : '<span class="status-badge status-inactive">Inactive</span>';
                let img = c.image
                    ? `<img src="<?= base_url("uploads/categories/") ?>${c.image}?${Date.now()}" class="table-image" alt="${c.name} category image">`
                    : `-`;
                tbody.append(`
                <tr>
                    <td>${i+1}</td>
                    <td>${img}</td>
                    <td>${c.name}</td>
                    <td>${c.slug}</td>
                    <td>${c.created_at || ''}</td>
                    <td>${statusBadge}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-info btn-action edit-category" 
                                title="Edit Category" 
                                aria-label="Edit Category"
                                data-bs-toggle="modal" 
                                data-bs-target="#editModal"
                                data-id="${c.id}"
                                data-name="${c.name}"
                                data-status="${c.status}" 
                                data-image="${c.image || ''}">
                            <i data-feather="edit-3" style="width: 14px; height: 14px;"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-action delete-category" 
                                title="Delete Category" 
                                aria-label="Delete Category with ID ${c.id}"
                                data-id="${c.id}">
                            <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                        </button>
                    </td>
                </tr>`);
            });
        }
        table = $('#categoryTable').DataTable();
        feather.replace();
        csrfHash = res.csrf_token || csrfHash;
    }).fail(function() {
        notify('Failed to load categories. Please try again.');
    });
}
$(document).on('click','.edit-category',function(){
    $('#edit_id').val($(this).data('id'));
    $('#edit_name').val($(this).data('name'));
    $('#edit_status').prop('checked', $(this).data('status')==1);
    let currentImage = $(this).data('image');
    $('#edit_current_image').val(currentImage);
    let preview = $('#edit_image_preview').empty();
    let currentHtml = '';
    if (currentImage) {
        currentHtml = `
            <div class="mb-3 current-preview">
                <strong>Current Image:</strong><br>
                <img src="<?= base_url("uploads/categories/") ?>${currentImage}?${Date.now()}" class="img-thumbnail" width="100" alt="Current category image">
                <button type="button" class="btn btn-sm btn-outline-danger remove-current mt-1">Remove Current</button>
            </div>
        `;
    } else {
        $('#edit_current_image').val('');
    }
    preview.html(currentHtml);
});
// Remove preview for add
$(document).on('click', '.remove-preview', function() {
    $('#add_image_preview').empty();
    $('#add_image').val('');
});
// Remove current image handler for edit
$(document).on('click', '.remove-current', function() {
    $(this).closest('.current-preview').remove();
    $('#edit_current_image').val('');
});
// Remove new image handler for edit
$(document).on('click', '.remove-new', function() {
    $(this).closest('.new-preview').remove();
    $('#edit_image').val('');
});
$(document).on('click','.delete-category',function(){
    let id = $(this).data('id');
    if(confirm('Delete this category?')){
        $.post('<?= base_url("delete-category/"); ?>'+id, {[csrfName]:csrfHash}, function(res){
            let r = JSON.parse(res);
            notify(r.message)
            csrfHash = r.csrf_token || csrfHash;
            loadCategories();
        }).fail(function() {
            notify('Failed to delete category. Please try again.');
        });
    }
});
</script>
</body>
</html>