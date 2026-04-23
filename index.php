<?php
// index.php

require_once 'router.php';
require_once 'helpers.php';

$router = new Router();
//.htaccess
// Простые маршруты
$router->get('', function() {
//    echo "Главная страница";
//    render('home');
    view('home', [
//        'userId' => $id,
//        'name' => 'Иван'
    ]);
});

$router->get('multiplication/training', function() {
    view('multiplication_training', []);
});
$router->get('multiplication/table', function() {
    view('multiplication_table', []);
});

// Маршруты с параметрами
$router->get('user/{id}', function($id) {
    echo "Профиль пользователя #$id";
});

$router->get('post/{id}/comment/{commentId}', function($postId, $commentId) {
    echo "Пост #$postId, Комментарий #$commentId";
});

// POST-запросы
$router->post('api/users', function() {
    $data = json_decode(file_get_contents('php://input'), true);
    echo "Создан пользователь: " . json_encode($data);
});

// Использование контроллеров
$router->get('products', 'ProductController@index');
$router->get('product/{id}', 'ProductController@show');

// Запуск
$router->dispatch();