<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../auth/db.php';

if (!isset($_SESSION["user_id"]) && !empty($_COOKIE["remember_token"])) {

    $token = $_COOKIE["remember_token"];

    $sql = $conn->prepare("SELECT id, role, first_name FROM users WHERE remember_token=?");
    $sql->bind_param("s", $token);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Auto login
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["name"] = $user["first_name"];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/user-main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>PhoneCycle</title>
</head>
<body>
    <div id="header"></div>

    <main>
        <?php include __DIR__ . '/../dev/banner.php'; ?>
    </main>

    <div id="footer"></div>
    <link rel="stylesheet" href="../css/user-main.css">
    <script src="../js/user-main.js" defer></script>
    <script src="../js/include-user.js"></script>
    <script src="../js/include-footer-user.js"></script>
</body>
</html>