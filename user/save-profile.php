<?php
session_start();

// ❗ ถ้าไม่ได้ login → เด้งกลับ
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require "db.php";

$user_id = $_SESSION['user_id'];

// --------------------------
//  รับข้อมูลจากฟอร์ม
// --------------------------
$firstname = $_POST['first_name'] ?? '';
$lastname  = $_POST['last_name'] ?? '';
$birthday  = $_POST['birthday'] ?? '';

// --------------------------
//  อัปเดทข้อมูลพื้นฐาน
// --------------------------
$update_sql = $conn->prepare("
    UPDATE users 
    SET first_name=?, last_name=?, birthday=? 
    WHERE id=?
");

$update_sql->bind_param("sssi", $firstname, $lastname, $birthday, $user_id);
$update_sql->execute();


// =================================================================
//  ส่วนอัปโหลดรูปโปรไฟล์ (ถ้ามีไฟล์ใหม่อัปโหลดมา)
// =================================================================
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {

    $fileName = time() . "_" . basename($_FILES['profile_pic']['name']);
    $targetDir = "../uploads/";
    $targetFile = $targetDir . $fileName;

    // สร้างโฟลเดอร์ถ้ายังไม่มี
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // ตรวจนามสกุลไฟล์
    $allowed = ['jpg','jpeg','png','gif'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        die("❌ ไฟล์รูปต้องเป็น JPG / PNG / GIF เท่านั้น");
    }

    // บันทึกรูปใหม่
    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {

        // อัปเดทคอลัมน์รูป
        $pic_sql = $conn->prepare("
            UPDATE users SET profile_pic=? WHERE id=?
        ");
        $pic_sql->bind_param("si", $fileName, $user_id);
        $pic_sql->execute();
    }
}


// --------------------------
//  เสร็จแล้ว → เด้งกลับไปหน้าโปรไฟล์
// --------------------------
header("Location: profile.php");
exit();

?>