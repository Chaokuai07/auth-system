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
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มสินค้า</title>
    <link rel="stylesheet" href="../css/dev-add-product.css">
</head>

<body>

<div class="form-container">
    <h2>เพิ่มสินค้าใหม่</h2>
    <p class="form-subtitle">โทรศัพท์มือสองจาก Dev – กรอกรายละเอียดให้ครบก่อนบันทึกนะ</p>

    <form action="save-product.php" method="POST" enctype="multipart/form-data">

        <!-- รูปสินค้าอยู่บนสุด -->
        <div class="form-group full">
            <label>รูปสินค้า (อัตราส่วน 4:3)</label>
            <div class="image-input-box">
                <div class="image-preview">
                    <!-- ถ้ามี JS ทีหลังค่อยใช้เติม preview ได้ -->
                    <span>เลือกรูป 4:3 ให้สวย ๆ</span>
                </div>
                <input type="file" name="image" accept="image/*" required>
            </div>
        </div>

        <!-- แบรนด์ + รุ่น -->
        <div class="form-group">
            <label>ยี่ห้อ</label>
            <select name="brand" required>
                <option value="">-- เลือกยี่ห้อ --</option>
                <option value="Apple">Apple</option>
                <option value="Samsung">Samsung</option>
                <option value="Xiaomi">Xiaomi</option>
                <option value="OPPO">OPPO</option>
                <option value="vivo">vivo</option>
                <option value="อื่นๆ">อื่น ๆ (ระบุในรายละเอียด)</option>
            </select>
        </div>

        <div class="form-group">
            <label>ชื่อรุ่น (เช่น iPhone 12, Galaxy S23)</label>
            <input type="text" name="model" required>
        </div>

        <div class="form-group">
            <label>ความจุ ROM</label>
            <select name="rom" required>
                <option value="">-- เลือกความจุ --</option>
                <option value="32GB">32GB</option>
                <option value="64GB">64GB</option>
                <option value="128GB">128GB</option>
                <option value="256GB">256GB</option>
                <option value="512GB">512GB</option>
                <option value="1TB">1TB</option>
                <option value="2TB">2TB</option>
            </select>
        </div>

        <div class="form-group">
            <label>สีเครื่อง</label>
            <input type="text" name="color" placeholder="เช่น ดำ , ขาว , ม่วง , Starlight" required>
        </div>

        <div class="form-group">
            <label>สุขภาพแบตเตอรี่</label>
            <select name="battery_health" required>
                <option value="">-- เลือกเปอร์เซ็นต์ --</option>
                <option value="100">100%</option>
                <option value="99">99%</option>
                <option value="98">98%</option>
                <option value="97">97%</option>
                <option value="96">96%</option>
                <option value="95">95%</option>
                <option value="94">94%</option>
                <option value="93">93%</option>
                <option value="92">92%</option>
                <option value="91">91%</option>
                <option value="90">90%</option>
                <option value="89">89%</option>
                <option value="88">88%</option>
                <option value="87">87%</option>
                <option value="86">86%</option>
                <option value="85">85%</option>
                <option value="84">84%</option>
                <option value="83">83%</option>
                <option value="82">82%</option>
                <option value="81">81%</option>
                <option value="80">80%</option>
                <option value="79">79%</option>
                <option value="78">78%</option>
                <option value="77">77%</option>
                <option value="76">76%</option>
                <option value="75">75%</option>
                <option value="74">74%</option>
                <option value="73">73%</option>
                <option value="72">72%</option>
                <option value="71">71%</option>
                <option value="70">70%</option>
            </select>
        </div>

        <div class="form-group">
            <label>สภาพสินค้า</label>
            <select name="condition" required>
                <option value="เหมือนใหม่ 99%">เหมือนใหม่ 99%</option>
                <option value="ดีมาก 95%">ดีมาก 95%</option>
                <option value="ดี 90%">ดี 90%</option>
                <option value="ปกติ 85%">ปกติ 85%</option>
                <option value="มีรอยบ้าง 80%">มีรอยบ้าง 80%</option>
            </select>
        </div>

        <div class="form-group">
            <label>ราคา (บาท)</label>
            <input type="number" name="price" min="0" step="100" required>
        </div>

        <div class="form-group">
            <label>เวอร์ชันระบบ (iOS / OneUI / MIUI ฯลฯ)</label>
            <input type="text" name="version" placeholder="เช่น iOS 17.2, OneUI 6.1" required>
        </div>

        <!-- ประกัน -->
        <div class="form-group">
            <label>ประกันร้าน</label>
            <select name="warranty" required>
                <option value="1 เดือน">1 เดือน</option>
                <option value="3 เดือน">3 เดือน</option>
                <option value="6 เดือน">6 เดือน</option>
                <option value="1 ปี">1 ปี</option>
                <option value="2 ปี">2 ปี</option>
            </select>
        </div>

        <!-- อุปกรณ์แถม -->
        <div class="form-group full">
            <label>อุปกรณ์แถม</label>
            <div class="accessories-group">
                <label><input type="checkbox" name="accessories[]" value="กล่องเครื่อง"> กล่องเครื่อง</label>
                <label><input type="checkbox" name="accessories[]" value="อแดปเตอร์ชาร์จ"> อแดปเตอร์ชาร์จ</label>
                <label><input type="checkbox" name="accessories[]" value="สายชาร์จ"> สายชาร์จ</label>
                <label><input type="checkbox" name="accessories[]" value="เคส"> เคส</label>
                <label><input type="checkbox" name="accessories[]" value="ฟิล์มหน้าจอ"> ฟิล์มกระจกหน้าจอ</label>
                <label><input type="checkbox" name="accessories[]" value="ฟิล์มหน้าจอ"> ฟิล์มไฮโดรเจลหน้าจอ</label>
                <label><input type="checkbox" name="accessories[]" value="ฟิล์มหน้าจอ"> ฟิล์มกระจกหลัง</label>
                <label><input type="checkbox" name="accessories[]" value="ฟิล์มหน้าจอ"> ฟิล์มไฮโดเจลหลัง</label>
            </div>
        </div>

        <!-- รายละเอียด -->
        <div class="form-group full">
            <label>รายละเอียดสินค้า</label>
            <textarea name="details" rows="4" placeholder="เล่าจุดเด่น จุดสังเกต รอยต่างๆ ให้ครบ" required></textarea>
        </div>

        <div class="form-actions full">
            <button type="submit" class="btn-primary">บันทึกสินค้า</button>
            <a href="product-list.php" class="btn-secondary">ย้อนกลับไปหน้ารายการสินค้า</a>
        </div>

    </form>
</div>

</body>
</html>