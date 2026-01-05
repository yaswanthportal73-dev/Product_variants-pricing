<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all products with joins
    public function get_all_products() {
        $this->db->select('p.*, s.name as store_name, w.name as warehouse_name, 
                          c.name as category_name, sc.name as sub_category_name, 
                          ssc.name as sub_sub_category_name, b.name as brand_name, u.name as unit_name');
        $this->db->from('products p');
        $this->db->join('stores s', 's.id = p.store_id', 'left');
        $this->db->join('warehouses w', 'w.id = p.warehouse_id', 'left');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        $this->db->join('sub_categories sc', 'sc.id = p.sub_category_id', 'left');
        $this->db->join('sub_sub_categories ssc', 'ssc.id = p.sub_sub_category_id', 'left');
        $this->db->join('brands b', 'b.id = p.brand_id', 'left');
        $this->db->join('units u', 'u.id = p.unit_id', 'left');
        $this->db->where('p.status', 1);
        $this->db->order_by('p.created_at', 'DESC');
        
        return $this->db->get()->result();
    }

    // Get product by ID
    public function get_product_by_id($id) {
        $this->db->select('p.*, s.name as store_name, w.name as warehouse_name, 
                          c.name as category_name, sc.name as sub_category_name, 
                          ssc.name as sub_sub_category_name, b.name as brand_name, u.name as unit_name');
        $this->db->from('products p');
        $this->db->join('stores s', 's.id = p.store_id', 'left');
        $this->db->join('warehouses w', 'w.id = p.warehouse_id', 'left');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        $this->db->join('sub_categories sc', 'sc.id = p.sub_category_id', 'left');
        $this->db->join('sub_sub_categories ssc', 'ssc.id = p.sub_sub_category_id', 'left');
        $this->db->join('brands b', 'b.id = p.brand_id', 'left');
        $this->db->join('units u', 'u.id = p.unit_id', 'left');
        $this->db->where('p.id', $id);
        
        return $this->db->get()->row();
    }

    // Create new product
    public function create_product($data) {
        $this->db->trans_start();
        
        // Prepare product data - convert empty strings to NULL
        $product_data = $data['product'];
        
        // Convert empty category IDs to NULL
        if (isset($product_data['sub_category_id']) && $product_data['sub_category_id'] === '') {
            $product_data['sub_category_id'] = NULL;
        }
        if (isset($product_data['sub_sub_category_id']) && $product_data['sub_sub_category_id'] === '') {
            $product_data['sub_sub_category_id'] = NULL;
        }
        
        // Insert product
        $this->db->insert('products', $product_data);
        $product_id = $this->db->insert_id();
        
        // Insert images if any
        if (!empty($data['images'])) {
            foreach ($data['images'] as $image) {
                $image_data = array(
                    'product_id' => $product_id,
                    'image_path' => $image,
                    'is_primary' => 0
                );
                $this->db->insert('product_images', $image_data);
            }
        }
        
        // Insert variants if any
        if (!empty($data['variants'])) {
            foreach ($data['variants'] as $variant) {
                $variant_data = array(
                    'product_id' => $product_id,
                    'variant_attribute' => $variant['attribute'],
                    'variant_value' => $variant['value'],
                    'sku' => $variant['sku'],
                    'quantity' => $variant['quantity'],
                    'price' => $variant['price']
                );
                $this->db->insert('product_variants', $variant_data);
            }
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() ? $product_id : false;
    }

    // Update product
    public function update_product($id, $data) {
        $this->db->trans_start();
        
        // Prepare product data - convert empty strings to NULL
        $product_data = $data['product'];
        
        // Convert empty category IDs to NULL
        if (isset($product_data['sub_category_id']) && $product_data['sub_category_id'] === '') {
            $product_data['sub_category_id'] = NULL;
        }
        if (isset($product_data['sub_sub_category_id']) && $product_data['sub_sub_category_id'] === '') {
            $product_data['sub_sub_category_id'] = NULL;
        }
        
        // Update product
        $this->db->where('id', $id);
        $this->db->update('products', $product_data);
        
        // Delete existing images and variants
        //$this->db->where('product_id', $id);
        //$this->db->delete('product_images');
        
        //$this->db->where('product_id', $id);
        //$this->db->delete('product_variants');
        
        // Insert new images
        if (!empty($data['images'])) {
            foreach ($data['images'] as $image) {
                $image_data = array(
                    'product_id' => $id,
                    'image_path' => $image,
                    'is_primary' => 0
                );
                $this->db->insert('product_images', $image_data);
            }
        }
        
        // Insert new variants
        if (!empty($data['variants'])) {
            foreach ($data['variants'] as $variant) {
                $variant_data = array(
                    'product_id' => $id,
                    'variant_attribute' => $variant['attribute'],
                    'variant_value' => $variant['value'],
                    'sku' => $variant['sku'],
                    'quantity' => $variant['quantity'],
                    'price' => $variant['price']
                );
                $this->db->insert('product_variants', $variant_data);
            }
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    // Delete product (soft delete)
    public function delete_product($id) {
        $this->db->where('id', $id);
        return $this->db->update('products', array('status' => 0));
    }

    // Get product images
    /* public function get_product_images($product_id) {
        $this->db->where('product_id', $product_id);
        return $this->db->get('product_images')->result();
    } */

    // Get product variants
    public function get_product_variants($product_id) {
        $this->db->where('product_id', $product_id);
        return $this->db->get('product_variants')->result();
    }

    // Generate SKU
    public function generate_sku($product_name) {
        $prefix = substr(strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $product_name)), 0, 3);
        $random = mt_rand(1000, 9999);
        return $prefix . $random;
    }

    // Get all stores
    public function get_stores() {
        $this->db->where('status', 1);
        return $this->db->get('stores')->result();
    }

    // Get all warehouses
    public function get_warehouses() {
        $this->db->where('status', 1);
        return $this->db->get('warehouses')->result();
    }

    

    // Get sub categories by category ID
    public function get_sub_categories($category_id) {
        $this->db->where('status', 1);
        $this->db->where('category_id', $category_id);
        return $this->db->get('sub_categories')->result();
    }

    // Get sub sub categories by sub category ID
    public function get_sub_sub_categories($sub_category_id) {
        $this->db->where('status', 1);
        $this->db->where('sub_category_id', $sub_category_id);
        return $this->db->get('sub_sub_categories')->result();
    }

    // Get all brands
    public function get_brands() {
        $this->db->where('status', 1);
        return $this->db->get('brands')->result();
    }

    // Get all units
    public function get_units() {
        $this->db->where('status', 1);
        return $this->db->get('units')->result();
    }
	
	public function get_product_image($image_id, $product_id = null) {
        $this->db->where('id', $image_id);
        
        if ($product_id) {
            $this->db->where('product_id', $product_id);
        }
        
        return $this->db->get('product_images')->row();
    }

    /**
     * Get product image with product validation
     */
    public function get_product_image_with_product($image_id, $product_id) {
        $this->db->select('pi.*, p.user_id, p.store_id');
        $this->db->from('product_images pi');
        $this->db->join('products p', 'pi.product_id = p.id', 'inner');
        $this->db->where('pi.id', $image_id);
        $this->db->where('pi.product_id', $product_id);
        
        return $this->db->get()->row();
    }

    /**
     * Delete product image from database
     */
    public function delete_product_image($image_id) {
        $this->db->where('id', $image_id);
        return $this->db->delete('product_images');
    }

    /**
     * Get all images for a product
     */
    public function get_product_images($product_id) {
        $this->db->where('product_id', $product_id);
        $this->db->order_by('id', 'ASC');
        return $this->db->get('product_images')->result();
    }

    /**
     * Get product by ID
     */
    public function get_product($product_id) {
        $this->db->where('id', $product_id);
        return $this->db->get('products')->row();
    }
	// Get all categories
    public function get_categories() {
        $this->db->where('status', 1);
        return $this->db->get('categories')->result();
    }
	 // Get all categories
	 
    public function get_all_categories() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('categories')->result();
    }

    // Get category by ID
    public function get_category_by_id($id) {
        return $this->db->get_where('categories', array('id' => $id))->row();
    }

    // Create new category
    public function create_category($data) {
        $this->db->insert('categories', $data);
        return $this->db->insert_id();
    }

    // Update category
    public function update_category($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('categories', $data);
    }

    // Delete category
    public function delete_category($id) {
        $this->db->where('id', $id);
        return $this->db->delete('categories');
    }
	  // Generate slug from category name
    public function generate_slug($name) {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return $slug;
    }

    // Check if slug exists
    public function slug_exists($slug, $id = null) {
        $this->db->where('slug', $slug);
        if ($id) {
            $this->db->where('id !=', $id);
        }
        return $this->db->get('categories')->num_rows() > 0;
    }
	
	
}