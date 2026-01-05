<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('partials/title-meta'); ?>
    <?php $this->load->view('partials/head-css'); ?>
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #06d6a0;
            --danger-color: #ef476f;
            --warning-color: #ffd166;
            --light-bg: #f8f9fa;
            --border-color: #e9ecef;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: #333;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .card-header {
            
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.2rem 1.5rem;
        }
        
        .badge-pill {
            padding: 6px 12px;
            font-weight: 500;
        }
        
        .info-box {
            background: white;
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        
        .info-box h6 {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .action-buttons .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        
        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .status-pending {
            background-color: rgba(255, 193, 7, 0.15);
            color: #e6a800;
        }
        
        .status-approved {
            background-color: rgba(25, 135, 84, 0.15);
            color: #198754;
        }
        
        .status-received {
            background-color: rgba(13, 110, 253, 0.15);
            color: #0d6efd;
        }
        
        .payment-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .payment-pending {
            background-color: rgba(255, 193, 7, 0.15);
            color: #e6a800;
        }
        
        .payment-paid {
            background-color: rgba(25, 135, 84, 0.15);
            color: #198754;
        }
        
        .payment-partial {
            background-color: rgba(13, 110, 253, 0.15);
            color: #0d6efd;
        }
        
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 25px;
        }
        
        .supplier-card {
            border-top: 4px solid var(--success-color);
        }
        
        .total-summary {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-color);
            border: 3px solid white;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            .card {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }
        }
    </style>
</head>
<body>
  <?php $this->load->view('partials/body'); ?>
      <!-- <div id="global-loader">
            <div class="whirly-loader"> </div>
        </div> -->

    <div class="main-wrapper">
        <?php $this->load->view('partials/menu'); ?>
        
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header d-flex justify-content-between align-items-center">
                    <div class="page-title">
                        <h4>Purchase Order List</h4>
                        <h6>Your purchases</h6>
                    </div>
                    <ul class="table-top-head">
                        <li><a href="#" id="pdfBtn" title="Export PDF"><i class="fas fa-file-pdf"></i></a></li>
                        <li><a href="#" id="excelBtn" title="Export Excel"><i class="fas fa-file-excel"></i></a></li>
                        <li><a href="#" id="printBtn" title="Print"><i class="fas fa-print"></i></a></li>
                        <li><a href="#" id="refreshBtn" title="Refresh"><i class="fas fa-sync-alt"></i></a></li>
                    </ul>
                </div>

                <div id="flashMessages"></div>

        <!-- Main Content -->
        <div class="row">
            <!-- Left Column - Order Info -->
            <div class="col-lg-8">
                <!-- Purchase Order Info Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Purchase Order Information</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <h6><i class="fas fa-hashtag me-2"></i>Reference Number</h6>
                                    <h4 class="fw-bold" id="reference_number"><?php echo isset($purchase->reference) ? $purchase->reference : ''; ?></h4>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <h6><i class="fas fa-calendar-day me-2"></i>Purchase Date</h6>
                                    <h4 class="fw-bold" id="purchase_date">
                                        <?php 
                                        if(isset($purchase->date)) {
                                            echo date('F d, Y', strtotime($purchase->date));
                                        }
                                        ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <h6><i class="fas fa-calendar-plus me-2"></i>Created Date</h6>
                                    <p class="mb-0" id="created_date">
                                        <?php 
                                        if(isset($purchase->created_at)) {
                                            echo date('F d, Y H:i', strtotime($purchase->created_at));
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <h6><i class="fas fa-money-bill-wave me-2"></i>Payment Status</h6>
                                    <span class="payment-badge 
                                        <?php 
                                        if(isset($purchase->payment_status)) {
                                            echo 'payment-' . $purchase->payment_status;
                                        }
                                        ?>" id="payment_status_badge">
                                        <?php 
                                        if(isset($purchase->payment_status)) {
                                            echo ucfirst($purchase->payment_status);
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <?php if(isset($purchase->notes) && !empty($purchase->notes)): ?>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-box">
                                    <h6><i class="fas fa-sticky-note me-2"></i>Notes</h6>
                                    <p class="mb-0"><?php echo $purchase->notes; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Purchase Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Sl.No</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Purchase Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="items_table_body">
                                    <?php if(isset($items) && !empty($items)): ?>
                                        <?php foreach($items as $index => $item): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($item->product_name); ?></td>
                                            <td><?php echo $item->qty; ?></td>
                                            <td>₹<?php echo number_format($item->purchase_price, 2); ?></td>
                                            <td><strong>₹<?php echo number_format($item->total, 2); ?></strong></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No items found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Supplier & Summary -->
            <div class="col-lg-4">
                <!-- Supplier Information -->
                <div class="card mb-4 supplier-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Supplier Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="icon-box mx-auto mb-3">
                                <i class="fas fa-building fa-2x"></i>
                            </div>
                            <h4 id="supplier_name"><?php echo isset($purchase->supplier_name) ? $purchase->supplier_name : 'No Supplier'; ?></h4>
                        </div>
                        
                        <!-- Show supplier phone number if available -->
                        <?php if(isset($purchase->supplier_phone) && !empty($purchase->supplier_phone)): ?>
                        <div class="info-box mt-3">
                            <h6><i class="fas fa-phone me-2"></i>Phone Number</h6>
                            <p class="mb-0 fw-bold"><?php echo $purchase->supplier_phone; ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Alternative: If you have email in your data -->
                        <?php if(isset($purchase->supplier_email) && !empty($purchase->supplier_email)): ?>
                        <div class="info-box mt-2">
                            <h6><i class="fas fa-envelope me-2"></i>Email</h6>
                            <p class="mb-0"><?php echo $purchase->supplier_email; ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Alternative: If you have address in your data -->
                        <?php if(isset($purchase->supplier_address) && !empty($purchase->supplier_address)): ?>
                        <div class="info-box mt-2">
                            <h6><i class="fas fa-map-marker-alt me-2"></i>Address</h6>
                            <p class="mb-0"><?php echo $purchase->supplier_address; ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Purchase Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="total-summary">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span class="fw-bold" id="subtotal">
                                    ₹<?php 
                                    $subtotal = 0;
                                    if(isset($items) && !empty($items)) {
                                        foreach($items as $item) {
                                            $subtotal += $item->total;
                                        }
                                    }
                                    echo number_format($subtotal, 2);
                                    ?>
                                </span>
                            </div>
                            <?php if(isset($purchase->discount_total) && $purchase->discount_total > 0): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span class="fw-bold text-success">-₹<?php echo number_format($purchase->discount_total, 2); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(isset($purchase->order_tax) && $purchase->order_tax > 0): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span class="fw-bold text-danger">₹<?php echo number_format($purchase->order_tax, 2); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(isset($purchase->shipping) && $purchase->shipping > 0): ?>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Shipping:</span>
                                <span class="fw-bold">₹<?php echo number_format($purchase->shipping, 2); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-0">Grand Total:</h5>
                                <h3 class="text-success mb-0" id="grand_total">
                                    ₹<?php echo isset($purchase->grand_total) ? number_format($purchase->grand_total, 2) : '0.00'; ?>
                                </h3>
                            </div>
                            
                            <?php if(isset($purchase->paid)): ?>
                            <hr class="mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Amount Paid:</span>
                                <span class="fw-bold text-success">₹<?php echo number_format($purchase->paid, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Amount Due:</span>
                                <span class="fw-bold text-danger" id="due_amount">
                                    ₹<?php 
                                    $due = 0;
                                    if(isset($purchase->grand_total) && isset($purchase->paid)) {
                                        $due = $purchase->grand_total - $purchase->paid;
                                    }
                                    echo number_format($due, 2);
                                    ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
       
    </div>


        <?php $this->load->view('partials/theme-settings') ?>
        <?php $this->load->view('partials/vendor-scripts') ?>
    
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('table').DataTable({
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50],
                "order": [[0, 'asc']],
                "searching": false,
                "paging": false,
                "info": false,
                "language": {
                    "search": "Search items:",
                    "lengthMenu": "Show _MENU_ items"
                }
            });
        });

        // Function to print the invoice
        function printInvoice() {
            window.print();
        }

        // Function to export as PDF
        function exportToPDF() {
            alert('PDF export functionality would be implemented here.');
        }

        // Function to edit purchase order
        function editPurchaseOrder(purchaseId) {
            window.location.href = '<?php echo base_url(); ?>edit_purchase/' + purchaseId;
        }

        // Function to duplicate order
        function duplicateOrder(purchaseId) {
            if (confirm('Create a duplicate of this purchase order?')) {
                window.location.href = '<?php echo base_url(); ?>add_purchase?duplicate=' + purchaseId;
            }
        }

        // Function to delete purchase
        function deletePurchase(purchaseId) {
            if (confirm('Are you sure you want to delete this purchase order?')) {
                $.ajax({
                    url: '<?php echo base_url(); ?>delete_purchase/' + purchaseId,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Purchase deleted successfully!');
                            window.location.href = '<?php echo base_url(); ?>purchase-list';
                        } else {
                            alert('Failed to delete purchase: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                    }
                });
            }
        }

        // Function to format currency
        function formatCurrency(amount) {
            return '₹' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }

        // Function to calculate due amount
        function calculateDue() {
            const grandTotal = <?php echo isset($purchase->grand_total) ? $purchase->grand_total : 0; ?>;
            const paid = <?php echo isset($purchase->paid) ? $purchase->paid : 0; ?>;
            const due = grandTotal - paid;
            document.getElementById('due_amount').textContent = formatCurrency(due);
            return due;
        }

        // Calculate due on page load
        $(document).ready(function() {
            calculateDue();
        });
    </script>
</body>
</html>