<?php
// controllers/ProductController.php

class ProductController {

    public function index() {
        $products = [
            ['id' => 1, 'name' => 'Ноутбук', 'price' => 50000],
            ['id' => 2, 'name' => 'Телефон', 'price' => 30000],
        ];

        header('Content-Type: application/json');
        echo json_encode($products);
    }

    public function show($id) {
        $product = ['id' => $id, 'name' => 'Товар #' . $id, 'price' => 10000];

        header('Content-Type: application/json');
        echo json_encode($product);
    }
}