<?php
namespace App\Middleware;

use App\Core\Request;

class AuthMiddleware
{
    public static function requireLogin(): void
    {
        if (!isset($_SESSION['user'])) {
            Request::flash('error', 'Please sign in to continue.');
            Request::redirect('/auth/login');
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (($_SESSION['user']['role'] ?? '') !== 'ADMIN') {
            http_response_code(403);
            include VIEWS . '/error/403.php';
            exit;
        }
    }

    public static function requireGuest(): void
    {
        if (isset($_SESSION['user'])) {
            Request::redirect('/');
        }
    }
}
