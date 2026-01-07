<?php
require 'db.php';

$token = $_GET['token'] ?? "";
$valid = false;
$error = "";
$user = null;

if ($token) {
    $sql = $conn->prepare("SELECT id FROM users WHERE reset_token = ?");
    $sql->bind_param("s", $token);
    $sql->execute();
    $res = $sql->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        $valid = true;
    } else {
        $error = "Invalid or expired token.";
    }
} else {
    $error = "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Reset Password</title>
</head>
<body>

<div class="auth-container">

    <h1 class="auth-title">RESET PASSWORD</h1>

    <?php if (!empty($error)): ?>
        <p class="message error"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($valid): ?>
        <form class="auth-form" method="POST" action="process_reset.php">

            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <input type="hidden" name="token" value="<?= $token ?>">

            <!-- New password -->
            <label>New Password</label>
            <div class="input-group">
                <input type="password" name="new_password" id="reset-password" placeholder="••••••••" required>
                <i class="fa-solid fa-lock input-icon" id="reset-password-icon"></i>
            </div>

            <!-- Confirm password -->
            <label style="margin-top: 15px;">Confirm Password</label>
            <div class="input-group">
                <input type="password" name="confirm_password" id="reset-confirm" placeholder="••••••••" required>
                <i class="fa-solid fa-lock input-icon" id="reset-confirm-icon"></i>
            </div>

            <div id="pwd-rules" class="pwd-rules">
                <p id="r-length" class="rule">• อย่างน้อย 8 ตัวอักษร</p>
                <p id="r-upper" class="rule">• มีตัวพิมพ์ใหญ่ 1 ตัว</p>
                <p id="r-lower" class="rule">• มีตัวพิมพ์เล็ก 1 ตัว</p>
                <p id="r-number" class="rule">• มีตัวเลข 1 ตัว</p>
                <p id="r-special" class="rule">• มีอักขระพิเศษ 1 ตัว เช่น @#?!</p>
            </div>

            <button type="submit" class="btn-primary">Change Password</button>
        </form>
    <?php endif; ?>

</div>

    <script src="../js/reset.js"></script>
</body>
</html>