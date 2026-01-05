<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $this->load->view('partials/title-meta'); ?>
<?php $this->load->view('partials/head-css'); ?>

<div class="main-wrapper">
<?php $this->load->view('partials/menu'); ?>

<div class="page-wrapper">
<div class="content">

<!-- ================= FLASH MESSAGES ================= -->
<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger flash-msg"><?= $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<!-- ================= PAGE HEADER ================= -->
<div class="page-header d-flex justify-content-between align-items-center mb-3">
    <h4>Villages List</h4>

    <div class="d-flex gap-2 align-items-center">

        <!-- SEARCH -->
        <form method="get"
              action="<?= base_url('villages') ?>"
              class="d-flex align-items-center"
              style="gap:6px; margin-bottom:0;">

            <input type="text"
                   name="keyword"
                   class="form-control form-control-sm"
                   style="width:220px"
                   placeholder="Search (V/M/D/S/C)"
                   value="<?= $this->input->get('keyword') ?>">

            <button class="btn btn-dark btn-sm">Search</button>
        </form>

        <!-- EXPORT -->
        <a href="<?= base_url('villages/export_excel') ?>" class="btn btn-success btn-sm">
            Export Excel
        </a>

        <!-- ADD -->
        <a href="javascript:void(0);"
           class="btn btn-warning btn-sm text-white"
           data-bs-toggle="modal"
           data-bs-target="#addVillage">
            Add Village
        </a>

    </div>
</div>

<!-- ================= TABLE ================= -->
<table class="table table-striped datatable">
<thead>
<tr>
    <th>S.No</th>
    <th>Village</th>
    <th>Mandal</th>
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

<tbody>
<?php foreach ($villages as $i => $v): ?>
<tr>
    <td><?= $i + 1 ?></td>
    <td><?= $v->village_name ?></td>
    <td><?= $v->mandal_name ?></td>
    <td><?= $v->district_name ?></td>
    <td><?= $v->state_name ?></td>
    <td><?= $v->country_name ?></td>

    <td>
        <a href="<?= base_url('villages/toggle_status/'.$v->id) ?>"
           class="badge <?= $v->status ? 'bg-success' : 'bg-danger' ?>">
            <?= $v->status ? 'Active' : 'Inactive' ?>
        </a>
    </td>

    <td><?= $v->created_by ?? '-' ?></td>
    <td><?= $v->updated_by ?? '-' ?></td>
    <td><?= $v->deleted_by ?? '-' ?></td>

    <td><?= $v->created_at ? date('d-m-Y', strtotime($v->created_at)) : '-' ?></td>
    <td><?= $v->updated_at ? date('d-m-Y', strtotime($v->updated_at)) : '-' ?></td>
    <td><?= $v->deleted_at ? date('d-m-Y', strtotime($v->deleted_at)) : '-' ?></td>

    <td>
        <button class="btn btn-warning btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#editVillage<?= $v->id ?>">
            Edit
        </button>

        <a href="<?= base_url('villages/delete/'.$v->id) ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Are you sure you want to delete this village?')">
   Delete
</a>

    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div>
</div>
</div>

<?php
/* ================= DROPDOWN HELPER ================= */
function dropdown($label,$name,$id,$list,$text,$dataKey=null,$selected=null){
    echo "<div class='mb-3'>
        <label>$label</label>
        <select name='$name' id='$id' class='form-control' required>
        <option value=''>Select $label</option>";
    foreach($list as $row){
        $data = $dataKey ? "data-$dataKey='{$row->$dataKey}'" : '';
        $sel  = ($selected==$row->id)?'selected':'';
        echo "<option value='{$row->id}' $data $sel>{$row->$text}</option>";
    }
    echo "</select></div>";
}
?>

<!-- ================= ADD MODAL ================= -->
<div class="modal fade" id="addVillage">
<div class="modal-dialog modal-dialog-centered">
<form method="post" action="<?= base_url('villages/store') ?>" class="modal-content">

<div class="modal-header">
    <h5>Add Village</h5>
    <button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <input type="text" name="village_name" class="form-control mb-3" placeholder="Village Name" required>

    <?php
    dropdown('Country','country_id','country_add',$countries,'country_name');
    dropdown('State','state_id','state_add',$states,'state_name','country_id');
    dropdown('District','district_id','district_add',$districts,'district_name','state_id');
    dropdown('Mandal','mandal_id','mandal_add',$mandals,'mandal_name','district_id');
    ?>

    <select name="status" class="form-control">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
</div>

<div class="modal-footer">
    <button class="btn btn-success">Save</button>
</div>

</form>
</div>
</div>

<?php foreach ($villages as $v): ?>
<div class="modal fade" id="editVillage<?= $v->id ?>">
<div class="modal-dialog modal-dialog-centered">
<form method="post" action="<?= base_url('villages/update/'.$v->id) ?>" class="modal-content">

<div class="modal-header">
    <h5>Edit Village</h5>
    <button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <input type="text" name="village_name" value="<?= $v->village_name ?>" class="form-control mb-3" required>

    <?php
    dropdown('Country','country_id','country_edit_'.$v->id,$countries,'country_name',null,$v->country_id);
    dropdown('State','state_id','state_edit_'.$v->id,$states,'state_name','country_id',$v->state_id);
    dropdown('District','district_id','district_edit_'.$v->id,$districts,'district_name','state_id',$v->district_id);
    dropdown('Mandal','mandal_id','mandal_edit_'.$v->id,$mandals,'mandal_name','district_id',$v->mandal_id);
    ?>

    <select name="status" class="form-control">
        <option value="1" <?= $v->status?'selected':'' ?>>Active</option>
        <option value="0" <?= !$v->status?'selected':'' ?>>Inactive</option>
    </select>
</div>

<div class="modal-footer">
    <button class="btn btn-success">Update</button>
</div>

</form>
</div>
</div>
<?php endforeach; ?>

<?php $this->load->view('partials/vendor-scripts'); ?>

<script>
// ================= FLASH AUTO HIDE =================
setTimeout(() => {
    document.querySelectorAll('.flash-msg').forEach(e => e.remove());
}, 2000);

// ================= DELETE (FIXED & WORKING) =================
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        if (confirm('Are you sure you want to delete this village?')) {
            window.location.href = "<?= base_url('villages/delete/') ?>" + id;
        }
    });
});
</script>
