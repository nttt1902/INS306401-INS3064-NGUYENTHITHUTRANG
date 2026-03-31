<?php
// 1. Nhúng file lớp Database để sử dụng [cite: 118]
require_once '../../classes/Database.php';

// 2. Lấy đối tượng kết nối duy nhất (Singleton) [cite: 125]
$db = Database::getInstance();

// 3. Truy vấn lấy toàn bộ sinh viên [cite: 119, 126]
$students = $db->fetchAll('SELECT * FROM students');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Danh sách sinh viên</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Quản lý sinh viên</h2>
    <a href="create.php">Thêm sinh viên mới</a>
    <br><br>

    <table>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($students as $s): ?>
        <tr>
            <td><?= $s['id'] ?></td>
            <td><?= htmlspecialchars($s['name']) ?></td>
            <td><?= htmlspecialchars($s['email']) ?></td>
            <td>
                <a href="edit.php?id=<?= $s['id'] ?>">Sửa</a> | 
                <a href="delete.php?id=<?= $s['id'] ?>" onclick="return confirm('Chắc chắn xóa?')">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
