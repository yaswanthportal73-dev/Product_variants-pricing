<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();
$CI->load->database();
$CI->load->library('session');

// ===================== DELETE =====================
if (isset($_GET['delete'])) {
    $CI->db->delete('employees', ['id' => $_GET['delete']]);
    redirect('employees-list');
}

// ===================== ADD / UPDATE =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name'   => $_POST['name'],
        'email'  => $_POST['email'],
        'phone'  => $_POST['phone'],
        'role'   => $_POST['role'],
        'status' => $_POST['status']
    ];

    if (!empty($_POST['password'])) {
        $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $data['plain_password'] = $_POST['password'];
    }

    if (empty($_POST['id'])) {
        $data['created_by'] = $CI->session->userdata('user_id') ?? 1;
        $CI->db->insert('employees', $data);
    } else {
        $CI->db->where('id', $_POST['id'])->update('employees', $data);
    }

    redirect('employees-list');
}

// ===================== EDIT =====================
$edit = null;
if (isset($_GET['edit'])) {
    $edit = $CI->db->get_where('employees', ['id' => $_GET['edit']])->row();
}

// ===================== FILTERS =====================
$filter_name   = $_GET['filter_name'] ?? '';
$filter_role   = $_GET['filter_role'] ?? '';
$filter_status = $_GET['filter_status'] ?? '';

// ===================== EMPLOYEE LIST =====================
$CI->db->select('employees.*, users.name AS created_by_name');
$CI->db->from('employees');
$CI->db->join('users', 'users.id = employees.created_by', 'left');

if ($filter_name)   $CI->db->like('employees.name', $filter_name);
if ($filter_role)   $CI->db->like('employees.role', $filter_role);
if ($filter_status) $CI->db->where('employees.status', $filter_status);

$employees = $CI->db->get()->result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('partials/title-meta'); ?>
    <?php $this->load->view('partials/head-css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .action-btn { width:36px; height:36px; display:flex; align-items:center; justify-content:center; border-radius:50%; color:#fff; margin-right:5px; }
        .edit-btn{ background:#0d6efd; }
        .delete-btn{ background:#dc3545; }
        .eye-icon{ position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:#6c757d; }
        .modal-backdrop-custom{ background:#0006; }
    </style>
</head>
<body>
<?php $this->load->view('partials/body'); ?>
<div class="main-wrapper">
    <?php $this->load->view('partials/menu'); ?>

    <div class="page-wrapper">
        <div class="container-fluid py-4">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Employees</h3>
                <button class="btn btn-success" onclick="openModal()">+ Add Employee</button>
            </div>

            <!-- FILTER -->
            <form method="get" class="row g-2 mb-4">
                <div class="col-md-3">
                    <input class="form-control" name="filter_name" placeholder="Name" value="<?= htmlspecialchars($filter_name) ?>">
                </div>
                <div class="col-md-3">
                    <input class="form-control" name="filter_role" placeholder="Role" value="<?= htmlspecialchars($filter_role) ?>">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="filter_status">
                        <option value="">All Status</option>
                        <option value="Active" <?= $filter_status=='Active'?'selected':'' ?>>Active</option>
                        <option value="Inactive" <?= $filter_status=='Inactive'?'selected':'' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex">
                    <button class="btn btn-primary me-2">Filter</button>
                    <a href="<?= base_url('employees-list') ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>

            <!-- EMPLOYEE TABLE -->
            <div class="card shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Password</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Updated By</th>
                                <th>Updated At</th>
                                <th>Deleted By</th>
                                <th>Deleted At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($employees): $i=1; foreach($employees as $e): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($e->name) ?></td>
                                <td><?= htmlspecialchars($e->email) ?></td>
                                <td><?= htmlspecialchars($e->phone) ?></td>
                                <td><?= htmlspecialchars($e->plain_password) ?></td>
                                <td><?= htmlspecialchars($e->role) ?></td>
                                <td><span class="badge <?= $e->status=='Active'?'bg-success':'bg-danger' ?>"><?= $e->status ?></span></td>
                                <td><?= $e->created_by_name ?? 'Admin' ?></td>
                                <td><?= $e->created_at ?></td>
                                <td><?= $e->updated_by ?></td>
                                <td><?= $e->updated_at ?></td>
                                <td><?= $e->deleted_by ?></td>
                                <td><?= $e->deleted_at ?></td>
                                <td>
                                    <a href="?edit=<?= $e->id ?>" class="action-btn edit-btn"><i class="fa fa-edit"></i></a>
                                    <a href="?delete=<?= $e->id ?>" class="action-btn delete-btn" onclick="return confirm('Delete employee?')"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="14" class="text-center">No records found</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="empModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post" autocomplete="off" onsubmit="return checkPassword()">
        <div class="modal-header">
            <h5 class="modal-title"><?= isset($edit)?'Edit':'Add' ?> Employee</h5>
            <button type="button" class="btn-close" onclick="closeModal()"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id" value="<?= isset($edit)?$edit->id:'' ?>">
            <div class="mb-2"><input class="form-control" type="text" name="name" placeholder="Name" required value="<?= isset($edit)?htmlspecialchars($edit->name):'' ?>"></div>
            <div class="mb-2"><input class="form-control" type="email" name="email" placeholder="Email" required value="<?= isset($edit)?htmlspecialchars($edit->email):'' ?>"></div>
            <div class="mb-2"><input class="form-control" type="text" name="phone" placeholder="Phone" required value="<?= isset($edit)?htmlspecialchars($edit->phone):'' ?>"></div>
            <div class="mb-2"><input class="form-control" type="text" name="role" placeholder="Role" required value="<?= isset($edit)?htmlspecialchars($edit->role):'' ?>"></div>
            <div class="mb-2 position-relative">
                <input class="form-control" type="password" id="password" name="password" placeholder="Password">
                <i class="fa fa-eye eye-icon" onclick="togglePassword('password')"></i>
            </div>
            <div class="mb-2 position-relative">
                <input class="form-control" type="password" id="confirm_password" placeholder="Confirm Password">
                <i class="fa fa-eye eye-icon" onclick="togglePassword('confirm_password')"></i>
            </div>
            <div class="mb-2">
                <select class="form-select" name="status" required>
                    <option value="Active" <?= (isset($edit)&&$edit->status=='Active')?'selected':'' ?>>Active</option>
                    <option value="Inactive" <?= (isset($edit)&&$edit->status=='Inactive')?'selected':'' ?>>Inactive</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openModal(){ new bootstrap.Modal(document.getElementById('empModal')).show(); }
function closeModal(){ window.location='<?= base_url("employees-list") ?>'; }
function togglePassword(id){ let x=document.getElementById(id); x.type = x.type==='password'?'text':'password'; }
function checkPassword(){
    let p=document.getElementById('password').value;
    let c=document.getElementById('confirm_password').value;
    if(p||c){ if(p!==c){ alert('Password & Confirm Password must match'); return false; } }
    return true;
}
<?php if(isset($edit)): ?>openModal();<?php endif; ?>
</script>

<?php $this->load->view('partials/vendor-scripts'); ?>
</body>
</html>
