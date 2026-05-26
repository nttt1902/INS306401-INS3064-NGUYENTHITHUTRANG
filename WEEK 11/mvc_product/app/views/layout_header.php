<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC Products</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f4f6f8; color: #333; }
        nav { background: #1a237e; padding: 14px 24px; }
        nav a { color: #fff; text-decoration: none; margin-right: 20px; font-weight: bold; }
        nav a:hover { text-decoration: underline; }
        .container { max-width: 900px; margin: 32px auto; padding: 0 20px; }
        h1 { margin-bottom: 20px; color: #1a237e; }
        .btn { display: inline-block; padding: 9px 18px; border-radius: 4px;
               text-decoration: none; font-size: 14px; cursor: pointer; border: none; }
        .btn-primary { background: #1a237e; color: #fff; }
        .btn-primary:hover { background: #283593; }
        .alert-error { background: #fce4ec; color: #b71c1c; padding: 10px 14px;
                       border-radius: 4px; margin-bottom: 16px; }
    </style>
</head>
<body>
<nav>
    <a href="/products">🛒 Products</a>
    <a href="/products/create">＋ Add Product</a>
</nav>
<div class="container">
