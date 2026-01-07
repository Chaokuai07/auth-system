<?php
session_start();
if (!isset($_SESSION["user_id"])) { header("Location: ../auth/login.php"); exit(); }
if ($_SESSION["role"] !== "dev") { header("Location: ../user/index.php"); exit(); }
require "../auth/db.php";

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if (!$id) { header("Location: product-list.php"); exit(); }

// ตรวจเจ้าของ
$stmt = $conn->prepare("SELECT dev_id, image FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$orig = $stmt->get_result()->fetch_assoc();
if (!$orig) { header("Location: product-list.php"); exit(); }
if ((int)$orig['dev_id'] !== (int)$_SESSION['user_id']) { header("Location: product-list.php"); exit(); }

$brand    = trim($_POST['brand'] ?? '');
$model    = trim($_POST['model'] ?? '');
$rom      = trim($_POST['rom'] ?? '');
$price    = (int)($_POST['price'] ?? 0);
$condition= trim($_POST['condition'] ?? '');
$battery  = (int)($_POST['battery_health'] ?? 0);
$version  = trim($_POST['version'] ?? '');
$warranty = trim($_POST['warranty'] ?? '');
$details  = trim($_POST['details'] ?? '');
$color    = trim($_POST['color'] ?? '');
$accessories = isset($_POST['accessories']) ? implode(", ", $_POST['accessories']) : "";

// ถ้ามีไฟล์ใหม่ -> อัปโหลด + ลบของเก่า (option)
$imageName = $currentImage = $orig['image'];
if (!empty($_FILES['image']['name'])) {
    $fileTmp  = $_FILES['image']['tmp_name'];
    $origName = basename($_FILES['image']['name']);
    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp','gif'];
    if (!in_array($ext, $allowed)) die("ไฟล์รูปไม่รองรับ");
    $imageName = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/','_', $origName);
    $targetDir = __DIR__ . "/../uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    if (!move_uploaded_file($fileTmp, $targetDir . $imageName)) {
        die("อัปโหลดรูปไม่สำเร็จ");
    }
    // ลบรูปเก่า (ถ้ามี)
    if (!empty($currentImage) && file_exists($targetDir . $currentImage)) {
        @unlink($targetDir . $currentImage);
    }
}

// update
$update = $conn->prepare("
    UPDATE products SET brand=?, model=?, rom=?, price=?, `condition`=?, battery_health=?, version=?, warranty=?, accessories=?, details=?, image=?, color=?
    WHERE id=?
");
if (!$update) die("Prepare failed: " . $conn->error);

$types = 'sssisissssssi'; // 12 values strings/ints then id
$update->bind_param(
    $types,
    $brand, $model, $rom, $price, $condition, $battery, $version, $warranty, $accessories, $details, $imageName, $color, $id
);

if ($update->execute()) {
    header("Location: product-view.php?id=" . $id);
    exit();
} else {
    echo "อัปเดตไม่สำเร็จ: " . $update->error;
}