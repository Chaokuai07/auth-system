<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "dev") {
    header("Location: ../user/index.php");
    exit();
}

require "../auth/db.php"; // ปรับ path ตามจริงของบูม

// รับค่าจากฟอร์ม (ตรวจ/escape ง่ายๆ)
$dev_id      = (int) $_SESSION['user_id'];
$brand       = isset($_POST['brand']) ? $conn->real_escape_string($_POST['brand']) : '';
$model       = isset($_POST['model']) ? $conn->real_escape_string($_POST['model']) : '';
$rom         = isset($_POST['rom']) ? $conn->real_escape_string($_POST['rom']) : '';
$color       = isset($_POST['color']) ? $conn->real_escape_string($_POST['color']) : '';
$price       = isset($_POST['price']) ? (int) $_POST['price'] : 0;
$condition   = isset($_POST['condition']) ? $conn->real_escape_string($_POST['condition']) : '';
$battery     = isset($_POST['battery_health']) ? (int) $_POST['battery_health'] : null;
$version     = isset($_POST['version']) ? $conn->real_escape_string($_POST['version']) : '';
$warranty    = isset($_POST['warranty']) ? $conn->real_escape_string($_POST['warranty']) : '';
$details     = isset($_POST['details']) ? $conn->real_escape_string($_POST['details']) : '';
$accessories = '';

if (isset($_POST['accessories']) && is_array($_POST['accessories'])) {
    // join และ escape แต่เรียงแบบง่าย
    $acc_arr = array_map(function($v) use ($conn) {
        return $conn->real_escape_string($v);
    }, $_POST['accessories']);
    $accessories = implode(', ', $acc_arr);
}

// อัปโหลดรูป
$imageName = null;
if (!empty($_FILES['image']['name'])) {
    $fileTmp  = $_FILES['image']['tmp_name'];
    $origName = basename($_FILES['image']['name']);
    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp','gif'];

    if (!in_array($ext, $allowed)) {
        die("ไฟล์รูปไม่รองรับ (jpg, jpeg, png, webp, gif เท่านั้น)");
    }

    $uploadDir = __DIR__ . "/../uploads/"; // ../uploads relative to dev/save-product.php
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $imageName = time() . "_" . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $origName);
    $targetFile = $uploadDir . $imageName;

    if (!move_uploaded_file($fileTmp, $targetFile)) {
        die("อัปโหลดรูปไม่สำเร็จ");
    }
}

// INSERT ลง DB (escape ไว้แล้ว)
$sql = "
INSERT INTO products 
(dev_id, brand, model, rom, color, price, `condition`, battery_health, version, warranty, accessories, details, image)
VALUES
($dev_id, '$brand', '$model', '$rom', '$color', $price, '$condition', ".($battery===null ? "NULL" : $battery).", '$version', '$warranty', '$accessories', '$details', ".($imageName ? "'$imageName'" : "NULL").")
";

if ($conn->query($sql)) {
    header("Location: product-list.php");
    exit();
} else {
    echo "เพิ่มสินค้าไม่สำเร็จ: " . $conn->error;
}