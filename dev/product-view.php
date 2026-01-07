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

$id = $_GET["id"];

$sql = $conn->prepare("SELECT * FROM products WHERE id=?");
$sql->bind_param("i", $id);
$sql->execute();
$product = $sql->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?= $product['brand'] . " " . $product['model'] ?></title>
    <link rel="stylesheet" href="../css/dev-product-view.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

<div class="product-view">

    <img src="../uploads/<?= $product['image'] ?>" class="product-image">

    <h2><?= $product['brand'] . " " . $product['model'] ?></h2>

    <div class="details-box">
        <p><strong>ราคา:</strong> <?= number_format($product['price']) ?> บาท</p>
        <p><strong>ROM:</strong> <?= $product['rom'] ?> GB</p>
        <p><strong>สภาพ:</strong> <?= $product['condition'] ?></p>
        <p><strong>แบตเตอรี่:</strong> <?= $product['battery_health'] ?>%</p>
        <p><strong>ระบบ:</strong> <?= $product['version'] ?></p>
        <p><strong>ประกัน:</strong> <?= $product['warranty'] ?></p>
        <p><strong>อุปกรณ์แถม:</strong> <?= $product['accessories'] ?></p>
        <p><strong>สีเครื่อง:</strong><?php echo $product['color']; ?></p>
    </div>

    <h3>รายละเอียดสินค้า</h3>
    <p class="detail-text"><?= nl2br($product['details']) ?></p>

    <div class="action-row">
        <a href="edit-product.php?id=<?= $product['id'] ?>" class="edit-btn">
            <i class="fa-solid fa-pen-to-square"></i> แก้ไขสินค้า
        </a>
        <a href="product-list.php" class="back-btn">กลับสู่รายการ</a>
    </div>

</div>

</body>
</html>