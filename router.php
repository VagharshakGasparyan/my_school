<?php
// router.php

class Router {
    private $routes = [];

    // Добавление маршрута
    public function add($method, $path, $callback) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'callback' => $callback
        ];
        return $this;
    }

    // Удобные методы для HTTP-глаголов
    public function get($path, $callback) {
        return $this->add('GET', $path, $callback);
    }

    public function post($path, $callback) {
        return $this->add('POST', $path, $callback);
    }

    public function put($path, $callback) {
        return $this->add('PUT', $path, $callback);
    }

    public function delete($path, $callback) {
        return $this->add('DELETE', $path, $callback);
    }

    // Обработка текущего запроса
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Убираем базовый путь если нужно
        //$uri = str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $uri);
        $uri = trim($uri, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            // Проверяем точное совпадение
            if ($route['path'] === $uri) {
                return $this->executeCallback($route['callback'], []);
            }

            // Проверяем параметризованные маршруты (/user/{id})
            $params = $this->matchRoute($route['path'], $uri);
            if ($params !== null) {
                return $this->executeCallback($route['callback'], $params);
            }
        }

        // 404 - маршрут не найден
        http_response_code(404);
        echo "404 Not Found (Էջը գոյություն չունի)";
    }

    // Проверка маршрута с параметрами
    private function matchRoute($routePath, $uri) {
        // Конвертируем {param} в регулярное выражение
        $pattern = preg_replace('/\{(\w+)}/', '(\w+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Убираем полное совпадение
            return $matches;
        }

        return null;
    }

    // Выполнение callback
    private function executeCallback($callback, $params) {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        if (is_string($callback) && strpos($callback, '@') !== false) {
            list($controller, $method) = explode('@', $callback);
            $controller = new $controller();
            return call_user_func_array([$controller, $method], $params);
        }
    }
}