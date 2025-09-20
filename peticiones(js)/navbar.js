const searchWrapper = document.getElementById("searchWrapper");
const searchIcon = document.getElementById("search-icon-navbar");
const searchInput = document.getElementById("searchInputNavbar");

searchIcon.addEventListener("click", () => {
  searchWrapper.classList.toggle("active");
});
