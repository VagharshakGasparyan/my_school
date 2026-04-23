<?php
// helpers.php

function view($template, $data = []) {
    $file = "views/{$template}.php";

    if (!file_exists($file)) {
        http_response_code(500);
        echo "Шаблон {$template} не найден";
        return;
    }

    // Извлекаем переменные для использования в шаблоне
    extract($data);

    // Буферизация вывода
    ob_start();
    include $file;
    echo ob_get_clean();
}

// Для чистого HTML без PHP-кода
function render($template) {
    $file = "views/{$template}.php";

    if (!file_exists($file)) {
        http_response_code(500);
        echo "Шаблон {$template} не найден";
        return;
    }

    header('Content-Type: text/html; charset=utf-8');
    readfile($file);
}
?>