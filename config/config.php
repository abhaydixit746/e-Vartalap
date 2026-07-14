<?php
// e-Vartalap configuration.
// Render/Aiven values are supplied through environment variables.

$isProduction = (getenv('APP_ENV') ?: 'local') === 'production';

return [
    'db' => [
        'host'        => getenv('DB_HOST') ?: 'localhost',
        'port'        => getenv('DB_PORT') ?: '3306',
        'dbname'      => getenv('DB_NAME') ?: 'evartalap',
        'username'    => getenv('DB_USER') ?: 'root',
        'password'    => getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '',
        'charset'     => 'utf8mb4',
        'ssl_mode'    => getenv('DB_SSL_MODE') ?: '',
        'ssl_ca'      => getenv('DB_SSL_CA') ?: '',
        'ssl_ca_cert' => getenv('DB_SSL_CA_CERT') ?: '',
    ],

    'app' => [
        'name'        => 'e-Vartalap',
        'url'         => getenv('APP_URL') ?: 'http://localhost:8080',
        'debug'       => !$isProduction,
        'page_size'   => 10,
        'upload_dir'  => __DIR__ . '/../public/uploads/photos',
        'upload_url'  => '/uploads/photos',
        'max_upload'  => 5 * 1024 * 1024,
        'allowed_img' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
    ],

    'session' => [
        'name'     => 'EV_SESSION',
        'lifetime' => 1800,
        'secure'   => $isProduction,
        'httponly' => true,
    ],
];
