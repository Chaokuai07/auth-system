<?php
session_start();
require __DIR__ . '/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $identity = $_POST["login_identity"] ?? '';
    $password = $_POST["login_password"] ?? '';

    try {
        $sql = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $sql->execute([$identity]);
        $user = $sql->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            if (password_verify($password, $user["password"])) {

                // SESSION
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["role"] = $user["role"];
                $_SESSION["name"] = $user["first_name"];

                // ⭐ REMEMBER ME ⭐
                if (!empty($_POST["remember"])) {

                    $token = bin2hex(random_bytes(32));

                    setcookie(
                        "remember_token",
                        $token,
                        time() + (86400 * 30),
                        "/",
                        "",
                        false,
                        true
                    );

                    $sql2 = $pdo->prepare(
                        "UPDATE users SET remember_token = ? WHERE id = ?"
                    );
                    $sql2->execute([$token, $user["id"]]);
                }

                // REDIRECT
                if ($user["role"] === "dev") {
                    header("Location: ../dev/index.php");
                } else {
                    header("Location: ../user/index.php");
                }
                exit;

            } else {
                $error = "Incorrect password!";
            }

        } else {
            $error = "Email not found!";
        }

    } catch (PDOException $e) {
        $error = "Database error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Log in</title>
</head>
<body>
    <?php if (!empty($error)): ?>
    <p style="color:red; margin: 10px 0; text-align:center;">
        <?= $error ?>
    </p>
    <?php endif; ?>

    <div class="auth-container">
        <h1 class="auth-title">LOGIN</h1>

        <form class="auth-form" method="POST" action="login.php">
            <!-- Email / Username -->
            <label>Email</label>
            <div class="input-group">
                <input type="text" id="login-identity" name="login_identity" placeholder="email" required>
                <i class="fa-solid fa-user input-icon"></i>
            </div>

            <!-- Password -->
            <label>Password</label>
            <div class="input-group" id="login-pwd-group">
                <input type="password" id="login-password" name="login_password" placeholder="••••••••" required>
                <i class="fa-solid fa-lock input-icon" id="login-password-icon"></i>
            </div>

            <!-- Remember + Forgot -->
            <div class="remember" style="justify-content: space-between; margin-top: 10px;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <input type="checkbox" name="remember" id="login-remember">
                    <label for="login-remember">Remember me</label>
                </div>

                <a href="forgot.php" class="forgot-link">Forgot password?</a>
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn-primary">LOG IN</button>

            <!-- Switch to Sign Up -->
            <p class="small-text">
                Don't have an account?
                <a href="signup.php">SIGN UP</a>
            </p>

            <!-- OR line -->
            <div class="divider">
                <span>OR</span>
            </div>

            <!-- Social -->
            <button type="button" class="btn-social google">
                <i class="fa-brands fa-google"></i>
                Continue with Google
            </button>

            <button type="button" class="btn-social apple">
                <i class="fa-brands fa-apple"></i>
                Continue with Apple
            </button>

        </form>
    </div>

    <div id="footer"></div>
    <script src="../js/auth.js"></script>
    <script src="../js/include-footer.js"></script>
</body>

</html>

