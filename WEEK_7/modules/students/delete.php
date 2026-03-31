<?php
require_once '../../classes/Database.php';
$db = Database::getInstance();

// Lấy ID từ URL và ép kiểu số nguyên để bảo mật
$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    // Thực hiện xóa
    $db->query('DELETE FROM students WHERE id = ?', [$id]);
}

// Quay về trang danh sách kèm thông báo
header('Location: index.php?deleted=1');
exit;