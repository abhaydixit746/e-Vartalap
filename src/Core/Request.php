<?php
namespace App\Core;

/**
 * Request — thin wrapper around PHP superglobals.
 * All values are sanitized. Provides CSRF token validation.
 */
class Request
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return isset($_GET[$key]) ? self::clean($_GET[$key]) : $default;
    }

    public static function post(string $key, mixed $default = null): mixed
    {
        return isset($_POST[$key]) ? self::clean($_POST[$key]) : $default;
    }

    public static function postRaw(string $key, mixed $default = null): mixed
    {
        // For text areas — we clean on output, not here
        return $_POST[$key] ?? $default;
    }

    public static function file(string $key): array|null
    {
        return $_FILES[$key] ?? null;
    }

    public static function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public static function uri(): string
    {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }

    public static function isPost(): bool
    {
        return self::method() === 'POST';
    }

    public static function isAjax(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }

    // ---- CSRF ----

    public static function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf(): void
    {
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('CSRF token mismatch. Please go back and try again.');
        }
    }

    // ---- Redirect ----

    public static function redirect(string $url, bool $permanent = false): never
    {
        header('Location: ' . $url, true, $permanent ? 301 : 302);
        exit;
    }

    public static function redirectBack(string $fallback = '/'): never
    {
        self::redirect($_SERVER['HTTP_REFERER'] ?? $fallback);
    }

    // ---- Flash messages ----

    public static function flash(string $key, string $value): void
    {
        $_SESSION['flash'][$key] = $value;
    }

    public static function getFlash(string $key): ?string
    {
        $value = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $value;
    }

    // ---- JSON response ----

    public static function json(mixed $data, int $code = 200): never
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private static function clean(mixed $value): mixed
    {
        if (is_array($value)) return array_map([self::class, 'clean'], $value);
        return trim((string) $value);
    }
}
