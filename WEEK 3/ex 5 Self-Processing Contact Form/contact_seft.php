<?php
$show_form = true;
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 2. Lấy dữ liệu
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($fullname) || empty($email) || empty($phone) || empty($message)) {
        $error_message = "Missing Data: Vui lòng điền đầy đủ các trường.";
    } else {
        $show_form = false;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Contact Self-Processing</title>
    <style>
        .error { color: red; font-weight: bold; }
        .success { color: green; border: 1px solid green; padding: 20px; }
    </style>
</head>
<body>

    <?php if ($show_form): ?>
        <h2>Liên hệ</h2>
        
        <?php if ($error_message): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="contact_self.php" method="post">
            <label>Full Name:</label><br>
            <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname ?? ''); ?>"><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>"><br><br>

            <label>Phone Number:</label><br>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>"><br><br>

            <label>Message:</label><br>
            <textarea name="message"><?php echo htmlspecialchars($message ?? ''); ?></textarea><br><br>

            <button type="submit">Submit</button>
        </form>

    <?php else: ?>
        <div class="success">
            <h2>Thank You!</h2>
            <p>Chúng tôi đã nhận được thông tin của bạn:</p>
            <ul>
                <li><strong>Họ tên:</strong> <?php echo htmlspecialchars($fullname); ?></li>
                <li><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></li>
            </ul>
            <p><a href="contact_self.php">Gửi lại phản hồi khác</a></p>
        </div>
    <?php endif; ?>

</body>
</html>