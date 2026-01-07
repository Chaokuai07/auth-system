<?php
session_start();
header('Content-Type: application/json');
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dev') exit(json_encode(['ok'=>false,'msg'=>'ต้องเป็น dev']));
require __DIR__ . '/../auth/db.php';
$id = intval($_POST['product_id'] ?? 0);
if(!$id) exit(json_encode(['ok'=>false,'msg'=>'missing']));

$upd = $conn->prepare("UPDATE products SET status='sold', reserved_by=NULL, reserved_until=NULL WHERE id=?");
$upd->bind_param('i',$id);
$upd->execute();
exit(json_encode(['ok'=>true]));
?>