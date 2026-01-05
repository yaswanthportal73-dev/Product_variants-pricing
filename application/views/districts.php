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

            <!-- Flash messages -->
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success flash-msg"><?= $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger flash-msg"><?= $this->session->flashdata('error'); ?></div>
            <?php endif; ?>

            <!-- Page Header + Search + Buttons -->
            <div class="page-header d-flex justify-content-between align-items-center mb-3">
                <h4>Districts List</h4>
                <div class="d-flex gap-2 align-items-center">
                    <!-- Search Box -->
                    <form method="get" action="<?= base_url('districts') ?>" class="d-flex gap-2">
                        <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Search (Ex: Anantapuram)" value="<?= $this->input->get('keyword') ?>">
                        <button class="btn btn-secondary btn-sm">Search</button>
                    </form>

                    <!-- Export / Add -->
                    <a href="<?= base_url('districts/export_excel') ?>" class="btn btn-success btn-sm">Export Excel</a>
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDistrict">Add District</a>
                </div>
            </div>

            <!-- Districts Table -->
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>District Name</th>
                        <th>State</th>
                        <th>Country</th>
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
                    <?php $i=1; foreach($districts as $d): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $d->district_name ?></td>
                            <td><?= $d->state_name ?></td>
                            <td><?= $d->country_name ?></td>
                            <td>
                                <a href="<?= base_url('districts/toggle_status/'.$d->id) ?>" class="btn btn-sm <?= $d->status?'btn-success':'btn-danger' ?>">
                                    <?= $d->status?'Active':'Inactive' ?>
                                </a>
                            </td>
                            <td><?= $d->created_by ?? '-' ?></td>
                            <td><?= $d->updated_by ?? '-' ?></td>
                            <td><?= $d->deleted_by ?? '-' ?></td>
                            <td><?= $d->created_at ? date('d-m-Y H:i', strtotime($d->created_at)) : '-' ?></td>
                            <td><?= $d->updated_at ? date('d-m-Y H:i', strtotime($d->updated_at)) : '-' ?></td>
                            <td><?= $d->deleted_at ? date('d-m-Y H:i', strtotime($d->deleted_at)) : '-' ?></td>
                            <td>
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editDistrictModal<?= $d->id ?>">Edit</a> |
                                <a href="<?= base_url('districts/delete/'.$d->id) ?>" onclick="return confirm('Delete this district?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<!-- Add District Modal -->
<div class="modal fade" id="addDistrict" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="<?= base_url('districts/store') ?>" class="modal-content">
            <div class="modal-header">
                <h5>Add District</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>District Name</label>
                    <input type="text" name="district_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Country</label>
                    <select name="country_id" id="country_add" class="form-control" required>
                        <option value="">Select Country</option>
                        <?php foreach($countries as $c): ?>
                            <option value="<?= $c->id ?>"><?= $c->country_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>State</label>
                    <select name="state_id" id="state_add" class="form-control" required>
                        <option value="">Select State</option>
                        <?php foreach($states as $s): ?>
                            <option value="<?= $s->id ?>" data-country="<?= $s->country_id ?>"><?= $s->state_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1" selected>Active</option>
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

<!-- Edit District Modals -->
<?php foreach($districts as $d): ?>
<div class="modal fade" id="editDistrictModal<?= $d->id ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="<?= base_url('districts/update/'.$d->id) ?>" class="modal-content">
            <div class="modal-header">
                <h5>Edit District</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>District Name</label>
                    <input type="text" name="district_name" value="<?= $d->district_name ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Country</label>
                    <select name="country_id" class="form-control country_edit" data-district="<?= $d->id ?>" required>
                        <option value="">Select Country</option>
                        <?php foreach($countries as $c): ?>
                            <option value="<?= $c->id ?>" <?= $d->country_id==$c->id?'selected':'' ?>><?= $c->country_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>State</label>
                    <select name="state_id" class="form-control state_edit" data-district="<?= $d->id ?>" required>
                        <option value="">Select State</option>
                        <?php foreach($states as $s): ?>
                            <option value="<?= $s->id ?>" data-country="<?= $s->country_id ?>" <?= $d->state_id==$s->id?'selected':'' ?>><?= $s->state_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1" <?= $d->status?'selected':'' ?>>Active</option>
                        <option value="0" <?= !$d->status?'selected':'' ?>>Inactive</option>
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
    feather.replace();

    // Auto-hide flash messages
    setTimeout(()=>{
        const msg=document.querySelector('.flash-msg'); 
        if(msg){ msg.style.transition='opacity 0.5s'; msg.style.opacity='0'; setTimeout(()=>msg.remove(),500); }
    }, 3000);

    // Filter states by country
    const filterStates = (countrySelect, stateSelect) => {
        const countryId = countrySelect.value;
        [...stateSelect.options].forEach(opt => {
            opt.style.display = (opt.value==='' || opt.dataset.country===countryId) ? 'block' : 'none';
        });
        if(stateSelect.value && stateSelect.options[stateSelect.selectedIndex].style.display==='none'){
            stateSelect.value='';
        }
    };

    // Add modal
    const countryAdd = document.getElementById('country_add');
    const stateAdd = document.getElementById('state_add');
    countryAdd.addEventListener('change', ()=>filterStates(countryAdd, stateAdd));
    document.getElementById('addDistrict').addEventListener('show.bs.modal', ()=>filterStates(countryAdd, stateAdd));

    // Edit modals
    document.querySelectorAll('.country_edit').forEach(select=>{
        const modal = select.closest('.modal');
        const stateSelect = modal.querySelector('.state_edit');
        modal.addEventListener('show.bs.modal', ()=>filterStates(select,stateSelect));
        select.addEventListener('change', ()=>filterStates(select,stateSelect));
    });
</script>
</body>
</html>
