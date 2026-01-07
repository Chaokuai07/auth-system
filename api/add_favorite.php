<?php
session_start();
require "../auth/db.php";
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo "login"; exit; }

$user_id = (int)$_SESSION['user_id'];
$product_id = (int)($_POST['product_id'] ?? 0);
if (!$product_id) { http_response_code(400); echo "product id required"; exit; }

$stmt = $conn->prepare("INSERT IGNORE INTO favorites (user_id, product_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $product_id);
if ($stmt->execute()) {
    echo json_encode(['ok'=>true]);
} else {
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>$stmt->error]);
}
?>