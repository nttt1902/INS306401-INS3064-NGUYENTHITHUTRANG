<?php

function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}


function validateLength($str, $min, $max) {
    $len = strlen($str);
    return ($len >= $min && $len <= $max);
}


function validatePassword($pass) {
    $minLength = 8;
    $hasSpecialChar = preg_match('/[^a-zA-Z\d]/', $pass);
    
    if (strlen($pass) >= $minLength && $hasSpecialChar) {
        return true;
    }
    return false;
}
?>