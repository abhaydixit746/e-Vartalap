<?php
// ============================================================
// e-Vartalap PHP — Application Configuration
// ============================================================

return [
    'db' => [
        'host'     => getenv('DB_HOST') ?: 'localhost',
        'port'     => getenv('DB_PORT') ?: '3306',
        'dbname'   => getenv('DB_NAME') ?: 'evartalap',
        'username' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '',
        'charset'  => 'utf8mb4',
    ],

    'app' => [
        'name'        => 'e-Vartalap',
        'url'         => getenv('APP_URL') ?: 'http://localhost:8080',   // no trailing slash
        'debug'       => true,
        'page_size'   => 10,
        'upload_dir'  => __DIR__ . '/../public/uploads/photos',
        'upload_url'  => '/uploads/photos',
        'max_upload'  => 5 * 1024 * 1024,     // 5 MB
        'allowed_img' => ['image/jpeg','image/png','image/gif','image/webp'],
    ],

    'session' => [
        'name'     => 'EV_SESSION',
        'lifetime' => 1800,            // 30 minutes
        'secure'   => false,           // set true on HTTPS
        'httponly' => true,
    ],
];
