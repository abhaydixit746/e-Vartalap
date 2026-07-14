<?php
namespace App\Core;

/**
 * View — renders PHP view templates with data extraction.
 * Outputs are HTML-escaped by default via the h() helper.
 */
class View
{
    public static function render(string $template, array $data = []): void
    {
        // Make $data keys available as variables inside the view
        extract($data, EXTR_SKIP);

        $file = VIEWS . '/' . ltrim($template, '/') . '.php';
        if (!file_exists($file)) {
            throw new \RuntimeException("View not found: {$file}");
        }
        include $file;
    }

    /** Render a partial snippet */
    public static function partial(string $template, array $data = []): void
    {
        self::render('partials/' . $template, $data);
    }
}

// ---- Global helpers (available in every view) ----

/** HTML-escape a value */
// function h(mixed $val): string
// {
//     return htmlspecialchars((string)($val ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
// }

// /** Current logged-in user from session */
// function auth(): ?array
// {
//     return $_SESSION['user'] ?? null;
// }

// function isLoggedIn(): bool
// {
//     return isset($_SESSION['user']);
// }

// function isAdmin(): bool
// {
//     return ($_SESSION['user']['role'] ?? '') === 'ADMIN';
// }

// /** Build URL (prepend base if needed) */
// function url(string $path = ''): string
// {
//     return '/' . ltrim($path, '/');
// }

// /** CSRF token HTML field */
// function csrfField(): string
// {
//     $token = \App\Core\Request::generateCsrfToken();
//     return '<input type="hidden" name="_csrf" value="' . h($token) . '">';
// }

// /** Format a DB timestamp nicely */
// function fmtDate(string $dt, string $format = 'd M Y, H:i'): string
// {
//     return date($format, strtotime($dt));
// }

// /** Truncate text */
// function truncate(string $text, int $len = 200): string
// {
//     return mb_strlen($text) > $len ? mb_substr($text, 0, $len) . '…' : $text;
// }

// /** Flash message getters (used in partials/header) */
// function flashGet(string $key): ?string
// {
//     return \App\Core\Request::getFlash($key);
// }
