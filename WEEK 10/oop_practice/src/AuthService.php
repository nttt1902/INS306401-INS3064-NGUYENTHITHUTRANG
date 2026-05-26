<?php
// src/AuthService.php

class AuthService {
    // Giả sử logic đăng nhập
    public function login(string $email, string $password): bool {
        // Trong thực tế sẽ kiểm tra database ở đây
        if ($email === "admin@example.com" && $password === "123456") {
            return true;
        }
        return false;
    }
}