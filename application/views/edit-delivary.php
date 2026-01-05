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
    </style>
</head>
<body>
    <?php $this->load->view('partials/body') ?>
    <div class="main-wrapper">
        <?php $this->load->view('partials/menu') ?>
       
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="add-item d-flex">
                        <div class="page-title">
                            <h4>Edit Delivery Boy</h4>
                            <h6>Update delivery boy details</h6>
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
               
                <!-- Flash Messages & Validation Errors -->
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

                <!-- Edit Form -->
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo base_url('home/updateDelivary/' . $deliveryboy['id']); ?>" 
                              method="post" 
                              enctype="multipart/form-data" 
                              id="deliveryForm">
                           
                            <!-- Profile Photo -->
                            <div class="text-center mb-4">
                                <div class="profile-pic-container" onclick="document.getElementById('photo').click();">
                                    <?php if(!empty($deliveryboy['photo'])): ?>
                                        <img src="<?php echo base_url($deliveryboy['photo']); ?>"
                                             id="profile-pic-preview" class="profile-pic">
                                        <div id="upload-placeholder" class="upload-placeholder" style="display: none;">
                                            <i class="fas fa-user-circle"></i>
                                            <p class="mt-2 mb-0">Click to upload photo</p>
                                        </div>
                                    <?php else: ?>
                                        <img src="" id="profile-pic-preview" class="profile-pic" style="display: none;">
                                        <div id="upload-placeholder" class="upload-placeholder">
                                            <i class="fas fa-user-circle"></i>
                                            <p class="mt-2 mb-0">Click to upload photo</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <input type="file" name="photo" id="photo" accept="image/*" hidden>
                               
                                <?php if(!empty($deliveryboy['photo'])): ?>
                                    <button type="button" id="remove-photo-btn" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash me-1"></i> Remove Photo
                                    </button>
                                    <input type="hidden" name="remove_photo" id="remove-photo-input" value="0">
                                <?php endif; ?>
                               
                                <small class="text-muted d-block mt-2">
                                    Allowed: JPG, PNG, GIF, WebP (Max 2MB)
                                </small>
                            </div>

                            <!-- Basic Information -->
                            <div class="form-section">
                                <h5><i class="fas fa-user me-2"></i>Basic Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">First Name</label>
                                        <input type="text" name="first_name" class="form-control"
                                               value="<?php echo set_value('first_name', $deliveryboy['first_name']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="last_name" class="form-control"
                                               value="<?php echo set_value('last_name', $deliveryboy['last_name']); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Mobile Number</label>
                                        <input type="text" name="mobile" class="form-control"
                                               value="<?php echo set_value('mobile', $deliveryboy['mobile']); ?>"
                                               pattern="[0-9]{10}" maxlength="10" required>
                                        <small class="text-muted">10 digits only</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control"
                                               value="<?php echo set_value('email', $deliveryboy['email']); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Delivery Boy Code</label>
                                        <input type="text" name="deliveryboy_code" class="form-control"
                                               value="<?php echo set_value('deliveryboy_code', $deliveryboy['deliveryboy_code']); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-select">
                                            <option value="">Select Gender</option>
                                            <option value="Male" <?php echo set_select('gender', 'Male', $deliveryboy['gender'] == 'Male'); ?>>Male</option>
                                            <option value="Female" <?php echo set_select('gender', 'Female', $deliveryboy['gender'] == 'Female'); ?>>Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" name="dob" class="form-control"
                                               value="<?php echo set_value('dob', $deliveryboy['dob']); ?>">
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
                                            <option value="Bike" <?php echo set_select('vehicle_type', 'Bike', $deliveryboy['vehicle_type'] == 'Bike'); ?>>Bike</option>
                                            <option value="Bicycle" <?php echo set_select('vehicle_type', 'Bicycle', $deliveryboy['vehicle_type'] == 'Bicycle'); ?>>Bicycle</option>
                                            <option value="Car" <?php echo set_select('vehicle_type', 'Car', $deliveryboy['vehicle_type'] == 'Car'); ?>>Car</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Vehicle Number</label>
                                        <input type="text" name="vehicle_number" class="form-control"
                                               value="<?php echo set_value('vehicle_number', $deliveryboy['vehicle_number']); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Joining Date</label>
                                        <input type="date" name="joining_date" class="form-control"
                                               value="<?php echo set_value('joining_date', $deliveryboy['joining_date']); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="form-section">
                                <h5><i class="fas fa-map-marker-alt me-2"></i>Address Information</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control" rows="3"><?php echo set_value('address', $deliveryboy['address']); ?></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" class="form-control"
                                               value="<?php echo set_value('city', $deliveryboy['city']); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pincode</label>
                                        <input type="text" name="pincode" class="form-control"
                                               value="<?php echo set_value('pincode', $deliveryboy['pincode']); ?>"
                                               pattern="[0-9]{6}" maxlength="6">
                                        <small class="text-muted">6 digits only</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Change Password -->
                            <div class="form-section">
                                <h5><i class="fas fa-lock me-2"></i>Change Password (Optional)</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" name="password" class="form-control" minlength="6">
                                        <small class="text-muted">Leave blank to keep current password</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" name="confirm_password" class="form-control" minlength="6">
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
                                            <option value="1" <?php echo set_select('status', '1', $deliveryboy['status'] == 1); ?>>Active</option>
                                            <option value="0" <?php echo set_select('status', '0', $deliveryboy['status'] == 0); ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="text-end mt-4">
                                <a href="<?php echo base_url('home/delivarylist'); ?>" class="btn btn-secondary me-2">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update Delivery Boy
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
       <?php $this->load->view('partials/theme-settings') ?>
    <?php $this->load->view('partials/vendor-scripts') ?>
    <script>
        feather.replace();

        // Photo preview and validation
        document.getElementById('photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPG, PNG, GIF, WebP)');
                    this.value = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    alert('Image size should be less than 2MB');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('profile-pic-preview');
                    img.src = e.target.result;
                    img.style.display = 'block';
                    document.getElementById('upload-placeholder').style.display = 'none';

                    const removeBtn = document.getElementById('remove-photo-btn');
                    if (removeBtn) removeBtn.style.display = 'none';

                    const removeInput = document.getElementById('remove-photo-input');
                    if (removeInput) removeInput.value = '0';
                };
                reader.readAsDataURL(file);
            }
        });

        // Remove photo (existing)
        <?php if(!empty($deliveryboy['photo'])): ?>
        document.getElementById('remove-photo-btn').addEventListener('click', function() {
            if(confirm('Are you sure you want to remove this photo?')) {
                document.getElementById('remove-photo-input').value = '1';
                document.getElementById('profile-pic-preview').style.display = 'none';
                document.getElementById('upload-placeholder').style.display = 'flex';
                this.style.display = 'none';
            }
        });
        <?php endif; ?>

        // Form validation
        document.getElementById('deliveryForm').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]');
            const confirmPassword = document.querySelector('input[name="confirm_password"]');
            if (password.value && password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }

            const mobile = document.querySelector('input[name="mobile"]');
            if (!/^\d{10}$/.test(mobile.value)) {
                e.preventDefault();
                alert('Please enter a valid 10-digit mobile number!');
                mobile.focus();
                return false;
            }

            const pincode = document.querySelector('input[name="pincode"]');
            if (pincode.value && !/^\d{6}$/.test(pincode.value)) {
                e.preventDefault();
                alert('Please enter a valid 6-digit pincode!');
                pincode.focus();
                return false;
            }
        });

        // Live input restrictions
        document.querySelector('input[name="mobile"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });

        document.querySelector('input[name="pincode"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });
    </script>
</body>
</html>