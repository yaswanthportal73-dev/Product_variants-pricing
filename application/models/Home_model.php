<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

 public function get_all_products($limit = 0, $offset = 0, $search = null) {
    // Step 1: Get base product + computed stock
    $this->db->select("
        p.id,
        p.name,
        p.barcode,
        p.item_code,
        p.category_id,
        p.sub_category_id,
        p.brand_id,
        p.unit_id,
        p.supplier_id,
        p.short_description,
        p.description,
        p.cost_price,
        p.original_price,
        p.selling_price,
        p.discount_type,
        p.discount_value,
        p.discount_amount,
        p.final_price,
        p.quantity,
        p.alert_quantity,
        p.min_order_qty,
        p.max_order_qty,
        p.expiry_date,
        p.status,
        p.created_at,
        p.updated_at,
        p.is_deleted,
        c.name as category_name,
        sc.name as sub_category_name,
        b.name as brand_name,
        u.name as unit_name,
        s.name as supplier_name,
        COALESCE(SUM(pv.stock), 0) as total_stock
    ");
    $this->db->from('products p');
    $this->db->join('categories c', 'p.category_id = c.id', 'left');
    $this->db->join('sub_categories sc', 'p.sub_category_id = sc.id', 'left');
    $this->db->join('brands b', 'p.brand_id = b.id', 'left');
    $this->db->join('units u', 'p.unit_id = u.id', 'left');
    $this->db->join('suppliers s', 'p.supplier_id = s.id', 'left');
    $this->db->join('product_variants pv', 'p.id = pv.product_id', 'left');
    $this->db->where('p.status', 1);
    $this->db->where('p.is_deleted', 0);

    if ($search) {
        $this->db->group_start();
        $this->db->like('p.name', $search);
        $this->db->or_like('p.barcode', $search);
        $this->db->or_like('p.item_code', $search);
        $this->db->or_like('c.name', $search);
        $this->db->or_like('b.name', $search);
        $this->db->group_end();
    }

    $this->db->group_by('
        p.id,
        p.name,
        p.barcode,
        p.item_code,
        p.category_id,
        p.sub_category_id,
        p.brand_id,
        p.unit_id,
        p.supplier_id,
        p.short_description,
        p.description,
        p.cost_price,
        p.original_price,
        p.selling_price,
        p.discount_type,
        p.discount_value,
        p.discount_amount,
        p.final_price,
        p.quantity,
        p.alert_quantity,
        p.min_order_qty,
        p.max_order_qty,
        p.expiry_date,
        p.status,
        p.created_at,
        p.updated_at,
        p.is_deleted,
        c.name,
        sc.name,
        b.name,
        u.name,
        s.name
    ');
    $this->db->order_by('p.created_at', 'DESC');

    if ($limit > 0) {
        $this->db->limit($limit, $offset);
    }

    $products = $this->db->get()->result();

    // ➕ Step 2: Enrich with primary image (1:1 — safe)
    foreach ($products as &$p) {
        // Get primary image path
        $img = $this->db
            ->select('image_path')
            ->from('product_images')
            ->where('product_id', $p->id)
            ->where('is_primary', 1)
            ->get()->row();
        $p->primary_image = $img ? $img->image_path : null;

        // ➕ Load variants (already done in controller, but just in case)
        $p->variants = $this->get_variants_by_product($p->id);
    }

    return $products;
}





    // Get total products count
   public function count_all_products($search = null) {
    $this->db->from('products p');
    $this->db->join('categories c', 'p.category_id = c.id', 'left');
    $this->db->join('brands b', 'p.brand_id = b.id', 'left');
    $this->db->where('p.status', 1);
    $this->db->where('p.is_deleted', 0);
    // ... rest

    if ($search) {
        $this->db->group_start();
        $this->db->like('p.name', $search);
        $this->db->or_like('p.barcode', $search);
        $this->db->or_like('p.item_code', $search);
        $this->db->or_like('c.name', $search);
        $this->db->or_like('b.name', $search);
        $this->db->group_end();
    }

    return $this->db->count_all_results();
}

    // Get product by ID
    public function get_product_by_id($id) {
        $this->db->select('p.*, 
            c.name as category_name, 
            sc.name as sub_category_name, 
            b.name as brand_name, 
            u.name as unit_name, 
            u.symbol as unit_symbol,
            s.name as supplier_name');
        $this->db->from('products p');
        $this->db->join('categories c', 'p.category_id = c.id', 'left');
        $this->db->join('sub_categories sc', 'p.sub_category_id = sc.id', 'left');
        $this->db->join('brands b', 'p.brand_id = b.id', 'left');
        $this->db->join('units u', 'p.unit_id = u.id', 'left');
        $this->db->join('suppliers s', 'p.supplier_id = s.id', 'left');
        $this->db->where('p.id', $id);
        $this->db->where('p.is_deleted', 0); 
        return $this->db->get()->row();
    }


    // Get product images by product ID
    public function get_product_images($product_id) {
        $this->db->where('product_id', $product_id);
        $this->db->order_by('is_primary', 'DESC');
        $query = $this->db->get('product_images');
        return $query->result();
    }

    // Save new product
    public function save_product($data)
{
    $this->db->insert('products', $data);
    return $this->db->insert_id();
}

    

    // Update product
    public function update_product($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    // Delete product (soft delete)
    public function delete_product($id)
    {
        return $this->db->where('id', $id)->update('products', [
            'is_deleted' => 1,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Save product image
    public function save_product_image($image_data) {
        return $this->db->insert('product_images', $image_data);
    }

    // Delete specific image
    public function delete_image($image_id) {
        // Get image info before deleting
        $this->db->where('id', $image_id);
        $image = $this->db->get('product_images')->row();
        
        if ($image) {
            $image_path = './uploads/products/' . $image->product_id . '/' . $image->image_path;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            
            $this->db->where('id', $image_id);
            return $this->db->delete('product_images');
        }
        return false;
    }

    // Set primary image
    public function set_primary_image($product_id, $image_id) {
        // Reset all images to not primary
        $this->db->where('product_id', $product_id);
        $this->db->update('product_images', array('is_primary' => 0));
        
        // Set specific image as primary
        $this->db->where('id', $image_id);
        return $this->db->update('product_images', array('is_primary' => 1));
    }

    // Get categories
    public function get_by_all_categories() {
        $this->db->order_by('name', 'ASC');
        return $this->db->get_where('categories', array('status' => 1))->result();
    }

    // Get subcategories by category ID
    public function get_by_all_sub_categories($category_id) {
        $this->db->where('category_id', $category_id);
        $this->db->where('status', 1);
        $this->db->order_by('name', 'ASC');
        return $this->db->get('sub_categories')->result();
    }

    // Get brands
    public function get_by_all_brands() {
        $this->db->order_by('name', 'ASC');
        return $this->db->get_where('brands', array('status' => 1))->result();
    }

    // Get units
    public function get_by_all_units() {
        $this->db->order_by('name', 'ASC');
        return $this->db->get_where('units', array('status' => 1))->result();
    }

    // Get suppliers
    public function get_by_all_suppliers() {
        $this->db->order_by('name', 'ASC');
        return $this->db->get_where('suppliers', array('status' => 1))->result();
    }

    // Check if barcode exists
    public function barcode_exists($barcode, $id = null) {
        $this->db->where('barcode', $barcode);
        $this->db->where('status', 1);
        if ($id) {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get('products');
        return $query->num_rows() > 0;
    }

    // Check if item code exists
    public function item_code_exists($item_code, $id = null) {
        $this->db->where('item_code', $item_code);
        $this->db->where('status', 1);
        if ($id) {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get('products');
        return $query->num_rows() > 0;
    }

    // Get low stock products
    public function get_low_stock_products($limit = 10) {
        $this->db->select('p.*, c.name as category_name');
        $this->db->from('products p');
        $this->db->join('categories c', 'p.category_id = c.id', 'left');
        $this->db->where('p.status', 1);
        $this->db->where('p.quantity <= p.alert_quantity');
        $this->db->order_by('p.quantity', 'ASC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    // Calculate profit margin
    public function calculate_profit_margin($selling_price, $cost_price) {
        if ($cost_price > 0) {
            return (($selling_price - $cost_price) / $cost_price) * 100;
        }
        return 0;
    }

    // Delete multiple images
    public function delete_images_by_ids($image_ids) {
        if (empty($image_ids)) return false;
        
        // Get images info before deleting
        $this->db->where_in('id', $image_ids);
        $images = $this->db->get('product_images')->result();
        
        foreach ($images as $image) {
            $image_path = './uploads/products/' . $image->product_id . '/' . $image->image_path;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        // Delete from database
        $this->db->where_in('id', $image_ids);
        return $this->db->delete('product_images');
    }

    // Update product images (remove existing and add new)
    public function update_product_images($product_id, $existing_images, $removed_images = []) {
        // Remove images if specified
        if (!empty($removed_images)) {
            $this->delete_images_by_ids($removed_images);
        }
        
        // Update existing images
        if (!empty($existing_images)) {
            // Reset all to not primary
            $this->db->where('product_id', $product_id);
            $this->db->update('product_images', array('is_primary' => 0));
            
            // Set the first one as primary
            if (isset($existing_images[0])) {
                $this->db->where('id', $existing_images[0]);
                $this->db->update('product_images', array('is_primary' => 1));
            }
        }
    }

    /*======================Products========================= */
    /* ===================== CATEGORIES ===================== */

public function get_all_categories() {
        return $this->db->order_by('id', 'DESC')->get('categories')->result();
    }

    public function get_category_by_id($id) {
        return $this->db->where('id', $id)->get('categories')->row();
    }

    public function insert_category($data) {
        return $this->db->insert('categories', $data);
    }

    public function update_category($id, $data) {
        return $this->db->where('id', $id)->update('categories', $data);
    }

    public function delete_category($id) {
        $cat = $this->get_category_by_id($id);
        if ($cat && $cat->image) {
            $path = FCPATH.'uploads/categories/'.$cat->image;
            if (file_exists($path)) unlink($path);
        }
        return $this->db->where('id', $id)->delete('categories');
    }

    public function category_slug_exists($slug, $exclude_id = null) {
        $this->db->where('slug', $slug);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get('categories')->num_rows() > 0;
    }

    public function get_last_id() {
        return $this->db->insert_id();
    }

/* ===================== SUB CATEGORIES ===================== */

    public function get_all_subcategories() {
        return $this->db
            ->select('sc.*, c.name as category_name')
            ->from('sub_categories sc')
            ->join('categories c', 'c.id = sc.category_id', 'left')
            ->order_by('sc.id', 'DESC')
            ->get()
            ->result();
    }

    public function insert_subcategory($data) {
        return $this->db->insert('sub_categories', $data);
    }

    public function update_subcategory($id, $data) {
        return $this->db->where('id', $id)->update('sub_categories', $data);
    }

    public function delete_subcategory($id) {
        return $this->db->where('id', $id)->delete('sub_categories');
    }

    public function subcategory_slug_exists($slug, $exclude_id = null) {
        $this->db->where('slug', $slug);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get('sub_categories')->num_rows() > 0;
    }

    public function subcategory_exists($name, $category_id, $exclude_id = null) {
        $this->db->where([
            'name' => $name,
            'category_id' => $category_id
        ]);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get('sub_categories')->num_rows() > 0;
    }

    /* ===================== UNITS ===================== */

    public function get_all_units() {
    return $this->db->order_by('id','DESC')->get('units')->result();
    }

    public function insert_unit($data) {
        return $this->db->insert('units', $data);
    }

    public function update_unit($id, $data) {
        return $this->db->where('id', $id)->update('units', $data);
    }

    public function delete_unit($id) {
        return $this->db->where('id', $id)->delete('units');
    }
    // brands
    public function get_all_brands() {
    return $this->db->order_by('id','DESC')->get('brands')->result();
    }

    public function brand_slug_exists($slug, $id = null) {
        $this->db->where('slug', $slug);
        if ($id) $this->db->where('id !=', $id);
        return $this->db->get('brands')->num_rows() > 0;
    }

    public function insert_brand($data) {
        return $this->db->insert('brands', $data);
    }

    public function update_brand($id, $data) {
        return $this->db->where('id', $id)->update('brands', $data);
    }

    public function delete_brand($id) {
        return $this->db->where('id', $id)->delete('brands');
    }

    public function toggle_brand_status($id, $status) {
        return $this->db->where('id',$id)
                        ->update('brands',['status'=>$status]);
    }
    // suppliers
    public function get_all() {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('suppliers');
        return $query->result();
    }

    public function get_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('suppliers');
        return $query->row();
    }

    public function insert($data) {
        return $this->db->insert('suppliers', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('suppliers', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('suppliers');
    }

    /* ===================== PURCHASES ===================== */

    public function get_suppliers() {
        return $this->db->get('suppliers')->result();
    }

    public function get_products() {
        return $this->db->get('products')->result();
    }

    public function get_all_purchases() {
        $this->db->select('p.*, s.name as supplier_name, s.phone as supplier_phone, s.email as supplier_email, s.address as supplier_address');
        return $this->db->from('purchases p')
            ->join('suppliers s', 's.id = p.supplier_id', 'left')
            ->get()->result();
    }

    public function insert_purchase($d) {
        $this->db->insert('purchases', $d);
        return $this->db->insert_id();
    }

    public function update_purchase($id, $d) {
        return $this->db->where('id', $id)->update('purchases', $d);
    }

    public function delete_purchase($id) {
        return $this->db->where('id', $id)->delete('purchases');
    }

    public function insert_purchase_item($d) {
        return $this->db->insert('purchase_items', $d);
    }

    public function get_purchase_items($id) {
        return $this->db->where('purchase_id', $id)->get('purchase_items')->result();
    }

    public function delete_purchase_items($id) {
        return $this->db->where('purchase_id', $id)->delete('purchase_items');
    }

    public function reference_exists($ref, $id = null) {
        $this->db->where('reference', $ref);
        if ($id) $this->db->where('id !=', $id);
        return $this->db->get('purchases')->num_rows() > 0;
    }

    public function get_product_name($id) {
        return $this->db->where('id', $id)->get('products')->row()->name ?? '';
    }

    public function update_product_quantity($pid, $qty, $type) {
        $this->db->set('quantity', $type == 'increase' ? "quantity + $qty" : "quantity - $qty", false);
        return $this->db->where('id', $pid)->update('products');
    }

    public function get_purchase_with_items($id) {
        $this->db->select('p.*, s.name as supplier_name, s.phone as supplier_phone, s.email as supplier_email, s.address as supplier_address');
        $this->db->from('purchases p');
        $this->db->join('suppliers s', 's.id = p.supplier_id', 'left');
        $this->db->where('p.id', $id);
        $purchase = $this->db->get()->row();

        $items = $this->get_purchase_items($id);

        return [
            'purchase' => $purchase,
            'items' => $items
        ];
    }
    
    /* ===================== PURCHASE METHODS ===================== */


  

    // ================ PAYMENT METHODS ================

        public function get_payment_methods() {
            return $this->db
                ->order_by('id', 'ASC')
                ->get('payment_methods')
                ->result_array();
        }

        public function insert_payment_method($data) {
            return $this->db->insert('payment_methods', $data);
        }

        public function update_payment_method($id, $data) {
            return $this->db
                ->where('id', $id)
                ->update('payment_methods', $data);
        }

        public function delete_payment_method($id) {
            return $this->db
                ->where('id', $id)
                ->delete('payment_methods');
        }

    
    // ================ SHIPPING METHODS ================
     public function get_shipping_methods() {
        $this->db->select('*');
        $this->db->from('shipping_methods');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Get shipping method by ID
     */
    public function get_shipping_method($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('shipping_methods');
        return $query->row_array();
    }
    
    /**
     * Add new shipping method
     */
    public function add_shipping_method($data) {
        $insert_data = array(
            'name' => $data['name'],
            'type' => $data['type'],
            'charge' => $data['charge'],
            'min_order_amount' => $data['min_order_amount'],
            'delivery_time' => $data['delivery_time'],
            'status' => isset($data['status']) ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        return $this->db->insert('shipping_methods', $insert_data);
    }
    
    /**
     * Update shipping method
     */
    public function update_shipping_method($id, $data) {
        $update_data = array(
            'name' => $data['name'],
            'type' => $data['type'],
            'charge' => $data['charge'],
            'min_order_amount' => $data['min_order_amount'],
            'delivery_time' => $data['delivery_time'],
            'status' => isset($data['status']) ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $id);
        return $this->db->update('shipping_methods', $update_data);
    }
    
    /**
     * Toggle shipping method status
     */
    public function toggle_status($id, $status) {
        $data = array(
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $id);
        return $this->db->update('shipping_methods', $data);
    }
    
    /**
     * Delete shipping method
     */
    public function delete_shipping_method($id) {
        $this->db->where('id', $id);
        return $this->db->delete('shipping_methods');
    }
    
    /**
     * Check if shipping method exists
     */
    public function shipping_method_exists($name, $exclude_id = null) {
        $this->db->where('name', $name);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        $query = $this->db->get('shipping_methods');
        return $query->num_rows() > 0;
    }



    
 // Get all delivery boys/////

  // Get all delivery boys
    public function get_all_deliveryboys() {
        $this->db->select('*');
        $this->db->from('delivery_boy');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    // Get single delivery boy by ID
    public function get_deliveryboy($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('delivery_boy');
        return $query->row_array();
    }
    
    // Check if mobile exists
    public function mobile_exists($mobile, $id = null) {
        $this->db->where('mobile', $mobile);
        if ($id) {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get('delivery_boy');
        return ($query->num_rows() > 0);
    }
    
    // Check if email exists
    public function email_exists($email, $id = null) {
        if (empty($email)) return false;
        
        $this->db->where('email', $email);
        if ($id) {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get('delivery_boy');
        return ($query->num_rows() > 0);
    }
    
    // Add new delivery boy
    public function add_deliveryboy($data) {
        return $this->db->insert('delivery_boy', $data);
    }
    
    // Get last insert ID
    public function get_last_insert_id() {
        return $this->db->insert_id();
    }
    
    // Update delivery boy
    public function update_deliveryboy($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('delivery_boy', $data);
    }
    
    // Delete delivery boy
    public function delete_deliveryboy($id) {
        $this->db->where('id', $id);
        return $this->db->delete('delivery_boy');
    }
    
    // Change status
    public function change_status($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('delivery_boy', ['status' => $status]);
    }
    
    // Get delivery boy count
    public function get_deliveryboy_count() {
        return $this->db->count_all('delivery_boy');
    }
    
    // Get active delivery boys
    public function get_active_deliveryboys() {
        $this->db->where('status', 1);
        $query = $this->db->get('delivery_boy');
        return $query->result_array();
    }
   

    // ------------------- varients code ------ yashh ---------------------


    // Main product list
    public function get_products_with_basic_price() {
        $this->db->select('
            p.id,
            p.name,
            p.barcode,
            p.item_code,
            p.status,
            c.name as category_name,
            pi.image_path as primary_image
        ');
        $this->db->from('products p');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        $this->db->join('product_images pi', 'pi.product_id = p.id AND pi.is_primary = 1', 'left');
        $this->db->order_by('p.id', 'DESC');
        return $this->db->get()->result();
    }

    // Variants by product
  public function get_variants_by_product($product_id) {
    $this->db->select('
        pv.id,
        pv.product_id,
        u.name as unit_name,
        u.symbol as unit_symbol,
        pv.volume,
        pv.purchase_price,
        pv.selling_price,
        pv.stock,
        pv.alert_stock,
        pv.status
    ');
    $this->db->from('product_variants pv');
    $this->db->join('units u', 'u.id = pv.unit_id', 'left');
    $this->db->where('pv.product_id', $product_id);
    $this->db->order_by('pv.volume', 'ASC');
    return $this->db->get()->result();
}




public function get_product_stats() {
    $total = $this->db->where(['is_deleted' => 0, 'status' => 1])->count_all_results('products');

    // Get all products + total stock
    $all = $this->db
        ->select('p.id, p.alert_quantity, COALESCE(SUM(pv.stock), 0) as total_stock')
        ->from('products p')
        ->join('product_variants pv', 'pv.product_id = p.id', 'left')
        ->where('p.status', 1)
        ->where('p.is_deleted', 0)
        ->group_by('p.id, p.alert_quantity')
        ->get()->result();

    $low = 0; $out = 0;
    foreach ($all as $p) {
        if ($p->total_stock == 0) $out++;
        elseif ($p->total_stock <= $p->alert_quantity) $low++;
    }

    $variants = $this->db->count_all_results('product_variants');

    return compact('total', 'low', 'out', 'variants');
}





    // All variants grouped (for product list)
    public function get_all_variants_grouped() {
        $variants = $this->db
            ->select('pv.*, u.name as unit_name, u.symbol as unit_symbol')
            ->from('product_variants pv')
            ->join('units u', 'u.id = pv.unit_id')
            ->get()
            ->result();

        $grouped = [];
        foreach ($variants as $v) {
            $grouped[$v->product_id][] = $v;
        }
        return $grouped;
    }

}
