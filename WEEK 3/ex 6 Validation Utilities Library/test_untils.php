<?php
require_once 'utils.php';

function runTest($testName, $condition) {
    echo "Test [$testName]: " . ($condition ? "<span style='color:green;'>PASS</span>" : "<span style='color:red;'>FAIL</span>") . "<br>";
}

echo "<h2>Kết quả kiểm tra hệ thống Utils</h2>";

// 1. Test Sanitize
$dirtyInput = "<script>alert('hack')</script> ";
$cleanInput = sanitize($dirtyInput);
runTest("Sanitize (XSS protection)", $cleanInput !== $dirtyInput && strpos($cleanInput, "&lt;") !== false);

// 2. Test Email
runTest("Email hợp lệ (test@gmail.com)", validateEmail("test@gmail.com") === true);
runTest("Email sai định dạng (abc.com)", validateEmail("abc.com") === false);

// 3. Test Length
runTest("Độ dài hợp lệ (abc, 1-5)", validateLength("abc", 1, 5) === true);
runTest("Độ dài quá ngắn (a, 3-5)", validateLength("a", 3, 5) === false);

// 4. Test Password
runTest("Password mạnh (Admin@123)", validatePassword("Admin@123") === true);
runTest("Password yếu - thiếu ký tự đặc biệt (Admin12345)", validatePassword("Admin12345") === false);
runTest("Password quá ngắn (A@1)", validatePassword("A@1") === false);
?>