<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    if (empty($fullname) || empty($email) || empty($phone) || empty($message)) {
        echo "<h3>Lỗi: Missing Data (Thiếu dữ liệu)</h3>";
        echo "<p>Vui lòng quay lại và điền đầy đủ thông tin.</p>";
    } else {
        echo "<h3>Dữ liệu đã nhận:</h3>";
        echo "<ul>";
        echo "<li><strong>Họ và tên:</strong> " . htmlspecialchars($fullname) . "</li>";
        echo "<li><strong>Email:</strong> " . htmlspecialchars($email) . "</li>";
        echo "<li><strong>Số điện thoại:</strong> " . htmlspecialchars($phone) . "</li>";
        echo "<li><strong>Tin nhắn:</strong> " . htmlspecialchars($message) . "</li>";
        echo "</ul>";
    }
} else {
    echo "Vui lòng truy cập biểu mẫu từ trang contact.html.";
}
?>
