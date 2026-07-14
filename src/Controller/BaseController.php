<?php
namespace App\Controller;

use App\Core\View;
use App\Core\Request;

abstract class BaseController
{
    protected function render(string $template, array $data = []): void
    {
        View::render($template, $data);
    }

    protected function redirect(string $url): never
    {
        Request::redirect($url);
    }

    protected function flash(string $key, string $msg): void
    {
        Request::flash($key, $msg);
    }

    protected function json(mixed $data, int $code = 200): never
    {
        Request::json($data, $code);
    }

    protected function currentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function isAdmin(): bool
    {
        return ($this->currentUser()['role'] ?? '') === 'ADMIN';
    }

    protected function page(): int
    {
        return max(1, (int) Request::get('page', 1));
    }
}
