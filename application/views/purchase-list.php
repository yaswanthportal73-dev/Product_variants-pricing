<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('partials/title-meta') ?>
    <?php $this->load->view('partials/head-css') ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container { width: 100% !important; }
        .item-row { background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 10px; }
        .action-eye { color: #28a745; }
        .feather-edit { color: #ffc107; }
        .feather-trash-2 { color: #dc3545; }
        .badge-status { padding: 5px 10px; border-radius: 4px; font-size: 12px; }
        .badge-received { background: #d4edda; color: #155724; }
        .badge-ordered { background: #fff3cd; color: #856404; }
        .badge-pending { background: #d1ecf1; color: #0c5460; }
        .badge-paid { background: #d4edda; color: #155724; }
        .badge-partial { background: #fff3cd; color: #856404; }
        .badge-unpaid { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <?php $this->load->view('partials/body') ?>

    <div id="global-loader">
        <div class="whirly-loader"></div>
    </div>

    <div class="main-wrapper">
        <?php $this->load->view('partials/menu') ?>

        <div class="page-wrapper">
            <div class="content">

                <!-- Page Header -->
                <div class="page-header transfer">
                    <div class="add-item d-flex">
                        <div class="page-title">
                            <h4>Purchase List</h4>
                            <h6>Manage your purchases</h6>
                        </div>
                    </div>
                    <ul class="table-top-head">
                        <li><a data-bs-toggle="tooltip" title="Pdf"><img src="<?php echo base_url(); ?>assets/img/icons/pdf.svg" alt="Pdf"></a></li>
                        <li><a data-bs-toggle="tooltip" title="Excel"><img src="<?php echo base_url(); ?>assets/img/icons/excel.svg" alt="Excel"></a></li>
                        <li><a data-bs-toggle="tooltip" title="Print"><img src="<?php echo base_url(); ?>assets/img/icons/printer.svg" alt="Print"></a></li>
                        <li><a data-bs-toggle="tooltip" title="Refresh" id="refreshBtn"><i data-feather="rotate-ccw"></i></a></li>
                        <li><a data-bs-toggle="tooltip" title="Collapse" id="collapse-header"><i data-feather="chevron-up"></i></a></li>
                    </ul>
                    <div class="d-flex purchase-pg-btn">
                        <div class="page-btn">
                            <button type="button" class="btn btn-added" id="addPurchaseBtn">
                                <i data-feather="plus-circle" class="me-2"></i>Add New Purchase
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Flash Messages -->
                <div id="flashMessages" class="mb-4"></div>

                <!-- Purchase List Card -->
                <div class="card table-list-card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-input">
                                    <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search"></i></a>
                                </div>
                            </div>
                            <div class="search-path">
                                <a class="btn btn-filter" id="filter_search">
                                    <i data-feather="filter" class="filter-icon"></i>
                                    <span><img src="<?php echo base_url(); ?>assets/img/icons/closes.svg" alt="img"></span>
                                </a>
                            </div>
                            <div class="form-sort">
                                <i data-feather="sliders" class="info-img"></i>
                                <select class="select" id="sortSelect">
                                    <option value="newest">Newest</option>
                                    <option value="oldest">Oldest</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filter Panel (unchanged) -->
                        <div class="card mb-0" id="filter_inputs" style="display:none;">
                            <!-- ... your filter fields ... -->
                        </div>

                        <div class="table-responsive product-list">
                            <table class="table datanew" id="purchaseTable">
                                <thead>
                                    <tr>
                                        <th class="no-sort">
                                            <label class="checkboxs">
                                                <input type="checkbox" id="select-all">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </th>
                                        <th>Supplier Name</th>
                                        <th>Reference</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Grand Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Payment</th>
                                        <th class="no-sort text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Filled by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="purchaseModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="purchaseForm">
                    <div class="modal-header bg-primary text-white">
                        <h5 id="modalTitle">Add Purchase</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="purchaseId">
                        <input type="hidden" id="csrfToken" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="due" id="due_hidden" value="0.00">

                        <div class="row">
                            <div class="col-md-4 mb-3"><label>Supplier *</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="">Select Supplier</option>
                                    <?php foreach($suppliers as $s): ?>
                                        <option value="<?= $s->id ?>"><?= htmlspecialchars($s->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3"><label>Reference *</label>
                                <input type="text" name="reference" id="reference" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3"><label>Date</label>
                                <input type="date" name="date" id="date" class="form-control" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4"><label>Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="Pending">Pending</option>
                                    <option value="Ordered">Ordered</option>
                                    <option value="Received">Received</option>
                                </select>
                            </div>
                        </div>

                        <hr><h6>Items</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="addItemBtn">
                            <i class="fa fa-plus"></i> Add Item
                        </button>
                        <div id="itemsContainer"></div>

                        <hr>
                        <div class="row">
                            <div class="col-md-3 mb-3"><label>Tax</label><input type="number" step="0.01" min="0" name="order_tax" id="order_tax" class="form-control" value="0"></div>
                            <div class="col-md-3 mb-3"><label>Discount</label><input type="number" step="0.01" min="0" name="discount_total" id="discount_total" class="form-control" value="0"></div>
                            <div class="col-md-3 mb-3"><label>Shipping</label><input type="number" step="0.01" min="0" name="shipping" id="shipping" class="form-control" value="0"></div>
                            <div class="col-md-3 mb-3"><label>Grand Total</label><input type="number" name="grand_total" id="grand_total" class="form-control" readonly></div>
                            <div class="col-md-3 mb-3"><label>Paid</label><input type="number" step="0.01" min="0" name="paid" id="paid" class="form-control" value="0"></div>
                            <div class="col-md-3 mb-3"><label>Due</label><input type="number" id="due_amount" class="form-control" readonly></div>
                            <div class="col-md-3 mb-3"><label>Payment Status</label>
                                <select name="payment_status" id="payment_status" class="form-control">
                                    <option value="Unpaid">Unpaid</option>
                                    <option value="Partial">Partial</option>
                                    <option value="Paid">Paid</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3"><label>Notes</label><textarea name="notes" id="notes" class="form-control" rows="3"></textarea></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-submit" id="saveBtn">Save Purchase</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View and Delete Modals (same as before) -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        let allProducts = <?= json_encode($products); ?>;
        let itemCounter = 0;

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text || '';
            return div.innerHTML;
        }

        function initProductSelect(el) {
            el.select2({
                dropdownParent: $('#purchaseModal'),
                width: '100%',
                placeholder: 'Search product...',
                templateResult: item => {
                    if (!item.id) return item.text;
                    let p = allProducts.find(x => x.id == item.id);
                    let code = p?.code ? p.code + ' - ' : '';
                    return $('<span>' + code + escapeHtml(p?.name || '') + '</span>');
                },
                templateSelection: item => {
                    if (!item.id) return item.text;
                    let p = allProducts.find(x => x.id == item.id);
                    let code = p?.code ? p.code + ' - ' : '';
                    return code + escapeHtml(p?.name || '');
                }
            });
        }

        $(function() {
            $('#global-loader').fadeOut('slow');
            loadPurchases();
            feather.replace();

            $('#filter_search').click(function() {
                $('#filter_inputs').slideToggle();
            });

            $('#addPurchaseBtn').click(function() {
                $('#purchaseForm')[0].reset();
                $('#purchaseId').val('');
                $('#due_hidden').val('0.00');
                $('#itemsContainer').html('');
                itemCounter = 0;
                addItemRow();
                $('#reference').prop('readonly', false);
                $('#modalTitle').text('Add Purchase');
                $('#purchaseModal').modal('show');
            });

            $('#refreshBtn').click(loadPurchases);

            function addItemRow() {
                let html = `
                <div class="item-row">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <input type="hidden" name="items[${itemCounter}][product_id]" class="pid">
                            <label>Product *</label>
                            <select class="form-control pname" required>
                                <option value="">Select Product</option>
                                ${allProducts.map(p => `<option value="${p.id}">${p.code ? p.code + ' - ' : ''}${escapeHtml(p.name)}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-2 mb-2"><label>Qty</label><input type="number" name="items[${itemCounter}][qty]" class="form-control qty" min="1" value="1" required></div>
                        <div class="col-md-3 mb-2"><label>Price</label><input type="number" step="0.01" min="0" name="items[${itemCounter}][purchase_price]" class="form-control price" required></div>
                        <div class="col-md-2 mb-2"><label>Total</label><input type="text" class="form-control total" readonly></div>
                        <div class="col-md-1 d-flex align-items-end mb-2">
                            <button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>`;
                $('#itemsContainer').append(html);
                initProductSelect($('#itemsContainer .item-row').last().find('.pname'));
                itemCounter++;
                calc();
            }

            $('#addItemBtn').click(addItemRow);

            $(document).on('change', '.pname', function() {
                let id = $(this).val();
                let p = allProducts.find(x => x.id == id);
                let row = $(this).closest('.item-row');
                row.find('.pid').val(id);
                row.find('.price').val(p ? p.cost_price : 0);
                calc();
            });

            $(document).on('input', '.qty, .price, #order_tax, #discount_total, #shipping, #paid', calc);
            $(document).on('click', '.remove-item', function() { $(this).closest('.item-row').remove(); calc(); });

            function calc() {
                let subtotal = 0;
                $('.item-row').each(function() {
                    let qty = parseFloat($(this).find('.qty').val()) || 0;
                    let price = parseFloat($(this).find('.price').val()) || 0;
                    let total = qty * price;
                    $(this).find('.total').val(total.toFixed(2));
                    subtotal += total;
                });
                let tax = parseFloat($('#order_tax').val()) || 0;
                let discount = parseFloat($('#discount_total').val()) || 0;
                let shipping = parseFloat($('#shipping').val()) || 0;
                let grand = subtotal + tax - discount + shipping;
                let paid = parseFloat($('#paid').val()) || 0;
                let due = Math.max(0, grand - paid);

                $('#grand_total').val(grand.toFixed(2));
                $('#due_amount').val(due.toFixed(2));
                $('#due_hidden').val(due.toFixed(2));

                if (due <= 0) $('#payment_status').val('Paid');
                else if (paid > 0) $('#payment_status').val('Partial');
                else $('#payment_status').val('Unpaid');
            }

            $('#purchaseForm').submit(function(e) {
                e.preventDefault();
                let id = $('#purchaseId').val();
                let url = id ? '<?= base_url("home/edit_purchase/"); ?>' + id : '<?= base_url("home/add_purchase"); ?>';
                let btn = $('#saveBtn').prop('disabled', true).html('Saving...');

                $.ajax({
                    url, type: 'POST', data: $(this).serialize(), dataType: 'json',
                    success: res => {
                        if (res.csrf_token) $('#csrfToken').val(res.csrf_token);
                        if (res.success) {
                            $('#purchaseModal').modal('hide');
                            loadPurchases();
                            showAlert(res.message, 'success');
                        } else showAlert(res.message || 'Error', 'danger');
                    },
                    error: () => showAlert('Network error', 'danger'),
                    complete: () => btn.prop('disabled', false).html('Save Purchase')
                });
            });

            $(document).on('click', '.viewBtn', function() {
                let id = $(this).data('id');
                $.getJSON('<?= base_url("home/get_purchase/"); ?>' + id, res => {
                    if (res.success) {
                        let p = res.data.purchase;
                        let items = res.data.items;
                        let statusClass = p.status === 'Received' ? 'badge-received' : p.status === 'Ordered' ? 'badge-ordered' : 'badge-pending';
                        let paymentClass = p.payment_status === 'Paid' ? 'badge-paid' : p.payment_status === 'Partial' ? 'badge-partial' : 'badge-unpaid';

                        let html = `
                            <div class="row">
                                <div class="col-6"><strong>Reference:</strong> ${escapeHtml(p.reference)}</div>
                                <div class="col-6"><strong>Date:</strong> ${p.date}</div>
                                <div class="col-6"><strong>Supplier:</strong> ${escapeHtml(p.supplier_name || '-')}</div>
                                <div class="col-6"><strong>Status:</strong> <span class="badge-status ${statusClass}">${p.status}</span></div>
                                <div class="col-6"><strong>Grand Total:</strong> ${p.grand_total}</div>
                                <div class="col-6"><strong>Paid:</strong> ${p.paid || '0.00'}</div>
                                <div class="col-6"><strong>Due:</strong> ${parseFloat(p.due || 0).toFixed(2)}</div>
                                <div class="col-6"><strong>Payment:</strong> <span class="badge-status ${paymentClass}">${p.payment_status}</span></div>
                                <div class="col-12 mt-3"><strong>Notes:</strong><br>${escapeHtml(p.notes || '-')}</div>
                            </div>
                            <hr><h6>Items</h6>
                            <table class="table table-bordered">
                                <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
                                <tbody>`;
                        items.forEach(it => {
                            let prod = allProducts.find(x => x.id == it.product_id);
                            html += `<tr>
                                <td>${escapeHtml(prod?.name || 'Unknown')}</td>
                                <td>${it.qty}</td>
                                <td>${it.purchase_price}</td>
                                <td>${(it.qty * it.purchase_price).toFixed(2)}</td>
                            </tr>`;
                        });
                        html += `</tbody></table>`;
                        $('#viewContent').html(html);
                        $('#viewModal').modal('show');
                    }
                });
            });

            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                $.getJSON('<?= base_url("home/get_purchase/"); ?>' + id, res => {
                    if (res.success) {
                        let p = res.data.purchase;
                        let items = res.data.items;
                        $('#purchaseId').val(p.id);
                        $('#supplier_id').val(p.supplier_id);
                        $('#reference').val(p.reference).prop('readonly', true);
                        $('#date').val(p.date);
                        $('#status').val(p.status);
                        $('#order_tax').val(p.order_tax);
                        $('#discount_total').val(p.discount_total);
                        $('#shipping').val(p.shipping);
                        $('#paid').val(p.paid);
                        $('#due_amount').val(parseFloat(p.due || 0).toFixed(2));
                        $('#due_hidden').val(parseFloat(p.due || 0).toFixed(2));
                        $('#payment_status').val(p.payment_status);
                        $('#notes').val(p.notes);

                        $('#itemsContainer').html('');
                        itemCounter = 0;
                        items.forEach(it => {
                            addItemRow();
                            let row = $('#itemsContainer .item-row').last();
                            let select = row.find('.pname');
                            select.val(it.product_id);
                            initProductSelect(select);
                            select.trigger('change');
                            row.find('.qty').val(it.qty);
                            row.find('.price').val(it.purchase_price);
                        });
                        calc();
                        $('#modalTitle').text('Edit Purchase');
                        $('#purchaseModal').modal('show');
                    }
                });
            });

            function loadPurchases() {
                $.getJSON('<?= base_url("home/get_purchases_data"); ?>', res => {
                    if (res.csrf_token) $('#csrfToken').val(res.csrf_token);
                    let rows = '';
                    $.each(res.purchases, function(i, p) {
                        let due = parseFloat(p.due || 0).toFixed(2);
                        let statusClass = p.status === 'Received' ? 'badge-received' : p.status === 'Ordered' ? 'badge-ordered' : 'badge-pending';
                        let paymentClass = p.payment_status === 'Paid' ? 'badge-paid' : p.payment_status === 'Partial' ? 'badge-partial' : 'badge-unpaid';

                        rows += `<tr>
                            <td><label class="checkboxs"><input type="checkbox"><span class="checkmarks"></span></label></td>
                            <td>${escapeHtml(p.supplier_name || '-')}</td>
                            <td>${escapeHtml(p.reference)}</td>
                            <td>${p.date}</td>
                            <td><span class="badge-status ${statusClass}">${escapeHtml(p.status)}</span></td>
                            <td>${p.grand_total}</td>
                            <td>${p.paid || '0.00'}</td>
                            <td>${due}</td>
                            <td><span class="badge-status ${paymentClass}">${escapeHtml(p.payment_status)}</span></td>
                            <td class="action-table-data">
                                <div class="edit-delete-action">                                 
                                        <a class="me-2 p-2 viewBtn" href="<?= base_url("home/view_purchase/"); ?>${p.id}">
                                            <i data-feather="eye" class="action-eye"></i>
                                        </a>
                                    <a class="me-2 p-2 editBtn" data-id="${p.id}" href="javascript:void(0);">
                                        <i data-feather="edit" class="feather-edit"></i>
                                    </a>
                                    <a class="confirm-text p-2 deleteBtn" data-id="${p.id}" href="javascript:void(0);">
                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>`;
                    });
                    $('#purchaseTable tbody').html(rows);
                    feather.replace();
                });
            }

            function showAlert(msg, type) {
                $('#flashMessages').html(`
                    <div class="alert alert-${type} alert-dismissible fade show">
                        ${msg}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                setTimeout(() => $('.alert').fadeOut(), 5000);
            }
        });
    </script>

    <?php $this->load->view('partials/theme-settings') ?>
    <?php $this->load->view('partials/vendor-scripts') ?>
</body>
</html>