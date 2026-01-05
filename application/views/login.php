<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('partials/title-meta'); ?>
</head>
<body class="account-page" style="margin:0; font-family: Arial, sans-serif; background-color: #000; min-height:100vh; position: relative;">

    <!-- Dark overlay -->
    <div style="position: fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.7); z-index:0;"></div>

    <div style="position: relative; z-index:1; display:flex; justify-content:center; align-items:center; min-height:100vh;">
        <div style="width:100%; max-width:400px; background:#111; padding:40px 30px; border-radius:12px; box-shadow:0 0 15px rgba(0,0,0,0.5); color:#fff; text-align:center;">
            
            <!-- Logo -->
            <img src="<?= base_url('assets/img/logo-white.png') ?>" alt="Logo" style="max-width:150px; margin-bottom:20px;">

            <h2 style="margin-bottom:20px;">Login</h2>

            <!-- Flash Messages -->
            <?php if($this->session->flashdata('error')): ?>
                <div style="color:#ff4b5c; margin-bottom:10px;"><?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>

            <?php echo validation_errors('<div style="color:#ff4b5c; margin-bottom:10px;">','</div>'); ?>

            <form method="post" action="<?= base_url('users/login') ?>">
                <input type="text" name="identifier" placeholder="Email or Phone" value="<?= set_value('identifier') ?>" 
                       style="width:100%; padding:12px; margin:10px 0; border-radius:6px; border:none; font-size:14px; color:#fff; background:#222;" />
                <input type="password" name="password" placeholder="Password" 
                       style="width:100%; padding:12px; margin:10px 0; border-radius:6px; border:none; font-size:14px; color:#fff; background:#222;" />
                <button type="submit" 
                        style="width:100%; padding:12px; margin-top:15px; background-color:#ff4b5c; color:#fff; border:none; border-radius:6px; font-size:16px; cursor:pointer;">
                    Login
                </button>
            </form>

            <p style="margin-top:15px;">
                Don't have an account? <a href="<?= base_url('users/register-3') ?>" style="color:#ff4b5c; text-decoration:none;">Register</a>
            </p>
        </div>
    </div>

</body>
</html>
