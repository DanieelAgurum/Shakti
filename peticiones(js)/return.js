const shaktiBtnTop = document.getElementById("shakti-btn-top");

window.addEventListener("scroll", () => {
    if (window.scrollY > 100) {
        shaktiBtnTop.classList.add("show");
    } else {
        shaktiBtnTop.classList.remove("show");
    }
});

shaktiBtnTop.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
});
