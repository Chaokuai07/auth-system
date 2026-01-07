<?php
session_start();
require "../auth/db.php";
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
$user_id = (int)$_SESSION['user_id'];

// ดึงสินค้าใน cart ของ user
$cartRes = $conn->query("SELECT * FROM carts WHERE user_id = $user_id");
$items = $cartRes->fetch_all(MYSQLI_ASSOC);
if (!$items) { echo "cart empty"; exit; }

$conn->begin_transaction();
try {
    // ตรวจเช็กแต่ละสินค้าแบบล็อก
    foreach ($items as $it) {
        $pid = (int)$it['product_id'];
        $sel = $conn->prepare("SELECT status, reserved_by FROM products WHERE id=? FOR UPDATE");
        $sel->bind_param("i",$pid);
        $sel->execute();
        $p = $sel->get_result()->fetch_assoc();
        if (!$p) throw new Exception("product missing");
        // ถ้าเป็น sold และไม่ใช่เจ้าของ reservation -> fail
        if ($p['status'] === 'sold') throw new Exception("product sold");
        if ($p['status'] === 'in_cart' && (int)$p['reserved_by'] !== $user_id) throw new Exception("product reserved by other");
    }

    // สร้าง order
    $total = 0;
    foreach ($items as $it) {
        // ต้อง get price from product
        $pid = (int)$it['product_id'];
        $prRes = $conn->query("SELECT price FROM products WHERE id=$pid");
        $row = $prRes->fetch_assoc();
        $total += (int)$row['price'] * $it['qty'];
    }
    $insOrder = $conn->prepare("INSERT INTO orders (user_id, status, total) VALUES (?, 'paid', ?)");
    $insOrder->bind_param("ii", $user_id, $total);
    $insOrder->execute();
    $order_id = $insOrder->insert_id;

    // insert order_items + update product status sold
    $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, price, qty) VALUES (?, ?, ?, ?)");
    $updProduct = $conn->prepare("UPDATE products SET status='sold', reserved_by=NULL, reserved_until=NULL WHERE id=?");
    foreach ($items as $it) {
        $pid = (int)$it['product_id'];
        $priceQ = $conn->query("SELECT price FROM products WHERE id=$pid")->fetch_assoc()['price'];
        $stmtItem->bind_param("iiii",$order_id, $pid, $priceQ, $it['qty']);
        $stmtItem->execute();

        $updProduct->bind_param("i",$pid);
        $updProduct->execute();
    }

    // ลบ cart
    $conn->query("DELETE FROM carts WHERE user_id = $user_id");

    $conn->commit();
    header("Location: ../user/order-success.php?order=".$order_id);
    exit;
} catch (Exception $e) {
    $conn->rollback();
    echo "Checkout failed: " . $e->getMessage();
}
?>