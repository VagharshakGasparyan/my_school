<?php
// index.php

require_once 'AdvancedRouter.php';

$router = new AdvancedRouter();

// Группа маршрутов API
$router->group('api', function($router) {

    // Публичные маршруты
    $router->get('products', function() {
        echo json_encode(['products' => []]);
    });

    // Защищённые маршруты
    $router->middleware('auth', function($router) {
        $router->get('users', 'UserController@index');
        $router->post('users', 'UserController@store');
        $router->get('users/{id}', 'UserController@show');
    });
});

// Вложенные группы
$router->group('admin', function($router) {
    $router->middleware('auth', function($router) {
        $router->group('dashboard', function($router) {
            $router->get('stats', function() {
                echo "Статистика";
            });
        });
    });
});

$router->dispatch();