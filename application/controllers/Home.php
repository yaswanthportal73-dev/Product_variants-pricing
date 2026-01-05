<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Home_model');
        $this->load->library(['form_validation', 'upload']);
        $this->load->helper(['url', 'text']);
    }

    public function dashboard() {
        $this->load->view('index');
    }





        // Products List
  public function products_list() {
    // Get main products
    $products = $this->Home_model->get_all_products();

    // ✅ Load variants & compute total_stock for each product
    foreach ($products as &$p) {
        // Get variants
        $p->variants = $this->Home_model->get_variants_by_product($p->id);
        
        // Compute total stock (sum of variant stocks)
        $p->total_stock = array_sum(array_column($p->variants, 'stock'));
    }

    $data['products'] = $products;

    // Stats
    $data['total_products'] = count($products);
    $data['total_variants'] = $this->db->count_all_results('product_variants');

    $low = $out = 0;
    foreach ($products as $p) {
        if ($p->total_stock == 0) $out++;
        elseif ($p->total_stock <= ($p->alert_quantity ?? 5)) $low++;
    }
    $data['low_stock'] = $low;
    $data['out_of_stock'] = $out;

    $this->load->view('product_list', $data);
}




    // Get Sub Categories (AJAX)
    public function products_get_sub_categories() {
        $category_id = $this->input->post('category_id');
        $sub_categories = $this->Home_model->get_by_all_sub_categories($category_id);
        echo json_encode(['status' => 'success', 'data' => $sub_categories]);
    }

    // Save Product
    public function add_product() {
        
        $data['categories'] = $this->Home_model->get_by_all_categories();
        $data['brands']     = $this->Home_model->get_by_all_brands();
        $data['units']      = $this->Home_model->get_by_all_units();
        $data['suppliers']  = $this->Home_model->get_by_all_suppliers();

        /* VALIDATION RULES */
        $this->form_validation->set_rules('name', 'Product Name', 'required|max_length[255]');
        $this->form_validation->set_rules('barcode', 'Barcode', 
            'required|exact_length[13]|numeric|callback_validate_barcode');
        $this->form_validation->set_rules('category_id', 'Category', 'required|numeric');
        //$this->form_validation->set_rules('unit_id', 'Unit', 'required|numeric');
        $this->form_validation->set_rules('original_price', 'Original Price', 
            'required|numeric|greater_than_equal_to[0]');
        //$this->form_validation->set_rules('cost_price', 'Cost Price', 
          //  'required|numeric|greater_than_equal_to[0]');
        //$this->form_validation->set_rules('quantity', 'Quantity', 
          //  'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('alert_quantity', 'Alert Quantity', 
            'numeric|greater_than_equal_to[0]');
        
        // Custom validation for discount
        $this->form_validation->set_rules('discount_type', 'Discount Type', 'callback_validate_discount');
        $this->form_validation->set_rules('original_price', 'Original Price', 'callback_check_original_price');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('add-product', $data);
            return;
        }

        
        
        // Calculate discount and final price
        $original_price = (float)$this->input->post('original_price');
        $discount_type = $this->input->post('discount_type');
        $discount_value = (float)$this->input->post('discount_value') ?: 0;
        
        // Calculate discount amount and percentage
        $discount_amount = 0;
        $discount_percentage = 0;
        
        if ($discount_type == 'percentage') {
            $discount_amount = $original_price * ($discount_value / 100);
            $discount_percentage = $discount_value;
        } elseif ($discount_type == 'flat') {
            $discount_amount = $discount_value;
            $discount_percentage = $original_price > 0 ? ($discount_amount / $original_price) * 100 : 0;
        }
        
        // Calculate final selling price
        $selling_price = $original_price - $discount_amount;
        if ($selling_price < 0) $selling_price = 0;

        // Auto-generate item code if empty
        $item_code = $this->input->post('item_code');
        if (empty($item_code)) {
            $item_code = $this->generate_item_code();
        }

        // Product Data
        $product_data = [
                    'name'              => $this->input->post('name'),
                    'barcode'           => $this->input->post('barcode'),
                    'barcode_type'      => 'EAN13',
                    'item_code'         => $item_code,
                    'category_id'       => $this->input->post('category_id'),
                    'sub_category_id'   => $this->input->post('sub_category_id') ?: NULL,
                    'brand_id'          => $this->input->post('brand_id') ?: NULL,
                    'supplier_id'       => $this->input->post('supplier_id') ?: NULL,
                    'short_description' => $this->input->post('short_description'),
                    'description'       => $this->input->post('description'),
                    'expiry_date'       => $this->input->post('expiry_date') ?: NULL,
                    'status'            => $this->input->post('status') ? 1 : 0,
                    'created_at'        => date('Y-m-d H:i:s')
                ];

        $product_id = $this->Home_model->save_product($product_data);

       // Upload Images
$this->upload_product_images($product_id);

// Save Variants
$this->product_variants($product_id);

$this->session->set_flashdata('success', 'Product added successfully!');
redirect('home/products_list');
}







/**
 * Save Product Variants
 */
private function product_variants($product_id)
{
    $pricing = $this->input->post('pricing'); // ← matches JS: pricing[0][unit_id]

    if (empty($pricing) || !is_array($pricing)) {
        return;
    }

    foreach ($pricing as $idx => $data) {
        $unit_id = (int)($data['unit_id'] ?? 0);
        if (!$unit_id) continue; // skip empty rows

        $volume = (float)($data['volume'] ?? 1);
        $purchase_price = (float)($data['purchase_price'] ?? 0);
        $selling_price = (float)($data['selling_price'] ?? 0);

        // Insert into product_variants (NO 'discount' column!)
        $this->db->insert('product_variants', [
            'product_id'     => $product_id,
            'unit_id'        => $unit_id,
            'volume'         => max(0.01, $volume),
            'purchase_price' => max(0, $purchase_price),
            'selling_price'  => max(0, $selling_price),
            'stock'          => 0,
            'alert_stock'    => 5,
            'status'         => 1,
            'created_at'     => date('Y-m-d H:i:s')
        ]);
    }
}






    // Edit Product Page
    public function edit_product($id) {
        $data['product'] = $this->Home_model->get_product_by_id($id);
        // Fetches all categories, brands, units, suppliers as required
        $data['categories'] = $this->Home_model->get_by_all_categories();
        $data['brands'] = $this->Home_model->get_by_all_brands();
        $data['units'] = $this->Home_model->get_by_all_units();
        $data['suppliers'] = $this->Home_model->get_by_all_suppliers();
        // Fetches product-specific images as required
        $data['images'] = $this->Home_model->get_product_images($id);
        $data['variants'] = $this->Home_model->get_variants_by_product($id);
        

        if (!$data['product']) {
            show_404();
        }

        $this->load->view('edit-product', $data);
    }

    // Update Product
    // Update Product
public function update_product($id) {
    // Re-fetch data for validation failure (includes all categories and images)
    $data['product'] = $this->Home_model->get_product_by_id($id);
    $data['images']  = $this->Home_model->get_product_images($id);
    $data['categories'] = $this->Home_model->get_by_all_categories();
    $data['brands'] = $this->Home_model->get_by_all_brands();
    $data['units'] = $this->Home_model->get_by_all_units();
    $data['suppliers'] = $this->Home_model->get_by_all_suppliers();

    if (!$data['product']) {
        show_404();
    }

    /* VALIDATION RULES */
    $this->form_validation->set_rules('name', 'Product Name', 'required|max_length[255]');
    $this->form_validation->set_rules('barcode', 'Barcode', 
        'required|exact_length[13]|numeric|callback_validate_barcode_edit['.$id.']');
    $this->form_validation->set_rules('category_id', 'Category', 'required|numeric');
    // 'unit_id' is optional now (handled via variants)
    // $this->form_validation->set_rules('unit_id', 'Unit', 'required|numeric');
    $this->form_validation->set_rules('original_price', 'Original Price', 
        'required|numeric|greater_than_equal_to[0]');
    $this->form_validation->set_rules('cost_price', 'Cost Price', 
        'required|numeric|greater_than_equal_to[0]');
    $this->form_validation->set_rules('quantity', 'Quantity', 
        'required|numeric|greater_than_equal_to[0]');
    $this->form_validation->set_rules('alert_quantity', 'Alert Quantity', 
        'numeric|greater_than_equal_to[0]');
    
    // Custom validation for discount
    $this->form_validation->set_rules('discount_type', 'Discount Type', 'callback_validate_discount');
    $this->form_validation->set_rules('original_price', 'Original Price', 'callback_check_original_price');

    if ($this->form_validation->run() == FALSE) {
        $this->load->view('edit-product', $data);
        return;
    }

    // Calculate discount and final price
    $original_price = (float)$this->input->post('original_price');
    $discount_type = $this->input->post('discount_type');
    $discount_value = (float)$this->input->post('discount_value') ?: 0;
    
    $discount_amount = 0;
    if ($discount_type == 'percentage') {
        $discount_amount = $original_price * ($discount_value / 100);
    } elseif ($discount_type == 'flat') {
        $discount_amount = $discount_value;
    }
    
    $selling_price = max(0, $original_price - $discount_amount);

    // Product Data (unit_id can be NULL — variants handle units)
    $product_data = [
        'name'              => $this->input->post('name'),
        'barcode'           => $this->input->post('barcode'),
        'category_id'       => $this->input->post('category_id'),
        'sub_category_id'   => $this->input->post('sub_category_id') ?: NULL,
        'brand_id'          => $this->input->post('brand_id') ?: NULL,
        'unit_id'           => NULL, // ✅ Set to NULL — variants store unit now
        'supplier_id'       => $this->input->post('supplier_id') ?: NULL,
        'short_description' => $this->input->post('short_description'),
        'description'       => $this->input->post('description'),
        'cost_price'        => $this->input->post('cost_price'),
        'original_price'    => $original_price,
        'selling_price'     => $selling_price,
        'discount_type'     => $discount_type ?: NULL,
        'discount_value'    => $discount_value,
        'discount_amount'   => $discount_amount,
        'quantity'          => $this->input->post('quantity'),
        'alert_quantity'    => $this->input->post('alert_quantity') ?: 5,
        'min_order_qty'     => $this->input->post('min_order_qty') ?: 1,
        'max_order_qty'     => $this->input->post('max_order_qty') ?: 10,
        'expiry_date'       => $this->input->post('expiry_date') ? date('Y-m-d', strtotime($this->input->post('expiry_date'))) : NULL,
        'status'            => $this->input->post('status') ? 1 : 0,
        'updated_at'        => date('Y-m-d H:i:s')
    ];

    // ✅ Update main product
    $updated = $this->Home_model->update_product($id, $product_data);
    
    if ($updated) {
        // ✅ Upload new images
        $this->upload_product_images($id);

        // ✅ Handle removed images
        $removed_images = $this->input->post('removed_images');
        if (!empty($removed_images)) {
            $ids = explode(',', $removed_images);
            foreach ($ids as $img_id) {
                $this->Home_model->delete_image((int)$img_id);
            }
        }

        // ✅ 🔥 CRITICAL: Delete old variants & insert new ones — AFTER validation
        $this->db->where('product_id', $id)->delete('product_variants');
        $this->product_variants($id); // ← uses your perfect method above

        $this->session->set_flashdata('success', 'Product updated successfully with variants.');
    } else {
        $this->session->set_flashdata('error', 'Failed to update product.');
    }

    redirect('home/products_list');
}







   // Delete Product
public function delete_product($id = null)
{
    // Check if ID is valid
    if (!$id || !is_numeric($id)) {
        $this->session->set_flashdata('error', 'Invalid Product ID');
        redirect('home/products_list'); // Make sure this matches your actual route
    }

    // Call the model to delete the product
    $deleted = $this->Home_model->delete_product($id);

    // Set flash message based on result
    if ($deleted) {
        $this->session->set_flashdata('success', 'Product deleted successfully!');
    } else {
        $this->session->set_flashdata('error', 'Failed to delete product!');
    }

    // Redirect back to product list
    redirect('home/products_list'); // Make sure this matches your route
}


    // CALLBACK: Validate Barcode for Add
    public function validate_barcode($barcode) {
        if ($this->Home_model->barcode_exists($barcode)) {
            $this->form_validation->set_message(
                'validate_barcode',
                'The {field} already exists.'
            );
            return false;
        }
        return true;
    }

    // CALLBACK: Validate Barcode for Edit
    public function validate_barcode_edit($barcode, $id) {
        if ($this->Home_model->barcode_exists($barcode, $id)) {
            $this->form_validation->set_message(
                'validate_barcode_edit',
                'The {field} already exists.'
            );
            return false;
        }
        return true;
    }

    // CALLBACK: Validate Discount
    public function validate_discount($discount_type) {
        $discount_value = (float)$this->input->post('discount_value');
        
        if ($discount_type == 'percentage' && $discount_value > 100) {
            $this->form_validation->set_message(
                'validate_discount',
                'Discount percentage cannot exceed 100%.'
            );
            return false;
        }
        
        if ($discount_type == 'flat') {
            $original_price = (float)$this->input->post('original_price');
            if ($discount_value > $original_price) {
                $this->form_validation->set_message(
                    'validate_discount',
                    'Discount amount cannot exceed original price.'
                );
                return false;
            }
        }
        
        return true;
    }

    // CALLBACK: Check Original Price
    public function check_original_price($original_price) {
        $cost_price = (float)$this->input->post('cost_price');
        
        if ($original_price < $cost_price) {
            $this->form_validation->set_message(
                'check_original_price',
                'Original price cannot be less than cost price.'
            );
            return false;
        }
        
        return true;
    }

    // Upload Product Images
    private function upload_product_images($product_id) {
        if (empty($_FILES['images']['name'][0])) {
            return;
        }

        $upload_path = './uploads/products/' . $product_id . '/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $files = $_FILES['images'];
        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            // Skip if no file
            if (empty($files['name'][$i])) continue;

            $_FILES['image']['name']     = $files['name'][$i];
            $_FILES['image']['type']     = $files['type'][$i];
            $_FILES['image']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['image']['error']    = $files['error'][$i];
            $_FILES['image']['size']     = $files['size'][$i];

            $config = [
                'upload_path'   => $upload_path,
                'allowed_types' => 'jpg|jpeg|png|gif|webp',
                'max_size'      => 2048, // 2MB
                'encrypt_name'  => true,
                'overwrite'     => false
            ];

            $this->upload->initialize($config);

            if ($this->upload->do_upload('image')) {
                $upload_data = $this->upload->data();

                // Check if this is the first image for this product
                $is_primary = 0;
                $existing_images = $this->Home_model->get_product_images($product_id);
                if (empty($existing_images)) {
                    $is_primary = 1;
                }

                $image_data = [
                    'product_id' => $product_id,
                    'image_path' => $upload_data['file_name'],
                    'is_primary' => $is_primary,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $this->Home_model->save_product_image($image_data);

            } else {
                $error = $this->upload->display_errors();
                log_message('error', 'Image upload failed: ' . $error);
                $this->session->set_flashdata('error', 'Some images failed to upload: ' . $error);
            }
        }
    }

    // Delete Image
    public function delete_image($id) {
        $success = $this->Home_model->delete_image($id);
        if ($success) {
            $this->session->set_flashdata('success', 'Image deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete image.');
        }
        redirect($this->agent->referrer());
    }

    // Set Primary Image
    public function set_primary_image($product_id, $image_id) {
        $success = $this->Home_model->set_primary_image($product_id, $image_id);
        if ($success) {
            $this->session->set_flashdata('success', 'Primary image updated!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update primary image.');
        }
        redirect($this->agent->referrer());
    }

    // Generate Item Code
    private function generate_item_code() {
        $timestamp = time();
        $random = mt_rand(1000, 9999);
        return 'PROD-' . date('Ymd', $timestamp) . '-' . $random;
    }

    // Calculate Final Price (Legacy - kept for compatibility)
    private function calculate_final_price($price, $type, $value) {
        if ($type == 'percentage') {
            return $price - ($price * $value / 100);
        } elseif ($type == 'flat') {
            return $price - $value;
        }
        return $price;
    }
    
    /* ===================== CATEGORIES ===================== */

       
    
  public function category_list() {
        $this->load->view('category-list');
    }

    public function get_categories() {
        $cats = $this->Home_model->get_all_categories();
        echo json_encode([
            'success' => true,
            'categories' => $cats,
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }

    public function add_category() {
        $this->form_validation->set_rules('name','Name','required');

        if(!$this->form_validation->run()){
            echo json_encode(['success'=>false,'message'=>validation_errors()]);
            return;
        }

        $name = $this->input->post('name',true);
        $status = $this->input->post('status') ? 1 : 0;

        $slug = url_title($name,'dash',true);
        $base = $slug; $i=1;
        while($this->Home_model->category_slug_exists($slug)){
            $slug = $base.'-'.$i++;
        }

        $data = [
            'name'=>$name,
            'slug'=>$slug,
            'status'=>$status,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s')
        ];

        if(!empty($_FILES['image']['name'])){
            $img = $this->upload_image('image');
            if(!$img['success']){
                echo json_encode(['success'=>false,'message'=>$img['error']]);
                return;
            }
            $data['image'] = $img['file'];
        }

        $this->Home_model->insert_category($data);
        echo json_encode(['success'=>true,'message'=>'Category added successfully']);
    }

    public function edit_category($id) {
        $cat = $this->Home_model->get_category_by_id($id);
        if(!$cat){
            echo json_encode(['success'=>false,'message'=>'Not found']); return;
        }

        $name = $this->input->post('name',true);
        $status = $this->input->post('status') ? 1 : 0;

        $slug = $cat->slug;
        if($name != $cat->name){
            $slug = url_title($name,'dash',true);
            $base = $slug; $i=1;
            while($this->Home_model->category_slug_exists($slug,$id)){
                $slug = $base.'-'.$i++;
            }
        }

        $data = [
            'name'=>$name,
            'slug'=>$slug,
            'status'=>$status,
            'updated_at'=>date('Y-m-d H:i:s')
        ];

        if(!empty($_FILES['image']['name'])){
            $img = $this->upload_image('image');
            if(!$img['success']){
                echo json_encode(['success'=>false,'message'=>$img['error']]); return;
            }
            if($cat->image){
                @unlink(FCPATH.'uploads/categories/'.$cat->image);
            }
            $data['image'] = $img['file'];
        }

        $this->Home_model->update_category($id,$data);
        echo json_encode(['success'=>true,'message'=>'Updated successfully']);
    }

    public function delete_category($id) {
        $this->Home_model->delete_category($id);
        echo json_encode(['success'=>true,'message'=>'Deleted successfully']);
    }

    private function upload_image($field){
        $path = FCPATH.'uploads/categories/';
        if(!is_dir($path)) mkdir($path,0755,true);

        $config = [
            'upload_path'=>$path,
            'allowed_types'=>'jpg|jpeg|png|gif|webp',
            'max_size'=>2048,
            'encrypt_name'=>true
        ];

        $this->upload->initialize($config);

        if($this->upload->do_upload($field)){
            $d = $this->upload->data();
            return ['success'=>true,'file'=>$d['file_name']];
        } else {
            return ['success'=>false,'error'=>$this->upload->display_errors()];
        }
    }

    /* ===================== SUB CATEGORIES ===================== */

    public function sub_categories() {
        $data['categories'] = $this->Home_model->get_all_categories();
        $this->load->view('sub-category-list', $data);
    }

    public function get_sub_categories() {
        echo json_encode([
            'success' => true,
            'categories' => $this->Home_model->get_all_subcategories(),
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        exit;
    }

    public function add_sub_category() {
        $this->form_validation->set_rules('name', 'Sub Category', 'required|trim');
        $this->form_validation->set_rules('category_id', 'Category', 'required|integer');

        if (!$this->form_validation->run()) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors(),
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            exit;
        }

        $name = $this->input->post('name', true);
        $category_id = $this->input->post('category_id', true);
        $status = $this->input->post('status') ? 1 : 0;

        if ($this->Home_model->subcategory_exists($name, $category_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Sub-category already exists',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            exit;
        }

        $slug = url_title($name, 'dash', true);
        $base = $slug; $i = 1;
        while ($this->Home_model->subcategory_slug_exists($slug)) {
            $slug = $base . '-' . $i++;
        }

        $this->Home_model->insert_subcategory([
            'category_id' => $category_id,
            'name' => $name,
            'slug' => $slug,
            'status' => $status
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Sub-category added',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        exit;
    }
    public function edit_sub_category($id) {
    $this->form_validation->set_rules('name', 'Sub Category', 'required|trim');
    $this->form_validation->set_rules('category_id', 'Category', 'required|integer');

    if (!$this->form_validation->run()) {
        echo json_encode([
            'success' => false,
            'message' => validation_errors(),
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        exit;
    }

    $name = $this->input->post('name', true);
    $category_id = $this->input->post('category_id', true);
    $status = $this->input->post('status') ? 1 : 0;

    // Check if subcategory exists with same name and category (excluding current one)
    if ($this->Home_model->subcategory_exists($name, $category_id, $id)) {
        echo json_encode([
            'success' => false,
            'message' => 'Sub-category already exists',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        exit;
    }

    $slug = url_title($name, 'dash', true);
    $base = $slug; $i = 1;
    
    // Check slug uniqueness (excluding current record)
    while ($this->Home_model->subcategory_slug_exists($slug, $id)) {
        $slug = $base . '-' . $i++;
    }

    $this->Home_model->update_subcategory($id, [
        'category_id' => $category_id,
        'name' => $name,
        'slug' => $slug,
        'status' => $status
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Sub-category updated',
        'csrf_token' => $this->security->get_csrf_hash()
    ]);
    exit;
}

    public function delete_sub_category($id) {
        $this->Home_model->delete_subcategory($id);
        echo json_encode([
            'success' => true,
            'message' => 'Sub-category deleted',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        exit;
    }

    /* ===================== UNITS ===================== */

    public function units() {
    $this->load->view('unit-list');
    }

    public function get_units_data() {
        echo json_encode([
            'success' => true,
            'units' => $this->Home_model->get_all_units(),
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        exit;
    }

    public function add_unit() {
        $this->form_validation->set_rules('name', 'Unit Name', 'required|trim');

        if (!$this->form_validation->run()) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors(),
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            exit;
        }

        $this->Home_model->insert_unit([
            'name' => $this->input->post('name', true),
            'symbol' => $this->input->post('symbol', true),
            'status' => $this->input->post('status') ? 1 : 0
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Unit added successfully',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        exit;
    }

    public function edit_unit($id) {
        $this->form_validation->set_rules('name', 'Unit Name', 'required|trim');

        if (!$this->form_validation->run()) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors(),
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            exit;
        }

        $this->Home_model->update_unit($id, [
            'name' => $this->input->post('name', true),
            'symbol' => $this->input->post('symbol', true),
            'status' => $this->input->post('status') ? 1 : 0
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Unit updated successfully',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        exit;
    }

        public function delete_unit($id) {
            $this->Home_model->delete_unit($id);
            echo json_encode([
                'success' => true,
                'message' => 'Unit deleted',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            exit;
        }
        // 
        public function brands() {
            $this->load->view('brand-list');
        }

        public function get_brands_data() {
            echo json_encode([
                'success' => true,
                'brands' => $this->Home_model->get_all_brands(),
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            exit;
        }

public function add_brand() {
    $this->form_validation->set_rules('name', 'Brand Name', 'required|trim');

    if (!$this->form_validation->run()) {
        echo json_encode([
            'success' => false,
            'message' => validation_errors(),
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        exit;
    }

    $name   = $this->input->post('name', true);
    $status = $this->input->post('status') ? 1 : 0;

    $this->Home_model->insert_brand([
        'name'   => $name,
        'status' => $status
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Brand added',
        'csrf_token' => $this->security->get_csrf_hash()
    ]);
    exit;
    }

    public function edit_brand($id) {
        $name   = $this->input->post('name', true);
        $status = $this->input->post('status') ? 1 : 0;

        $this->Home_model->update_brand($id, [
            'name'   => $name,
            'status' => $status
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Brand updated',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        exit;
    }

    public function toggle_brand_status($id) {
        $this->Home_model->toggle_brand_status(
            $id,
            $this->input->post('status')
        );

        echo json_encode([
            'success' => true,
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        exit;
    }

    public function delete_brand($id) {
        $this->Home_model->delete_brand($id);

        echo json_encode([
            'success' => true,
            'message' => 'Brand deleted',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
        exit;
    }


// Supplier
    public function get_all()
    {
        $data['suppliers'] = $this->Home_model->get_all();
        $this->load->view('supplier-list', $data);
    }

    public function store()
    {
        $this->_validate_form();

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
            redirect('suppliers');
            return;
        }

        $data = [
            'name'           => $this->input->post('name', TRUE),
            'email'          => $this->input->post('email', TRUE),
            'phone'          => $this->input->post('phone', TRUE),
            'address'        => $this->input->post('address', TRUE),
            'city'           => $this->input->post('city', TRUE),
            'country'        => $this->input->post('country', TRUE),
            'description'    => $this->input->post('description', TRUE),
            'contact_person' => $this->input->post('contact_person', TRUE),
            'payment_terms'  => $this->input->post('payment_terms', TRUE),
            'categories'     => $this->input->post('categories', TRUE),
            'status'         => $this->input->post('status') ? 1 : 0,
            'created_at'     => date('Y-m-d H:i:s')
        ];

        $result = $this->Home_model->insert($data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Supplier added successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to add supplier');
        }
        
        redirect('suppliers');
    }

    public function update($id)
    {
        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $this->_validate_form();

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
            redirect('suppliers');
            return;
        }

        $data = [
            'name'           => $this->input->post('name', TRUE),
            'email'          => $this->input->post('email', TRUE),
            'phone'          => $this->input->post('phone', TRUE),
            'address'        => $this->input->post('address', TRUE),
            'city'           => $this->input->post('city', TRUE),
            'country'        => $this->input->post('country', TRUE),
            'description'    => $this->input->post('description', TRUE),
            'contact_person' => $this->input->post('contact_person', TRUE),
            'payment_terms'  => $this->input->post('payment_terms', TRUE),
            'categories'     => $this->input->post('categories', TRUE),
            'status'         => $this->input->post('status') ? 1 : 0,
            'updated_at'     => date('Y-m-d H:i:s')
        ];

        $result = $this->Home_model->update($id, $data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Supplier updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to update supplier');
        }
        
        redirect('suppliers');
    }

    public function delete($id)
    {
        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $result = $this->Home_model->delete($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Supplier deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete supplier');
        }
        
        redirect('suppliers');
    }

    public function get_supplier($id)
    {
        if (!$id || !is_numeric($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            return;
        }

        $supplier = $this->Home_model->get_by_id($id);
        
        if ($supplier) {
            echo json_encode([
                'success' => true,
                'data' => $supplier
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Supplier not found'
            ]);
        }
    }

    private function _validate_form()
    {
        $this->form_validation->set_rules('name', 'Supplier Name', 'required|trim|min_length[3]|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[100]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('address', 'Address', 'trim|max_length[500]');
        $this->form_validation->set_rules('city', 'City', 'trim|max_length[100]');
        $this->form_validation->set_rules('country', 'Country', 'trim|max_length[100]');
        $this->form_validation->set_rules('description', 'Description', 'trim|max_length[500]');
        $this->form_validation->set_rules('contact_person', 'Contact Person', 'trim|max_length[100]');
        $this->form_validation->set_rules('payment_terms', 'Payment Terms', 'trim|max_length[50]');

        $this->form_validation->set_error_delimiters('<div class="text-danger small mt-1">', '</div>');
    }


    /* ===================== PURCHASES ===================== */

        public function purchases() {
        $data['suppliers'] = $this->Home_model->get_suppliers();
        $data['products'] = $this->Home_model->get_products();
        $this->load->view('purchase-list', $data);
    }

    public function get_purchases_data() {
        echo json_encode([
            'purchases' => $this->Home_model->get_all_purchases(),
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }

    public function get_purchase($id) {
        echo json_encode([
            'success' => true,
            'data' => $this->Home_model->get_purchase_with_items($id),
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }

    public function add_purchase() {
        $post = $this->input->post();
        $this->db->trans_begin();

        if ($this->Home_model->reference_exists($post['reference'])) {
            echo json_encode(['success' => false, 'message' => 'Reference exists']);
            return;
        }

        $grand = (float)$post['grand_total'];
        $paid = (float)$post['paid'];

        $pid = $this->Home_model->insert_purchase([
            'supplier_id' => $post['supplier_id'],
            'reference' => $post['reference'],
            'date' => $post['date'],
            'status' => $post['status'],
            'grand_total' => $grand,
            'paid' => $paid,
            'due' => max(0, $grand - $paid),
            'order_tax' => $post['order_tax'],
            'discount_total' => $post['discount_total'],
            'shipping' => $post['shipping'],
            'payment_status' => $post['payment_status'],
            'notes' => $post['notes']
        ]);

        foreach ($post['items'] as $it) {
            $this->Home_model->insert_purchase_item([
                'purchase_id' => $pid,
                'product_id' => $it['product_id'],
                'product_name' => $this->Home_model->get_product_name($it['product_id']),
                'qty' => $it['qty'],
                'purchase_price' => $it['purchase_price'],
                'total' => $it['qty'] * $it['purchase_price']
            ]);
            $this->Home_model->update_product_quantity($it['product_id'], $it['qty'], 'increase');
        }

        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            echo json_encode(['success' => true, 'message' => 'Added successfully', 'csrf_token' => $this->security->get_csrf_hash()]);
        } else {
            $this->db->trans_rollback();
            echo json_encode(['success' => false, 'message' => 'Failed']);
        }
    }

    public function edit_purchase($id) {
        $post = $this->input->post();
        $this->db->trans_begin();

        if ($this->Home_model->reference_exists($post['reference'], $id)) {
            echo json_encode(['success' => false, 'message' => 'Reference exists']);
            return;
        }

        foreach ($this->Home_model->get_purchase_items($id) as $old) {
            $this->Home_model->update_product_quantity($old->product_id, $old->qty, 'decrease');
        }
        $this->Home_model->delete_purchase_items($id);

        $grand = (float)$post['grand_total'];
        $paid = (float)$post['paid'];

        $this->Home_model->update_purchase($id, [
            'supplier_id' => $post['supplier_id'],
            'reference' => $post['reference'],
            'date' => $post['date'],
            'status' => $post['status'],
            'grand_total' => $grand,
            'paid' => $paid,
            'due' => max(0, $grand - $paid),
            'order_tax' => $post['order_tax'],
            'discount_total' => $post['discount_total'],
            'shipping' => $post['shipping'],
            'payment_status' => $post['payment_status'],
            'notes' => $post['notes']
        ]);

        foreach ($post['items'] as $it) {
            $this->Home_model->insert_purchase_item([
                'purchase_id' => $id,
                'product_id' => $it['product_id'],
                'product_name' => $this->Home_model->get_product_name($it['product_id']),
                'qty' => $it['qty'],
                'purchase_price' => $it['purchase_price'],
                'total' => $it['qty'] * $it['purchase_price']
            ]);
            $this->Home_model->update_product_quantity($it['product_id'], $it['qty'], 'increase');
        }

        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            echo json_encode(['success' => true, 'message' => 'Updated successfully', 'csrf_token' => $this->security->get_csrf_hash()]);
        } else {
            $this->db->trans_rollback();
            echo json_encode(['success' => false, 'message' => 'Update failed']);
        }
    }

    public function delete_purchase($id) {
        $this->db->trans_begin();

        foreach ($this->Home_model->get_purchase_items($id) as $it) {
            $this->Home_model->update_product_quantity($it->product_id, $it->qty, 'decrease');
        }
        $this->Home_model->delete_purchase_items($id);
        $this->Home_model->delete_purchase($id);

        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            echo json_encode(['success' => true, 'message' => 'Deleted', 'csrf_token' => $this->security->get_csrf_hash()]);
        } else {
            $this->db->trans_rollback();
            echo json_encode(['success' => false, 'message' => 'Delete failed']);
        }
    }

    // New method for viewing a single purchase
    public function view_purchase($id) {
        $data = $this->Home_model->get_purchase_with_items($id);
        if (!$data['purchase']) {
            show_404();
        }
        $data['purchase'] = $data['purchase'];
        $data['items'] = $data['items'];
        $this->load->view('purchase-details', $data);
    }
    
    /* ================= PAGE ================= */
    public function payment_methods() {
        $this->load->view('payment-methods-view');
    }

    /* ================= LIST ================= */
    public function get_payment_methods_data() {

        $payment_methods = $this->Home_model->get_payment_methods();

        $this->output->set_content_type('application/json');
        echo json_encode([
            'payment_methods' => $payment_methods,
            'csrf_token'      => $this->security->get_csrf_hash()
        ]);
    }

    /* ================= ADD ================= */
    public function add_payment_method() {

        $data = [
            'name'         => $this->input->post('name'),
            'code'         => $this->input->post('code'),
            'extra_charge' => $this->input->post('extra_charge'),
            'status'       => $this->input->post('status') ? 1 : 0
        ];

        $insert = $this->Home_model->insert_payment_method($data);

        $this->output->set_content_type('application/json');
        echo json_encode([
            'success'    => $insert ? true : false,
            'message'    => $insert ? 'Payment method added successfully' : 'Failed to add payment method',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }

    /* ================= UPDATE ================= */
    public function edit_payment_method($id) {

        $data = [
            'name'         => $this->input->post('name'),
            'code'         => $this->input->post('code'),
            'extra_charge' => $this->input->post('extra_charge'),
            'status'       => $this->input->post('status') ? 1 : 0
        ];

        $update = $this->Home_model->update_payment_method($id, $data);

        $this->output->set_content_type('application/json');
        echo json_encode([
            'success'    => $update ? true : false,
            'message'    => $update ? 'Payment method updated successfully' : 'Failed to update payment method',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }

    /* ================= STATUS ================= */
    public function toggle_payment_status($id) {

        $update = $this->Home_model->update_payment_method($id, [
            'status' => $this->input->post('status')
        ]);

        $this->output->set_content_type('application/json');
        echo json_encode([
            'success'    => $update ? true : false,
            'message'    => 'Status updated successfully',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }

    /* ================= DELETE ================= */
    public function delete_payment_method($id) {

        $delete = $this->Home_model->delete_payment_method($id);

        $this->output->set_content_type('application/json');
        echo json_encode([
            'success'    => $delete ? true : false,
            'message'    => $delete ? 'Payment method deleted successfully' : 'Failed to delete payment method',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }


    /*======================SHIPPINGS=========================*/
    
    public function shipping_methods() {
        $this->load->view('shipping_methods_view');
    }
    
    /**
     * Get shipping methods data for DataTable
     */
    public function get_shipping_methods_data() {
        $this->load->helper('url');
        
        $data = $this->Home_model->get_shipping_methods();
        
        $response = array(
            'shipping_methods' => $data,
            'csrf_token' => $this->security->get_csrf_hash()
        );
        
        echo json_encode($response);
    }
    
    /**
     * Add new shipping method
     */
    public function add_shipping_method() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('name', 'Shipping Name', 'required|trim');
        // $this->form_validation->set_rules('type', 'Shipping Type', 'required|trim');
        $this->form_validation->set_rules('charge', 'Shipping Charge', 'required|numeric');
        $this->form_validation->set_rules('min_order_amount', 'Minimum Order Amount', 'numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $response = array(
                'success' => false,
                'message' => validation_errors(),
                'csrf_token' => $this->security->get_csrf_hash()
            );
        } else {
            // Check if shipping method already exists
            if ($this->Home_model->shipping_method_exists($this->input->post('name'))) {
                $response = array(
                    'success' => false,
                    'message' => 'Shipping method with this name already exists.',
                    'csrf_token' => $this->security->get_csrf_hash()
                );
            } else {
                $data = array(
                    'name' => $this->input->post('name'),
                    'type' => $this->input->post('type'),
                    'charge' => $this->input->post('charge'),
                    'min_order_amount' => $this->input->post('min_order_amount') ?: 0,
                    'delivery_time' => $this->input->post('delivery_time'),
                    'status' => $this->input->post('status')
                );
                
                if ($this->Home_model->add_shipping_method($data)) {
                    $response = array(
                        'success' => true,
                        'message' => 'Shipping method added successfully.',
                        'csrf_token' => $this->security->get_csrf_hash()
                    );
                } else {
                    $response = array(
                        'success' => false,
                        'message' => 'Failed to add shipping method. Please try again.',
                        'csrf_token' => $this->security->get_csrf_hash()
                    );
                }
            }
        }
        
        echo json_encode($response);
    }
    
    /**
     * Edit shipping method
     */
    public function edit_shipping_method($id) {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('name', 'Shipping Name', 'required|trim');
        // $this->form_validation->set_rules('type', 'Shipping Type', 'required|trim');
        $this->form_validation->set_rules('charge', 'Shipping Charge', 'required|numeric');
        $this->form_validation->set_rules('min_order_amount', 'Minimum Order Amount', 'numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $response = array(
                'success' => false,
                'message' => validation_errors(),
                'csrf_token' => $this->security->get_csrf_hash()
            );
        } else {
            // Check if shipping method already exists (excluding current)
            if ($this->Home_model->shipping_method_exists($this->input->post('name'), $id)) {
                $response = array(
                    'success' => false,
                    'message' => 'Another shipping method with this name already exists.',
                    'csrf_token' => $this->security->get_csrf_hash()
                );
            } else {
                $data = array(
                    'name' => $this->input->post('name'),
                    'type' => $this->input->post('type'),
                    'charge' => $this->input->post('charge'),
                    'min_order_amount' => $this->input->post('min_order_amount') ?: 0,
                    'delivery_time' => $this->input->post('delivery_time'),
                    'status' => $this->input->post('status')
                );
                
                if ($this->Home_model->update_shipping_method($id, $data)) {
                    $response = array(
                        'success' => true,
                        'message' => 'Shipping method updated successfully.',
                        'csrf_token' => $this->security->get_csrf_hash()
                    );
                } else {
                    $response = array(
                        'success' => false,
                        'message' => 'Failed to update shipping method. Please try again.',
                        'csrf_token' => $this->security->get_csrf_hash()
                    );
                }
            }
        }
        
        echo json_encode($response);
    }
    
    /**
     * Toggle shipping method status
     */
    public function toggle_shipping_status($id) {
        $status = $this->input->post('status');
        
        if ($this->Home_model->toggle_status($id, $status)) {
            $response = array(
                'success' => true,
                'message' => 'Shipping method status updated.',
                'csrf_token' => $this->security->get_csrf_hash()
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Failed to update status.',
                'csrf_token' => $this->security->get_csrf_hash()
            );
        }
        
        echo json_encode($response);
    }
    
    /**
     * Delete shipping method
     */
    public function delete_shipping_method($id) {
        $shipping_method = $this->Home_model->get_shipping_method($id);
        
        if (!$shipping_method) {
            $response = array(
                'success' => false,
                'message' => 'Shipping method not found.',
                'csrf_token' => $this->security->get_csrf_hash()
            );
        } else {
            if ($this->Home_model->delete_shipping_method($id)) {
                $response = array(
                    'success' => true,
                    'message' => 'Shipping method deleted successfully.',
                    'csrf_token' => $this->security->get_csrf_hash()
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Failed to delete shipping method.',
                    'csrf_token' => $this->security->get_csrf_hash()
                );
            }
        }
        
        echo json_encode($response);
    }




//////............deliveryboy........../////////

    
    // Delivery Boys List
    public function delivarylist() {
        $data['deliveryboys'] = $this->Home_model->get_all_deliveryboys();
        $this->load->view('delivery-list', $data);
    }
    
    // Add Form
    public function addDelivaryboy() {
        $this->load->view('add-delivary');
    }
    
    // Save Delivery Boy - WORKING VERSION
    public function saveDelivary() {
        // Validation rules
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|exact_length[10]|numeric|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('email', 'Email', 'valid_email|trim|max_length[150]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[255]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->addDelivaryboy();
            return;
        }
        
        // Check duplicate mobile
        if ($this->Home_model->mobile_exists($this->input->post('mobile'))) {
            $this->session->set_flashdata('error', 'Mobile number already exists!');
            redirect('home/addDelivaryboy');
        }
        
        // Check duplicate email if provided
        $email = $this->input->post('email');
        if (!empty($email) && $this->Home_model->email_exists($email)) {
            $this->session->set_flashdata('error', 'Email already exists!');
            redirect('home/addDelivaryboy');
        }
        
        // Prepare data
        $data = [
            'first_name'       => $this->input->post('first_name'),
            'last_name'        => $this->input->post('last_name'),
            'email'            => $email,
            'mobile'           => $this->input->post('mobile'),
            'deliveryboy_code' => $this->input->post('deliveryboy_code'),
            'dob'              => $this->input->post('dob'),
            'gender'           => $this->input->post('gender'),
            'vehicle_type'     => $this->input->post('vehicle_type'),
            'vehicle_number'   => $this->input->post('vehicle_number'),
            'joining_date'     => $this->input->post('joining_date') ?: date('Y-m-d'),
            'address'          => $this->input->post('address'),
            'city'             => $this->input->post('city'),
            'pincode'          => $this->input->post('pincode'),
            'status'           => $this->input->post('status') ? 1 : 0,
            'password'         => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
        ];
        
        // Handle photo upload using SIMPLE METHOD
        if (!empty($_FILES['photo']['name'])) {
            $upload_result = $this->_simple_upload_photo();
            if ($upload_result['status'] === true) {
                $data['photo'] = $upload_result['file_path'];
            } else {
                $this->session->set_flashdata('error', $upload_result['error']);
                redirect('home/addDelivaryboy');
            }
        }
        
        // Insert data
        if ($this->Home_model->add_deliveryboy($data)) {
            $this->session->set_flashdata('success', 'Delivery Boy Added Successfully!');
            redirect('home/delivarylist');
        } else {
            // Delete uploaded photo if database insert fails
            if (isset($data['photo']) && !empty($data['photo']) && file_exists(FCPATH . $data['photo'])) {
                unlink(FCPATH . $data['photo']);
            }
            $this->session->set_flashdata('error', 'Failed to add Delivery Boy!');
            redirect('home/addDelivaryboy');
        }
    }
    
    // Edit Form
    public function editDelivary($id) {
        $data['deliveryboy'] = $this->Home_model->get_deliveryboy($id);
        if (!$data['deliveryboy']) {
            $this->session->set_flashdata('error', 'Delivery Boy not found!');
            redirect('home/delivarylist');
        }
        $this->load->view('edit-delivary', $data);
    }
    
    // Update Delivery Boy - WORKING VERSION
    public function updateDelivary($id) {
        // Check if delivery boy exists
        $deliveryboy = $this->Home_model->get_deliveryboy($id);
        if (!$deliveryboy) {
            $this->session->set_flashdata('error', 'Delivery Boy not found!');
            redirect('home/delivarylist');
        }
        
        // Validation rules
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|exact_length[10]|numeric|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('email', 'Email', 'valid_email|trim|max_length[150]');
        
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]|max_length[255]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'matches[password]');
        }
        
        if ($this->form_validation->run() == FALSE) {
            $this->editDelivary($id);
            return;
        }
        
        // Check duplicate mobile (excluding current)
        if ($this->Home_model->mobile_exists($this->input->post('mobile'), $id)) {
            $this->session->set_flashdata('error', 'Mobile number already exists!');
            redirect('home/editDelivary/'.$id);
        }
        
        // Check duplicate email if provided
        $email = $this->input->post('email');
        if (!empty($email) && $this->Home_model->email_exists($email, $id)) {
            $this->session->set_flashdata('error', 'Email already exists!');
            redirect('home/editDelivary/'.$id);
        }
        
        // Prepare data
        $data = [
            'first_name'       => $this->input->post('first_name'),
            'last_name'        => $this->input->post('last_name'),
            'email'            => $email,
            'mobile'           => $this->input->post('mobile'),
            'deliveryboy_code' => $this->input->post('deliveryboy_code'),
            'dob'              => $this->input->post('dob'),
            'gender'           => $this->input->post('gender'),
            'vehicle_type'     => $this->input->post('vehicle_type'),
            'vehicle_number'   => $this->input->post('vehicle_number'),
            'joining_date'     => $this->input->post('joining_date'),
            'address'          => $this->input->post('address'),
            'city'             => $this->input->post('city'),
            'pincode'          => $this->input->post('pincode'),
            'status'           => $this->input->post('status') ? 1 : 0
        ];
        
        // Update password if provided
        if ($this->input->post('password')) {
            $data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
        }
        
        // Handle photo removal
        if ($this->input->post('remove_photo')) {
            // Delete old photo if exists
            if (!empty($deliveryboy['photo']) && file_exists(FCPATH . $deliveryboy['photo'])) {
                unlink(FCPATH . $deliveryboy['photo']);
            }
            $data['photo'] = null;
        }
        // Handle new photo upload
        else if (!empty($_FILES['photo']['name'])) {
            $upload_result = $this->_simple_upload_photo();
            if ($upload_result['status'] === true) {
                // Delete old photo if exists
                if (!empty($deliveryboy['photo']) && file_exists(FCPATH . $deliveryboy['photo'])) {
                    unlink(FCPATH . $deliveryboy['photo']);
                }
                $data['photo'] = $upload_result['file_path'];
            } else {
                $this->session->set_flashdata('error', $upload_result['error']);
                redirect('home/editDelivary/'.$id);
            }
        }
        
        // Update data
        if ($this->Home_model->update_deliveryboy($id, $data)) {
            $this->session->set_flashdata('success', 'Delivery Boy Updated Successfully!');
            redirect('home/delivarylist');
        } else {
            $this->session->set_flashdata('error', 'Failed to update Delivery Boy!');
            redirect('home/editDelivary/'.$id);
        }
    }
    
    // Delete Delivery Boy
    public function deleteDelivary($id) {
        $deliveryboy = $this->Home_model->get_deliveryboy($id);
        
        if ($deliveryboy) {
            // Delete photo if exists
            if (!empty($deliveryboy['photo']) && file_exists(FCPATH . $deliveryboy['photo'])) {
                unlink(FCPATH . $deliveryboy['photo']);
            }
            
            // Delete from database
            if ($this->Home_model->delete_deliveryboy($id)) {
                $this->session->set_flashdata('success', 'Delivery Boy Deleted Successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete Delivery Boy!');
            }
        } else {
            $this->session->set_flashdata('error', 'Delivery Boy not found!');
        }
        
        redirect('home/delivarylist');
    }
    
    // Change Status
    public function change_status($id, $status) {
        $status = ($status == 1) ? 0 : 1;
        if ($this->Home_model->change_status($id, $status)) {
            $status_text = ($status == 1) ? 'Activated' : 'Deactivated';
            $this->session->set_flashdata('success', 'Delivery Boy ' . $status_text . ' Successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to change status!');
        }
        redirect('home/delivarylist');
    }
    
    // =============== UPLOAD METHODS ===============
    
    /**
     * SIMPLE UPLOAD METHOD - 100% WORKING
     * Uses native PHP functions instead of CI Upload library
     */
    private function _simple_upload_photo() {
        // Check if file was uploaded
        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] != UPLOAD_ERR_OK) {
            return ['status' => false, 'error' => 'No file uploaded or upload error.'];
        }
        
        // Get file info
        $file_name = $_FILES['photo']['name'];
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_size = $_FILES['photo']['size'];
        $file_error = $_FILES['photo']['error'];
        
        // Get file extension
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allowed extensions
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        // Validate file type
        if (!in_array($file_ext, $allowed_ext)) {
            return ['status' => false, 'error' => 'Invalid file type. Allowed: JPG, JPEG, PNG, GIF, WebP'];
        }
        
        // Validate file size (2MB max)
        if ($file_size > 2 * 1024 * 1024) {
            return ['status' => false, 'error' => 'File size too large. Maximum 2MB allowed.'];
        }
        
        // Create upload directory if it doesn't exist
        $upload_dir = FCPATH . 'uploads/deliveryboys/';
        
        if (!is_dir($upload_dir)) {
            // Try to create directory
            if (!mkdir($upload_dir, 0755, true)) {
                return ['status' => false, 'error' => 'Failed to create upload directory.'];
            }
            
            // Create security files
            file_put_contents($upload_dir . '.htaccess', "Deny from all");
            file_put_contents($upload_dir . 'index.html', '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>');
        }
        
        // Generate unique filename
        $new_filename = 'deliveryboy_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
        $destination = $upload_dir . $new_filename;
        
        // Move uploaded file
        if (move_uploaded_file($file_tmp, $destination)) {
            return [
                'status' => true,
                'file_path' => 'uploads/deliveryboys/' . $new_filename,
                'file_name' => $new_filename
            ];
        } else {
            return ['status' => false, 'error' => 'Failed to save uploaded file.'];
        }
    }
    
    /**
     * Alternative upload method using CI Upload library
     * (Use this if you prefer CI's library)
     */
    private function _ci_upload_photo() {
        // Create directory first
        $upload_dir = FCPATH . 'uploads/deliveryboys/';
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
            file_put_contents($upload_dir . '.htaccess', "Deny from all");
            file_put_contents($upload_dir . 'index.html', '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>');
        }
        
        // Configure upload
        $config = [
            'upload_path'   => $upload_dir,
            'allowed_types' => 'jpg|jpeg|png|gif|webp',
            'max_size'      => 2048,
            'file_name'     => 'deliveryboy_' . time() . '_' . rand(1000, 9999),
            'overwrite'     => false
        ];
        
        $this->upload->initialize($config);
        
        if ($this->upload->do_upload('photo')) {
            $upload_data = $this->upload->data();
            return [
                'status' => true,
                'file_path' => 'uploads/deliveryboys/' . $upload_data['file_name'],
                'file_name' => $upload_data['file_name']
            ];
        } else {
            return ['status' => false, 'error' => $this->upload->display_errors()];
        }
    }
    

    public function employees_grid()
    {
        $this->load->view('employees-grid');         
    }

    public function employees_list()
    {
        $this->load->view('employees-list');         
    }

}