<?php
// AdvancedRouter.php

class AdvancedRouter {
    private $routes = [];
    private $middlewares = [];
    private $prefix = '';

    public function group($prefix, $callback) {
        $previousPrefix = $this->prefix;
        $this->prefix .= trim($prefix, '/') . '/';

        call_user_func($callback, $this);

        $this->prefix = $previousPrefix;
    }

    public function middleware($middleware, $callback) {
        $previousMiddlewares = $this->middlewares;
        $this->middlewares[] = $middleware;

        call_user_func($callback, $this);

        $this->middlewares = $previousMiddlewares;
    }

    public function add($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => trim($this->prefix . trim($path, '/'), '/'),
            'callback' => $callback,
            'middlewares' => $this->middlewares
        ];
        return $this;
    }

    public function get($path, $callback) {
        return $this->add('GET', $path, $callback);
    }

    public function post($path, $callback) {
        return $this->add('POST', $path, $callback);
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            $params = $this->extractParams($route['path'], $uri);

            if ($params !== null) {
                // Выполняем middleware
                foreach ($route['middlewares'] as $middleware) {
                    $result = $this->runMiddleware($middleware);
                    if ($result === false) return;
                }

                return $this->executeCallback($route['callback'], $params);
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }

    private function extractParams($route, $uri) {
        $routeParts = explode('/', $route);
        $uriParts = explode('/', $uri);

        if (count($routeParts) !== count($uriParts)) {
            return null;
        }

        $params = [];
        foreach ($routeParts as $i => $part) {
            if (preg_match('/^\{(\w+)\}$/', $part, $matches)) {
                $params[$matches[1]] = $uriParts[$i];
            } elseif ($part !== $uriParts[$i]) {
                return null;
            }
        }

        return $params;
    }

    private function runMiddleware($middleware) {
        // Пример: проверка авторизации
        if ($middleware === 'auth') {
            $headers = getallheaders();
            if (empty($headers['Authorization'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                return false;
            }
        }
        return true;
    }

    private function executeCallback($callback, $params) {
        if (is_callable($callback)) {
            return call_user_func_array($callback, array_values($params));
        }

        if (is_string($callback) && strpos($callback, '@') !== false) {
            list($controller, $method) = explode('@', $callback);
            $instance = new $controller();
            return call_user_func_array([$instance, $method], array_values($params));
        }
    }
}