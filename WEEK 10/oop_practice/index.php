<?php
// index.php

// Bước 1: Nhúng các file định nghĩa Class vào
require_once 'src/Person.php';
require_once 'src/AuthService.php';

// Bước 2: Khởi tạo đối tượng
$user = new Person();
$auth = new AuthService();

try {
    // Bước 3: Sử dụng các phương thức
    $user->setAge(20);
    echo "Người dùng hiện tại " . $user->getAge() . " tuổi.\n";
    echo "Trạng thái: " . ($user->isAdult() ? "Đã trưởng thành" : "Trẻ em") . "\n";

    // Thử đăng nhập
    if ($auth->login("admin@example.com", "123456")) {
        echo "Đăng nhập thành công! ✅";
    }
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
