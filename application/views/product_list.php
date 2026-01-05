<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('partials/title-meta'); ?>
<?php $this->load->view('partials/head-css'); ?>
<style>
.product-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
    cursor: pointer;
}
.low-stock { color: #dc3545; font-weight: bold; }
.out-of-stock { color: #6c757d; font-weight: bold; }
.status-active { color: #28a745; font-weight: 500; }
.status-inactive { color: #dc3545; font-weight: 500; }
.variant-row { background-color: #f8f9fa !important; }
.variant-table td { padding: 0.5rem 0.75rem !important; }
.toggle-variants {
    cursor: pointer;
    width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    border: 1px solid #e9ecef;
    border-radius: 4px;
}
.toggle-variants:hover {
    color: #0d6efd;
    background-color: #e9ecef;
}
.stats-card h3 { font-weight: 700; }
.badge-variant { font-size: 0.75em; }
.expand-icon { transition: transform 0.3s; }
.expanded { transform: rotate(90deg); }
.s-no {
    font-weight: 500;
    text-align: center;
    width: 5%;
}
.no-expand { color: #adb5bd; }
</style>
</head>
<body>
<?php $this->load->view('partials/body'); ?>
<div class="main-wrapper">
<?php $this->load->view('partials/menu'); ?>
<div class="page-wrapper">
<div class="content">
<!-- Page Header -->
<div class="page-header">
<div class="add-item d-flex">
<div class="page-title">
<h4>Products List</h4>
<h6>Manage your products & variants</h6>
</div>
</div>
<ul class="table-top-head">
<li>
<div class="page-btn">
<a href="<?php echo base_url('home/add_product'); ?>" class="btn btn-primary">
<i data-feather="plus" class="me-2"></i>Add New Product
</a>
</div>
</li>
</ul>
</div>
<!-- Stats Cards -->
<div class="row mb-4">
<div class="col-md-3">
<div class="card stats-card text-center shadow-sm">
<div class="card-body">
<h6 class="text-muted">Total Products</h6>
<h3><?php echo $total_products ?? 0; ?></h3>
</div>
</div>
</div>
<div class="col-md-3">
<div class="card stats-card text-center shadow-sm border-warning">
<div class="card-body">
<h6 class="text-warning">Low Stock</h6>
<h3 class="text-warning"><?php echo $low_stock ?? 0; ?></h3>
</div>
</div>
</div>
<div class="col-md-3">
<div class="card stats-card text-center shadow-sm border-danger">
<div class="card-body">
<h6 class="text-danger">Out of Stock</h6>
<h3 class="text-danger"><?php echo $out_of_stock ?? 0; ?></h3>
</div>
</div>
</div>
<div class="col-md-3">
<div class="card stats-card text-center shadow-sm border-info">
<div class="card-body">
<h6 class="text-info">Total Variants</h6>
<h3 class="text-info"><?php echo $total_variants ?? 0; ?></h3>
</div>
</div>
</div>
</div>
<!-- Alerts -->
<?php if($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show">
<?php echo $this->session->flashdata('success'); ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show">
<?php echo $this->session->flashdata('error'); ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<!-- Search -->
<div class="card mb-4">
<div class="card-body">
<form method="get" action="<?php echo base_url('home/products_list'); ?>">
<div class="row g-3">
<div class="col-md-8">
<div class="input-group">
<input type="text" class="form-control" name="search"
placeholder="Search by name, barcode, category..."
value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>">
<button class="btn btn-primary" type="submit">
<i data-feather="search"></i> Search
</button>
<?php if(!empty($search)): ?>
<a href="<?php echo base_url('home/products_list'); ?>" class="btn btn-secondary">Clear</a>
<?php endif; ?>
</div>
</div>
<div class="col-md-4 text-end">
<span class="text-muted">Showing: <?php echo count($products); ?> of <?php echo $total_products ?? 0; ?> products</span>
</div>
</div>
</form>
</div>
</div>
<!-- Products Table -->
<div class="card">
<div class="card-body">
<div class="table-responsive">
<table class="table table-hover table-bordered align-middle">
<thead class="table-dark">
<tr>
<th width="5%" class="text-center">S.No</th>
<th width="5%" class="text-center">Variants</th>
<th width="8%">Image</th>
<th>Product Name</th>
<th>Barcode</th>
<th>Category → Sub Category</th>
<th width="10%">Stock</th>
<th width="10%">Status</th>
<th width="12%">Actions</th>
</tr>
</thead>
<tbody>
<?php if(empty($products)): ?>
<tr>
<td colspan="9" class="text-center py-4">No products found.</td>
</tr>
<?php else:
$base = $this->uri->segment(3) ? ($this->uri->segment(3) - 1) * $this->config->item('per_page') : 0;
$sno = $base + 1;
foreach($products as $product): ?>
<tr>
<!-- S.No -->
<td class="text-center s-no"><?php echo $sno++; ?></td>
<!-- Variant Toggle -->
<td class="text-center">
    <?php if (!empty($product->variants)): ?>
    <span class="toggle-variants"
        data-product-id="<?php echo $product->id; ?>"
        title="View <?php echo count($product->variants); ?> variant(s)">
        <i data-feather="chevron-right" class="expand-icon"></i>
    </span>
    <?php else: ?>
    <span class="no-expand" title="No variants">—</span>
    <?php endif; ?>
</td>
<!-- Image -->
<td class="text-center">
    <?php
    $img_path = !empty($product->primary_image)
        ? base_url("uploads/products/{$product->id}/{$product->primary_image}")
        : base_url('assets/img/default-product.png');
    ?>
    <img src="<?php echo $img_path; ?>" class="product-img"
        onerror="this.src='<?php echo base_url('assets/img/default-product.png'); ?>'">
</td>
<!-- Product Info -->
<td>
    <strong><?php echo htmlspecialchars($product->name); ?></strong><br>
    <small class="text-muted">
        <?php echo !empty($product->item_code) ? '#' . $product->item_code : '—'; ?>
    </small>
</td>
<td><code><?php echo htmlspecialchars($product->barcode); ?></code></td>
<td>
    <?php echo htmlspecialchars($product->category_name ?? '—'); ?>
    <?php if(!empty($product->sub_category_name)): ?>
    <br><small class="text-muted">→ <?php echo htmlspecialchars($product->sub_category_name); ?></small>
    <?php endif; ?>
</td>
<!-- Stock -->
<td class="text-center">
    <?php
    $total_stock = $product->total_stock ?? 0;
    if($total_stock == 0): ?>
    <span class="out-of-stock">Out</span>
    <?php elseif($total_stock <= ($product->alert_quantity ?? 5)): ?>
    <span class="low-stock"><?php echo $total_stock; ?> Low</span>
    <?php else: ?>
    <span class="text-success"><?php echo $total_stock; ?></span>
    <?php endif; ?>
</td>
<!-- Status -->
<td class="text-center">
    <?php if($product->status == 1): ?>
    <span class="status-active">Active</span>
    <?php else: ?>
    <span class="status-inactive">Inactive</span>
    <?php endif; ?>
</td>
<!-- Actions -->
<td class="text-center">
    <div class="d-flex justify-content-center gap-1">
        <a href="<?php echo base_url('home/view_product/'.$product->id); ?>"
           class="btn btn-info btn-sm" title="View">
            <i data-feather="eye"></i>
        </a>
        <a href="<?php echo base_url('home/edit_product/'.$product->id); ?>"
           class="btn btn-warning btn-sm" title="Edit">
            <i data-feather="edit-2"></i>
        </a>
        <a href="<?php echo base_url('home/delete_product/'.$product->id); ?>"
           onclick="return confirm('Are you sure you want to delete this product and all its variants?')"
           class="btn btn-danger btn-sm" title="Delete">
            <i data-feather="trash-2"></i>
        </a>
    </div>
</td>
</tr>

<!-- ✅ VARIANT SUB-LIST (hidden by default) -->
<?php if (!empty($product->variants)): ?>
<tr class="variant-row d-none" id="variants-<?php echo $product->id; ?>">
    <td colspan="9" class="p-0">
        <div class="px-3 py-2 bg-light">
            <h6 class="mb-2">
                <i data-feather="package" class="me-1"></i>
                <strong>Variants</strong> for "<?php echo htmlspecialchars($product->name); ?>"
                <span class="badge bg-primary ms-2"><?php echo count($product->variants); ?></span>
            </h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="15%">Unit</th>
                            <th width="12%">Volume</th>
                            <th width="20%">Purchase Price</th>
                            <th width="20%">Selling Price</th>
                            <th width="15%">Stock</th>
                            <th width="10%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($product->variants as $v): ?>
                        <tr>
                            <td>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($v->unit_name); ?></span>
                                <?php if ($v->unit_symbol): ?>
                                <small class="text-muted"> (<?php echo $v->unit_symbol; ?>)</small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo number_format($v->volume, 2); ?></td>
                            <td class="fw-bold text-success">₹<?php echo number_format($v->purchase_price, 2); ?></td>
                            <td class="fw-bold">₹<?php echo number_format($v->selling_price, 2); ?></td>
                            <td class="text-center">
                                <?php
                                $stock = $v->stock ?? 0;
                                if($stock == 0): ?>
                                    <span class="out-of-stock">Out</span>
                                <?php elseif($stock <= ($v->alert_stock ?? 5)): ?>
                                    <span class="low-stock"><?php echo $stock; ?></span>
                                <?php else: ?>
                                    <span class="text-success"><?php echo $stock; ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($v->status == 1): ?>
                                    <span class="status-active">✓ Active</span>
                                <?php else: ?>
                                    <span class="status-inactive">✗ Inactive</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </td>
</tr>
<?php endif; ?>
<?php endforeach; endif; ?>
</tbody>
</table>
</div>
<!-- Pagination -->
<?php if(isset($links)): ?>
<div class="mt-3 d-flex justify-content-center">
    <?php echo $links; ?>
</div>
<?php endif; ?>
</div>
</div>
</div>
</div>
</div>
<?php $this->load->view('partials/theme-settings'); ?>
<?php $this->load->view('partials/vendor-scripts'); ?>
<script>
$(document).ready(function() {
    feather.replace();

    // ✅ Toggle variants (delegated for dynamic content)
    $(document).on('click', '.toggle-variants', function() {
        const $icon = $(this).find('i.expand-icon');
        const productId = $(this).data('product-id');
        const $variantRow = $('#variants-' + productId);

        if ($variantRow.length) {
            $icon.toggleClass('expanded');
            $variantRow.toggleClass('d-none');
            // Optional: smooth scroll
            if (!$icon.hasClass('expanded')) {
                $('html, body').animate({
                    scrollTop: $variantRow.offset().top - 100
                }, 300);
            }
        }
    });

    // Auto-hide alerts
    setTimeout(() => $('.alert').fadeOut('slow', () => $(this).alert('close')), 5000);
});
</script>
</body>
</html>