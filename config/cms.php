<?php

return [
    'seed_users' => [
        'admin' => [
            'name' => env('CMS_ADMIN_NAME', 'CMS Administrator'),
            'email' => env('CMS_ADMIN_EMAIL', 'admin@garuda.test'),
            'password' => env('CMS_ADMIN_PASSWORD', 'Garuda123!'),
        ],
        'editor' => [
            'name' => env('CMS_EDITOR_NAME', 'Content Editor'),
            'email' => env('CMS_EDITOR_EMAIL', 'editor@garuda.test'),
            'password' => env('CMS_EDITOR_PASSWORD', 'Garuda123!'),
        ],
        'viewer' => [
            'name' => env('CMS_VIEWER_NAME', 'Content Viewer'),
            'email' => env('CMS_VIEWER_EMAIL', 'viewer@garuda.test'),
            'password' => env('CMS_VIEWER_PASSWORD', 'Garuda123!'),
        ],
    ],
];
