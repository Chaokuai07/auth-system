<?php
require 'db.php';

$user_id = $_POST["user_id"];
$token   = $_POST["token"];

$new_password = $_POST["new_password"];
$confirm      = $_POST["confirm_password"];

// RULES CHECK
$rules = [
    strlen($new_password) >= 8,
    preg_match('/[A-Z]/', $new_password),       // พิมพ์ใหญ่
    preg_match('/[a-z]/', $new_password),       // พิมพ์เล็ก
    preg_match('/[0-9]/', $new_password),       // ตัวเลข
    preg_match('/[@$!%*?&.#_\-]/', $new_password) // อักขระพิเศษ
];

if (in_array(false, $rules)) {
    echo "<p style='color:red; text-align:center;'>Password does not meet the requirements.</p>";
    exit();
}

// CHECK CONFIRM
if ($new_password !== $confirm) {
    echo "<p style='color:red; text-align:center;'>Passwords do not match!</p>";
    exit();
}

$newpass = password_hash($new_password, PASSWORD_DEFAULT);

$sql = $conn->prepare("
    UPDATE users
    SET password = ?, reset_token = NULL, reset_expire = NULL
    WHERE id = ? AND reset_token = ?
");
$sql->bind_param("sis", $newpass, $user_id, $token);
$sql->execute();

header("Location: login.php");
exit();