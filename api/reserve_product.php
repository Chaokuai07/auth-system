<?php
session_start();
header('Content-Type: application/json');
if(!isset($_SESSION['user_id'])) exit(json_encode(['ok'=>false,'msg'=>'ต้องล็อกอิน']));
require __DIR__ . '/../auth/db.php';
$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id'] ?? 0);
$reserve_minutes = intval($_POST['minutes'] ?? 30); // ระยะเวลาจอง

if(!$product_id) exit(json_encode(['ok'=>false,'msg'=>'missing product']));

// พยายามอัปเดตสถานะเฉพาะถ้า status = 'available'
$expires = date('Y-m-d H:i:s', time() + $reserve_minutes*60);
$up = $conn->prepare("UPDATE products SET status='reserved', reserved_by=?, reserved_until=? WHERE id=? AND status='available'");
$up->bind_param('isi', $user_id, $expires, $product_id);
$up->execute();

if($up->affected_rows === 1){
    // บันทึก reservation history
    $ins = $conn->prepare("INSERT INTO reservations (user_id,product_id,status,reserved_until) VALUES (?,?, 'reserved', ?)");
    $ins->bind_param('iis', $user_id, $product_id, $expires);
    $ins->execute();
    exit(json_encode(['ok'=>true,'msg'=>'จองสำเร็จ','reserved_until'=>$expires]));
}else{
    exit(json_encode(['ok'=>false,'msg'=>'ไม่สามารถจองได้ (มีคนจองแล้วหรือสินค้าหมด)']));
}
?>