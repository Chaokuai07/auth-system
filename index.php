<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>PhoneCycle</title>
</head>
<body>
    <div id="header"></div>

    <main>
        <div id="product-list"></div>
    </main>


    <div id="footer"></div>
    <script src="js/include-guest.js"></script>
    <script src="js/include-footer.js"></script>
    <script src="js/product.js"></script>
</body>
</html>
