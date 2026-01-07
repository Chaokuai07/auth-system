const currentPage = window.location.pathname.split("/").pop();
const pagesWithHeader = ["", "index.html"];

if (pagesWithHeader.includes(currentPage)) {
    console.log("Trying to fetch header...");
    fetch("header/header-guest.html")
        .then(res => res.text())
        .then(data => {
            document.getElementById("header").innerHTML = data;
            console.log("Header loaded succesfully!");

            const link = document.createElement("link");
            link.rel = "stylesheet";
            link.href = "./css/header.css";
            document.head.appendChild(link);

            const script = document.createElement("script");
            script.src = "./js/header.js";
            document.body.appendChild(script);
        })
        .catch(err => console.erroe("Error loading header", err));
}