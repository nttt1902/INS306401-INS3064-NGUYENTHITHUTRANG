<?php

class ProductController extends Controller {

    // GET /products – show product list
    public function index(): void {
        $products = Product::all();
        $this->view('products/index', ['products' => $products]);
    }

    // GET /products/create – show create form
    public function create(): void {
        $this->view('products/create');
    }

    // POST /products/create – handle form submission
    public function store(): void {
        $name  = htmlspecialchars(trim($_POST['name']  ?? ''));
        $price = (float) ($_POST['price'] ?? 0);
        $stock = (int)   ($_POST['stock'] ?? 0);

        if ($name === '') {
            $error = 'Product name is required.';
            $this->view('products/create', ['error' => $error]);
            return;
        }

        Product::create(['name' => $name, 'price' => $price, 'stock' => $stock]);

        // Redirect back to list
        header('Location: /products');
        exit;
    }
}
