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

            <!-- Flash messages -->
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success flash-msg"><?= $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger flash-msg"><?= $this->session->flashdata('error'); ?></div>
            <?php endif; ?>

            <!-- Page Header + Search + Buttons -->
            <div class="page-header d-flex justify-content-between align-items-center mb-3">
                <h4>Countries List</h4>

                <div class="d-flex gap-2 align-items-center">

                    <!-- Search Box -->
                    <form method="get" action="<?= base_url('countries') ?>" class="d-flex gap-2">
                        <input 
                            type="text" 
                            name="keyword" 
                            class="form-control form-control-sm"
                            placeholder="Search (Ex: Anantapuram)"
                            value="<?= $this->input->get('keyword') ?>"
                        >
                        <button class="btn btn-secondary btn-sm">Search</button>
                    </form>

                    <!-- Export / Add -->
                    <a href="<?= base_url('countries/export_excel') ?>" class="btn btn-success btn-sm">Export Excel</a>
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCountry">Add Country</a>
                </div>
            </div>

            <!-- Countries Table -->
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Country Name</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Updated By</th>
                        <th>Deleted By</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Deleted At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; foreach($countries as $c): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $c->country_name ?></td>
                            <td><?= $c->status ? 'Active':'Inactive' ?></td>
                            <td><?= $c->created_by ?? '-' ?></td>
                            <td><?= $c->updated_by ?? '-' ?></td>
                            <td><?= $c->deleted_by ?? '-' ?></td>
                            <td><?= date('d-m-Y', strtotime($c->created_at)) ?></td>
                            <td><?= isset($c->updated_at) ? date('d-m-Y', strtotime($c->updated_at)) : '-' ?></td>
                            <td><?= isset($c->deleted_at) ? date('d-m-Y', strtotime($c->deleted_at)) : '-' ?></td>
                            <td>
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#edit<?= $c->id ?>">Edit</a> |
                                <a href="<?= base_url('countries/delete/'.$c->id) ?>" onclick="return confirm('Delete this country?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<!-- Add Country Modal -->
<div class="modal fade" id="addCountry" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="<?= base_url('countries/store') ?>" class="modal-content">
            <div class="modal-header">
                <h5>Add Country</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Country Name</label>
                    <input type="text" name="country_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Country Modals -->
<?php foreach($countries as $c): ?>
<div class="modal fade" id="edit<?= $c->id ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="<?= base_url('countries/update/'.$c->id) ?>" class="modal-content">
            <div class="modal-header">
                <h5>Edit Country</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Country Name</label>
                    <input type="text" name="country_name" value="<?= $c->country_name ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1" <?= $c->status?'selected':'' ?>>Active</option>
                        <option value="0" <?= !$c->status?'selected':'' ?>>Inactive</option>
                    </select>
                </div>
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
    feather.replace();

    // Auto hide flash messages
    setTimeout(()=>{
        const msg=document.querySelector('.flash-msg'); 
        if(msg){ 
            msg.style.transition='opacity 0.5s'; 
            msg.style.opacity='0'; 
            setTimeout(()=>msg.remove(),500);
        }
    }, 300);
</script>
</body>
</html>
