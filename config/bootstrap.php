<?php
// ============================================================
// e-Vartalap PHP — Bootstrap
// Loaded once at the top of public/index.php
// ============================================================

define('ROOT', dirname(__DIR__));
define('SRC',  ROOT . '/src');
define('VIEWS', ROOT . '/views');

// Load config
$config = require ROOT . '/config/config.php';
define('CFG', $config);
require_once ROOT . '/src/helpers.php';

// PSR-4 style autoloader  (App\ → src/)
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $base   = SRC . '/';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) return;
    $rel  = substr($class, strlen($prefix));
    $file = $base . str_replace('\\', '/', $rel) . '.php';
    if (file_exists($file)) require $file;
});

// Session
$sc = $config['session'];
session_name($sc['name']);
session_set_cookie_params([
    'lifetime' => $sc['lifetime'],
    'path'     => '/',
    'secure'   => $sc['secure'],
    'httponly' => $sc['httponly'],
    'samesite' => 'Lax',
]);
if (session_status() === PHP_SESSION_NONE) session_start();

// Regenerate session id on login (done in AuthController)

// Error handling
if ($config['app']['debug']) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Timezone
date_default_timezone_set('Asia/Kolkata');
