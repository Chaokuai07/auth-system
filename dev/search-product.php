<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "dev") {
    exit("unauthorized");
}

require "../auth/db.php";

$search = isset($_GET["search"]) ? "%" . $_GET["search"] . "%" : "%%";

$sql = $conn->prepare("
    SELECT id, brand, model, color, price, rom, battery_health 
    FROM products
    WHERE brand LIKE ? 
       OR model LIKE ?
       OR color LIKE ?
       OR rom LIKE ?
    ORDER BY id DESC
");

$sql->bind_param("ssss", $search, $search, $search, $search);
$sql->execute();
$result = $sql->get_result();

while ($row = $result->fetch_assoc()):
?>

<div class="product-item">

    <a href="product-view.php?id=<?php echo $row['id']; ?>" class="info-link">
        <div class="info">
            <h3><?php echo $row['brand'] . " " . $row['model']; ?></h3>
            <p>ราคา: <?php echo number_format($row['price']); ?> บาท</p>
            <p>ROM: <?php echo $row['rom']; ?></p>
            <p>สี: <?php echo $row['color']; ?></p>
            <p>แบตเตอรี่: <?php echo $row['battery_health']; ?>%</p>
        </div>
    </a>

    <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="edit-btn">
        <i class="fa-regular fa-pen-to-square"></i>
    </a>

    <a href="delete-product.php?id=<?php echo $row['id']; ?>" 
       class="delete-btn"
       onclick="return confirm('ต้องการลบสินค้าชิ้นนี้จริงไหม?');">
        <i class="fa-solid fa-trash-can"></i>
    </a>

</div>

<?php endwhile; ?>