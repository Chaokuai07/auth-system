<?php
session_start();
require "../auth/db.php"; // หรือ path ที่บูมใช้จริง

// ต้องล็อกอิน
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

// เฉพาะ dev เท่านั้น (ถ้าต้องการให้ทั้ง dev และ admin แก้ได้ ปรับเงื่อนไขได้)
if ($_SESSION["role"] !== "dev") {
    header("Location: ../user/index.php");
    exit();
}

// รับ id (รองรับ GET หรือ POST) — แต่แนะนำ POST
$id = null;
if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];
} elseif (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
} else {
    die("ไม่พบสินค้าที่ต้องการลบ");
}

// ดึงข้อมูลสินค้า (รวม dev_id และชื่อไฟล์รูป) เพื่อเช็คความเป็นเจ้าของก่อนลบ
$stmt = $conn->prepare("SELECT dev_id, image FROM products WHERE id = ?");
if (!$stmt) {
    die("SQL prepare error: " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$product = $res->fetch_assoc();

if (!$product) {
    die("ไม่พบสินค้าที่ระบุ");
}

// ตรวจสอบว่าเป็นเจ้าของหรือไม่
if ((int)$product['dev_id'] !== (int)$_SESSION['user_id']) {
    die("คุณไม่มีสิทธิ์ลบสินค้านี้");
}

// เริ่ม transaction (ถ้าใช้ InnoDB จะช่วยให้ปลอดภัย)
$conn->begin_transaction();

try {
    // ลบเรคคอร์ด
    $del = $conn->prepare("DELETE FROM products WHERE id = ?");
    $del->bind_param("i", $id);
    if (!$del->execute()) {
        throw new Exception("ไม่สามารถลบข้อมูล: " . $del->error);
    }

    // ถ้ามีรูปอยู่ ให้ลบทิ้ง — ตรวจสอบความปลอดภัย path ก่อน
    $image = $product['image'];
    if ($image) {
        $uploadsDir = realpath(__DIR__ . "/../uploads"); // path จริงของโฟลเดอร์ uploads
        $target = realpath($uploadsDir . "/" . $image);

        // ตรวจว่าตัวไฟล์มีจริงและอยู่ใน folder uploads เท่านั้น (ป้องกัน path traversal)
        if ($target && strpos($target, $uploadsDir) === 0 && file_exists($target)) {
            if (!unlink($target)) {
                // ถ้าลบไฟล์ไม่สำเร็จ เราเลือกไม่ยกเลิก transaction แต่ log ข้อผิดพลาด
                // ถ้าต้องการให้ rollback ให้ throw exception แทน
                // throw new Exception("ลบไฟล์ไม่สำเร็จ");
            }
        }
    }

    $conn->commit();
    header("Location: product-list.php?msg=deleted");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}