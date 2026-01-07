document.addEventListener("DOMContentLoaded", () => {
    console.log("footer js loaded");

    const footer = document.getElementById("footer");
    if (!footer) return;

    fetch("./footer/footer.html")
        .then(res => res.text())
        .then(data => {
            footer.innerHTML = data;

            // โหลด CSS
            const link = document.createElement("link");
            link.rel = "stylesheet";
            link.href = "./css/footer.css";
            document.head.appendChild(link);
        })
        .catch(err => console.error("Footer load error:", err));
});
