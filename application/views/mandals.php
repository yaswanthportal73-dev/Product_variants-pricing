<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php $this->load->view('partials/title-meta'); ?>
<?php $this->load->view('partials/head-css'); ?>

<div class="main-wrapper">
    <?php $this->load->view('partials/menu'); ?>
    <div class="page-wrapper">
        <div class="content">

            <!-- Flash Messages -->
            <!-- <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success flash-msg"><?= $this->session->flashdata('success'); ?></div>
            <?php endif; ?> -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger flash-msg"><?= $this->session->flashdata('error'); ?></div>
            <?php endif; ?>

            <!-- Page Header + Search + Buttons -->
           <div class="page-header d-flex justify-content-between align-items-center mb-3">
                <h4>Mandals List</h4>
                <div class="d-flex gap-2 align-items-center">
            <!-- SEARCH -->
            <form method="get"
                  action="<?= base_url('mandals') ?>"
                  class="d-flex align-items-center"
                  style="gap:6px; margin-bottom:0;">

                <input type="text"
                       name="keyword"
                       class="form-control form-control-sm"
                       style="width:220px"
                       placeholder="Search (M/D/S/C)"
                       value="<?= $this->input->get('keyword') ?>">

                <button class="btn btn-dark btn-sm">
                    Search
                </button>
            </form>

            <!-- EXPORT -->
            <a href="<?= base_url('districts/export_excel') ?>"
               class="btn btn-success btn-sm">
                Export Excel
            </a>

            <!-- ADD -->
            <a href="javascript:void(0);"
               class="btn btn-warning btn-sm text-white"
               data-bs-toggle="modal"
               data-bs-target="#addMandal">
                Add Mandal
            </a>

        </div>
    </div>
</div>


            <!-- Mandals Table -->
            <table class="table table-striped datatable" id="mandalsTable">
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Mandal Name</th>
                        <th>District</th>
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
                <tbody id="mandalsBody">
                    <?php $i=1; foreach($mandals as $m): ?>
                    <tr data-id="<?= $m->id ?>">
                        <td class="row-num"><?= $i++ ?></td>
                        <td><?= $m->mandal_name ?></td>
                        <td><?= $m->district_name ?></td>
                        <td><?= $m->state_name ?></td>
                        <td><?= $m->country_name ?></td>
                        <td>
                            <a href="<?= base_url('mandals/toggle_status/'.$m->id) ?>" class="btn btn-sm <?= $m->status ? 'btn-success' : 'btn-danger' ?>">
                                <?= $m->status ? 'Active' : 'Inactive' ?>
                            </a>
                        </td>
                        <td><?= !empty($m->created_by) ? $m->created_by : '-' ?></td>
                        <td><?= !empty($m->updated_by) ? $m->updated_by : '-' ?></td>
                        <td><?= !empty($m->deleted_by) ? $m->deleted_by : '-' ?></td>
                        <td><?= isset($m->created_at) ? date('d-m-Y', strtotime($m->created_at)) : '-' ?></td>
                        <td><?= isset($m->updated_at) ? date('d-m-Y', strtotime($m->updated_at)) : '-' ?></td>
                        <td><?= isset($m->deleted_at) ? date('d-m-Y', strtotime($m->deleted_at)) : '-' ?></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editMandalModal<?= $m->id ?>">Edit</a>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $m->id ?>">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<!-- ADD MANDAL MODAL -->
<div class="modal fade" id="addMandal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="<?= base_url('mandals/store') ?>" class="modal-content">
            <div class="modal-header">
                <h5>Add Mandal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Mandal Name</label>
                    <input type="text" name="mandal_name" class="form-control" required>
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
                    <label>District</label>
                    <select name="district_id" id="district_add" class="form-control" required>
                        <option value="">Select District</option>
                        <?php foreach($districts as $d): ?>
                            <option value="<?= $d->id ?>" data-state="<?= $d->state_id ?>"><?= $d->district_name ?></option>
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

<!-- EDIT MANDAL MODALS -->
<?php foreach($mandals as $m): ?>
<div class="modal fade" id="editMandalModal<?= $m->id ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="<?= base_url('mandals/update/'.$m->id) ?>" class="modal-content">
            <div class="modal-header">
                <h5>Edit Mandal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Mandal Name</label>
                    <input type="text" name="mandal_name" value="<?= $m->mandal_name ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Country</label>
                    <select name="country_id" class="form-control country_edit" data-mandal="<?= $m->id ?>" required>
                        <option value="">Select Country</option>
                        <?php foreach($countries as $c): ?>
                            <option value="<?= $c->id ?>" <?= $m->country_id==$c->id?'selected':'' ?>><?= $c->country_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>State</label>
                    <select name="state_id" class="form-control state_edit" data-mandal="<?= $m->id ?>" required>
                        <option value="">Select State</option>
                        <?php foreach($states as $s): ?>
                            <option value="<?= $s->id ?>" data-country="<?= $s->country_id ?>" <?= $m->state_id==$s->id?'selected':'' ?>><?= $s->state_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>District</label>
                    <select name="district_id" class="form-control district_edit" data-mandal="<?= $m->id ?>" required>
                        <option value="">Select District</option>
                        <?php foreach($districts as $d): ?>
                            <option value="<?= $d->id ?>" data-state="<?= $d->state_id ?>" <?= $m->district_id==$d->id?'selected':'' ?>><?= $d->district_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1" <?= $m->status?'selected':'' ?>>Active</option>
                        <option value="0" <?= !$m->status?'selected':'' ?>>Inactive</option>
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
    // Auto-hide flash messages
    setTimeout(() => {
        const msg = document.querySelector('.flash-msg');
        if(msg) { msg.style.opacity = '0'; setTimeout(()=>msg.remove(), 500); }
    }, 300);

    // Delete button logic
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            if(confirm('Are you sure you want to delete this mandal?')){
                window.location.href = '<?= base_url("mandals/delete/") ?>' + id;
            }
        });
    });

    // Dependent dropdowns (Add/Edit)
    const countryAdd = document.getElementById('country_add');
    const stateAdd = document.getElementById('state_add');
    const districtAdd = document.getElementById('district_add');

    const filterAddStates = () => {
        const country_id = countryAdd.value;
        Array.from(stateAdd.options).forEach(opt => {
            opt.style.display = (opt.value=='' || opt.dataset.country==country_id) ? 'block' : 'none';
        });
        stateAdd.value='';
        filterAddDistricts();
    };
    const filterAddDistricts = () => {
        const state_id = stateAdd.value;
        Array.from(districtAdd.options).forEach(opt => {
            opt.style.display = (opt.value=='' || opt.dataset.state==state_id) ? 'block' : 'none';
        });
        districtAdd.value='';
    };
    countryAdd.addEventListener('change', filterAddStates);
    stateAdd.addEventListener('change', filterAddDistricts);
    document.getElementById('addMandal').addEventListener('show.bs.modal', filterAddStates);

    // Edit modal filtering
    document.querySelectorAll('.country_edit').forEach(select => {
        const modal = select.closest('.modal-content');
        const stateSelect = modal.querySelector('.state_edit');
        const districtSelect = modal.querySelector('.district_edit');

        const filterStates = () => {
            const country_id = select.value;
            Array.from(stateSelect.options).forEach(opt => {
                opt.style.display = (opt.value=='' || opt.dataset.country==country_id) ? 'block' : 'none';
            });
            if(stateSelect.value && stateSelect.options[stateSelect.selectedIndex].style.display==='none') stateSelect.value='';
            filterDistricts();
        };
        const filterDistricts = () => {
            const state_id = stateSelect.value;
            Array.from(districtSelect.options).forEach(opt => {
                opt.style.display = (opt.value=='' || opt.dataset.state==state_id) ? 'block' : 'none';
            });
            if(districtSelect.value && districtSelect.options[districtSelect.selectedIndex].style.display==='none') districtSelect.value='';
        };

        const modalEl = select.closest('.modal');
        modalEl.addEventListener('show.bs.modal', () => {
            filterStates();
            const firstState = Array.from(stateSelect.options).find(o=>o.style.display=='block' && o.value!='');
            if(firstState) stateSelect.value = firstState.value;
            filterDistricts();
            const firstDistrict = Array.from(districtSelect.options).find(o=>o.style.display=='block' && o.value!='');
            if(firstDistrict) districtSelect.value = firstDistrict.value;
        });

        select.addEventListener('change', filterStates);
        stateSelect.addEventListener('change', filterDistricts);
    });
</script>