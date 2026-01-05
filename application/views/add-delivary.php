<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('partials/title-meta') ?>
    <?php $this->load->view('partials/head-css') ?>

    <style>
        .profile-pic-container {
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
            border: 3px dashed #dee2e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
            background: #f8f9fa;
            position: relative;
            transition: all 0.3s ease;
        }
        .profile-pic-container:hover {
            border-color: #0d6efd;
            background: #e7f1ff;
        }
        .profile-pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        .upload-placeholder {
            text-align: center;
            color: #6c757d;
        }
        .upload-placeholder i {
            font-size: 4rem;
        }
        .required:after {
            content: " *";
            color: red;
        }
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 1px solid #e9ecef;
        }
        .form-section h5 {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: #495057;
            font-weight: 600;
        }
        #remove-photo-btn {
            max-width: 180px;
            margin: 10px auto 0;
            display: block;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }
        .table-top-head {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .table-top-head li {
            display: inline-block;
        }
        .add-item.d-flex {
            flex-direction: column;
        }
    </style>
</head>
<body>
    <?php $this->load->view('partials/body') ?>

    <div class="main-wrapper">
        <?php $this->load->view('partials/menu') ?>

        <div class="page-wrapper">
            <div class="content">

                <!-- Flash Messages -->
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php echo validation_errors('<div class="alert alert-danger alert-dismissible fade show" role="alert">', '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'); ?>

                <!-- Page Header -->
                <div class="page-header">
                    <div class="add-item d-flex">
                        <div class="page-title">
                            <h4>Add Delivery Boy</h4>
                            <h6>Register a new delivery boy</h6>
                        </div>
                    </div>
                    <ul class="table-top-head">
                        <li>
                            <a href="<?php echo base_url('home/delivarylist'); ?>" class="btn btn-secondary">
                                <i data-feather="arrow-left" class="me-2"></i>Back to List
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Form Content -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="<?php echo base_url('home/saveDelivary'); ?>" method="post" enctype="multipart/form-data" id="deliveryForm">

                                    <!-- Profile Photo Section -->
                                    <div class="text-center mb-4">
                                        <div class="profile-pic-container" onclick="document.getElementById('photo').click();">
                                            <img src="" id="profile-pic-preview" class="profile-pic" style="display: none;">
                                            <div class="upload-placeholder" id="upload-placeholder">
                                                <i class="fas fa-camera"></i>
                                                <p class="mt-2 mb-0">Click to upload photo</p>
                                            </div>
                                        </div>
                                        <input type="file" name="photo" id="photo" accept="image/*" hidden>
                                        <small class="text-muted d-block">Allowed: JPG, PNG, GIF, WebP (Max 2MB)</small>
                                    </div>

                                    <!-- Basic Information -->
                                    <div class="form-section">
                                        <h5><i class="fas fa-user me-2"></i>Basic Information</h5>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label required">First Name</label>
                                                <input type="text" name="first_name" class="form-control" value="<?php echo set_value('first_name'); ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Last Name</label>
                                                <input type="text" name="last_name" class="form-control" value="<?php echo set_value('last_name'); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label required">Mobile Number</label>
                                                <input type="text" name="mobile" class="form-control" value="<?php echo set_value('mobile'); ?>" pattern="[0-9]{10}" maxlength="10" required>
                                                <small class="text-muted">10 digits only</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Delivery Boy Code</label>
                                                <input type="text" name="deliveryboy_code" class="form-control" value="<?php echo set_value('deliveryboy_code'); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Gender</label>
                                                <select name="gender" class="form-select">
                                                    <option value="">Select Gender</option>
                                                    <option value="Male" <?php echo set_select('gender', 'Male'); ?>>Male</option>
                                                    <option value="Female" <?php echo set_select('gender', 'Female'); ?>>Female</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Date of Birth</label>
                                                <input type="date" name="dob" class="form-control" value="<?php echo set_value('dob'); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Vehicle Information -->
                                    <div class="form-section">
                                        <h5><i class="fas fa-motorcycle me-2"></i>Vehicle Information</h5>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Vehicle Type</label>
                                                <select name="vehicle_type" class="form-select">
                                                    <option value="">Select Vehicle Type</option>
                                                    <option value="Bike" <?php echo set_select('vehicle_type', 'Bike'); ?>>Bike</option>
                                                    <option value="Bicycle" <?php echo set_select('vehicle_type', 'Bicycle'); ?>>Bicycle</option>
                                                    <option value="Car" <?php echo set_select('vehicle_type', 'Car'); ?>>Car</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Vehicle Number</label>
                                                <input type="text" name="vehicle_number" class="form-control" value="<?php echo set_value('vehicle_number'); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Joining Date</label>
                                                <input type="date" name="joining_date" class="form-control" value="<?php echo set_value('joining_date', date('Y-m-d')); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Address Information -->
                                    <div class="form-section">
                                        <h5><i class="fas fa-map-marker-alt me-2"></i>Address Information</h5>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Address</label>
                                                <textarea name="address" class="form-control" rows="3"><?php echo set_value('address'); ?></textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">City</label>
                                                <input type="text" name="city" class="form-control" value="<?php echo set_value('city'); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Pincode</label>
                                                <input type="text" name="pincode" class="form-control" value="<?php echo set_value('pincode'); ?>" pattern="[0-9]{6}" maxlength="6">
                                                <small class="text-muted">6 digits only</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Login Credentials -->
                                    <div class="form-section">
                                        <h5><i class="fas fa-lock me-2"></i>Login Credentials</h5>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label required">Password</label>
                                                <input type="password" name="password" class="form-control" required minlength="6">
                                                <small class="text-muted">Minimum 6 characters</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label required">Confirm Password</label>
                                                <input type="password" name="confirm_password" class="form-control" required minlength="6">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="form-section">
                                        <h5><i class="fas fa-toggle-on me-2"></i>Status</h5>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Account Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="1" <?php echo set_select('status', '1', TRUE); ?>>Active</option>
                                                    <option value="0" <?php echo set_select('status', '0'); ?>>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="text-end mt-4">
                                        <a href="<?php echo base_url('home/delivarylist'); ?>" class="btn btn-secondary me-2">
                                            <i class="fas fa-times me-1"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>Save Delivery Boy
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- end .content -->
        </div> <!-- end .page-wrapper -->
    </div> <!-- end .main-wrapper -->

    <!-- JavaScript -->
   <?php $this->load->view('partials/theme-settings') ?>
    <?php $this->load->view('partials/vendor-scripts') ?>

    <script>
        // Profile Picture Preview & Remove Button
        document.getElementById('photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image (JPG, PNG, GIF, WebP)');
                this.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('Image size must be less than 2MB');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profile-pic-preview');
                preview.src = e.target.result;
                preview.style.display = 'block';
                document.getElementById('upload-placeholder').style.display = 'none';

                // Add Remove Button
                if (!document.getElementById('remove-photo-btn')) {
                    const removeBtn = document.createElement('button');
                    removeBtn.id = 'remove-photo-btn';
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-danger btn-sm';
                    removeBtn.innerHTML = '<i class="fas fa-times me-1"></i>Remove Photo';
                    removeBtn.onclick = function() {
                        document.getElementById('photo').value = '';
                        preview.src = '';
                        preview.style.display = 'none';
                        document.getElementById('upload-placeholder').style.display = 'flex';
                        this.remove();
                    };
                    document.querySelector('.text-center.mb-4').appendChild(removeBtn);
                }
            };
            reader.readAsDataURL(file);
        });

        // Form Validation
        document.getElementById('deliveryForm').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]');
            const confirm = document.querySelector('input[name="confirm_password"]');
            if (password.value !== confirm.value) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }

            const mobile = document.querySelector('input[name="mobile"]');
            if (!/^\d{10}$/.test(mobile.value)) {
                e.preventDefault();
                alert('Please enter a valid 10-digit mobile number');
                return;
            }

            const pincode = document.querySelector('input[name="pincode"]');
            if (pincode.value && !/^\d{6}$/.test(pincode.value)) {
                e.preventDefault();
                alert('Please enter a valid 6-digit pincode');
                return;
            }
        });

        // Input Restrictions
        document.querySelector('input[name="mobile"]').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 10);
        });
        document.querySelector('input[name="pincode"]').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 6);
        });
    </script>
</body>
</html>