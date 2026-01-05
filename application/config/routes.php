<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Main routes
$route['dashboard'] = 'home/dashboard';
$route['sales-dashboard'] = 'home/salesdashboard';

// Application routes
$route['chat'] = 'home/chat';
$route['video-call'] = 'home/videocall';
$route['audio-call'] = 'home/audio_call';
$route['call-history'] = 'home/call_history';
$route['calendar'] = 'home/calendar';
$route['email'] = 'home/email';
$route['todo'] = 'home/todo';
$route['notes'] = 'home/notes';
$route['file-manager'] = 'home/file_manager';

// Inventory routes
$route['product-list'] = 'home/productlist';
$route['product-details'] = 'home/productdetails';
$route['add-product'] = 'home/add_product';
$route['expired-products'] = 'home/expired_products';
$route['low-stocks'] = 'home/low_stocks';
$route['category-list'] = 'home/category_list';
$route['sub-categories'] = 'home/subcategories';
$route['brand-list'] = 'home/brand_list';
$route['units'] = 'home/units';
$route['varriant-attributes'] = 'home/varriantattributes';
$route['warranty'] = 'home/warranty';
$route['barcode'] = 'home/barcode';
$route['qrcode'] = 'home/qrcode';

// Stock routes
$route['manage-stocks'] = 'home/managestocks';
$route['stock-adjustment'] = 'home/stockadjustment';
$route['stock-transfer'] = 'home/stocktransfer';

// Sales routes
$route['sales-list'] = 'home/saleslist';
$route['invoice-report'] = 'home/invoice_report';
$route['sales-returns'] = 'home/salesreturns';
$route['quotation-list'] = 'home/quotationlist';
$route['pos'] = 'home/pos';
$route['coupons'] = 'home/coupons';

// Purchase routes
$route['purchase-list'] = 'home/purchaselist';
$route['purchase-order-report'] = 'home/purchaseorderreport';
$route['purchase-returns'] = 'home/purchasereturns';

// Expense routes
$route['expense-list'] = 'home/expense_list';
$route['expense-category'] = 'home/expense_category';

// People routes
$route['customers'] = 'home/customers';
$route['suppliers'] = 'home/suppliers';
$route['store-list'] = 'home/storelist';
$route['warehouse'] = 'warehouse/index';

// HRM routes
$route['employees-grid'] = 'home/employees_grid';
$route['employees-list'] = 'home/employees_list';
$route['add-employee'] = 'home/add_employee';
$route['edit-employee'] = 'home/edit_employee';
$route['department-grid'] = 'home/department_grid';
$route['department-list'] = 'home/department_list';
$route['designation'] = 'home/designation';
$route['shift'] = 'home/shift';
$route['attendance-employee'] = 'home/attendance_employee';
$route['attendance-admin'] = 'home/attendance_admin';
$route['leaves-admin'] = 'home/leaves_admin';
$route['leaves-employee'] = 'home/leaves_employee';
$route['leave-types'] = 'home/leave_types';
$route['holidays'] = 'home/holidays';
$route['payroll-list'] = 'home/payrolllist';
$route['payslip'] = 'home/payslip';

// Report routes
$route['sales-report'] = 'home/salesreport';
$route['purchase-report'] = 'home/purchasereport';
$route['inventory-report'] = 'home/inventory_report';
$route['supplier-report'] = 'home/supplierreport';
$route['customer-report'] = 'home/customer_report';
$route['expense-report'] = 'home/expense_report';
$route['income-report'] = 'home/income_report';
$route['tax-reports'] = 'home/taxreports';
$route['profit-and-loss'] = 'home/profitandloss';

// User Management routes
$route['users'] = 'home/users';
$route['roles-permissions'] = 'home/rolespermissions';
$route['permissions'] = 'home/permissions';
$route['delete-account'] = 'home/delete_account';

// Pages routes
$route['profile'] = 'home/profile';
$route['signin'] = 'home/signin';
$route['signin-2'] = 'home/signin2';
$route['signin-3'] = 'home/signin3';
$route['register'] = 'home/register';
$route['register-2'] = 'home/register2';
$route['register-3'] = 'home/register3';
$route['forgot-password'] = 'home/forgot_password';
$route['forgot-password-2'] = 'home/forgot_password_2';
$route['forgot-password-3'] = 'home/forgot_password_3';
$route['reset-password'] = 'home/resetpassword';
$route['reset-password-2'] = 'home/resetpassword2';
$route['reset-password-3'] = 'home/resetpassword3';
$route['email-verification'] = 'home/email_verification';
$route['email-verification-2'] = 'home/email_verification_2';
$route['email-verification-3'] = 'home/email_verification_3';
$route['two-step-verification'] = 'home/twostepverification';
$route['two-step-verification-2'] = 'home/twostepverification2';
$route['two-step-verification-3'] = 'home/twostepverification3';
$route['lock-screen'] = 'home/lock_screen';
$route['error-404'] = 'home/error_404';
$route['error-500'] = 'home/error_500';
$route['countries'] = 'home/countries';
$route['states'] = 'home/states';
$route['blank-page'] = 'home/blank_page';
$route['coming-soon'] = 'home/coming_soon';
$route['under-maintenance'] = 'home/undermaintenance';

// Settings routes
$route['general-settings'] = 'home/general_settings';
$route['security-settings'] = 'home/securitysettings';
$route['notification'] = 'home/notification';
$route['connected-apps'] = 'home/connected_apps';
$route['system-settings'] = 'home/systemsettings';
$route['company-settings'] = 'home/company_settings';
$route['localization-settings'] = 'home/localization_settings';
$route['prefixes'] = 'home/prefixes';
$route['preference'] = 'home/preference';
$route['appearance'] = 'home/appearance';
$route['social-authentication'] = 'home/socialauthentication';
$route['language-settings'] = 'home/language_settings';
$route['language-settings-web'] = 'home/language_settings_web';
$route['invoice-settings'] = 'home/invoice_settings';
$route['printer-settings'] = 'home/printersettings';
$route['pos-settings'] = 'home/possettings';
$route['custom-fields'] = 'home/custom_fields';
$route['email-settings'] = 'home/email_settings';
$route['sms-gateway'] = 'home/smsgateway';
$route['otp-settings'] = 'home/otpsettings';
$route['gdpr-settings'] = 'home/gdpr_settings';
$route['payment-gateway-settings'] = 'home/paymentgatewaysettings';
$route['bank-settings-grid'] = 'home/bank_settings_grid';
$route['bank-settings-list'] = 'home/bank_settings_list';
$route['tax-rates'] = 'home/taxrates';
$route['currency-settings'] = 'home/currency_settings';
$route['storage-settings'] = 'home/storagesettings';
$route['ban-ip-address'] = 'home/ban_ip_address';

// UI Interface routes
$route['ui-alerts'] = 'home/uialerts';
$route['ui-accordion'] = 'home/uiaccordion';
$route['ui-avatar'] = 'home/uiavatar';
$route['ui-badges'] = 'home/uibadges';
$route['ui-borders'] = 'home/uiborders';
$route['ui-buttons'] = 'home/uibuttons';
$route['ui-buttons-group'] = 'home/uibuttonsgroup';
$route['ui-breadcrumb'] = 'home/uibreadcrumb';
$route['ui-cards'] = 'home/uicards';
$route['ui-carousel'] = 'home/uicarousel';
$route['ui-colors'] = 'home/uicolors';
$route['ui-dropdowns'] = 'home/uidropdowns';
$route['ui-grid'] = 'home/uigrid';
$route['ui-images'] = 'home/uiimages';
$route['ui-lightbox'] = 'home/uilightbox';
$route['ui-media'] = 'home/uimedia';
$route['ui-modals'] = 'home/uimodals';
$route['ui-offcanvas'] = 'home/uioffcanvas';
$route['ui-pagination'] = 'home/uipagination';
$route['ui-popovers'] = 'home/uipopovers';
$route['ui-progress'] = 'home/uiprogress';
$route['ui-placeholders'] = 'home/uiplaceholders';
$route['ui-rangeslider'] = 'home/uirangeslider';
$route['ui-spinner'] = 'home/uispinner';
$route['ui-sweetalerts'] = 'home/uisweetalerts';
$route['ui-nav-tabs'] = 'home/uinavtabs';
$route['ui-toasts'] = 'home/uitoasts';
$route['ui-tooltips'] = 'home/uitooltips';
$route['ui-typography'] = 'home/uitypography';
$route['ui-video'] = 'home/uivideo';
$route['ui-ribbon'] = 'home/uiribbon';
$route['ui-clipboard'] = 'home/uiclipboard';
$route['ui-drag-drop'] = 'home/uidragdrop';
$route['ui-rating'] = 'home/uirating';
$route['ui-text-editor'] = 'home/uitexteditor';
$route['ui-counter'] = 'home/uicounter';
$route['ui-scrollbar'] = 'home/uiscrollbar';
$route['ui-stickynote'] = 'home/uistickynote';
$route['ui-timeline'] = 'home/uitimeline';

// Chart routes
$route['chart-apex'] = 'home/chart_apex';
$route['chart-c3'] = 'home/chart_c3';
$route['chart-js'] = 'home/chart_js';
$route['chart-morris'] = 'home/chart_morris';
$route['chart-flot'] = 'home/chart_flot';
$route['chart-peity'] = 'home/chart_peity';

// Icon routes
$route['icon-fontawesome'] = 'home/icon_fontawesome';
$route['icon-feather'] = 'home/icon_feather';
$route['icon-ionic'] = 'home/icon_ionic';
$route['icon-material'] = 'home/icon_material';
$route['icon-pe7'] = 'home/icon_pe7';
$route['icon-simpleline'] = 'home/icon_simpleline';
$route['icon-themify'] = 'home/icon_themify';
$route['icon-weather'] = 'home/icon_weather';
$route['icon-typicon'] = 'home/icon_typicon';
$route['icon-flag'] = 'home/icon_flag';

// Form routes
$route['form-basic-inputs'] = 'home/form_basic_inputs';
$route['form-checkbox-radios'] = 'home/form_checkbox_radios';
$route['form-input-groups'] = 'home/form_input_groups';
$route['form-grid-gutters'] = 'home/form_grid_gutters';
$route['form-select'] = 'home/form_select';
$route['form-mask'] = 'home/form_mask';
$route['form-fileupload'] = 'home/form_fileupload';
$route['form-horizontal'] = 'home/form_horizontal';
$route['form-vertical'] = 'home/form_vertical';
$route['form-floating-labels'] = 'home/form_floating_labels';
$route['form-validation'] = 'home/form_validation';
$route['form-select2'] = 'home/form_select2';
$route['form-wizard'] = 'home/form_wizard';

// Table routes
$route['tables-basic'] = 'home/tablesbasic';
$route['data-tables'] = 'home/data_tables';

// Other routes
$route['activities'] = 'home/activities';
$route['success'] = 'home/success';
$route['success-2'] = 'home/success2';
$route['success-3'] = 'home/success3';

$route['home/get-sub-categories'] = 'home/get_sub_categories';
$route['home/get-sub-sub-categories'] = 'home/get_sub_sub_categories';
$route['product-details/(:num)'] = 'home/product_details/$1';
$route['edit-product/(:num)'] = 'home/edit_product/$1';

$route['category-list'] = 'home/category_list';
$route['home/add_category'] = 'home/add_category';
$route['home/edit_category/(:num)'] = 'home/edit_category/$1';
$route['home/delete_category/(:num)'] = 'home/delete_category/$1';
$route['home/get_category_data/(:num)'] = 'home/get_category_data/$1';

$route['home/export_pdf'] = 'home/export_pdf';
$route['home/export_excel'] = 'home/export_excel';
$route['home/print_categories'] = 'home/print_categories';






// naa creations

$route['default_controller'] = 'Countries';
$route['countries'] = 'Countries/index';
$route['countries/store'] = 'Countries/store';
$route['countries/update/(:num)'] = 'Countries/update/$1';
$route['countries/delete/(:num)'] = 'Countries/delete/$1';
$route['countries/export_excel'] = 'Countries/export_excel';



// ================= STATES =================
$route['states'] = 'States/index';
$route['states/store'] = 'States/store';
$route['states/update/(:num)'] = 'States/update/$1';
$route['states/delete/(:num)'] = 'States/delete/$1';
$route['states/export_excel'] = 'States/export_excel';

// ================= DISTRICTS =================
$route['districts'] = 'districts/index';            // List districts
$route['districts/add'] = 'districts/add';          // Add district
$route['districts/edit/(:num)'] = 'districts/edit/$1';   // Edit district
$route['districts/delete/(:num)'] = 'districts/delete/$1'; // Delete district
$route['districts/toggle_status/(:num)'] = 'districts/toggle_status/$1'; // Toggle status

// ================== MANDALS =================
$route['mandals'] = 'mandals/index';
$route['mandals/store'] = 'mandals/store';
$route['mandals/update/(:num)'] = 'mandals/update/$1';
$route['mandals/delete/(:num)'] = 'mandals/delete/$1';
$route['mandals/toggle_status/(:num)'] = 'mandals/toggle_status/$1';
$route['mandals/export_excel'] = 'mandals/export_excel';

// ================== VILLAGES =================
$route['villages'] = 'villages/index';
$route['villages/store'] = 'villages/store';
$route['villages/update/(:num)'] = 'villages/update/$1';
$route['villages/delete/(:num)'] = 'villages/delete/$1';
$route['villages/toggle_status/(:num)'] = 'villages/toggle_status/$1';
$route['villages/export_excel'] = 'villages/export_excel';




// ================== USERS =================


$route['default_controller'] = 'users/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// User Registration
$route['users/register-3'] = 'users/register';

// User Login
$route['users/login'] = 'users/login';

// User Dashboard (Super Admin view)
$route['users/dashboard'] = 'users/dashboard';

// Delete User
$route['users/delete/(:num)'] = 'users/delete/$1';


// default route for users controller

$route['users/(:any)'] = 'users/something_else';


$route['users/signin-3'] = 'users/login';
$route['register-3'] = 'users/register'; // if needed
$route['forgot-password-3'] = 'users/forgot_password';
$route['reset-password-3'] = 'users/reset_password';




// ================= PAYMENT METHODS =================
$route['payment-methods'] = 'paymentMethods/index';
$route['payment-methods/save'] = 'paymentMethods/save';
$route['payment-methods/delete/(:num)'] = 'paymentMethods/delete/$1';
$route['payment-methods/toggle/(:num)'] = 'paymentMethods/toggle/$1';



// ================= Advanced location =================


$route['location1'] = 'Location1/index';
$route['location1/store'] = 'Location1/store';
$route['location1/update/(:num)'] = 'Location1/update/$1';
$route['location1/delete/(:num)'] = 'Location1/delete/$1';
$route['location1/toggle/(:num)'] = 'Location1/toggle/$1';
$route['location1/export_excel'] = 'Location1/export_excel';










// advanced developed routs =============



$route['product_list']           = 'home/products_list';
$route['add-product']             = 'home/add_product';
$route['product/edit/(:num)']     = 'home/edit_product/$1';
$route['product/update/(:num)']   = 'home/update_product/$1';
$route['product/delete_product/(:num)'] = 'home/delete_product/$1';
$route['product/sub-categories']  = 'home/products_get_sub_categories';


// category
$route['category-list'] = 'home/category_list';
$route['get-categories'] = 'home/get_categories';
$route['add-category'] = 'home/add_category';
$route['edit-category/(:num)'] = 'home/edit_category/$1';
$route['delete-category/(:num)'] = 'home/delete_category/$1';
// sub-categories
$route['sub-categories'] = 'home/sub_categories';
$route['get-sub-categories'] = 'home/get_sub_categories';
$route['add-sub-category'] = 'home/add_sub_category';
$route['edit-sub-category/(:num)'] = 'home/edit_sub_category/$1';
$route['delete-sub-category/(:num)'] = 'home/delete_sub_category/$1';

// units
$route['units']                     = 'home/units';
$route['home/get_units_data']       = 'home/get_units_data';
$route['home/add_unit']             = 'home/add_unit';
$route['home/edit_unit/(:num)']     = 'home/edit_unit/$1';
$route['home/delete_unit/(:num)']   = 'home/delete_unit/$1';

// brands
$route['brands']               = 'home/brands';
$route['brands/data']          = 'home/get_brands_data';
$route['brands/add']           = 'home/add_brand';
$route['brands/edit/(:num)']   = 'home/edit_brand/$1';
$route['brands/delete/(:num)'] = 'home/delete_brand/$1';
$route['brands/toggle/(:num)'] = 'home/toggle_brand_status/$1';



// Supplier Routes
$route['suppliers'] = 'home/get_all';
$route['suppliers/add'] = 'home/store';
$route['suppliers/edit/(:num)'] = 'home/update/$1';
$route['suppliers/delete/(:num)'] = 'home/delete/$1';
$route['suppliers/get/(:num)'] = 'home/get_supplier/$1';


// purcahse


$route['purchase-list'] = 'home/purchases';
$route['home/get_purchases_data'] = 'home/get_purchases_data';
$route['home/get_purchase/(:num)'] = 'home/get_purchase/$1';
$route['home/add_purchase'] = 'home/add_purchase';
$route['home/edit_purchase/(:num)'] = 'home/edit_purchase/$1';
$route['home/delete_purchase/(:num)'] = 'home/delete_purchase/$1';
$route['home/view_purchase/(:num)'] = 'home/view_purchase/$1';



// Payment_methods
$route['payment-methods'] = 'home/payment_methods';
$route['home/get_payment_methods_data'] = 'home/get_payment_methods_data';
$route['home/add_payment_method'] = 'home/add_payment_method';
$route['home/edit_payment_method/(:num)'] = 'home/edit_payment_method/$1';
$route['home/toggle_payment_status/(:num)'] = 'home/toggle_payment_status/$1';
$route['home/delete_payment_method/(:num)'] = 'home/delete_payment_method/$1';

// Shipping_methods
$route['shipping-methods'] = 'home/shipping_methods';
$route['home/get_shipping_methods_data'] = 'home/get_shipping_methods_data';
$route['home/add_shipping_method'] = 'home/add_shipping_method';
$route['home/edit_shipping_method/(:num)'] = 'home/edit_shipping_method/$1';
$route['home/toggle_shipping_status/(:num)'] = 'home/toggle_shipping_status/$1';
$route['home/delete_shipping_method/(:num)'] = 'home/delete_shipping_method/$1';


// Delivery Boy Routes

$route['delivery-boys'] = 'home/delivarylist';
$route['deliveryboys/add'] = 'home/addDelivaryboy';
$route['deliveryboys/edit/(:num)'] = 'home/editDelivary/$1';
$route['deliveryboys/delete/(:num)'] = 'home/deleteDelivary/$1';
$route['deliveryboys/status/(:num)/(:num)'] = 'home/change_status/$1/$2';



// rough routs ------
$route['location1']  = 'LocationMaster/location1';
$route['countries']  = 'LocationMaster/countries';
$route['states']     = 'LocationMaster/states';
$route['districts']  = 'LocationMaster/districts';
$route['mandals']    = 'LocationMaster/mandals';
$route['villages']   = 'LocationMaster/villages';




