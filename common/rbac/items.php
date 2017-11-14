<?php
return [
    'accessBackend' => [
        'type' => 2,
        'description' => 'Can access backend',
    ],
    'user' => [
        'type' => 1,
        'description' => 'User',
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Admin',
        'children' => [
            'user',
            'accessBackend',
        ],
    ],
];
