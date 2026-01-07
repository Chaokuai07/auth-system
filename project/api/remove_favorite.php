<?php
session_start();
require "../auth/db.php";
$user_id = (int)$_SESSION['user_id'];
$product_id = (int)($_POST['product_id'] ?? 0);
$stmt = $conn->prepare("DELETE FROM favorites WHERE user_id=? AND product_id=?");
$stmt->bind_param("ii",$user_id,$product_id);
$stmt->execute();
echo json_encode(['ok'=>true]);
?>