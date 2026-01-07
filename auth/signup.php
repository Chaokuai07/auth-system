<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST['email'];
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // เช็ก email ซ้ำ
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email already exists!";
    } else {
        // insert ข้อมูลใหม่
        $sql = $conn->prepare("
            INSERT INTO users (email, first_name, last_name, password, birthday)
            VALUES (?, ?, ?, ?, ?)
        ");
        $sql->bind_param("sssss", $email, $fname, $lname, $password, $birthday);
        $sql->execute();

        header("Location: login.php");
        exit();
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
    <title>Sign up</title>
</head>
<body>
    <div class="auth-container">
    <h1 class="auth-title">SIGN UP</h1>

        <form class="auth-form" method="POST" action="">
            <!-- Email -->
            <label>Email</label>
            <div class="input-group">
                <input type="email" id="email" name="email" placeholder="you@example.com" required>
                <i class="fa-regular fa-envelope input-icon"></i>
            </div>

            <!-- First name -->
            <label>First name</label>
            <div class="input-group">
                <input type="text" id="first-name" name="first_name" placeholder="Firstname" required>
                <i class="fa-solid fa-user input-icon"></i>
            </div>

            <!-- Last name -->
            <label>Last name</label>
            <div class="input-group">
                <input type="text" id="last-name" name="last_name" placeholder="Lastname" required>
                <i class="fa-solid fa-user input-icon"></i>
            </div>

            <!-- Password -->
            <label>Password</label>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="••••••••" required>
                <i class="fa-solid fa-lock input-icon" id="password-icon"></i>
            </div>

            <!-- Birthday -->
            <label>Birthday</label>
            <div class="input-group">
                <input type="date" id="birthday" name="birthday" placeholder="MM-DD-YYYY" required>
                <i class="fa-regular fa-calendar input-icon"></i>
            </div>

            <!-- Remember -->
            <div class="remember">
                <input type="checkbox" id="remember">
                <label for="remember">Remember me</label>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-primary">SIGN UP</button>

            <!-- Already have account -->
            <p class="small-text">
                Already have an account?
                <a href="login.php">LOG IN</a>
            </p>

            <!-- OR line -->
            <div class="divider">
                <span>OR</span>
            </div>

            <!-- Google / Apple -->
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

        <script src="js/auth.js"></script>
</body>
</html>