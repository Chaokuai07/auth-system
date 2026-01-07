<?php
session_start();
require "../auth/db.php";
if (!isset($_SESSION['user_id'])) { http_response_code(401); exit; }

$user_id = (int)$_SESSION['user_id'];
$product_id = (int)($_POST['product_id'] ?? 0);
if (!$product_id) { http_response_code(400); echo "need product"; exit; }

// เช็กสถานะแบบล็อก row
$conn->begin_transaction();

try {
    $sel = $conn->prepare("SELECT status, reserved_by, reserved_until FROM products WHERE id = ? FOR UPDATE");
    $sel->bind_param("i",$product_id);
    $sel->execute();
    $res = $sel->get_result()->fetch_assoc();
    if (!$res) throw new Exception("not found");

    // ถ้าสินค้าขายแล้ว
    if ($res['status'] === 'sold') throw new Exception("sold");

    // ถ้ามี reserved โดยคนอื่นและยังไม่หมดเวลา
    if (($res['status'] === 'in_cart' || $res['status'] === 'reserved') 
         && (int)$res['reserved_by'] !== $user_id 
         && strtotime($res['reserved_until']) > time()) {
        throw new Exception("reserved_by_other");
    }

    // สร้าง/อัปเดต cart
    $ins = $conn->prepare("INSERT INTO carts (user_id, product_id, qty) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE qty=1, added_at=NOW()");
    $ins->bind_param("ii", $user_id, $product_id);
    $ins->execute();

    // อัปเดตสถานะสินค้า ให้ล็อกสำหรับ user นี้ (เช่นเก็บ 20 นาที)
    $reserved_until = date("Y-m-d H:i:s", time() + 20*60);
    $upd = $conn->prepare("UPDATE products SET status='in_cart', reserved_by=?, reserved_until=? WHERE id=?");
    $upd->bind_param("isi",$user_id, $reserved_until, $product_id);
    $upd->execute();

    $conn->commit();
    echo json_encode(['ok'=>true]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(409);
    echo json_encode(['ok'=>false, 'msg'=>$e->getMessage()]);
}
?>