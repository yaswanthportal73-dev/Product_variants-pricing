<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->library('pagination');
        $this->load->helper('url');
    }

    // ===== MAIN DASHBOARD (Products List + Modal) =====
    public function index() {
        // Pagination config
        $config['base_url'] = base_url('products');
        $config['total_rows'] = $this->Product_model->count_all_products();
        $config['per_page'] = 20;
        $config['uri_segment'] = 2;
        $config['use_page_numbers'] = TRUE;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2)) ? ($this->uri->segment(2) - 1) * $config['per_page'] : 0;

        // Search
        $search = $this->input->get('search');
        $products = $this->Product_model->get_products_with_variants($config['per_page'], $page, $search);

        // Stats
        $stats = $this->Product_model->get_dashboard_stats();

        $data = [
            'products' => $products,
            'total_products' => $stats['total'],
            'low_stock' => $stats['low_stock'],
            'out_of_stock' => $stats['out_of_stock'],
            'total_variants' => $stats['total_variants'],
            'search' => $search,
            'links' => $this->pagination->create_links()
        ];

        // Load master views
        $this->load->view('partials/head-css');
        $this->load->view('partials/title-meta');
        $this->load->view('products_list', $data);
    }

    // ===== SUB-CATEGORIES (AJAX) =====
    public function get_sub_categories() {
        $this->output->set_content_type('application/json');
        $cat_id = $this->input->post('category_id');
        $csrf_name = $this->security->get_csrf_token_name();
        $csrf_hash = $this->security->get_csrf_hash();

        if (!$cat_id) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid category']);
            return;
        }

        $subs = $this->Product_model->get_sub_categories_by_category($cat_id);
        echo json_encode([
            'status' => 'success',
            'data' => $subs,
            $csrf_name => $csrf_hash
        ]);
    }

    // ===== ADD PRODUCT (AJAX) =====
    public function add_product() {
        $this->output->set_content_type('application/json');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Product Name', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('barcode', 'Barcode', 'required|exact_length[13]|numeric|is_unique[products.barcode]');
        $this->form_validation->set_rules('category_id', 'Category', 'required|integer');
        $this->form_validation->set_rules('pricing[][unit_id]', 'Unit', 'required');
        $this->form_validation->set_rules('pricing[][volume]', 'Volume', 'required|numeric');
        $this->form_validation->set_rules('pricing[][purchase_price]', 'Purchase Price', 'required|numeric');
        $this->form_validation->set_rules('pricing[][selling_price]', 'Selling Price', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors()
            ]);
            return;
        }

        // Insert product
        $product_data = [
            'name' => $this->input->post('name'),
            'barcode' => $this->input->post('barcode'),
            'category_id' => $this->input->post('category_id'),
            'sub_category_id' => $this->input->post('sub_category_id') ?: NULL,
            'brand_id' => $this->input->post('brand_id') ?: NULL,
            'supplier_id' => $this->input->post('supplier_id') ?: NULL,
            'short_description' => $this->input->post('short_description'),
            'description' => $this->input->post('description'),
            'status' => $this->input->post('status') ? 1 : 0
        ];

        $product_id = $this->Product_model->insert_product($product_data);

        if (!$product_id) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save product']);
            return;
        }

        // Insert variants
        $pricing = $this->input->post('pricing');
        foreach ($pricing as $p) {
            if (empty($p['unit_id'])) continue;
            $variant = [
                'product_id' => $product_id,
                'unit_id' => $p['unit_id'],
                'volume' => $p['volume'],
                'purchase_price' => $p['purchase_price'],
                'selling_price' => $p['selling_price'],
                'discount' => $p['discount'],
                'stock' => 0,
                'status' => 1
            ];
            $this->Product_model->insert_variant($variant);
        }

        // Handle image uploads (optional)
        if (!empty($_FILES['images']['name'][0])) {
            $this->load->library('upload');

            $config['upload_path'] = './uploads/products/'.$product_id;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048; // 2MB
            $config['encrypt_name'] = TRUE;

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

            $this->upload->initialize($config);
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                $_FILES['userfile']['name'] = $_FILES['images']['name'][$i];
                $_FILES['userfile']['type'] = $_FILES['images']['type'][$i];
                $_FILES['userfile']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
                $_FILES['userfile']['error'] = $_FILES['images']['error'][$i];
                $_FILES['userfile']['size'] = $_FILES['images']['size'][$i];

                if ($this->upload->do_upload('userfile')) {
                    $upload_data = $this->upload->data();
                    $image_data = [
                        'product_id' => $product_id,
                        'image_path' => $upload_data['file_name'],
                        'is_primary' => ($i == 0) ? 1 : 0
                    ];
                    $this->Product_model->insert_image($image_data);
                }
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Product added successfully!',
            'redirect' => base_url('products')
        ]);
    }

    // ===== EDIT / VIEW / DELETE ===== (Add as needed — let me know if you want full edit/delete logic in this reply)

    // For brevity, I’ll stop here for now. **Do you want me to continue with:**
    // - `edit_product()`
    // - `update_product()`
    // - `delete_product()`
    // - `view_product()` (modal or page?)
    // — or proceed to `views/products_list.php` with **embedded Add Product Modal**?

    // Let me know — and I’ll send the full `products_list.php` next.
}