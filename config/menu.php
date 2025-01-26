<?php
return [
    'main' => [
        'title' => 'Main',
        'items' => [
            [
                'label' => 'Dashboard',
                'icon' => 'fe fe-home',
                'route' => 'admin.dashboard',
                'active' => 'admin',
            ],
        ],
    ],
    'expenditure' => [
        'items' => [
            [
                'label' => 'Expenditures',
                'icon' => 'fe fe-dollar-sign',
                'route' => 'admin.expenditures.index',
                'active' => 'admin/expenditures*',
            ],
        ],
    ],
    'customer' => [
        'items' => [
            [
                'label' => 'Customers',
                'icon' => 'fe fe-users',
                'route' => 'admin.customers.index',
                'active' => 'admin/customers*',
            ],
        ],
    ],
    'product' => [
        'items' => [
            [
                'label' => 'Products',
                'icon' => 'fe fe-tag',
                'route' => 'admin.products.index',
                'active' => 'admin/products*',
            ],
        ],
    ],
    'quotation' => [
        'items' => [
            [
                'label' => 'Quotations',
                'icon' => 'fe fe-message-circle',
                'route' => 'admin.quotations.index',
                'active' => 'admin/quotations*',
            ],
        ],
    ],
    'invoice' => [
        'items' => [
            [
                'label' => 'Invoices',
                'icon' => 'fe fe-file-text',
                'route' => 'admin.invoices.index',
                'active' => 'admin/invoices*',
            ],
        ],
    ],
    'purchase' => [
        'items' => [
            [
                'label' => 'Purchases',
                'icon' => 'fe fe-shopping-cart',
                'route' => 'admin.purchases.index',
                'active' => 'admin/purchases*',
            ],
            [
                'label' => 'Suppliers',
                'icon' => 'fe fe-truck',
                'route' => 'admin.suppliers.index',
                'active' => 'admin/suppliers*',
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
