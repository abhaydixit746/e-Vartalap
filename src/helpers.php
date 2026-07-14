<?php

use App\Core\Request;

function h($val): string
{
    return htmlspecialchars((string)($val ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function auth(): ?array
{
    return $_SESSION['user'] ?? null;
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user']);
}

function isAdmin(): bool
{
    return ($_SESSION['user']['role'] ?? '') === 'ADMIN';
}

function url(string $path = ''): string
{
    $base = rtrim(CFG['app']['base_url'], '/');

    return $path === ''
        ? $base
        : $base . '/' . ltrim($path, '/');
}

function csrfField(): string
{
    $token = Request::generateCsrfToken();

    return '<input type="hidden" name="_csrf" value="' . h($token) . '">';
}

function fmtDate(string $dt, string $format = 'd M Y, H:i'): string
{
    return date($format, strtotime($dt));
}

function truncate(string $text, int $len = 200): string
{
    return mb_strlen($text) > $len
        ? mb_substr($text, 0, $len) . '…'
        : $text;
}

function flashGet(string $key): ?string
{
    return Request::getFlash($key);
}