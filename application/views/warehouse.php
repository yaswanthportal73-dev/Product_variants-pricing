<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $this->load->view('partials/title-meta'); ?>
<?php $this->load->view('partials/head-css'); ?>

<div class="main-wrapper">
    <?php $this->load->view('partials/menu'); ?>

    <div class="page-wrapper">
        <div class="content">

            <!-- Flash messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success flash-msg"><?= $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger flash-msg"><?= $this->session->flashdata('error'); ?></div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="page-header d-flex justify-content-between align-items-center mb-3">
                <h4>Warehouse List</h4>
                <div class="d-flex gap-2">
                    <!-- Search Form -->
                    <form method="get" action="<?= base_url('warehouse') ?>" class="d-flex gap-2">
                        <input type="text" name="keyword" class="form-control form-control-sm"
                               placeholder="Search Warehouse / Person / Phone"
                               value="<?= $this->input->get('keyword') ?>">
                        <button class="btn btn-secondary btn-sm">Search</button>
                    </form>

                    <!-- Add Warehouse -->
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addWarehouse">Add Warehouse</button>
                </div>
            </div>

            <!-- Warehouse Table -->
            <div class="table-responsive">
                <table class="table table-striped datatable" id="warehouseTable">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Contact Person</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Image</th>
                            <th>Description</th>
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
                        <?php if(!empty($warehouses) && is_array($warehouses)): ?>
                            <?php foreach ($warehouses as $i => $w): ?>
                                <tr>
                                    <td><?= $i+1 ?></td>
                                    <td><?= $w->name ?></td>
                                    <td><?= $w->contact_person ?></td>
                                    <td><?= $w->phone ?></td>
                                    <td><?= $w->email ?></td>
                                    <td>
                                        <?php if($w->image): ?>
                                            <img src="<?= base_url('uploads/warehouses/'.$w->image) ?>" alt="Warehouse Image" width="50">
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $w->description ?></td>
                                    <td>
                                        <a href="<?= base_url('warehouse/toggle_status/'.$w->id) ?>"
                                           class="btn btn-sm <?= isset($w->status) && $w->status ? 'btn-success' : 'btn-danger' ?>">
                                           <?= isset($w->status) && $w->status ? 'Active' : 'Inactive' ?>
                                        </a>
                                    </td>
                                    <td><?= isset($w->created_by) ? $w->created_by : '-' ?></td>
                                    <td><?= isset($w->updated_by) ? $w->updated_by : '-' ?></td>
                                    <td><?= isset($w->deleted_by) ? $w->deleted_by : '-' ?></td>
                                    <td><?= isset($w->created_at) ? date('d-m-Y H:i', strtotime($w->created_at)) : '-' ?></td>
                                    <td><?= isset($w->updated_at) ? date('d-m-Y H:i', strtotime($w->updated_at)) : '-' ?></td>
                                    <td><?= isset($w->deleted_at) ? date('d-m-Y H:i', strtotime($w->deleted_at)) : '-' ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editWarehouse<?= $w->id ?>">Edit</button>
                                        <button class="btn btn-danger btn-sm btn-delete" data-id="<?= $w->id ?>">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="15" class="text-center">No warehouses found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- ================= ADD WAREHOUSE MODAL ================= -->
<div class="modal fade" id="addWarehouse">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="<?= base_url('warehouse/store') ?>" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5>Add Warehouse</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control">
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

<!-- ================= EDIT WAREHOUSE MODALS ================= -->
<?php if(!empty($warehouses) && is_array($warehouses)): ?>
    <?php foreach ($warehouses as $w): ?>
        <div class="modal fade" id="editWarehouse<?= $w->id ?>">
            <div class="modal-dialog modal-dialog-centered">
                <form method="post" action="<?= base_url('warehouse/update/'.$w->id) ?>" class="modal-content" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5>Edit Warehouse</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" value="<?= $w->name ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Contact Person</label>
                            <input type="text" name="contact_person" value="<?= $w->contact_person ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" value="<?= $w->phone ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" value="<?= $w->email ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control"><?= $w->description ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control">
                            <?php if($w->image): ?>
                                <img src="<?= base_url('uploads/warehouses/'.$w->image) ?>" width="80">
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="1" <?= isset($w->status) && $w->status ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= isset($w->status) && !$w->status ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php $this->load->view('partials/vendor-scripts'); ?>

<script>
    // Flash auto hide
    setTimeout(() => document.querySelectorAll('.flash-msg').forEach(e => e.remove()), 3000);

    // Delete confirm
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.onclick = () => {
            if(confirm('Delete this warehouse?')) {
                window.location.href = '<?= base_url("warehouse/delete/") ?>' + btn.dataset.id;
            }
        }
    });
</script>
