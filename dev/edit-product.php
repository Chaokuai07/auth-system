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

// รับ id จาก querystring
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: product-list.php");
    exit();
}
$id = (int)$_GET['id'];

// ดึงข้อมูลสินค้า
$sql = $conn->prepare("SELECT * FROM products WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$product = $sql->get_result()->fetch_assoc();
if (!$product) {
    header("Location: product-list.php");
    exit();
}

// ถ้าต้องการให้ dev แก้ได้เฉพาะของตัวเอง ตรวจสอบ dev_id
if ((int)$product['dev_id'] !== (int)$_SESSION['user_id']) {
    // ถ้าไม่ใช่เจ้าของจะรีไดเร็กไปหน้า list
    header("Location: product-list.php");
    exit();
}

// เตรียมค่า accessories เป็น array เพื่อเช็ค checkbox ใน form
$accessories = [];
if (!empty($product['accessories'])) {
    $accessories = array_map('trim', explode(',', $product['accessories']));
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขสินค้า</title>
    <link rel="stylesheet" href="../css/edit-product.css">
</head>
<body>
<div class="form-container">
    <h2>แก้ไขสินค้า</h2>

    <form action="update-product.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo (int)$product['id']; ?>">
        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($product['image']); ?>">

        <div class="form-group">
            <label>รูปสินค้า (อัตราส่วน 4:3) — รูปปัจจุบัน</label>
            <div class="image-preview">
                <?php if (!empty($product['image']) && file_exists(__DIR__ . "/../uploads/" . $product['image'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="product">
                <?php else: ?>
                    <div style="width:220px;height:165px;background:#f0f0f0;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#aaa;">No image</div>
                <?php endif; ?>
            </div>
            <div style="margin-top:8px;">
                <input type="file" name="image" accept="image/*">
                <small>ถ้าไม่เลือกไฟล์ใหม่ ระบบจะเก็บรูปเดิมไว้</small>
            </div>
        </div>

        <div class="form-group">
            <label>ยี่ห้อ</label>
            <select name="brand" required>
                <?php
                $brands = ['Apple','Samsung','Xiaomi','OPPO','vivo','อื่นๆ'];
                foreach ($brands as $b) {
                    $sel = ($product['brand'] === $b) ? "selected" : "";
                    echo "<option value=\"".htmlspecialchars($b)."\" $sel>".htmlspecialchars($b)."</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>ชื่อรุ่น</label>
            <input type="text" name="model" value="<?php echo htmlspecialchars($product['model']); ?>" required>
        </div>

        <div class="form-group">
            <label>ROM (GB)</label>
            <input type="number" name="rom" value="<?php echo htmlspecialchars($product['rom']); ?>" min="1" required>
        </div>

        
        <div class="color-row">
            <label>สีเครื่อง</label>   
            <input type="text" name="color" value="<?php echo htmlspecialchars($product['color']); ?>" required>
        </div>

        <div class="form-group">
            <label>ราคา (บาท)</label>
            <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" min="0" step="100" required>
        </div>

        <div class="form-group">
            <label>สภาพสินค้า</label>
            <select name="condition" required>
                <?php
                $conds = ["เหมือนใหม่ 99%","ดีมาก 95%","ดี 90%","ปกติ 85%","มีรอยบ้าง 80%"];
                foreach ($conds as $c) {
                    $sel = ($product['condition'] === $c) ? "selected" : "";
                    echo "<option value=\"".htmlspecialchars($c)."\" $sel>".htmlspecialchars($c)."</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>สุขภาพแบตเตอรี่ (%)</label>
            <select name="battery_health" required>
                <?php
                for ($i=100;$i>=70;$i--) {
                    $sel = ((int)$product['battery_health'] === $i) ? "selected" : "";
                    echo "<option value=\"$i\" $sel>$i%</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>เวอร์ชันระบบ</label>
            <input type="text" name="version" value="<?php echo htmlspecialchars($product['version']); ?>" placeholder="เช่น iOS 17.2, OneUI 6.1">
        </div>

        <div class="form-group">
            <label>ประกัน</label>
            <select name="warranty" required>
                <?php
                $w = ['1 เดือน','3 เดือน','6 เดือน','1 ปี','2 ปี'];
                foreach ($w as $opt) {
                    $sel = ($product['warranty'] === $opt) ? "selected" : "";
                    echo "<option value=\"".htmlspecialchars($opt)."\" $sel>".htmlspecialchars($opt)."</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>อุปกรณ์แถม</label>
            <div class="accessories-group">
                <?php
                $allAcc = ["กล่องเครื่อง","อแดปเตอร์ชาร์จ","สายชาร์จ","เคส","ฟิล์มกระจกหน้าจอ","ฟิล์มไฮโดรเจลหน้าจอ","ฟิล์มกระจกหลัง","ฟิล์มไฮโดเจลหลัง"];
                foreach ($allAcc as $acc) {
                    $checked = in_array($acc, $accessories) ? "checked" : "";
                    echo '<label><input type="checkbox" name="accessories[]" value="'.htmlspecialchars($acc).'" '.$checked.'> '.htmlspecialchars($acc).'</label> ';
                }
                ?>
            </div>
        </div>

        <div class="form-group">
            <label>รายละเอียดสินค้า</label>
            <textarea name="details" rows="5"><?php echo htmlspecialchars($product['details']); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">บันทึกการแก้ไข</button>
            <a href="product-view.php?id=<?php echo (int)$product['id']; ?>" class="btn-secondary">ยกเลิก</a>
        </div>
    </form>
</div>
</body>
</html>