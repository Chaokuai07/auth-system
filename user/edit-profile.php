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
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../css/edit-profile.css">
</head>

<body>

<div class="edit-container">

    <h2>แก้ไขโปรไฟล์</h2>

    <form action="save-profile.php" method="POST" enctype="multipart/form-data">

        <!-- รูปโปรไฟล์ -->
        <div class="image-box">
            <?php if (!empty($user['profile_pic'])): ?>
                <img src="../uploads/<?php echo $user['profile_pic']; ?>" class="profile-preview">
            <?php else: ?>
                <img src="../img/default-user.png" class="profile-preview">
            <?php endif; ?>

            <input type="file" name="profile_pic" accept="image/*">
        </div>

        <!-- ชื่อจริง -->
        <label>ชื่อจริง</label>
        <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required>

        <!-- นามสกุล -->
        <label>นามสกุล</label>
        <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required>

        <!-- วันเกิด -->
        <label>วันเกิด</label>
        <input type="date" name="birthday" value="<?php echo $user['birthday']; ?>" required>

        <button type="submit" class="save-btn">บันทึกข้อมูล</button>
    </form>

</div>

</body>
</html>