const btnTop = document.getElementById("btn-top");

window.addEventListener("scroll", () => {
    if (window.scrollY > 100) {
        btnTop.classList.add("show");
    } else {
        btnTop.classList.remove("show");
    }
});

btnTop.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
});