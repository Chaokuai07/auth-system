const currentPage = window.location.pathname.split("/").pop();
const pagesWithHeader = ["index.html", "index.php"];

if (pagesWithHeader.includes(currentPage)) {

    fetch("../header/header-user.html")
        .then(res => res.text())
        .then(data => {
            document.getElementById("header").innerHTML = data;

            const link = document.createElement("link");
            link.rel = "stylesheet";
            link.href = "../css/header.css";
            document.head.appendChild(link);

            const script = document.createElement("script");
            script.src = "../js/header.js";
            document.body.appendChild(script);
        })
        .catch(err => console.error("Header load error:", err));
}