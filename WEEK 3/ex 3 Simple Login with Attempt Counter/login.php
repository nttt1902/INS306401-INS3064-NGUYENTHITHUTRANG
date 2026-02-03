<?php
session_start();

if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

$message = "";
$hardcoded_user = "admin";
$hardcoded_pass = "123456";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $hardcoded_user && $password === $hardcoded_pass) {
        $message = "<b style='color: green;'>Login Successful!</b>";
        $_SESSION['attempts'] = 0; 
    } else {
        $_SESSION['attempts']++; 
        $message = "<b style='color: red;'>Invalid Credentials.</b>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Simple Login</title>
</head>
<body>
    <h2>Đăng nhập hệ thống</h2>
    
    <form method="post" action="login.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <hr>
    <div>
        <?php 
            echo $message; 
            echo "<p>Failed Attempts: " . $_SESSION['attempts'] . "</p>";
        ?>
    </div>
</body>
</html>