<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $this->load->view('partials/title-meta'); ?>
<?php $this->load->view('partials/head-css'); ?>

<div class="main-wrapper">
<?php $this->load->view('partials/menu'); ?>

<div class="page-wrapper">
<div class="content">

<!-- FLASH MESSAGES -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success flash-msg"><?= $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger flash-msg"><?= $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<!-- HEADER -->
<div class="page-header d-flex justify-content-between align-items-center mb-3">
    <h4>States List</h4>

    <div class="d-flex gap-2 align-items-center">
        <!-- SEARCH -->
        <form method="get" action="<?= base_url('states') ?>" class="d-flex gap-2">
            <input type="text" name="keyword" class="form-control form-control-sm"
                   placeholder="Search (State / Country)"
                   value="<?= $this->input->get('keyword') ?>">
            <button class="btn btn-secondary btn-sm">Search</button>
        </form>

        <!-- ADD -->
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addState">
            Add State
        </button>
    </div>
</div>

<!-- TABLE -->
<table class="table table-striped">
<thead>
<tr>
    <th>S.No</th>
    <th>State</th>
    <th>Country</th>
    <th>Status</th>
    <th>Created By</th>
    <th>Updated By</th>
    <th>Deleted By</th>
    <th>Created At</th>
    <th>Updated At</th>
    <th>Deleted At</th>
    <th>Actions</th>
</tr>
</thead>

<tbody>
<?php $i=1; foreach($states as $s): ?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= $s->state_name ?></td>
    <td><?= $s->country_name ?></td>

    <!-- STATUS -->
    <td>
        <a href="<?= base_url('states/toggle_status/'.$s->id) ?>"
           class="btn btn-sm <?= $s->status ? 'btn-success':'btn-danger' ?>">
           <?= $s->status ? 'Active':'Inactive' ?>
        </a>
    </td>

    <td><?= $s->created_by ?? '-' ?></td>
    <td><?= $s->updated_by ?? '-' ?></td>
    <td><?= $s->deleted_by ?? '-' ?></td>

    <td><?= $s->created_at ? date('d-m-Y',strtotime($s->created_at)):'-' ?></td>
    <td><?= $s->updated_at ? date('d-m-Y',strtotime($s->updated_at)):'-' ?></td>
    <td><?= $s->deleted_at ? date('d-m-Y',strtotime($s->deleted_at)):'-' ?></td>

    <td>
        <button class="btn btn-warning btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#editState<?= $s->id ?>">Edit</button>

        <a href="<?= base_url('states/delete/'.$s->id) ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Delete this state?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>

<?php if(empty($states)): ?>
<tr><td colspan="11" class="text-center">No Records Found</td></tr>
<?php endif; ?>
</tbody>
</table>

</div>
</div>
</div>

<!-- ================= ADD STATE MODAL ================= -->
<div class="modal fade" id="addState">
<div class="modal-dialog modal-dialog-centered">
<form method="post" action="<?= base_url('states/store') ?>" class="modal-content">

<div class="modal-header">
    <h5>Add State</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="mb-3">
        <label>State Name</label>
        <input type="text" name="state_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Country</label>
        <select name="country_id" class="form-control" required>
            <option value="">Select Country</option>
            <?php foreach($countries as $c): ?>
                <option value="<?= $c->id ?>"><?= $c->country_name ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    <input type="hidden" name="created_by" value="<?= $this->session->userdata('username') ?? 'Admin' ?>">
</div>

<div class="modal-footer">
    <button class="btn btn-success">Save</button>
</div>

</form>
</div>
</div>

<!-- ================= EDIT STATE MODALS ================= -->
<?php foreach($states as $s): ?>
<div class="modal fade" id="editState<?= $s->id ?>">
<div class="modal-dialog modal-dialog-centered">
<form method="post" action="<?= base_url('states/update/'.$s->id) ?>" class="modal-content">

<div class="modal-header">
    <h5>Edit State</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="mb-3">
        <label>State Name</label>
        <input type="text" name="state_name" value="<?= $s->state_name ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Country</label>
        <select name="country_id" class="form-control" required>
            <?php foreach($countries as $c): ?>
                <option value="<?= $c->id ?>" <?= $s->country_id==$c->id?'selected':'' ?>>
                    <?= $c->country_name ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="1" <?= $s->status?'selected':'' ?>>Active</option>
            <option value="0" <?= !$s->status?'selected':'' ?>>Inactive</option>
        </select>
    </div>

    <input type="hidden" name="updated_by" value="<?= $this->session->userdata('username') ?? 'Admin' ?>">
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
    const msg = document.querySelector('.flash-msg');
    if(msg){ msg.style.opacity='0'; setTimeout(()=>msg.remove(),500); }
}, 300);
</script>
