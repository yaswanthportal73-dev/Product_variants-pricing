<html>
<head><title>User List</title></head>
<body>
<h2>All Users</h2>
<?php if($this->session->flashdata('success')) echo $this->session->flashdata('success'); ?>
<table border="1">
    <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Registered At</th>
        <th>Action</th>
    </tr>
    <?php foreach($users as $user): ?>
    <tr>
        <td><?= $user->id ?></td>
        <td><?= $user->user_id ?></td>
        <td><?= $user->name ?></td>
        <td><?= $user->email ?></td>
        <td><?= $user->phone ?></td>
        <td><?= $user->created_at ?></td>
        <td><a href="<?= base_url('users/delete/'.$user->id) ?>">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
