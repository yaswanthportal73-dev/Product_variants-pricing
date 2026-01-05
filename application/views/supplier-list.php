<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('partials/title-meta') ?>
        <?php $this->load->view('partials/head-css') ?>
        <style>
            .section-card {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 20px;
                border: 1px solid #e9ecef;
                margin-bottom: 20px;
            }
            .section-title {
                color: #495057;
                font-weight: 600;
                font-size: 16px;
                padding-bottom: 8px;
                border-bottom: 2px solid #dee2e6;
                margin-bottom: 15px;
            }
            .modal-header {
                background: #f8f9fa;
                border-bottom: 1px solid #dee2e6;
            }
            .btn-close-sm {
                font-size: 0.6rem;
                padding: 0.25rem;
            }
            .badge-category {
                padding: 0.4rem 0.75rem;
                font-weight: 500;
                background: #e9ecef;
                color: #495057;
                border: 1px solid #dee2e6;
            }
            .payment-badge {
                background: #e7f5ff;
                color: #0a58ca;
                padding: 0.25rem 0.75rem;
                border-radius: 4px;
                font-size: 0.875rem;
            }
            .status-badge {
                padding: 0.25rem 0.75rem;
                border-radius: 4px;
                font-size: 0.875rem;
            }
            .status-active {
                background: #d1e7dd;
                color: #0f5132;
            }
            .status-inactive {
                background: #f8d7da;
                color: #842029;
            }
            .action-buttons a {
                margin: 0 3px;
                display: inline-block;
            }
            .avatar-xs {
                width: 32px;
                height: 32px;
            }
            .avatar-title {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 100%;
                font-size: 14px;
                font-weight: bold;
            }
            .search-item {
                margin-left: 50px;
                flex: 1;
                max-width: 400px;
            }
        </style>
    </head>
    <?php $this->load->view('partials/body') ?>
        <!-- <div id="global-loader">
            <div class="whirly-loader"> </div>
        </div> -->
        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <?php $this->load->view('partials/menu') ?>
            <div class="page-wrapper">
                <div class="content">
                    <!-- Flash Messages -->
                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $this->session->flashdata('success'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $this->session->flashdata('error'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $this->session->flashdata('errors'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="page-header">
                        <div class="add-item d-flex">
                            <div class="page-title">
                                <h4>Supplier List</h4>
                                <h6>Manage Your Suppliers</h6>
                            </div>
                        </div>

                        
                        <!-- Search Filter Section -->
                        <div class="search-item">
                            <div class="input-group" style="width: 300px;">
                                <input type="text" id="supplierSearch" class="form-control" placeholder="Search by supplier name...">
                                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                    <i data-feather="search" class="feather-sm"></i>
                                </button>
                                <button class="btn btn-outline-secondary" type="button" id="clearSearch" style="display: none;">
                                    <i data-feather="x" class="feather-sm"></i>
                                </button>
                            </div>
                        </div>
                        <!-- End of Search Filter Section -->
                        
                        <ul class="table-top-head">
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="<?php echo base_url(); ?>assets/img/icons/pdf.svg" alt="img"></a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="<?php echo base_url(); ?>assets/img/icons/excel.svg" alt="img"></a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><i data-feather="printer" class="feather-rotate-ccw"></i></a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" onclick="location.reload()"><i data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-chevron-up"></i></a>
                            </li>
                        </ul>
                        <div class="page-btn">
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                                <i data-feather="plus-circle" class="me-2"></i>Add New Supplier
                            </a>
                        </div>
                    </div>

                    <!-- Supplier List -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable" id="suppliersTable">
                                    <thead>
                                        <tr>
                                            <th>Supplier Name</th>
                                            <th>Contact Person</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Country</th>
                                            <th>Categories</th>
                                            <th>Payment Terms</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($suppliers)): ?>
                                            <?php foreach($suppliers as $supplier): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-2">
                                                                <span class="avatar-title bg-light rounded-circle text-dark">
                                                                    <?php echo strtoupper(substr($supplier->name, 0, 1)); ?>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <strong><?php echo htmlspecialchars($supplier->name); ?></strong>
                                                                <?php if($supplier->city): ?>
                                                                    <div class="text-muted small"><?php echo htmlspecialchars($supplier->city); ?></div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo $supplier->contact_person ? htmlspecialchars($supplier->contact_person) : '-'; ?></td>
                                                    <td>
                                                        <?php if($supplier->email): ?>
                                                            <a href="mailto:<?php echo htmlspecialchars($supplier->email); ?>">
                                                                <?php echo htmlspecialchars($supplier->email); ?>
                                                            </a>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo $supplier->phone ? htmlspecialchars($supplier->phone) : '-'; ?></td>
                                                    <td><?php echo $supplier->country ? htmlspecialchars($supplier->country) : '-'; ?></td>
                                                    <td>
                                                        <?php if($supplier->categories && trim($supplier->categories) !== ''): 
                                                            $categories = explode(',', $supplier->categories);
                                                            $validCategories = array_filter(array_map('trim', $categories));
                                                            foreach(array_slice($validCategories, 0, 2) as $cat): ?>
                                                                <span class="badge badge-category mb-1 me-1"><?php echo htmlspecialchars($cat); ?></span>
                                                            <?php endforeach; 
                                                            if(count($validCategories) > 2): ?>
                                                                <span class="badge badge-category">+<?php echo count($validCategories) - 2; ?> more</span>
                                                            <?php endif;
                                                        else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="payment-badge"><?php echo $supplier->payment_terms ? htmlspecialchars($supplier->payment_terms) : 'Net 30'; ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="status-badge <?php echo $supplier->status ? 'status-active' : 'status-inactive'; ?>">
                                                            <?php echo $supplier->status ? 'Active' : 'Inactive'; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <a href="#" class="text-info me-2" data-bs-toggle="modal" data-bs-target="#editSupplierModal" 
                                                               onclick="loadSupplierData(<?php echo $supplier->id; ?>)">
                                                                <i data-feather="edit" class="feather-sm"></i>
                                                            </a>
                                                            <a href="<?php echo base_url('suppliers/delete/' . $supplier->id); ?>" 
                                                               class="text-danger delete-btn" 
                                                               data-id="<?php echo $supplier->id; ?>">
                                                                <i data-feather="trash-2" class="feather-sm"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i data-feather="users" class="feather-xl mb-2"></i>
                                                        <p>No suppliers found. Add your first supplier!</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Supplier List -->
                </div>
            </div>
        </div>
        <!-- /Main Wrapper -->

        <!-- Add Supplier Modal -->
        <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?php echo base_url('suppliers/add'); ?>" method="POST" id="addSupplierForm">
                        <div class="modal-body">
                            <p class="text-muted mb-4">Provide supplier details to register them in the system.</p>
                            
                            <!-- Supplier Information Section -->
                            <div class="section-card">
                                <h6 class="section-title">Supplier Information</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="Enter supplier name" required>
                                        <?php echo form_error('name', '<div class="text-danger small mt-1">', '</div>'); ?>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control" rows="2" placeholder="Enter full address"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Details Section -->
                            <div class="section-card">
                                <h6 class="section-title">Contact Details</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Contact Person</label>
                                        <input type="text" name="contact_person" class="form-control" placeholder="Enter contact person's name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Enter email address">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
                                    </div>
                                </div>
                            </div>

                            <!-- Business Details Section -->
                            <div class="section-card">
                                <h6 class="section-title">Business Details</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Product Categories</label>
                                        <div class="d-flex flex-wrap gap-2 mb-2" id="add-categories-container">
                                            <!-- Categories will be added here dynamically -->
                                        </div>
                                        <div class="input-group">
                                            <input type="text" id="add-category-input" class="form-control" placeholder="Add category">
                                            <button type="button" class="btn btn-outline-secondary" onclick="addCategory('add')">Add</button>
                                        </div>
                                        <input type="hidden" name="categories" id="add-categories-input">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Payment Terms</label>
                                        <select name="payment_terms" class="form-select">
                                            <option value="Net 30" selected>Net 30</option>
                                            <option value="Net 15">Net 15</option>
                                            <option value="Net 45">Net 45</option>
                                            <option value="Net 60">Net 60</option>
                                            <option value="Due on Receipt">Due on Receipt</option>
                                            <option value="2% 10, Net 30">2% 10, Net 30</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" class="form-control" placeholder="Enter city">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="country" class="form-control" placeholder="Enter country">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="2" placeholder="Enter supplier description"></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                                            <label class="form-check-label">Active Supplier</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Supplier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Supplier Modal -->
        <div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editSupplierForm" action="" method="POST">
                        <input type="hidden" name="id" id="edit-supplier-id">
                        <div class="modal-body">
                            <p class="text-muted mb-4">Update supplier details in the system.</p>
                            
                            <!-- Supplier Information Section -->
                            <div class="section-card">
                                <h6 class="section-title">Supplier Information</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="edit-name" class="form-control" placeholder="Enter supplier name" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" id="edit-address" class="form-control" rows="2" placeholder="Enter full address"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Details Section -->
                            <div class="section-card">
                                <h6 class="section-title">Contact Details</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Contact Person</label>
                                        <input type="text" name="contact_person" id="edit-contact-person" class="form-control" placeholder="Enter contact person's name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Email</label>
                                        <input type="email" name="email" id="edit-email" class="form-control" placeholder="Enter email address">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phone" id="edit-phone" class="form-control" placeholder="Enter phone number">
                                    </div>
                                </div>
                            </div>

                            <!-- Business Details Section -->
                            <div class="section-card">
                                <h6 class="section-title">Business Details</h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Product Categories</label>
                                        <div class="d-flex flex-wrap gap-2 mb-2" id="edit-categories-container">
                                            <!-- Categories will be added here dynamically -->
                                        </div>
                                        <div class="input-group">
                                            <input type="text" id="edit-category-input" class="form-control" placeholder="Add category">
                                            <button type="button" class="btn btn-outline-secondary" onclick="addCategory('edit')">Add</button>
                                        </div>
                                        <input type="hidden" name="categories" id="edit-categories-input">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Payment Terms</label>
                                        <select name="payment_terms" id="edit-payment-terms" class="form-select">
                                            <option value="Net 30">Net 30</option>
                                            <option value="Net 15">Net 15</option>
                                            <option value="Net 45">Net 45</option>
                                            <option value="Net 60">Net 60</option>
                                            <option value="Due on Receipt">Due on Receipt</option>
                                            <option value="2% 10, Net 30">2% 10, Net 30</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" id="edit-city" class="form-control" placeholder="Enter city">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="country" id="edit-country" class="form-control" placeholder="Enter country">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" id="edit-description" class="form-control" rows="2" placeholder="Enter supplier description"></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="edit-status" value="1">
                                            <label class="form-check-label">Active Supplier</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Supplier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php $this->load->view('partials/theme-settings') ?>
        <?php $this->load->view('partials/vendor-scripts') ?>
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        // Category management
        let addCategories = [];
        let editCategories = [];

        // Initialize modals
        document.addEventListener('DOMContentLoaded', function() {
            // Reset add modal when closed
            $('#addSupplierModal').on('hidden.bs.modal', function () {
                addCategories = [];
                updateCategoriesDisplay('add');
                $('#addSupplierForm')[0].reset();
            });

            // Reset edit modal when closed
            $('#editSupplierModal').on('hidden.bs.modal', function () {
                editCategories = [];
                updateCategoriesDisplay('edit');
            });
        });

        // Initialize DataTable only once
        $(document).ready(function() {
            // Check if DataTable is already initialized
            if (!$.fn.dataTable.isDataTable('#suppliersTable')) {
                $('#suppliersTable').DataTable({
                    "pageLength": 10,
                    "ordering": true,
                    "responsive": true,
                    "autoWidth": false,
                    "language": {
                        "emptyTable": "No suppliers found. Add your first supplier!"
                    }
                });
            }

            // Add form validation
            $('#addSupplierForm').submit(function(e) {
                const name = $(this).find('input[name="name"]').val().trim();
                if(name === '') {
                    e.preventDefault();
                    Swal.fire('Error', 'Supplier Name is required', 'error');
                    return false;
                }
                // Update categories input before submission
                $('#add-categories-input').val(addCategories.join(','));
            });
            
            // Edit form validation
            $('#editSupplierForm').submit(function(e) {
                const name = $(this).find('input[name="name"]').val().trim();
                if(name === '') {
                    e.preventDefault();
                    Swal.fire('Error', 'Supplier Name is required', 'error');
                    return false;
                }
                // Update categories input before submission
                $('#edit-categories-input').val(editCategories.join(','));
            });
            
            // Delete confirmation with sweet alert
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var deleteUrl = $(this).attr('href');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
            
            // =========== SEARCH FILTER FUNCTIONALITY ===========
            // Search filter for supplier name
            function performSearch() {
                const searchTerm = $('#supplierSearch').val().trim().toLowerCase();
                
                // Show/hide clear button
                if (searchTerm.length > 0) {
                    $('#clearSearch').show();
                } else {
                    $('#clearSearch').hide();
                }
                
                // Get all table rows
                const rows = $('#suppliersTable tbody tr');
                let visibleCount = 0;
                
                // Remove any existing "no results" row
                $('#suppliersTable tbody tr.no-results').remove();
                
                // Filter rows
                rows.each(function() {
                    // Skip if this is a "no results" row
                    if ($(this).hasClass('no-results')) {
                        return;
                    }
                    
                    const supplierName = $(this).find('td:eq(0)').text().toLowerCase();
                    const isVisible = supplierName.includes(searchTerm) || searchTerm === '';
                    
                    if (isVisible) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });
                
                // Show "no results" message if no visible rows
                if (visibleCount === 0 && searchTerm !== '') {
                    $('#suppliersTable tbody').append(
                        '<tr class="no-results"><td colspan="9" class="text-center py-4">' +
                        '<div class="text-muted">' +
                        '<i data-feather="search" class="feather-xl mb-2"></i>' +
                        '<p>No suppliers found matching "' + searchTerm + '"</p>' +
                        '</div></td></tr>'
                    );
                }
                
                // Update feather icons (if needed)
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
            
            // Search on button click
            $('#searchBtn').click(function() {
                performSearch();
            });
            
            // Search on Enter key
            $('#supplierSearch').keypress(function(e) {
                if (e.which === 13) { // Enter key
                    performSearch();
                    e.preventDefault();
                }
            });
            
            // Real-time search as user types (optional - uncomment if you want it)
            /*
            $('#supplierSearch').on('input', function() {
                performSearch();
            });
            */
            
            // Clear search
            $('#clearSearch').click(function() {
                $('#supplierSearch').val('');
                performSearch();
                $('#supplierSearch').focus();
            });
            
            // Clear search when escape key is pressed
            $('#supplierSearch').keydown(function(e) {
                if (e.key === 'Escape') {
                    $('#supplierSearch').val('');
                    performSearch();
                }
            });
            
            // Reset search when page is refreshed or when add/edit modal closes
            $('#addSupplierModal, #editSupplierModal').on('hidden.bs.modal', function() {
                // Optional: Keep the search if you want, or clear it
                // $('#supplierSearch').val('');
                // performSearch();
            });
            // =========== END SEARCH FILTER FUNCTIONALITY ===========
        });

        function addCategory(type) {
            const inputId = type === 'add' ? 'add-category-input' : 'edit-category-input';
            const categoriesArray = type === 'add' ? addCategories : editCategories;
            
            const input = document.getElementById(inputId);
            const category = input.value.trim();
            
            if (category && !categoriesArray.includes(category)) {
                categoriesArray.push(category);
                updateCategoriesDisplay(type);
                input.value = '';
            } else if (categoriesArray.includes(category)) {
                Swal.fire('Error', 'Category already exists!', 'error');
            }
        }

        function removeCategory(category, type) {
            const categoriesArray = type === 'add' ? addCategories : editCategories;
            const index = categoriesArray.indexOf(category);
            if (index !== -1) {
                categoriesArray.splice(index, 1);
                updateCategoriesDisplay(type);
            }
        }

        function updateCategoriesDisplay(type) {
            const containerId = type === 'add' ? 'add-categories-container' : 'edit-categories-container';
            const inputId = type === 'add' ? 'add-categories-input' : 'edit-categories-input';
            const categoriesArray = type === 'add' ? addCategories : editCategories;
            
            const container = document.getElementById(containerId);
            const hiddenInput = document.getElementById(inputId);
            
            container.innerHTML = '';
            categoriesArray.forEach(category => {
                const badge = document.createElement('span');
                badge.className = 'badge badge-category d-inline-flex align-items-center me-1 mb-1';
                badge.innerHTML = `
                    ${category}
                    <button type="button" class="btn-close btn-close-sm ms-1" onclick="removeCategory('${category.replace(/'/g, "\\'")}', '${type}')"></button>
                `;
                container.appendChild(badge);
            });
            
            // Update hidden input value
            hiddenInput.value = categoriesArray.join(',');
        }

        // Load supplier data for editing
        function loadSupplierData(id) {
            // Reset edit categories
            editCategories = [];
            
            // Fetch supplier data via AJAX
            $.ajax({
                url: '<?php echo base_url("suppliers/get/"); ?>' + id,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if(response.success && response.data) {
                        const supplier = response.data;
                        
                        // Set form action
                        $('#editSupplierForm').attr('action', '<?php echo base_url("suppliers/edit/"); ?>' + id);
                        $('#edit-supplier-id').val(id);
                        
                        // Populate form fields
                        $('#edit-name').val(supplier.name);
                        $('#edit-email').val(supplier.email);
                        $('#edit-phone').val(supplier.phone);
                        $('#edit-address').val(supplier.address);
                        $('#edit-city').val(supplier.city);
                        $('#edit-country').val(supplier.country);
                        $('#edit-description').val(supplier.description);
                        $('#edit-contact-person').val(supplier.contact_person);
                        $('#edit-payment-terms').val(supplier.payment_terms || 'Net 30');
                        
                        // Handle categories
                        if(supplier.categories && supplier.categories.trim() !== '') {
                            editCategories = supplier.categories.split(',').map(cat => cat.trim()).filter(cat => cat !== '');
                            updateCategoriesDisplay('edit');
                        }
                        
                        // Handle status checkbox
                        $('#edit-status').prop('checked', supplier.status == 1);
                        
                        // Show the modal
                        $('#editSupplierModal').modal('show');
                    } else {
                        Swal.fire('Error', response.message || 'Failed to load supplier data', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Error loading supplier data. Please try again.', 'error');
                    console.error('AJAX Error:', error);
                }
            });
        }
        </script>
    </body>
</html>