<?php
session_start();

require '../auth/db.php';

if (!isset($_SESSION["user_id"]) && !empty($_COOKIE["remember_token"])) {

    $token = $_COOKIE["remember_token"];

    $sql = $conn->prepare("SELECT id, role, first_name FROM users WHERE remember_token=?");
    $sql->bind_param("s", $token);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["name"] = $user["first_name"];
    }
}
?>
<h1>Welcome Dev <?= $_SESSION["name"] ?></h1>
<a href="../auth/logout.php">Logout</a>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Document</title>
</head>
<body>
    DEV
    <a href="./add-product.php">add</a>
    <a href="product-list.php">list</a>
    <a href="report-model.php">model</a>
</body>
</html>