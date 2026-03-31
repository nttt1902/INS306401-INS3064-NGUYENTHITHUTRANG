<?php
require_once '../../classes/Database.php';
$db = Database::getInstance();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // 1. Kiểm tra trống 
    if (empty($name)) $errors['name'] = 'Bắt buộc nhập tên';
    if (empty($email)) $errors['email'] = 'Bắt buộc nhập email';

    // 2. Kiểm tra định dạng email 
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ';
    }

    // 3. Kiểm tra trùng email (Business Logic) 
    if (empty($errors)) {
        $existing = $db->fetch('SELECT id FROM students WHERE email = ?', [$email]);
        if ($existing) {
            $errors['email'] = 'Email này đã tồn tại!'; [cite: 153]
        } else {
            // INSERT dữ liệu nếu mọi thứ ổn [cite: 143, 155]
            $db->query('INSERT INTO students (name, email) VALUES (?, ?)', [$name, $email]);
            header('Location: index.php?success=1'); [cite: 156]
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm sinh viên</title>
</head>
<body>
    <h2>Thêm sinh viên mới</h2>
    <form method="POST">
        <div>
            <label>Tên:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name ?? '') ?>">
            <span style="color:red"><?= $errors['name'] ?? '' ?></span>
        </div>
        <br>
        <div>
            <label>Email:</label>
            <input type="text" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
            <span style="color:red"><?= $errors['email'] ?? '' ?></span>
        </div>
        <br>
        <button type="submit">Lưu sinh viên</button>
        <a href="index.php">Quay lại</a>
    </form>
</body>
</html>