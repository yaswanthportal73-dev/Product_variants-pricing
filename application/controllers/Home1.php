<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load any helpers or models if needed
        // $this->load->helper('url');
		$this->load->model('Home_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->load->view('index');
    }

    public function Dashboard()
    {
        $this->load->view('index');
    }
	
    // Product list page
    public function productlist() {
        $data['products'] = $this->Home_model->get_all_products();
        $this->load->view('product-list', $data);
    }

    // Add product page
    public function add_product() {
        $data['stores'] = $this->Home_model->get_stores();
        $data['warehouses'] = $this->Home_model->get_warehouses();
        $data['categories'] = $this->Home_model->get_categories();
        $data['brands'] = $this->Home_model->get_brands();
        $data['units'] = $this->Home_model->get_units();
        
        $this->load->view('add-product', $data);
    }

    // Edit product page
    public function edit_product($id) {
		$data['product'] = $this->Home_model->get_product_by_id($id);
		$data['stores'] = $this->Home_model->get_stores();
		$data['warehouses'] = $this->Home_model->get_warehouses();
		$data['categories'] = $this->Home_model->get_categories();
		$data['brands'] = $this->Home_model->get_brands();
		$data['units'] = $this->Home_model->get_units();
		$data['images'] = $this->Home_model->get_product_images($id);
		$data['variants'] = $this->Home_model->get_product_variants($id);
		
		$this->load->view('edit-product', $data);
	}

    // Create product
    public function create_product() {
        $this->form_validation->set_rules('name', 'Product Name', 'required');
        $this->form_validation->set_rules('sku', 'SKU', 'required|is_unique[products.sku]');
        $this->form_validation->set_rules('price', 'Price', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('add_product');
        }

        // Handle file uploads
        $images = array();
        if (!empty($_FILES['images']['name'][0])) {
            $files = $_FILES['images'];
            $config['upload_path'] = './uploads/products/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048;
            
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

            $this->load->library('upload', $config);

            foreach ($files['name'] as $key => $image) {
                $_FILES['images[]']['name'] = $files['name'][$key];
                $_FILES['images[]']['type'] = $files['type'][$key];
                $_FILES['images[]']['tmp_name'] = $files['tmp_name'][$key];
                $_FILES['images[]']['error'] = $files['error'][$key];
                $_FILES['images[]']['size'] = $files['size'][$key];

                $extension = pathinfo($files['name'][$key], PATHINFO_EXTENSION);
				$fileName = time() . '_' . rand(1,99999999) . '.' . $extension;
                $config['file_name'] = $fileName;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('images[]')) {
                    $images[] = 'uploads/products/' . $fileName;
                }
            }
        }

        // Prepare product data
        $product_data = array(
            'store_id' => $this->input->post('store_id'),
            'warehouse_id' => $this->input->post('warehouse_id'),
            'name' => $this->input->post('name'),
            'slug' => url_title($this->input->post('name'), '-', TRUE),
            'sku' => $this->input->post('sku'),
            'category_id' => $this->input->post('category_id'),
            'sub_category_id' => $this->input->post('sub_category_id'),
            'sub_sub_category_id' => $this->input->post('sub_sub_category_id'),
            'brand_id' => $this->input->post('brand_id'),
            'unit_id' => $this->input->post('unit_id'),
            'selling_type' => $this->input->post('selling_type'),
            'barcode_symbology' => $this->input->post('barcode_symbology'),
            'item_code' => $this->input->post('item_code'),
            'description' => $this->input->post('description'),
            'product_type' => $this->input->post('product_type'),
            'quantity' => $this->input->post('quantity'),
            'price' => $this->input->post('price'),
            'tax_type' => $this->input->post('tax_type'),
            'discount_type' => $this->input->post('discount_type'),
            'discount_value' => $this->input->post('discount_value'),
            'quantity_alert' => $this->input->post('quantity_alert'),
            'warranties' => $this->input->post('warranties') ? 1 : 0,
            'manufacturer' => $this->input->post('manufacturer'),
            'expiry_date' => $this->input->post('expiry_date'),
            'manufactured_date' => $this->input->post('manufactured_date'),
            'created_by' => $this->session->userdata('user_id') // Assuming you have user session
        );

        // Handle variants for variable products
        $variants = array();
        if ($this->input->post('product_type') == 'Variable Product') {
            $variant_attributes = $this->input->post('variant_attribute');
            $variant_values = $this->input->post('variant_value');
            $variant_skus = $this->input->post('variant_sku');
            $variant_quantities = $this->input->post('variant_quantity');
            $variant_prices = $this->input->post('variant_price');

            if (!empty($variant_attributes)) {
                foreach ($variant_attributes as $key => $attribute) {
                    $variants[] = array(
                        'attribute' => $attribute,
                        'value' => $variant_values[$key],
                        'sku' => $variant_skus[$key],
                        'quantity' => $variant_quantities[$key],
                        'price' => $variant_prices[$key]
                    );
                }
            }
        }

        $data = array(
            'product' => $product_data,
            'images' => $images,
            'variants' => $variants
        );

        $product_id = $this->Home_model->create_product($data);

        if ($product_id) {
            $this->session->set_flashdata('success', 'Product created successfully!');
            redirect('product-list');
        } else {
            $this->session->set_flashdata('error', 'Failed to create product!');
            redirect('add_product');
        }
    }

    // Update product
    public function update_product($id) {
        $this->form_validation->set_rules('name', 'Product Name', 'required');
        $this->form_validation->set_rules('price', 'Price', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('edit_product/' . $id);
        }

        // Handle file uploads
        $images = array();
        if (!empty($_FILES['images']['name'][0])) {
            $files = $_FILES['images'];
            $config['upload_path'] = './uploads/products/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048;
            
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

            $this->load->library('upload', $config);

            foreach ($files['name'] as $key => $image) {
                $_FILES['images[]']['name'] = $files['name'][$key];
                $_FILES['images[]']['type'] = $files['type'][$key];
                $_FILES['images[]']['tmp_name'] = $files['tmp_name'][$key];
                $_FILES['images[]']['error'] = $files['error'][$key];
                $_FILES['images[]']['size'] = $files['size'][$key];

                $extension = pathinfo($files['name'][$key], PATHINFO_EXTENSION);
				$fileName = time() . '_' . rand(1,99999999) . '.' . $extension;
                $config['file_name'] = $fileName;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('images[]')) {
                    $images[] = 'uploads/products/' . $fileName;
                }
            }
        }

        // Prepare product data
        $product_data = array(
            'store_id' => $this->input->post('store_id'),
            'warehouse_id' => $this->input->post('warehouse_id'),
            'name' => $this->input->post('name'),
            'slug' => url_title($this->input->post('name'), '-', TRUE),
            'category_id' => $this->input->post('category_id'),
            'sub_category_id' => $this->input->post('sub_category_id'),
            'sub_sub_category_id' => $this->input->post('sub_sub_category_id'),
            'brand_id' => $this->input->post('brand_id'),
            'unit_id' => $this->input->post('unit_id'),
            'selling_type' => $this->input->post('selling_type'),
            'barcode_symbology' => $this->input->post('barcode_symbology'),
            'item_code' => $this->input->post('item_code'),
            'description' => $this->input->post('description'),
            'product_type' => $this->input->post('product_type'),
            'quantity' => $this->input->post('quantity'),
            'price' => $this->input->post('price'),
            'tax_type' => $this->input->post('tax_type'),
            'discount_type' => $this->input->post('discount_type'),
            'discount_value' => $this->input->post('discount_value'),
            'quantity_alert' => $this->input->post('quantity_alert'),
            'warranties' => $this->input->post('warranties') ? 1 : 0,
            'manufacturer' => $this->input->post('manufacturer'),
            'expiry_date' => $this->input->post('expiry_date'),
            'manufactured_date' => $this->input->post('manufactured_date')
        );

        // Handle variants for variable products
        $variants = array();
        if ($this->input->post('product_type') == 'Variable Product') {
            $variant_attributes = $this->input->post('variant_attribute');
            $variant_values = $this->input->post('variant_value');
            $variant_skus = $this->input->post('variant_sku');
            $variant_quantities = $this->input->post('variant_quantity');
            $variant_prices = $this->input->post('variant_price');

            if (!empty($variant_attributes)) {
                foreach ($variant_attributes as $key => $attribute) {
                    $variants[] = array(
                        'attribute' => $attribute,
                        'value' => $variant_values[$key],
                        'sku' => $variant_skus[$key],
                        'quantity' => $variant_quantities[$key],
                        'price' => $variant_prices[$key]
                    );
                }
            }
        }

        $data = array(
            'product' => $product_data,
            'images' => $images,
            'variants' => $variants
        );

        $result = $this->Home_model->update_product($id, $data);

        if ($result) {
            $this->session->set_flashdata('success', 'Product updated successfully!');
            redirect('product-list');
        } else {
            $this->session->set_flashdata('error', 'Failed to update product!');
            redirect('edit_product/' . $id);
        }
    }

    // Delete product
    public function delete_product($id) {
        $result = $this->Home_model->delete_product($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Product deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete product!');
        }
        
        redirect('product-list');
    }

    // Generate SKU via AJAX
    public function generate_sku() {
        $product_name = $this->input->post('product_name');
        $sku = $this->Home_model->generate_sku($product_name);
        
        echo json_encode(array('sku' => $sku));
    }

    
	
	// In your Product controller
	public function get_sub_categories() {
		$category_id = $this->input->post('category_id');
		$sub_categories = $this->Home_model->get_sub_categories($category_id);
		echo json_encode($sub_categories);
	}

	public function get_sub_sub_categories() {
		$sub_category_id = $this->input->post('sub_category_id');
		$sub_sub_categories = $this->Home_model->get_sub_sub_categories($sub_category_id);
		echo json_encode($sub_sub_categories);
	}


    public function Managestocks()
    {
        $this->load->view('manage-stocks');
    }

    public function Notes()
    {
        $this->load->view('notes');
    }

    public function Notification()
    {
        $this->load->view('notification');
    }

    public function Otpsettings()
    {
        $this->load->view('otp-settings');
    }

    public function Paymentgatewaysettings()
    {
        $this->load->view('payment-gateway-settings');
    }

    public function Payrolllist()
    {
        $this->load->view('payroll-list');
    }

    public function Payslip()
    {
        $this->load->view('payslip');
    }

    public function Permissions()
    {
        $this->load->view('permissions');
    }

    public function Pos()
    {
        $this->load->view('pos');
    }

    public function Possettings()
    {
        $this->load->view('pos-settings');
    }

    public function Preference()
    {
        $this->load->view('preference');
    }

    public function Prefixes()
    {
        $this->load->view('prefixes');
    }

    public function Printersettings()
    {
        $this->load->view('printer-settings');
    }

    // Product Details page
	public function product_details($id) {
		// Get product data
		$data['product'] = $this->Home_model->get_product_by_id($id);
		
		if (!$data['product']) {
			show_404(); // Show 404 if product not found
		}
		
		// Get product images
		$data['images'] = $this->Home_model->get_product_images($id);
		
		// Get product variants
		$data['variants'] = $this->Home_model->get_product_variants($id);
		
		// Load the view
		$this->load->view('product-details', $data);
	}

    

    public function Profile()
    {
        $this->load->view('profile');
    }

    public function Profitandloss()
    {
        $this->load->view('profit-and-loss');
    }

    public function Purchaselist()
    {
        $this->load->view('purchase-list');
    }

    public function Purchaseorderreport()
    {
        $this->load->view('purchase-order-report');
    }

    public function Purchasereport()
    {
        $this->load->view('purchase-report');
    }

    public function Purchasereturns()
    {
        $this->load->view('purchase-returns');
    }

    public function Qrcode()
    {
        $this->load->view('qrcode');
    }

    public function Quotationlist()
    {
        $this->load->view('quotation-list');
    }

    public function Register()
    {
        $this->load->view('register');
    }

    public function Register2()
    {
        $this->load->view('register-2');
    }

    public function Register3()
    {
        $this->load->view('register-3');
    }

    public function Resetpassword()
    {
        $this->load->view('reset-password');
    }

    public function Resetpassword2()
    {
        $this->load->view('reset-password-2');
    }

    public function Resetpassword3()
    {
        $this->load->view('reset-password-3');
    }

    public function Rolespermissions()
    {
        $this->load->view('roles-permissions');
    }

    public function Salesdashboard()
    {
        $this->load->view('sales-dashboard');
    }

    public function Saleslist()
    {
        $this->load->view('sales-list');
    }

    public function Salesreport()
    {
        $this->load->view('sales-report');
    }

    public function Salesreturns()
    {
        $this->load->view('sales-returns');
    }

    public function Securitysettings()
    {
        $this->load->view('security-settings');
    }

    public function Shift()
    {
        $this->load->view('shift');
    }

    public function Signin()
    {
        $this->load->view('signin');
    }

    public function Signin2()
    {
        $this->load->view('signin-2');
    }

    public function Signin3()
    {
        $this->load->view('signin-3');
    }

    public function Smsgateway()
    {
        $this->load->view('sms-gateway');
    }

    public function Socialauthentication()
    {
        $this->load->view('social-authentication');
    }

    public function Stockadjustment()
    {
        $this->load->view('stock-adjustment');
    }

    public function Stocktransfer()
    {
        $this->load->view('stock-transfer');
    }

    public function Storagesettings()
    {
        $this->load->view('storage-settings');
    }

    public function Storelist()
    {
        $this->load->view('store-list');
    }

    public function Subcategories()
    {
        $this->load->view('sub-categories');
    }

    public function Success()
    {
        $this->load->view('success');
    }

    public function Success2()
    {
        $this->load->view('success-2');
    }

    public function Success3()
    {
        $this->load->view('success-3');
    }

    public function Supplierreport()
    {
        $this->load->view('supplier-report');
    }

    public function Suppliers()
    {
        $this->load->view('suppliers');
    }

    public function Systemsettings()
    {
        $this->load->view('system-settings');
    }

    public function Tablesbasic()
    {
        $this->load->view('tables-basic');
    }

    public function Taxrates()
    {
        $this->load->view('tax-rates');
    }

    public function Taxreports()
    {
        $this->load->view('tax-reports');
    }

    public function Todo()
    {
        $this->load->view('todo');
    }

    public function Twostepverification()
    {
        $this->load->view('two-step-verification');
    }

    public function Twostepverification2()
    {
        $this->load->view('two-step-verification-2');
    }

    public function Twostepverification3()
    {
        $this->load->view('two-step-verification-3');
    }

    public function Uiaccordion()
    {
        $this->load->view('ui-accordion');
    }

    public function Uialerts()
    {
        $this->load->view('ui-alerts');
    }

    public function Uiavatar()
    {
        $this->load->view('ui-avatar');
    }

    public function Uibadges()
    {
        $this->load->view('ui-badges');
    }

    public function Uiborders()
    {
        $this->load->view('ui-borders');
    }

    public function Uibreadcrumb()
    {
        $this->load->view('ui-breadcrumb');
    }

    public function Uibuttonsgroup()
    {
        $this->load->view('ui-buttons-group');
    }

    public function Uibuttons()
    {
        $this->load->view('ui-buttons');
    }

    public function Uicards()
    {
        $this->load->view('ui-cards');
    }

    public function Uicarousel()
    {
        $this->load->view('ui-carousel');
    }

    public function Uiclipboard()
    {
        $this->load->view('ui-clipboard');
    }

    public function Uicolors()
    {
        $this->load->view('ui-colors');
    }

    public function Uicounter()
    {
        $this->load->view('ui-counter');
    }

    public function Uidragdrop()
    {
        $this->load->view('ui-drag-drop');
    }

    public function Uidropdowns()
    {
        $this->load->view('ui-dropdowns');
    }

    public function Uigrid()
    {
        $this->load->view('ui-grid');
    }

    public function Uiimages()
    {
        $this->load->view('ui-images');
    }

    public function Uilightbox()
    {
        $this->load->view('ui-lightbox');
    }

    public function Uimedia()
    {
        $this->load->view('ui-media');
    }

    public function Uimodals()
    {
        $this->load->view('ui-modals');
    }

    public function Uinavtabs()
    {
        $this->load->view('ui-nav-tabs');
    }

    public function Uioffcanvas()
    {
        $this->load->view('ui-offcanvas');
    }

    public function Uipagination()
    {
        $this->load->view('ui-pagination');
    }

    public function Uiplaceholders()
    {
        $this->load->view('ui-placeholders');
    }

    public function Uipopovers()
    {
        $this->load->view('ui-popovers');
    }

    public function Uiprogress()
    {
        $this->load->view('ui-progress');
    }

    public function Uirangeslider()
    {
        $this->load->view('ui-rangeslider');
    }

    public function Uirating()
    {
        $this->load->view('ui-rating');
    }

    public function Uiribbon()
    {
        $this->load->view('ui-ribbon');
    }

    public function Uiscrollbar()
    {
        $this->load->view('ui-scrollbar');
    }

    public function Uispinner()
    {
        $this->load->view('ui-spinner');
    }

    public function Uistickynote()
    {
        $this->load->view('ui-stickynote');
    }

    public function Uisweetalerts()
    {
        $this->load->view('ui-sweetalerts');
    }

    public function Uitexteditor()
    {
        $this->load->view('ui-text-editor');
    }

    public function Uitimeline()
    {
        $this->load->view('ui-timeline');
    }

    public function Uitoasts()
    {
        $this->load->view('ui-toasts');
    }

    public function Uitooltips()
    {
        $this->load->view('ui-tooltips');
    }

    public function Uitypography()
    {
        $this->load->view('ui-typography');
    }

    public function Uivideo()
    {
        $this->load->view('ui-video');
    }

    public function Undermaintenance()
    {
        $this->load->view('under-maintenance');
    }

    public function Units()
    {
        $this->load->view('units');
    }

    public function Users()
    {
        $this->load->view('users');
    }

    public function Varriantattributes()
    {
        $this->load->view('varriant-attributes');
    }   

    public function Videocall()
    {
        $this->load->view('video-call');
    }

    public function warehouse()
{
    // Load the Warehouse model
    $this->load->model('Warehouse_model');

    // Fetch all warehouses
    $data['warehouses'] = $this->Warehouse_model->getAll();

    // Load view and pass data
    $this->load->view('warehouse', $data);
}


    public function Warranty()
    {
        $this->load->view('warranty');
    }

    public function activities()
    {
        $this->load->view('activities');
    }

    public function add_employee()
    {
        $this->load->view('add-employee');
    }

    

    public function appearance()
    {
        $this->load->view('appearance');
    }

    public function attendance_admin()
    {
        $this->load->view('attendance-admin');
    }

    public function attendance_employee()
    {
        $this->load->view('attendance-employee');
    }

    public function audio_call()
    {
        $this->load->view('audio-call');
    }

    public function ban_ip_address()
    {
        $this->load->view('ban-ip-address');
    }

    public function bank_settings_grid()
    {
        $this->load->view('bank-settings-grid');
    }

    public function bank_settings_list()
    {
        $this->load->view('bank-settings-list');
    }

    public function barcode()
    {
        $this->load->view('barcode');
    }

    public function blank_page()
    {
        $this->load->view('blank-page');
    }

    public function brand_list()
    {
        $this->load->view('brand-list');
    }

    public function calendar()
    {
        $this->load->view('calendar');
    }

    public function call_history()
    {
        $this->load->view('call-history');
    }

    public function chart_apex()
    {
        $this->load->view('chart-apex');
    }

    public function chart_c3()
    {
        $this->load->view('chart-c3');
    }

    public function chart_flot()
    {
        $this->load->view('chart-flot');
    }

    public function chart_js()
    {
        $this->load->view('chart-js');
    }

    public function chart_morris()
    {
        $this->load->view('chart-morris');
    }

    public function chart_peity()
    {
        $this->load->view('chart-peity');
    }

    public function chat()
    {
        $this->load->view('chat');
    }

    public function coming_soon()
    {
        $this->load->view('coming-soon');
    }

    public function company_settings()
    {
        $this->load->view('company-settings');
    }

    public function connected_apps()
    {
        $this->load->view('connected-apps');
    }

    public function countries()
    {
        $this->load->view('countries');
    }

    public function coupons()
    {
        $this->load->view('coupons');
    }

    public function currency_settings()
    {
        $this->load->view('currency-settings');
    }

    public function custom_fields()
    {
        $this->load->view('custom-fields');
    }

    public function customer_report()
    {
        $this->load->view('customer-report');
    }

    public function customers()
    {
        $this->load->view('customers');
    }

    public function data_tables()
    {
        $this->load->view('data-tables');
    }

    public function delete_account()
    {
        $this->load->view('delete-account');         
    }

    public function department_grid()
    {
        $this->load->view('department-grid');         
    }

    public function department_list()
    {
        $this->load->view('department-list');         
    }

    public function designation()
    {
        $this->load->view('designation');         
    }

    public function edit_employee()
    {
        $this->load->view('edit-employee');         
    }


    public function email_verification_2()
    {
        $this->load->view('email-verification-2');         
    }

    public function email_verification_3()
    {
        $this->load->view('email-verification-3');         
    }

    public function email_verification()
    {
        $this->load->view('email-verification');         
    }

    public function email()
    {
        $this->load->view('email');         
    }

    public function employees_grid()
    {
        $this->load->view('employees-grid');         
    }

    public function employees_list()
    {
        $this->load->view('employees-list');         
    }

    public function error_404()
    {
        $this->load->view('error-404');         
    }

    public function error_500()
    {
        $this->load->view('error-500');         
    }

    public function expense_category()
    {
        $this->load->view('expense-category');         
    }

    public function expense_list()
    {
        $this->load->view('expense-list');         
    }

    public function expense_report()
    {
        $this->load->view('expense-report');         
    }

    public function expired_products()
    {
        $this->load->view('expired-products');         
    }

    public function file_archived()
    {
        $this->load->view('file-archived');         
    }

    public function file_document()
    {
        $this->load->view('file-document');         
    }

    public function file_favourites()
    {
        $this->load->view('file-favourites');         
    }

    public function file_manager_deleted()
    {
        $this->load->view('file-manager-deleted');         
    }

    public function file_manager()
    {
        $this->load->view('file-manager');         
    }

    public function file_recent()
    {
        $this->load->view('file-recent');         
    }

    public function file_shared()
    {
        $this->load->view('file-shared');         
    }

    public function forgot_password_2()
    {
        $this->load->view('forgot-password-2');         
    }

    public function forgot_password_3()
    {
        $this->load->view('forgot-password-3');         
    }

    public function forgot_password()
    {
        $this->load->view('forgot-password');         
    }

    public function form_basic_inputs()
    {
        $this->load->view('form-basic-inputs');         
    }

    public function form_checkbox_radios()
    {
        $this->load->view('form-checkbox-radios');         
    }

    public function form_elements()
    {
        $this->load->view('form-elements');         
    }

    public function form_fileupload()
    {
        $this->load->view('form-fileupload');         
    }

    public function form_floating_labels()
    {
        $this->load->view('form-floating-labels');         
    }

    public function form_grid_gutters()
    {
        $this->load->view('form-grid-gutters');         
    }

    public function form_horizontal()
    {
        $this->load->view('form-horizontal');         
    }

    public function form_input_groups()
    {
        $this->load->view('form-input-groups');         
    }

    public function form_mask()
    {
        $this->load->view('form-mask');         
    }

    public function form_select()
    {
        $this->load->view('form-select');         
    }

    public function form_select2()
    {
        $this->load->view('form-select2');         
    }

    public function form_validation()
    {
        $this->load->view('form-validation');         
    }

    public function form_vertical()
    {
        $this->load->view('form-vertical');         
    }

    public function form_wizard()
    {
        $this->load->view('form-wizard');         
    }

    public function gdpr_settings()
    {
        $this->load->view('gdpr-settings');         
    }

    public function general_settings()
    {
        $this->load->view('general-settings');         
    }

    public function holidays()
    {
        $this->load->view('holidays');         
    }

    public function icon_feather()
    {
        $this->load->view('icon-feather');         
    }

    public function icon_flag()
    {
        $this->load->view('icon-flag');         
    }

    public function icon_fontawesome()
    {
        $this->load->view('icon-fontawesome');         
    }

    public function icon_ionic()
    {
        $this->load->view('icon-ionic');         
    }

    public function icon_material()
    {
        $this->load->view('icon-material');         
    }

    public function icon_pe7()
    {
        $this->load->view('icon-pe7');         
    }

    public function icon_simpleline()
    {
        $this->load->view('icon-simpleline');         
    }

    public function icon_themify()
    {
        $this->load->view('icon-themify');         
    }

    public function icon_typicon()
    {
        $this->load->view('icon-typicon');         
    }

    public function icon_weather()
    {
        $this->load->view('icon-weather');         
    }

    public function income_report()
    {
        $this->load->view('income-report');         
    }

    public function inventory_report()
    {
        $this->load->view('inventory-report');         
    }

    public function invoice_report()
    {
        $this->load->view('invoice-report');         
    }

    public function invoice_settings()
    {
        $this->load->view('invoice-settings');         
    }

    public function language_settings_web()
    {
        $this->load->view('language-settings-web');         
    }

    public function language_settings()
    {
        $this->load->view('language-settings');         
    }

    public function leave_types()
    {
        $this->load->view('leave-types');         
    }

    public function leaves_admin()
    {
        $this->load->view('leaves-admin');         
    }

    public function leaves_employee()
    {
        $this->load->view('leaves-employee');         
    }

    public function localization_settings()
    {
        $this->load->view('localization-settings');         
    }

    public function lock_screen()
    {
        $this->load->view('lock-screen');         
    }

    public function low_stocks()
    {
        $this->load->view('low-stocks');         
    }

    public function states()
    {
        $this->load->view('states');         
    }




// new creations

    public function Districts()
    {
        $this->load->view('districts');         
    }

    public function Mandals()
    {
        $this->load->view('mandals');         
    }
    
    public function Villages()
    {
        $this->load->view('villages');         
    }





    public function email_settings()
    {
        $this->load->view('email-settings');         
    }
	
	/**
     * Remove product image via AJAX
     */
    public function remove_product_image() {
        // Check if it's an AJAX request
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $response = array('success' => false, 'message' => '');
        
        try {
            $image_id = $this->input->post('image_id');
            $product_id = $this->input->post('product_id');

            // Validate inputs
            if (empty($image_id) || empty($product_id)) {
                throw new Exception('Invalid parameters');
            }

            // Get image details
            $image = $this->Home_model->get_product_image($image_id, $product_id);
            
            if (!$image) {
                throw new Exception('Image not found');
            }

            // Delete file from server
            $file_path = FCPATH . $image->image_path;
            if (file_exists($file_path)) {
                if (!unlink($file_path)) {
                    throw new Exception('Failed to delete image file');
                }
            }

            // Delete record from database
            $delete_result = $this->Home_model->delete_product_image($image_id);
            
            if (!$delete_result) {
                throw new Exception('Failed to remove image from database');
            }

            $response['success'] = true;
            $response['message'] = 'Image removed successfully';
            $response['image_path'] = $image->image_path;

        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
            log_message('error', 'Image removal error: ' . $e->getMessage());
        }

        // Return JSON response
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * Alternative version with more validation
     */
    public function remove_product_image_secure() {
        // Check AJAX request
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // Verify CSRF token
        if (!$this->security->csrf_verify()) {
            $response = array(
                'success' => false,
                'message' => 'Security token invalid'
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        }

        $response = array('success' => false, 'message' => '');
        
        $this->db->trans_start(); // Start transaction

        try {
            $image_id = (int)$this->input->post('image_id');
            $product_id = (int)$this->input->post('product_id');
            $user_id = $this->session->userdata('user_id'); // Assuming you have user authentication

            // Validate user permissions
            if (!$this->check_product_ownership($product_id, $user_id)) {
                throw new Exception('You do not have permission to modify this product');
            }

            // Get image details with product validation
            $image = $this->Home_model->get_product_image_with_product($image_id, $product_id);
            
            if (!$image) {
                throw new Exception('Image not found or does not belong to this product');
            }

            $file_path = FCPATH . $image->image_path;
            
            // Delete physical file
            if (file_exists($file_path) && is_file($file_path)) {
                if (!unlink($file_path)) {
                    throw new Exception('Failed to delete image file from server');
                }
                
                // Also delete any generated thumbnails if they exist
                $this->delete_image_thumbnails($file_path);
            }

            // Delete database record
            $delete_success = $this->Home_model->delete_product_image($image_id);
            
            if (!$delete_success) {
                throw new Exception('Failed to remove image record from database');
            }

            $this->db->trans_commit(); // Commit transaction

            $response['success'] = true;
            $response['message'] = 'Image removed successfully';
            $response['image_path'] = $image->image_path;

            // Log the action
            $this->log_image_removal($product_id, $image_id, $user_id);

        } catch (Exception $e) {
            $this->db->trans_rollback(); // Rollback transaction on error
            
            $response['message'] = $e->getMessage();
            log_message('error', 'Product image removal failed: ' . $e->getMessage());
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * Check if user owns the product
     */
    private function check_product_ownership($product_id, $user_id) {
        // Implement your ownership logic here
        // This could check if the user owns the store that the product belongs to
        $product = $this->Home_model->get_product($product_id);
        return $product && $product->user_id == $user_id; // Adjust based on your database structure
    }

    /**
     * Delete image thumbnails if they exist
     */
    private function delete_image_thumbnails($original_path) {
        $path_info = pathinfo($original_path);
        $thumbnail_pattern = $path_info['dirname'] . '/thumbs/' . $path_info['filename'] . '_*.' . $path_info['extension'];
        
        $thumbnails = glob($thumbnail_pattern);
        foreach ($thumbnails as $thumbnail) {
            if (file_exists($thumbnail)) {
                unlink($thumbnail);
            }
        }
    }

    /**
     * Log image removal for audit purposes
     */
    private function log_image_removal($product_id, $image_id, $user_id) {
        $log_data = array(
            'product_id' => $product_id,
            'image_id' => $image_id,
            'user_id' => $user_id,
            'action' => 'image_removed',
            'timestamp' => date('Y-m-d H:i:s'),
            'ip_address' => $this->input->ip_address()
        );
        
        $this->db->insert('activity_logs', $log_data); // Assuming you have an activity_logs table
    }
	
	public function category_list() {
		$data['categories'] = $this->Home_model->get_all_categories();
		$this->load->view('category-list', $data);
	}

	// Add new category (AJAX)
	public function add_category() {
		$response = array('success' => false, 'message' => '', 'category' => array());
		
		$this->form_validation->set_rules('name', 'Category Name', 'required|trim');
		
		if ($this->form_validation->run() == FALSE) {
			$response['message'] = validation_errors();
		} else {
			$name = $this->input->post('name');
			$description = $this->input->post('description');
			$status = $this->input->post('status') ? 1 : 0;
			
			// Generate slug
			$slug = $this->Home_model->generate_slug($name);
			
			// Make slug unique if it exists
			$original_slug = $slug;
			$counter = 1;
			while ($this->Home_model->slug_exists($slug)) {
				$slug = $original_slug . '-' . $counter;
				$counter++;
			}
			
			$data = array(
				'name' => $name,
				'slug' => $slug,
				'description' => $description,
				'status' => $status,
				'created_at' => date('Y-m-d H:i:s')
			);
			
			$insert_id = $this->Home_model->create_category($data);
			if ($insert_id) {
				$response['success'] = true;
				$response['message'] = 'Category added successfully!';
				$response['category'] = $this->Home_model->get_category_by_id($insert_id);
			} else {
				$response['message'] = 'Failed to add category!';
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}

	// Edit category (AJAX)
	public function edit_category($id) {
		$response = array('success' => false, 'message' => '');
		
		$this->form_validation->set_rules('name', 'Category Name', 'required|trim');
		
		if ($this->form_validation->run() == FALSE) {
			$response['message'] = validation_errors();
		} else {
			$name = $this->input->post('name');
			$description = $this->input->post('description');
			$status = $this->input->post('status') ? 1 : 0;
			
			// Generate slug
			$slug = $this->Home_model->generate_slug($name);
			
			// Make slug unique if it exists (excluding current category)
			$original_slug = $slug;
			$counter = 1;
			while ($this->Home_model->slug_exists($slug, $id)) {
				$slug = $original_slug . '-' . $counter;
				$counter++;
			}
			
			$data = array(
				'name' => $name,
				'slug' => $slug,
				'description' => $description,
				'status' => $status,
				'updated_at' => date('Y-m-d H:i:s')
			);
			
			if ($this->Home_model->update_category($id, $data)) {
				$response['success'] = true;
				$response['message'] = 'Category updated successfully!';
			} else {
				$response['message'] = 'Failed to update category!';
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}

	// Delete category (AJAX)
	public function delete_category($id) {
		$response = array('success' => false, 'message' => '');
		
		if ($this->Home_model->delete_category($id)) {
			$response['success'] = true;
			$response['message'] = 'Category deleted successfully!';
		} else {
			$response['message'] = 'Failed to delete category!';
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}

	// Get category data for edit modal (AJAX)
	public function get_category_data($id) {
		$category = $this->Home_model->get_category_by_id($id);
		if ($category) {
			echo json_encode($category);
		} else {
			echo json_encode(['error' => 'Category not found']);
		}
	}
	
	// Get categories data for AJAX refresh (JSON)
	public function get_categories_data() {
		$categories = $this->Home_model->get_all_categories();
		$response = array(
			'success' => true,
			'categories' => $categories
		);
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	



}