<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION["role"] !== "dev") {
    header("Location: ../user/index.php");
    exit();
}

require "../auth/db.php";
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการสินค้า</title>
    <link rel="stylesheet" href="../css/dev-product-list.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

<div class="list-container">
    <h2>รายการสินค้า</h2>

    <a href="add-product.php" class="add-btn">+ เพิ่มสินค้า</a>

    <!-- 🔍 Search bar -->
    <input type="text" id="searchBox" placeholder="ค้นหาด้วยชื่อรุ่น / ยี่ห้อ / สี / ROM ..." class="search-input">

    <div id="productList">
        <!-- เนื้อหาจะถูกโหลดด้วย AJAX จาก search-product.php -->
    </div>

</div>



</body>
</html>