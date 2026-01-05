<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ===== PRODUCTS =====

    public function get_products_with_variants($limit = 20, $offset = 0, $search = null) {
        $this->db->select('
            p.id, p.name, p.barcode, p.status, p.category_id, p.sub_category_id,
            c.name AS category_name,
            sc.name AS sub_category_name,
            pi.image_path AS primary_image
        ');

        $this->db->from('products p');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        $this->db->join('sub_categories sc', 'sc.id = p.sub_category_id', 'left');
        $this->db->join('product_images pi', 'pi.product_id = p.id AND pi.is_primary = 1', 'left');

        if ($search) {
            $this->db->group_start()
                     ->like('p.name', $search)
                     ->or_like('p.barcode', $search)
                     ->or_like('c.name', $search)
                     ->or_like('sc.name', $search)
                     ->group_end();
        }

        $this->db->order_by('p.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();

        if (!$query->num_rows()) return [];

        $products = $query->result();

        // Get variants & stock for each product
        foreach ($products as &$product) {
            // Total stock (sum of all variant stocks)
            $this->db->select('SUM(stock) as total_stock, MIN(alert_qty) as alert_quantity')
                     ->from('product_variants')
                     ->where('product_id', $product->id);
            $stock_row = $this->db->get()->row();
            $product->total_stock = (int) $stock_row->total_stock;
            $product->alert_quantity = (int) $stock_row->alert_quantity;

            // Variants with unit names
            $this->db->select('
                pv.id, pv.unit_id, pv.volume, pv.purchase_price, pv.selling_price,
                pv.discount, pv.stock, pv.alert_qty, pv.status,
                u.name AS unit_name, u.symbol AS unit_symbol
            ')
            ->from('product_variants pv')
            ->join('units u', 'u.id = pv.unit_id', 'left')
            ->where('pv.product_id', $product->id)
            ->order_by('pv.id', 'ASC');
            $product->variants = $this->db->get()->result();
        }

        return $products;
    }

    public function count_all_products($search = null) {
        if ($search) {
            $this->db->group_start()
                     ->like('name', $search)
                     ->or_like('barcode', $search)
                     ->or_like('category_id', $search)
                     ->group_end();
        }
        return $this->db->count_all_results('products');
    }

    public function insert_product($data) {
        $this->db->insert('products', $data);
        return $this->db->insert_id();
    }

    public function update_product($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    public function delete_product($id) {
        $this->db->trans_start();

        // Delete variants & images first (FK constraints)
        $this->db->where('product_id', $id)->delete('product_variants');
        $this->db->where('product_id', $id)->delete('product_images');

        $this->db->where('id', $id)->delete('products');

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_product_by_id($id) {
        $this->db->select('*');
        $this->db->from('products');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }

    // ===== VARIANTS =====

    public function insert_variant($data) {
        $this->db->insert('product_variants', $data);
        return $this->db->insert_id();
    }

    public function get_variants_by_product($product_id) {
        $this->db->select('pv.*, u.name as unit_name');
        $this->db->from('product_variants pv');
        $this->db->join('units u', 'u.id = pv.unit_id', 'left');
        $this->db->where('pv.product_id', $product_id);
        return $this->db->get()->result();
    }

    public function update_variant($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('product_variants', $data);
    }

    public function delete_variant($id) {
        $this->db->where('id', $id);
        return $this->db->delete('product_variants');
    }

    // ===== IMAGES =====

    public function insert_image($data) {
        $this->db->insert('product_images', $data);
        return $this->db->insert_id();
    }

    public function get_images_by_product($product_id) {
        $this->db->where('product_id', $product_id);
        return $this->db->get('product_images')->result();
    }

    public function delete_image($id) {
        $this->db->where('id', $id);
        return $this->db->delete('product_images');
    }

    // ===== DASHBOARD STATS =====

    public function get_dashboard_stats() {
        // Total products
        $total = $this->db->count_all('products');

        // Total variants
        $total_variants = $this->db->count_all('product_variants');

        // Low stock: any variant with stock <= alert_qty (typically 5)
        $this->db->select('COUNT(*) as cnt')
                 ->from('product_variants')
                 ->where('stock <=', 'alert_qty', FALSE)
                 ->where('stock >', 0);
        $low_stock_row = $this->db->get()->row();
        $low_stock = (int) $low_stock_row->cnt;

        // Out of stock: stock = 0
        $this->db->select('COUNT(*) as cnt')
                 ->from('product_variants')
                 ->where('stock', 0);
        $out_row = $this->db->get()->row();
        $out_of_stock = (int) $out_row->cnt;

        return [
            'total' => $total,
            'low_stock' => $low_stock,
            'out_of_stock' => $out_of_stock,
            'total_variants' => $total_variants
        ];
    }

    // ===== MASTER DATA (for dropdowns) =====

    public function get_all_categories() {
        return $this->db->order_by('name', 'ASC')->get('categories')->result();
    }

    public function get_all_sub_categories() {
        return $this->db->order_by('name', 'ASC')->get('sub_categories')->result();
    }

    public function get_sub_categories_by_category($category_id) {
        return $this->db->where('category_id', $category_id)
                        ->order_by('name', 'ASC')
                        ->get('sub_categories')
                        ->result();
    }

    public function get_all_brands() {
        return $this->db->order_by('name', 'ASC')->get('brands')->result();
    }

    public function get_all_suppliers() {
        return $this->db->order_by('name', 'ASC')->get('suppliers')->result();
    }

    public function get_all_units() {
        return $this->db->order_by('name', 'ASC')->get('units')->result();
    }

    // ===== VALIDATION HELPERS =====

    public function barcode_exists($barcode, $exclude_id = null) {
        $this->db->where('barcode', $barcode);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results('products') > 0;
    }

    // Optional: If you want real-time variant stock update
    public function update_variant_stock($variant_id, $new_stock) {
        $this->db->set('stock', $new_stock);
        $this->db->where('id', $variant_id);
        return $this->db->update('product_variants');
    }
}