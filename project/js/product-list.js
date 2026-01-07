// ดึง container ที่จะเอาสินค้ามาแสดง
const productGrid = document.querySelector(".product-grid");

// จำลองสินค้า (ก่อนเชื่อม DB)
const products = [
    {
        id: 1,
        name: "iPhone 12",
        price: 11900,
        grade: "A+",
        version: "iOS 17",
        img: "../img/iphone12.jpg"
    },
    {
        id: 2,
        name: "Samsung S21",
        price: 8900,
        grade: "A",
        version: "OneUI 6",
        img: "../img/s21.jpg"
    }
];

// ฟังก์ชันสร้างการ์ดสินค้า
function renderProducts() {
    productGrid.innerHTML = ""; // ล้างก่อน

    products.forEach(p => {
        const card = document.createElement("div");
        card.classList.add("product-card");

        card.innerHTML = `
            <a href="product-detail.php?id=${p.id}" class="card-link">
                <div class="img-box">
                    <img src="${p.img}" alt="${p.name}">
                </div>

                <div class="info">
                    <h3>${p.name}</h3>
                    <p class="price">${p.price.toLocaleString()} ฿</p>
                    <p class="grade">สภาพ: ${p.grade}</p>
                </div>
            </a>
        `;

        productGrid.appendChild(card);
    });
}

document.addEventListener("DOMContentLoaded", renderProducts);