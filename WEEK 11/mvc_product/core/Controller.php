<?php

class Controller {
    protected function view(string $view, array $data = []): void {
        extract($data);
        $viewPath = __DIR__ . '/../app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "<p>View not found: {$view}</p>";
        }
    }
}
