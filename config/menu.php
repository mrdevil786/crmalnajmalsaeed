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
    'tools' => [
        'title' => 'Tools & Management',
        'items' => [
            [
                'label' => 'Users',
                'icon' => 'fe fe-user',
                // 'route' => 'javascript:void(0)',
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
