<?php
// ── Autoload core & app classes ───────────────────────────────────────────────
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../app/models/Product.php';
require_once __DIR__ . '/../app/controllers/ProductController.php';

// ── Bootstrap router ─────────────────────────────────────────────────────────
$router = new Router();

// Register routes
require_once __DIR__ . '/../config/routes.php';

// ── Dispatch ──────────────────────────────────────────────────────────────────
$router->dispatch();
