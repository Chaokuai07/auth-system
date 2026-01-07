<?php
// project/dev/banner.php
// ปรับ path db ให้ชัวร์ (db.php อยู่ที่ project/auth/db.php)
require_once __DIR__ . '/../auth/db.php';

// ดึงสินค้า (แก้เงื่อนไขถ้าต้องการกรองพิเศษ)
$sql = "SELECT id, brand, model, rom, price, battery_health, image FROM products ORDER BY id DESC LIMIT 50";
$res = $conn->query($sql);

// ถ้า error ให้แสดง (ชั่วคราว)
if (!$res) {
    echo "<pre>DB ERROR: " . $conn->error . "</pre>";
    return;
}
?>

<div class="banner-grid">
    <?php if ($res->num_rows === 0): ?>
        <div class="no-products">ยังไม่มีสินค้าลงประกาศ</div>
    <?php else: ?>
        <?php while ($p = $res->fetch_assoc()): ?>
            <div class="card">
                <a href="/dev/product-view.php?id=<?php echo $p['id']; ?>" class="thumb-link">
                    <?php if (!empty($p['image']) && file_exists(__DIR__ . "/../uploads/" . $p['image'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($p['image']); ?>" alt="" class="thumb">
                    <?php else: ?>
                        <div class="thumb placeholder">No image</div>
                    <?php endif; ?>
                </a>

                <div class="meta">
                    <div class="title"><?php echo htmlspecialchars($p['brand'] . ' ' . $p['model']); ?></div>
                    <div class="sub">
                        <span class="rom"><?php echo htmlspecialchars($p['rom']); ?></span>
                        <span class="dot">•</span>
                        <span class="battery"><?php echo htmlspecialchars($p['battery_health']); ?>%</span>
                    </div>
                    <div class="price"><?php echo number_format($p['price']); ?> ฿</div>
                </div>

                <div class="actions">
                    <div class="bottom-actions">
                        <button class="heart-btn" data-id="<?php echo $p['id']; ?>" aria-label="like">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                        <button class="cart-btn" data-id="<?php echo $p['id']; ?>"><i class="fas fa-shopping-cart"></i></button>
                        <button class="reserve-btn" data-id="<?php echo $p['id']; ?>">จอง</button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<!-- CSS แบบสั้น ให้วางใน /css/user-main.css หรือไฟล์ CSS ที่ include -->
<style>
.banner-grid { display:flex; flex-wrap:wrap; gap:18px; }
.card { width: 220px; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 6px 18px rgba(0,0,0,0.06); display:flex; flex-direction:column; }
.thumb { width:100%; aspect-ratio:4/3; object-fit:cover; display:block; }
.thumb.placeholder { display:flex; align-items:center; justify-content:center; height:165px; background:#f4f4f4; color:#aaa; }
.meta { padding:10px 12px; }
.title { font-weight:600; font-size:14px; color:#111; margin-bottom:6px; }
.sub { font-size:12px; color:#888; display:flex; gap:6px; align-items:center; }
.price { font-weight:700; margin-top:8px; color:#222; }
.actions { padding:10px 12px 14px; margin-top:auto; display:flex; flex-direction:column; gap:8px; }
.heart-btn { width:40px; height:40px; border-radius:50%; background:rgba(0,0,0,0.06); border:0; cursor:pointer; font-size:18px; display: flex; align-items:center; justify-content:center; }
.heart-btn .fa-heart { color:#fff; } /* initial white icon (we use regular/solid toggles) */
.heart-btn.liked { background: #ffe6f0; }
.bottom-actions { display:flex; gap:8px; }
.cart-btn, .reserve-btn { flex:1; padding:8px; border-radius:8px; border:0; cursor:pointer; background:#222; color:#fff; font-weight:600; }
.reserve-btn { background:#555; }
.no-products { padding:20px; color:#666; background:#fff; border-radius:10px; }
</style>

<!-- JS ตรงนี้จำลองการกดหัวใจ/ใส่ตะกร้า (ใช้ fetch/ajax เพิ่มได้) -->
<script>
document.addEventListener('click', (e) => {
    // heart
    const h = e.target.closest('.heart-btn');
    if (h) {
        const id = h.dataset.id;
        h.classList.toggle('liked');
        const icon = h.querySelector('i');
        if (h.classList.contains('liked')) {
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
            icon.style.color = '#ff66a3';
            // TODO: call server to save favorite via fetch('/user/fav.php', {method:'POST', body:...})
        } else {
            icon.classList.remove('fa-solid');
            icon.classList.add('fa-regular');
            icon.style.color = '';
            // TODO: remove favorite on server
        }
    }

    // cart/reserve (จำลอง)
    const c = e.target.closest('.cart-btn');
    if (c) {
        alert('เพิ่มสินค้าลงตะกร้าจำลอง id=' + c.dataset.id);
    }
    const r = e.target.closest('.reserve-btn');
    if (r) {
        alert('จองสินค้าจำลอง id=' + r.dataset.id);
    }
});
</script>