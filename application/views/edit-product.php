<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('partials/title-meta'); ?>
    <?php $this->load->view('partials/head-css'); ?>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

    <style>
        /* ===== IMAGE UPLOAD ===== */
        .image-upload {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all .3s;
        }
        .image-upload:hover {
            border-color: #007bff;
            background: #f8f9fa;
        }
        .image-uploads i {
            font-size: 48px;
            color: #007bff;
            margin-bottom: 15px;
        }
        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        .image-preview-item {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd;
            transition: transform .3s;
        }
        .image-preview-item:hover {
            transform: scale(1.05);
        }
        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .btn-remove {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #dc3545;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: .8;
            transition: opacity .3s;
        }
        .btn-remove:hover {
            opacity: 1;
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: .375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        #barcodePreview {
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            background: white;
        }

        .is-invalid { border-color: #dc3545 !important; }
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: .25rem;
            font-size: .875em;
            color: #dc3545;
        }

        /* Loading State */
        .variants-loading {
            text-align: center;
            padding: 1rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <?php $this->load->view('partials/body'); ?>
    <div class="main-wrapper">
        <?php $this->load->view('partials/menu'); ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header mb-3">
                    <h4>Edit Product</h4>
                </div>

                <form action="<?php echo base_url('home/update_product/'.$product->id); ?>" method="post" enctype="multipart/form-data" id="productForm">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="existing_images" id="existingImages" value="<?php echo implode(',', array_column($images ?? [], 'id')); ?>">
                    <input type="hidden" name="removed_images" id="removedImages" value="">

                    <!-- PRODUCT INFO -->
                    <div class="card mb-3">
                        <div class="card-header"><h5>Product Information</h5></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Product Name *</label>
                                    <input type="text" class="form-control <?php echo form_error('name') ? 'is-invalid' : ''; ?>" 
                                           name="name" required maxlength="255" 
                                           value="<?php echo set_value('name', $product->name); ?>" 
                                           placeholder="Product Name">
                                    <?php echo form_error('name', '<div class="invalid-feedback">', '</div>'); ?>
                                </div>
                                <div class="col-md-3">
                                    <label>Barcode Type</label>
                                    <div class="form-control bg-light"><strong>EAN-13</strong>
                                        <input type="hidden" name="barcode_type" value="EAN13">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>EAN-13 Barcode *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control <?php echo form_error('barcode') ? 'is-invalid' : ''; ?>" 
                                               name="barcode" required pattern="\d{13}" maxlength="13" 
                                               id="barcodeInput" value="<?php echo set_value('barcode', $product->barcode); ?>" 
                                               placeholder="13 digit EAN-13">
                                        <button type="button" class="btn btn-primary" onclick="generateBarcode()">Generate</button>
                                    </div>
                                    <small class="text-muted">Click Generate for auto-create</small>
                                    <?php echo form_error('barcode', '<div class="invalid-feedback d-block">', '</div>'); ?>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label>Category *</label>
                                    <select class="form-control select2" name="category_id" id="categorySelect" required>
                                        <option value="">Choose Category</option>
                                        <?php foreach($categories as $c): ?>
                                            <option value="<?php echo $c->id; ?>" <?php echo $product->category_id == $c->id ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($c->name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Sub Category</label>
                                    <select class="form-control select2" name="sub_category_id" id="subCategorySelect">
                                        <option value="">Choose Sub Category</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Brand</label>
                                    <select class="form-control select2" name="brand_id">
                                        <option value="">Choose Brand</option>
                                        <?php foreach($brands as $b): ?>
                                            <option value="<?php echo $b->id; ?>" <?php echo $product->brand_id == $b->id ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($b->name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label>Supplier</label>
                                    <select class="form-control select2" name="supplier_id">
                                        <option value="">Choose Supplier</option>
                                        <?php foreach($suppliers as $s): ?>
                                            <option value="<?php echo $s->id; ?>" <?php echo $product->supplier_id == $s->id ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($s->name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Short Description</label>
                                    <textarea name="short_description" class="form-control" rows="2"><?php echo set_value('short_description', $product->short_description); ?></textarea>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" id="description" rows="4"><?php echo set_value('description', $product->description); ?></textarea>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label>Barcode Preview</label>
                                    <div id="barcodePreview" class="mb-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PRICING & VARIANTS -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>Pricing & Variants</h5>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-success mb-3" onclick="addRow()">
                                <i data-feather="plus-circle"></i> Add Variant
                            </button>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="pricingTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Unit</th>
                                            <th>Volume</th>
                                            <th>Purchase Price (₹)</th>
                                            <th>Selling Price (₹)</th>
                                            <th width="80">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Variants will be inserted here -->
                                        <tr class="variants-loading">
                                            <td colspan="5">Loading existing variants...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- PRODUCT IMAGES -->
                    <div class="card mb-3">
                        <div class="card-header"><h5>Product Images</h5></div>
                        <div class="card-body">
                            <div class="image-upload" onclick="document.getElementById('imageUpload').click()">
                                <input type="file" id="imageUpload" name="images[]" multiple accept="image/*" style="display:none;" onchange="previewImages(this)">
                                <div class="image-uploads">
                                    <i class="fa fa-plus-circle"></i>
                                    <h4>Click to upload</h4>
                                </div>
                            </div>
                            <div class="image-preview" id="imagePreview">
                                <?php if (!empty($images)): ?>
                                    <?php foreach($images as $img): ?>
                                        <div class="image-preview-item" data-image-id="<?php echo $img->id; ?>">
                                            <img src="<?php echo base_url('uploads/products/' . $product->id . '/' . $img->image_path); ?>" 
                                                 onerror="this.src='<?php echo base_url('assets/img/default-product.png'); ?>'">
                                            <button type="button" class="btn-remove" 
                                                    onclick="removeExistingImage(this, <?php echo $img->id; ?>)">
                                                <i data-feather="x"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- STATUS -->
                    <div class="card mb-3">
                        <div class="card-header"><h5>Status</h5></div>
                        <div class="card-body">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" value="1" 
                                       <?php echo $product->status == 1 ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="statusSwitch">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mb-5">
                        <a href="<?php echo base_url('home/products_list'); ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $this->load->view('partials/vendor-scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Global units
        const units = <?php echo json_encode($units ?? []); ?>;
        let variantsLoaded = false;

        $(document).ready(function() {
            console.log('Initializing edit product page...');

            // Step 1: Initialize Select2
            $('.select2').select2({ width: '100%' }).on('select2:open', () => {
                $('.select2-search__field').focus();
            });

            // Step 2: CKEditor
            if (document.getElementById('description')) {
                CKEDITOR.replace('description', { height: 200 });
            }

            // Step 3: Icons
            feather.replace();

            // Step 4: Load subcategories (async-safe)
            setTimeout(() => {
                <?php if ($product->category_id): ?>
                    loadSubCategories(<?php echo (int)$product->category_id; ?>, <?php echo (int)$product->sub_category_id; ?>);
                <?php endif; ?>
            }, 100);

            // Step 5: Barcode
            updateBarcodePreview();
            $('#barcodeInput').on('input', updateBarcodePreview);

            // Step 6: Load variants AFTER UI is ready (critical!)
            // Delay to ensure Select2 & DOM are stable
            setTimeout(initVariants, 300);
        });

        // ✅ BARCODE
        function generateBarcode() {
            const firstTwo = Math.floor(Math.random() * 90) + 10;
            const nextTen = Math.floor(Math.random() * 1e10).toString().padStart(10, '0');
            const base = firstTwo + nextTen;
            const check = calculateEAN13CheckDigit(base);
            const code = base + check;
            $('#barcodeInput').val(code).trigger('input');
        }

        function calculateEAN13CheckDigit(code) {
            let sum = 0;
            for (let i = 0; i < 12; i++) {
                const d = parseInt(code[i]);
                sum += (i % 2 === 0) ? d : d * 3;
            }
            return sum % 10 === 0 ? 0 : 10 - (sum % 10);
        }

        function updateBarcodePreview() {
            const code = $('#barcodeInput').val().trim();
            const $preview = $('#barcodePreview');

            if (!/^\d{13}$/.test(code)) {
                $preview.html('<small class="text-muted">Enter 13 digits to preview barcode</small>');
                return;
            }

            const canvas = document.createElement('canvas');
            $preview.empty().append(canvas);
            try {
                JsBarcode(canvas, code, {
                    format: "EAN13",
                    width: 2,
                    height: 100,
                    displayValue: true,
                    fontSize: 16,
                    textAlign: "center",
                    textPosition: "bottom"
                });
            } catch (e) {
                $preview.html('<small class="text-danger">Invalid barcode</small>');
            }
        }

        // ✅ SUBCATEGORIES
        function loadSubCategories(catId, selectedId = 0) {
            if (!catId) {
                $('#subCategorySelect').html('<option value="">Choose Sub Category</option>').trigger('change');
                return;
            }

            $.post('<?php echo base_url("home/products_get_sub_categories"); ?>', {
                category_id: catId,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            }, function(res) {
                let options = '<option value="">Choose Sub Category</option>';
                if (res.status === 'success' && Array.isArray(res.data)) {
                    res.data.forEach(c => {
                        options += `<option value="${c.id}" ${c.id == selectedId ? 'selected' : ''}>${c.name}</option>`;
                    });
                }
                $('#subCategorySelect').html(options).trigger('change');
            }, 'json');
        }

        $('#categorySelect').on('change', function() {
            loadSubCategories($(this).val());
        });

        // ✅ VARIANTS — CORE FIX
        function initVariants() {
            const $tbody = $('#pricingTable tbody');
            $tbody.empty(); // clear loader

            // Add existing variants
            const existingVariants = <?php echo json_encode($variants ?? []); ?>;
            if (existingVariants && Array.isArray(existingVariants) && existingVariants.length > 0) {
                existingVariants.forEach(v => {
                    addRow({
                        unit_id: parseInt(v.unit_id),
                        volume: parseFloat(v.volume),
                        purchase_price: parseFloat(v.purchase_price),
                        selling_price: parseFloat(v.selling_price)
                    });
                });
                console.log(`Loaded ${existingVariants.length} existing variant(s)`);
            } else {
                // At least one row for new entry
                addRow();
                console.log('No existing variants — added 1 empty row');
            }

            // Run calculations after all rows inserted
            setTimeout(() => {
                recalculateAllRows();
                feather.replace(); // refresh icons in new rows
                variantsLoaded = true;
            }, 50);
        }

        function addRow(preset = {}) {
            const $tbody = $('#pricingTable tbody');
            const rowIndex = $tbody.children('tr').length;

            let unitOptions = '<option value="">Select Unit</option>';
            units.forEach(u => {
                const sel = preset.unit_id == u.id ? 'selected' : '';
                unitOptions += `<option value="${u.id}" ${sel}>${u.name} (${u.symbol})</option>`;
            });

            const $row = $(`
                <tr>
                    <td><select name="pricing[${rowIndex}][unit_id]" class="form-control unit-select" required>${unitOptions}</select></td>
                    <td><input type="number" name="pricing[${rowIndex}][volume]" class="form-control volume-input" 
                               value="${preset.volume || '1'}" min="0.01" step="0.01" required></td>
                    <td><input type="number" name="pricing[${rowIndex}][purchase_price]" class="form-control purchase-input" 
                               value="${preset.purchase_price || '0.00'}" step="0.01" required></td>
                    <td><input type="number" name="pricing[${rowIndex}][selling_price]" class="form-control selling-input" 
                               value="${preset.selling_price || '0.00'}" step="0.01" required></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                            <i data-feather="trash-2"></i>
                        </button>
                    </td>
                </tr>
            `);

            $tbody.append($row);
            attachRowListeners($row[0]);
            feather.replace(); // important!
        }

        function attachRowListeners(row) {
            $(row).find('.volume-input, .purchase-input, .selling-input').on('input blur', recalculateAllRows);
        }

        function removeRow(btn) {
            $(btn).closest('tr').remove();
            recalculateAllRows();
            // If no rows left, add one
            if ($('#pricingTable tbody tr').length === 0) {
                addRow();
            }
        }

        function recalculateAllRows() {
            const $rows = $('#pricingTable tbody tr');
            if ($rows.length === 0) return;

            const $base = $rows.eq(0);
            const baseVol = parseFloat($base.find('.volume-input').val()) || 1;
            const basePur = parseFloat($base.find('.purchase-input').val()) || 0;
            const baseSel = parseFloat($base.find('.selling-input').val()) || 0;

            $rows.each(function(idx) {
                if (idx === 0) return;

                const $row = $(this);
                const vol = parseFloat($row.find('.volume-input').val()) || 1;
                const ratio = vol / baseVol;

                $row.find('.purchase-input').val((basePur * ratio).toFixed(2));
                $row.find('.selling-input').val((baseSel * ratio).toFixed(2));
            });
        }

        // ✅ IMAGE HANDLING
        function previewImages(input) {
            const preview = document.getElementById('imagePreview');
            if (!input.files.length) return;

            Array.from(input.files).forEach(file => {
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire('⚠️ Warning', `"${file.name}" exceeds 2MB limit.`, 'warning');
                    return;
                }
                if (!file.type.match(/^image\//)) {
                    Swal.fire('❌ Invalid', `"${file.name}" is not an image.`, 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.className = 'image-preview-item new-image';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="btn-remove" onclick="removePreview(this)">
                            <i data-feather="x"></i>
                        </button>
                    `;
                    preview.appendChild(div);
                    feather.replace();
                };
                reader.readAsDataURL(file);
            });
        }

        function removePreview(el) {
            el.closest('.image-preview-item').remove();
        }

        function removeExistingImage(el, id) {
            const $removed = $('#removedImages');
            let removed = $removed.val() ? $removed.val().split(',') : [];
            if (!removed.includes(id.toString())) {
                removed.push(id);
                $removed.val(removed.join(','));
            }
            $(el).closest('.image-preview-item').remove();
        }
    </script>
</body>
</html>