<?php

class Product {
    // In a real app this would query a database.
    // For the exercise we use a static in-memory store.
    private static array $products = [
        ['id' => 1, 'name' => 'Laptop',     'price' => 1200.00, 'stock' => 10],
        ['id' => 2, 'name' => 'Smartphone', 'price' =>  699.00, 'stock' => 25],
        ['id' => 3, 'name' => 'Headphones', 'price' =>   89.99, 'stock' => 50],
    ];

    public static function all(): array {
        return self::$products;
    }

    public static function create(array $data): void {
        $data['id'] = count(self::$products) + 1;
        self::$products[] = $data;
    }
}
