<?php
return [
    'main' => [
        'title' => 'Main Navigation',
        'items' => [
            [
                'label' => 'Dashboard',
                'icon' => 'fe fe-home',
                'route' => 'admin.dashboard',
                'active' => 'admin',
            ],
        ],
    ],
    'sales' => [
        'title' => 'Sales Management',
        'items' => [
            [
                'label' => 'Sales',
                'icon' => 'fe fe-shopping-bag',
                'active' => 'admin/sales*',
                'sub_items' => [
                    [
                        'label' => 'Customers',
                        'route' => 'admin.customers.index',
                        'active' => 'admin/customers*',
                    ],
                    [
                        'label' => 'Quotations',
                        'route' => 'admin.quotations.index',
                        'active' => 'admin/quotations*',
                    ],
                    [
                        'label' => 'Invoices',
                        'route' => 'admin.invoices.index',
                        'active' => 'admin/invoices*',
                    ],
                ],
            ],
        ],
    ],
    'inventory' => [
        'title' => 'Inventory Management',
        'items' => [
            [
                'label' => 'Products',
                'icon' => 'fe fe-tag',
                'route' => 'admin.products.index',
                'active' => 'admin/products*',
            ],
        ],
    ],
    'purchase' => [
        'title' => 'Purchase Management',
        'items' => [
            [
                'label' => 'Purchase',
                'icon' => 'fe fe-shopping-cart',
                'active' => 'admin/purchases*',
                'sub_items' => [
                    [
                        'label' => 'All Purchases',
                        'route' => 'admin.purchases.index',
                        'active' => 'admin/purchases*',
                    ],
                    [
                        'label' => 'Suppliers',
                        'route' => 'admin.suppliers.index',
                        'active' => 'admin/suppliers*',
                    ],
                ],
            ],
        ],
    ],
    'finance' => [
        'title' => 'Financial Management',
        'items' => [
            [
                'label' => 'Finance',
                'icon' => 'fe fe-dollar-sign',
                'active' => 'admin/finance*',
                'sub_items' => [
                    [
                        'label' => 'Expenditures',
                        'route' => 'admin.expenditures.index',
                        'active' => 'admin/expenditures*',
                    ],
                    [
                        'label' => 'VAT Returns',
                        'route' => 'admin.vat-returns.index',
                        'active' => 'admin/vat-returns*',
                    ],
                ],
            ],
        ],
    ],
    'tools' => [
        'title' => 'Tools & Management',
        'items' => [
            [
                'label' => 'Users',
                'icon' => 'fe fe-user',
                'active' => 'admin/users*',
                'sub_items' => [
                    [
                        'label' => 'Manage Users',
                        'route' => 'admin.users.index',
                        'active' => 'admin/users*',
                    ],
                ],
            ],
        ],
    ],
];
