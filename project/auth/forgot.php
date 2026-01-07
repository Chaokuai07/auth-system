<?php
require 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    // เช็กว่ามีอีเมลนี้มั้ย
    $sql = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $res = $sql->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        $token = bin2hex(random_bytes(30));

        // เก็บ token ลง DB
        $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_expire = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE id = ?");
        $update->bind_param("si", $token, $user["id"]);
        $update->execute();

        // ลิงก์จำลอง (ถ้าบูมจะยังไม่ใช้อีเมล)
        $reset_link = "http://localhost/project/auth/reset.php?token=" . $token;

        $message = "Reset link (ชั่วคราว): <a href='$reset_link'>$reset_link</a>";
    } else {
        $message = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/forgot.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Forgot Password</title>
</head>
<body>

<div class="auth-container">

    <h1 class="auth-title">RESET</h1>

    <?php if (!empty($message)): ?>
        <p class="message success"><?= $message ?></p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="message error"><?= $error ?></p>
    <?php endif; ?>

    <form class="auth-form" method="POST">
        <label>Email</label>
        <input type="email" name="email" placeholder="you@example.com" required>

        <button type="submit" class="btn-primary">Send reset link</button>
    </form>

    <p class="small-text">
        Remembered?
        <a href="login.php">LOG IN</a>
    </p>

</div>

</body>
</html>