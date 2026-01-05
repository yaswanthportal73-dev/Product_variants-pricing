<?php
$segments = $this->uri->segment_array();
$page = end($segments);
?>

<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>

                <!-- MAIN -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?= in_array($page, ['dashboard', 'sales-dashboard', '']) ? 'active subdrop' : ''; ?>">
                                <i data-feather="grid"></i>
                                <span>Dashboard</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="<?= base_url('dashboard'); ?>" class="<?= ($page == 'dashboard' || $page == '') ? 'active' : ''; ?>">SuperAdmin</a></li>
                                <li><a href="<?= base_url('sales-dashboard'); ?>" class="<?= $page == 'sales-dashboard' ? 'active' : ''; ?>">Admin</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- INVENTORY -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Inventory</h6>
                    <ul>
                        <li class="<?= in_array($page, ['product_list', 'product-details']) ? 'active' : ''; ?>">
                            <a href="<?= base_url('product_list'); ?>">
                                <i data-feather="box"></i>
                                <span>Products</span>
                            </a>
                        </li>

                        <li class="<?= in_array($page, ['add-product', 'edit-product']) ? 'active' : ''; ?>">
                            <a href="<?= base_url('add-product'); ?>"><i data-feather="plus-square"></i><span>Create Product</span></a>
                        </li>
                      

                        <!-- LOCATION -->
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?= in_array($page, ['villages','countries','location1','states', 'districts', 'mandals', 'areas', 'pincodes']) ? 'active subdrop' : ''; ?>">
                                <i data-feather="map"></i>
                                <span>Location</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="<?= base_url('location1'); ?>" class="<?= $page == 'location1' ? 'active' : ''; ?>"> All Locations</a></li>
                                <li><a href="<?= base_url('countries'); ?>" class="<?= $page == 'countries' ? 'active' : ''; ?>">Countries</a></li>
                                <li><a href="<?= base_url('states'); ?>" class="<?= $page == 'states' ? 'active' : ''; ?>">State</a></li>
                                <li><a href="<?= base_url('districts'); ?>" class="<?= $page == 'districts' ? 'active' : ''; ?>">District</a></li>
                                <li><a href="<?= base_url('mandals'); ?>" class="<?= $page == 'mandals' ? 'active' : ''; ?>">Mandal</a></li>
                                <li><a href="<?= base_url('villages'); ?>" class="<?= $page == 'villages' ? 'active' : ''; ?>">Village</a></li>
                           </ul>
                        </li>

                        <!-- PRODUCT MASTER -->
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?= in_array($page, ['category-list', 'sub-categories', 'sub-sub-categories', 'brands', 'units', 'variants']) ? 'active subdrop' : ''; ?>">
                                <i data-feather="box"></i>
                                <span>Catelogs</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="<?= base_url('category-list'); ?>" class="<?= $page == 'category-list' ? 'active' : ''; ?>">Category</a></li>
                                <li><a href="<?= base_url('sub-categories'); ?>" class="<?= $page == 'sub-categories' ? 'active' : ''; ?>">Sub Category</a></li>
                                <li><a href="<?= base_url('brands'); ?>" class="<?= $page == 'brands' ? 'active' : ''; ?>">brands</a></li>
                                <li><a href="<?= base_url('units'); ?>" class="<?= $page == 'units' ? 'active' : ''; ?>">Units</a></li>
                                <li><a href="<?= base_url('variants'); ?>" class="<?= $page == 'variants' ? 'active' : ''; ?>">Variants</a></li>
                            </ul>
                        </li>

                        <li class="<?= $page == 'suppliers' ? 'active' : ''; ?>"><a href="<?= base_url('suppliers'); ?>"><i data-feather="truck"></i><span>Supplier</span></a></li>
                        <li class="<?= $page == 'customers' ? 'active' : ''; ?>"><a href="<?= base_url('customers'); ?>"><i data-feather="user"></i><span>Customer</span></a></li>
                        <li class="<?= $page == 'warehouse' ? 'active' : ''; ?>"><a href="<?= base_url('warehouse'); ?>"><i data-feather="archive"></i><span>Warehouse</span></a></li>
                        <li class="<?= $page == 'employees-grid' ? 'active' : ''; ?>"><a href="<?= base_url('employees-grid'); ?>"><i data-feather="users"></i><span>Employees</span></a></li>
                        <li class="<?= $page == 'delivery-boys' ? 'active' : ''; ?>"><a href="<?= base_url('delivery-boys'); ?>"><i data-feather="send"></i><span>Delivery Boys</span></a></li>

                        <!-- TAX -->
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?= in_array($page, ['payment-methods','shipping-methods', 'tax-slabs']) ? 'active subdrop' : ''; ?>">
                                <i data-feather="percent"></i>
                                <span>Tax & Payment</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="<?= base_url('payment-methods'); ?>" class="<?= $page == 'payment-methods' ? 'active' : ''; ?>">Payment Methods</a></li>
                                <li><a href="<?= base_url('shipping-methods'); ?>" class="<?= $page == 'shipping-methods' ? 'active' : ''; ?>">Shipping Methods</a></li>
                                <li><a href="<?= base_url('tax-slabs'); ?>" class="<?= $page == 'tax-slabs' ? 'active' : ''; ?>">Tax Slabs</a></li>
                            </ul>
                        </li>

                        <li class="<?= $page == 'roles-permissions' ? 'active' : ''; ?>"><a href="<?= base_url('roles-permissions'); ?>"><i data-feather="shield"></i><span>Users & Roles</span></a></li>
                        <li class="<?= $page == 'discounts' ? 'active' : ''; ?>"><a href="<?= base_url('discounts'); ?>"><i data-feather="gift"></i><span>Discounts / Offers</span></a></li>
     
                    </ul>
                </li>

                <!-- TRANSACTIONS -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Transactions</h6>
                    <ul>
                          <li class="<?php echo ($page =='purchase-list') ? 'active' : '' ;?>"><a
                                href="<?php echo base_url(); ?>purchase-list"><i
                                    data-feather="shopping-bag"></i><span>Purchases</span></a></li>
                        <!-- <li class="<?= $page == 'inventory' ? 'active' : ''; ?>"><a href="<?= base_url('inventory'); ?>"><i data-feather="layers"></i><span>Inventory</span></a></li> -->
                        <li class="<?= $page == 'invoice-management' ? 'active' : ''; ?>"><a href="<?= base_url('invoice-management'); ?>"><i data-feather="file-text"></i><span>Invoice Management</span></a></li>
                    </ul>
                </li>

                <!-- REPORTS -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Reports</h6>
                    <ul>
                        <?php
                        $reports = [
                            'sales-report' => 'Sales Report',
                            'stock-report' => 'Stock Report',
                            'delivery-report' => 'Delivery Reports',
                            'low-stock-alerts' => 'Low Stock Alerts',
                            'expiry-report' => 'Expiry Report',
                            'profit-and-loss' => 'Profit & Loss',
                            'tax-report' => 'Tax / GST Report',
                            'customer-report' => 'Customer Report',
                            'supplier-report' => 'Supplier Report',
                            'employee-report' => 'Employee / Staff Report'
                        ];
                        foreach ($reports as $url => $label): ?>
                            <li>
                                <a href="<?= base_url($url); ?>" class="<?= $page == $url ? 'active' : ''; ?>">
                                    <span><?= $label; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <!-- SETTINGS -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Settings</h6>
                    <ul>
                        <li><a href="<?= base_url('system-settings'); ?>" class="<?= $page == 'system-settings' ? 'active' : ''; ?>"><span>System Settings</span></a></li>
                        <li><a href="<?= base_url('roles-permissions'); ?>" class="<?= $page == 'roles-permissions' ? 'active' : ''; ?>"><span>Role & Permission Management</span></a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>