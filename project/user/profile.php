<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require "db.php";

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>โปรไฟล์ของฉัน</title>
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

<div class="profile-container">

    <!-- ░▒▒ HEADER ▒▒░ -->
    <div class="profile-box">

        <div class="profile-picture">
            <?php if (!empty($user['profile_pic'])): ?>
                <img src="../uploads/<?php echo $user['profile_pic']; ?>" alt="รูปโปรไฟล์">
            <?php else: ?>
                <img src="../img/default-user.png" alt="default">
            <?php endif; ?>
        </div>

        <!-- แสดงเฉพาะชื่อ–นามสกุล -->
        <h2><?php echo $user['first_name'] . " " . $user['last_name']; ?></h2>

        <!-- ลบแสดงอีเมลออกแล้ว -->

        <a href="edit-profile.php" class="edit-btn">แก้ไขข้อมูล</a>
    </div>


    <!-- ░▒▒ เมนูด้านล่าง ▒▒░ -->
    <div class="menu-section">

        <a href="#" class="menu-item">
            <i class="fa-solid fa-gear"></i>
            <span>ตั้งค่าบัญชี</span>
        </a>

        <a href="#" class="menu-item">
            <i class="fa-solid fa-heart"></i>
            <span>รายการโปรด</span>
        </a>

        <a href="#" class="menu-item">
            <i class="fa-solid fa-box"></i>
            <span>ประวัติการสั่งซื้อ</span>
        </a>

        <a href="#" class="menu-item">
            <i class="fa-solid fa-medal"></i>
            <span>Member Level</span>
        </a>

        <a href="../auth/logout.php" class="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ
        </a>

    </div>

</div>

</body>
</html>