<?php
// (ถ้าจะใช้ database ค่อยเพิ่มภายหลัง ตอนนี้คือ UI ล้วน)
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดสินค้า</title>
    <link rel="stylesheet" href="../css/product-detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

<div class="product-container">

    <!-- รูปสินค้า -->
    <div class="product-image-box">
        <img src="../img/jinxcat.png" alt="product">
    </div>

    <!-- รายละเอียด -->
    <div class="product-info">

        <h2 class="product-name">iPhone 12</h2>

        <p class="product-price">฿ 9,900.-</p>

        <div class="product-detail-list">

            <p><strong>สภาพ:</strong> 95% (สวยมาก)</p>
            <p><strong>ระบบปฏิบัติการ:</strong> iOS 17.4</p>
            <p><strong>ความจุ:</strong> 128GB</p>
            <p><strong>สี:</strong> ดำ</p>
            <p><strong>แบตเตอรี่:</strong> 89%</p>
            <p><strong>อื่น ๆ:</strong> ผ่านการตรวจเช็คแล้ว ใช้งานได้ 100%</p>

        </div>

        <!-- ปุ่ม -->
        <div class="btn">
            <button class="add-cart-btn">
                <i class="fa-solid fa-cart-plus"></i> เพิ่มลงตะกร้า
            </button>

            <button class="buy-now-btn">
                สั่งซื้อทันที
            </button>
        </div>
    </div>

</div>

</body>
</html>