<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION["role"] !== "dev") {
    header("Location: ../user/index.php");
    exit();
}

require "../auth/db.php";

// ดึงจำนวนสินค้าแต่ละรุ่น
$sql = "
    SELECT brand, model, COUNT(*) AS total
    FROM products
    GROUP BY brand, model
    ORDER BY brand ASC, model ASC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายงานจำนวนสินค้าแต่ละรุ่น</title>
    <link rel="stylesheet" href="../css/dev-report.css">
</head>

<!-- ... บรรทัดบนสุดเท่าเดิม ... -->
<body>

<div class="report-container">
    <h2>รายงานจำนวนสินค้าแต่ละรุ่น</h2>

    <!-- ช่องค้นหา -->
    <div class="report-controls">
        <input type="search" id="reportSearch" placeholder="ค้นหา ยี่ห้อ / รุ่น (พิมพ์ตัวเดียวก็เจอ)..." />
        <div id="reportCount">ทั้งหมด: <?php echo $result->num_rows; ?> รายการ</div>
    </div>

    <table class="report-table" id="reportTable">
        <thead>
            <tr>
                <th>ยี่ห้อ</th>
                <th>รุ่น</th>
                <th>จำนวน (ชิ้น)</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td class="col-brand"><?php echo htmlspecialchars($row['brand']); ?></td>
                <td class="col-model"><?php echo htmlspecialchars($row['model']); ?></td>
                <td><?php echo $row['total']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="index.php" class="back-btn">กลับ</a>
</div>

<!-- JS filter (วางก่อนปิด </body>) -->
<script src="../js/dev-report.js" defer></script>

</body>
</html>