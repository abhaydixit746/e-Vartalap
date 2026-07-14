<?php
namespace App\Core;

/**
 * Router — lightweight front-controller pattern.
 * Maps GET/POST URI patterns to Controller@method.
 */
class Router
{
    private array $routes = [];

    public function get(string $pattern, string $handler): void
    {
        $this->routes[] = ['GET', $pattern, $handler];
    }

    public function post(string $pattern, string $handler): void
    {
        $this->routes[] = ['POST', $pattern, $handler];
    }

    public function dispatch(string $method, string $uri): void
    {
        // Strip query string
        $uri = strtok($uri, '?');
        // Normalize trailing slash
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as [$routeMethod, $pattern, $handler]) {
            if ($routeMethod !== $method) continue;

            // Convert :param segments to named capture groups
            $regex = preg_replace('#:([a-z_]+)#', '(?P<$1>[^/]+)', $pattern);
            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $uri, $matches)) {
                // Extract named params only
                $params = array_filter($matches, fn($k) => !is_int($k), ARRAY_FILTER_USE_KEY);

                [$controllerClass, $action] = explode('@', $handler);
                $fullClass = 'App\\Controller\\' . $controllerClass;

                if (!class_exists($fullClass)) {
                    $this->abort(500, "Controller {$fullClass} not found.");
                    return;
                }

                $controller = new $fullClass();
                if (!method_exists($controller, $action)) {
                    $this->abort(500, "Action {$action} not found.");
                    return;
                }

                $controller->$action($params);
                return;
            }
        }

        $this->abort(404, "Page not found: {$uri}");
    }

    private function abort(int $code, string $msg): void
    {
        http_response_code($code);
        $title   = match($code) { 404 => 'Page Not Found', 403 => 'Access Denied', default => 'Server Error' };
        $message = $msg;
        include VIEWS . '/error/' . $code . '.php';
    }
}
