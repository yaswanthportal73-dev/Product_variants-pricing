<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('partials/title-meta') ?>
    <?php $this->load->view('partials/head-css') ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* ===== IMAGE UPLOAD ===== */
        .image-upload {border:2px dashed #ddd;border-radius:10px;padding:40px;text-align:center;cursor:pointer;transition:all .3s}
        .image-upload:hover {border-color:#007bff;background:#f8f9fa}
        .image-uploads i {font-size:48px;color:#007bff;margin-bottom:15px}
        .image-preview {display:flex;flex-wrap:wrap;gap:15px;margin-top:20px}
        .image-preview-item {position:relative;width:120px;height:120px;border-radius:8px;overflow:hidden;border:1px solid #ddd;transition:transform .3s}
        .image-preview-item:hover {transform:scale(1.05)}
        .image-preview-item img {width:100%;height:100%;object-fit:cover}
        .btn-remove {position:absolute;top:5px;right:5px;width:30px;height:30px;border-radius:50%;background:#dc3545;color:white;border:none;display:flex;align-items:center;justify-content:center;cursor:pointer;opacity:.8;transition:opacity .3s}
        .btn-remove:hover {opacity:1}
        .select2-container--default .select2-selection--single {height:38px;border:1px solid #ced4da;border-radius:.375rem}
        .select2-container--default .select2-selection--single .select2-selection__rendered {line-height:36px}
        .select2-container--default .select2-selection--single .select2-selection__arrow {height:36px}
        #barcodePreview {min-height:150px;display:flex;align-items:center;justify-content:center;border:1px solid #dee2e6;border-radius:5px;padding:20px;background:white}
        .is-invalid {border-color:#dc3545 !important}
        .invalid-feedback {display:block;width:100%;margin-top:.25rem;font-size:.875em;color:#dc3545}
    </style>
</head>
<body>
<?php $this->load->view('partials/body') ?>
<div class="main-wrapper">
    <?php $this->load->view('partials/menu') ?>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header mb-3">
                <h4><?php echo isset($product) ? 'Edit Product' : 'New Product'; ?></h4>
            </div>

            <form action="<?php echo isset($product)? base_url('home/update_product/'.$product->id): base_url('home/add_product'); ?>" method="post" enctype="multipart/form-data" id="productForm">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <?php if(isset($product)): ?>
                    <input type="hidden" name="existing_images" id="existingImages" value="<?php echo implode(',', array_column($images,'id')); ?>">
                    <input type="hidden" name="removed_images" id="removedImages" value="">
                <?php endif; ?>

                <!-- PRODUCT INFO -->
                <div class="card mb-3">
                    <div class="card-header"><h5>Product Information</h5></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Product Name *</label>
                                <input type="text" class="form-control <?php echo form_error('name')?'is-invalid':'' ?>" name="name" required maxlength="255" value="<?php echo set_value('name',isset($product)?$product->name:''); ?>" placeholder="Product Name">
                                <?php echo form_error('name','<div class="invalid-feedback">','</div>'); ?>
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
                                    <input type="text" class="form-control <?php echo form_error('barcode')?'is-invalid':'' ?>" name="barcode" required pattern="\d{13}" maxlength="13" id="barcodeInput" value="<?php echo set_value('barcode',isset($product)?$product->barcode:''); ?>" placeholder="13 digit EAN-13">
                                    <button type="button" class="btn btn-primary" onclick="generateBarcode()">Generate</button>
                                </div>
                                <small class="text-muted">Click Generate for auto-create</small>
                                <?php echo form_error('barcode','<div class="invalid-feedback d-block">','</div>'); ?>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label>Category *</label>
                                <select class="form-control select2" name="category_id" id="categorySelect" required>
                                    <option value="">Choose Category</option>
                                    <?php foreach($categories as $c): ?>
                                    <option value="<?php echo $c->id ?>" <?php echo isset($product)&&$product->category_id==$c->id?'selected':'';?>><?php echo $c->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Sub Category</label>
                                <select class="form-control select2" name="sub_category_id" id="subCategorySelect"><option value="">Choose Sub Category</option></select>
                            </div>
                            <div class="col-md-4">
                                <label>Brand</label>
                                <select class="form-control select2" name="brand_id">
                                    <option value="">Choose Brand</option>
                                    <?php foreach($brands as $b): ?>
                                    <option value="<?php echo $b->id ?>" <?php echo isset($product)&&$product->brand_id==$b->id?'selected':'';?>><?php echo $b->name ?></option>
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
                                    <option value="<?php echo $s->id ?>" <?php echo isset($product)&&$product->supplier_id==$s->id?'selected':'';?>><?php echo $s->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Short Description</label>
                                <textarea name="short_description" class="form-control" rows="2"><?php echo set_value('short_description',isset($product)?$product->short_description:'');?></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label>Description</label>
                                <textarea name="description" class="form-control" id="description" rows="4"><?php echo set_value('description',isset($product)?$product->description:'');?></textarea>
                            </div>
                        </div>

                        <!-- BARCODE PREVIEW -->
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
                    <div class="card-header"><h5>Pricing & Variants</h5></div>
                    <div class="card-body">
                        <button type="button" class="btn btn-success mb-3" onclick="addRow()"><i data-feather="plus-circle"></i> Add Variant</button>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="pricingTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Unit</th>
                                        <th>Volume</th>
                                        <th>Purchase Price</th>
                                        <th>Selling Price</th>
                                        <th>Discount</th>
                                        <th width="80">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
                            <div class="image-uploads"><i class="fa fa-plus-circle"></i><h4>Click to upload</h4></div>
                        </div>
                        <div class="image-preview" id="imagePreview">
                            <?php if(isset($images) && $images): foreach($images as $img): ?>
                            <div class="image-preview-item" data-image-id="<?php echo $img->id ?>">
                                <img src="<?php echo base_url('uploads/products/'.$product->id.'/'.$img->image_path) ?>">
                                <button type="button" class="btn-remove" onclick="removeExistingImage(this,<?php echo $img->id ?>)"><i data-feather="x"></i></button>
                            </div>
                            <?php endforeach; endif;?>
                        </div>
                    </div>
                </div>

                <!-- STATUS -->
                <div class="card mb-3">
                    <div class="card-header"><h5>Status</h5></div>
                    <div class="card-body">
                        <input type="checkbox" name="status" value="1" <?php echo isset($product)&&$product->status==1?'checked':'';?>>
                        Active
                    </div>
                </div>

                <div class="text-end mb-5">
                    <a href="<?php echo base_url('home/products_list'); ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><?php echo isset($product)?'Update Product':'Save Product';?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('partials/vendor-scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){
    $('.select2').select2({width:'100%'});
    if(document.getElementById('description')) CKEDITOR.replace('description',{height:200});
    feather.replace();
    updateBarcodePreview();

    <?php if(isset($product)&&$product->category_id): ?>
    loadSubCategories(<?php echo $product->category_id; ?>);
    <?php endif;?>
});

// ======= BARCODE =======
function generateBarcode() {
    var firstTwo=Math.floor(Math.random()*90)+10;
    var nextTen=Math.floor(Math.random()*1e10).toString().padStart(10,'0');
    var base=firstTwo+nextTen;
    var check=calculateEAN13CheckDigit(base);
    var code=base+check;
    $('#barcodeInput').val(code);
    updateBarcodePreview();
}

function calculateEAN13CheckDigit(code){
    var sum=0;
    for(var i=0;i<12;i++){
        var d=parseInt(code[i]);
        sum+=(i%2===0?d:d*3);
    }
    var r=sum%10;
    return r===0?0:10-r;
}

function updateBarcodePreview(){
    var code=$('#barcodeInput').val();
    var preview=$('#barcodePreview');
    if(!/^\d{13}$/.test(code)){ preview.html('<small>Enter 13 digits</small>'); return;}
    var canvas=document.createElement('canvas');
    preview.html(''); preview.append(canvas);
    JsBarcode(canvas,code,{format:"EAN13",width:2,height:100,displayValue:true});
}

// ======= SUBCATEGORY =======
function loadSubCategories(catId){
    $.post('<?php echo base_url("home/products_get_sub_categories") ?>',{category_id:catId,'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},function(res){
        var options='<option value="">Choose Sub Category</option>';
        if(res.status==='success') res.data.forEach(c=>options+=`<option value="${c.id}">${c.name}</option>`);
        $('#subCategorySelect').html(options);
    },'json');
}
$('#categorySelect').on('change',function(){loadSubCategories($(this).val());});

// ======= PRICING & VARIANTS =======
const units=<?php echo json_encode($units); ?>;
function addRow(){
    const tbody=document.querySelector('#pricingTable tbody');
    const i=tbody.rows.length;
    let opts='<option value="">Select Unit</option>';
    units.forEach(u=>opts+=`<option value="${u.id}">${u.name} (${u.symbol})</option>`);
    const tr=document.createElement('tr');
    tr.innerHTML=`
        <td><select name="pricing[${i}][unit_id]" class="form-control" required>${opts}</select></td>
        <td><input type="number" name="pricing[${i}][volume]" class="form-control volume" value="1" min="1"></td>
        <td><input type="number" name="pricing[${i}][purchase_price]" class="form-control purchase" value="0" step="0.01"></td>
        <td><input type="number" name="pricing[${i}][selling_price]" class="form-control selling" value="0" step="0.01"></td>
        <td><input type="number" name="pricing[${i}][discount]" class="form-control discount" value="0" step="0.01" readonly></td>
        <td class="text-center"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i data-feather="trash-2"></i></button></td>
    `;
    tbody.appendChild(tr); feather.replace();
    tr.querySelectorAll('.volume,.purchase,.selling').forEach(el=>el.addEventListener('input',()=>autoCalculate(tr)));
}
function autoCalculate(row){
    const rows=document.querySelectorAll('#pricingTable tbody tr');
    if(rows.length===0) return;
    const base=rows[0]; const baseVol=parseFloat(base.querySelector('.volume').value)||1; const basePur=parseFloat(base.querySelector('.purchase').value)||0; const baseSel=parseFloat(base.querySelector('.selling').value)||0;
    rows.forEach(r=>{
        const vol=parseFloat(r.querySelector('.volume').value)||1;
        const ratio=vol/baseVol;
        r.querySelector('.purchase').value=(basePur*ratio).toFixed(2);
        r.querySelector('.selling').value=(baseSel*ratio).toFixed(2);
        const d=(r.querySelector('.purchase').value-r.querySelector('.selling').value>0?r.querySelector('.purchase').value-r.querySelector('.selling').value:0).toFixed(2);
        r.querySelector('.discount').value=d;
    });
}
function removeRow(btn){btn.closest('tr').remove();}
$(document).ready(function(){if($('#pricingTable tbody tr').length===0)addRow();});

// ======= IMAGE PREVIEW =======
function previewImages(input){
    const preview=document.getElementById('imagePreview');
    if(input.files) for(let i=0;i<input.files.length;i++){
        const file=input.files[i];
        if(file.size>2*1024*1024){alert('Max 2MB');continue;}
        const reader=new FileReader();
        reader.onload=function(e){
            const div=document.createElement('div'); div.className='image-preview-item new-image';
            div.innerHTML=`<img src="${e.target.result}"><button type="button" class="btn-remove" onclick="removePreview(this)"><i data-feather="x"></i></button>`;
            preview.appendChild(div); feather.replace();
        }; reader.readAsDataURL(file);
    }
}
function removePreview(el){el.parentElement.remove();}
function removeExistingImage(el,id){
    let removed=$('#removedImages').val()?$('#removedImages').val().split(','):[];
    if(!removed.includes(id.toString())) removed.push(id); $('#removedImages').val(removed.join(','));
    $(el).parent().remove();
}
</script>
</body>
</html>
