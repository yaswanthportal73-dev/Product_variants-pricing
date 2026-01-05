

<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('partials/title-meta'); ?>
    <?php $this->load->view('partials/head-css'); ?>
</head>

<?php $this->load->view('partials/body'); ?>

<div class="main-wrapper">
<?php $this->load->view('partials/menu'); ?>

<div class="page-wrapper">
<div class="content">

<!-- ================= FLASH MESSAGES ================= -->
<?php if(
    $this->session->flashdata('location_success') 
    && empty($_GET)
): ?>
<div class="alert alert-success flash-msg">
    <?= $this->session->flashdata('location_success'); ?>
</div>
<?php endif; ?>

<!-- ================= HEADER ================= -->
<div class="page-header d-flex justify-content-between align-items-center mb-3">
    <h4>Location Master</h4>

    <button class="btn btn-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#addLocation">
        Add Location
    </button>
</div>

<!-- ================= SEARCH & FILTER ================= -->
<form method="get" action="" class="row g-2 mb-3">

   

    <div class="col-md-2">
        <input type="text" name="search"
               value="<?= $this->input->get('search'); ?>"
               class="form-control"
               placeholder="Search All">
    </div>

    <div class="col-md-2 d-flex gap-1">
        <button class="btn btn-primary w-100">Search</button>
        <a href="<?= base_url('location1'); ?>" class="btn btn-secondary w-100">
            Reset
        </a>
        
                    <a href="<?= base_url('countries/export_excel') ?>" class="btn btn-success btn-sm">Export Excel</a>
    </div>

</form>

<!-- ================= TABLE ================= -->
<div class="table-responsive">
<table class="table table-striped datatable">
<thead>
<tr>
    <th>ID</th>
    <th>Country</th>
    <th>State</th>
    <th>District</th>
    <th>Mandal</th>
    <th>Village</th>
    <th>Status</th>

    <th>Created By</th>
    <th>Updated By</th>

    <th>Created At</th>
    <th>Updated At</th>

    <th>Actions</th>
</tr>
</thead>

<tbody>
<?php if(!empty($locations)): foreach($locations as $l): ?>
<tr>
    <td><?= $l->id ?></td>
    <td><?= $l->country_name ?: '-' ?></td>
    <td><?= $l->state_name ?: '-' ?></td>
    <td><?= $l->district_name ?: '-' ?></td>
    <td><?= $l->mandal_name ?: '-' ?></td>
    <td><?= $l->village_name ?: '-' ?></td>

    <td>
        <a href="<?= base_url('location1/toggle/'.$l->id); ?>"
           class="btn btn-sm <?= $l->status ? 'btn-success':'btn-danger'; ?>">
            <?= $l->status ? 'Active':'Inactive'; ?>
        </a>
    </td>

    <td><?= $l->created_by ?: '-' ?></td>
    <td><?= $l->updated_by ?: '-' ?></td>

    <td><?= $l->created_at ? date('d-m-Y H:i',strtotime($l->created_at)) : '-' ?></td>
    <td><?= $l->updated_at ? date('d-m-Y H:i',strtotime($l->updated_at)) : '-' ?></td>

    <td>
        <button class="btn btn-warning btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#edit<?= $l->id ?>">
            Edit
        </button>

        <a href="<?= base_url('location1/delete/'.$l->id); ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Delete this record?')">
            Delete
        </a>
    </td>
</tr>
<?php endforeach; else: ?>
<tr>
    <td colspan="14" class="text-center">No Records Found</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>

</div>
</div>
</div>

<!-- ================= ADD MODAL ================= -->
<div class="modal fade" id="addLocation">
<div class="modal-dialog modal-dialog-centered">
<form method="post" action="<?= base_url('location1/store'); ?>" class="modal-content">

<div class="modal-header">
    <h5>Add Location</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<?php
$fields = [
    'country_name'=>'Country',
    'state_name'=>'State',
    'district_name'=>'District',
    'mandal_name'=>'Mandal',
    'village_name'=>'Village'
];
foreach($fields as $name=>$label):
?>
<div class="mb-2">
    <label><?= $label ?></label>
    <input type="text" name="<?= $name ?>" class="form-control">
</div>
<?php endforeach; ?>

<div class="mb-2">
    <label>Status</label>
    <select name="status" class="form-control">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
</div>

<input type="hidden" name="created_by"
value="<?= $this->session->userdata('username') ?? 'Admin'; ?>">
</div>

<div class="modal-footer">
    <button class="btn btn-success">Save</button>
</div>

</form>
</div>
</div>

<!-- ================= EDIT MODALS ================= -->
<?php foreach($locations as $l): ?>
<div class="modal fade" id="edit<?= $l->id ?>">
<div class="modal-dialog modal-dialog-centered">
<form method="post"
      action="<?= base_url('location1/update/'.$l->id); ?>"
      class="modal-content">

<div class="modal-header">
    <h5>Edit Location</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<?php foreach($fields as $name=>$label): ?>
<div class="mb-2">
    <label><?= $label ?></label>
    <input type="text"
           name="<?= $name ?>"
           value="<?= $l->$name ?>"
           class="form-control">
</div>
<?php endforeach; ?>

<div class="mb-2">
    <label>Status</label>
    <select name="status" class="form-control">
        <option value="1" <?= $l->status?'selected':'' ?>>Active</option>
        <option value="0" <?= !$l->status?'selected':'' ?>>Inactive</option>
    </select>
</div>

<input type="hidden" name="updated_by"
value="<?= $this->session->userdata('username') ?? 'Admin'; ?>">
</div>

<div class="modal-footer">
    <button class="btn btn-primary">Update</button>
</div>

</form>
</div>
</div>
<?php endforeach; ?>

<?php $this->load->view('partials/vendor-scripts'); ?>

<script>
setTimeout(() => {
    document.querySelectorAll('.flash-msg').forEach(el => {
        el.style.opacity = '0';
        setTimeout(()=>el.remove(),500);
    });
}, 3000);
</script>

</body>
</html>