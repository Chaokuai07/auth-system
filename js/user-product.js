fetch("../product/product.php")
    .then(res => res.text())
    .then(data => {
        document.getElementById("product-list").innerHTML = data;
    });