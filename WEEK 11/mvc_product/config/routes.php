<?php
// Register all application routes here

$router->get('/products',        'ProductController', 'index');
$router->get('/products/create', 'ProductController', 'create');
$router->post('/products/create','ProductController', 'store');
