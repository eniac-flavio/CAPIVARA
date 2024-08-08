// Parte 1
window.addEventListener("scroll", function(){
    var nav = document.querySelector("nav")
    nav.classList.toggle("sticky", window.scrollY > 0)
})

// Parte 2

document.addEventListener("DOMContentLoaded", function() {
    const searchIcon = document.getElementById("searchIcon");
    const searchContainer = document.getElementById("searchContainer");
    const searchInput = document.getElementById("searchInput");

    searchIcon.addEventListener("click", function() {
        searchContainer.classList.toggle("hide");
        searchIcon.classList.toggle("search-outline"); // Adiciona ou remove a classe "search-outline"
    });
});

// Parte 3

document.addEventListener("DOMContentLoaded", function() {
    const searchIcon = document.getElementById("searchIcon");
    const searchContainer = document.getElementById("searchContainer");
    const searchInput = document.getElementById("searchInput");

    searchIcon.addEventListener("click", function() {
        searchContainer.classList.toggle("show");
        searchIcon.classList.toggle("search");
        searchIcon.classList.toggle("search-outline");

        if (searchContainer.classList.contains("show")) {
            searchInput.style.display = "block";
            searchIcon.setAttribute("name", "search");
        } else {
            searchInput.style.display = "none";
            searchIcon.setAttribute("name", "search-outline");
        }
    });
});

// Parte 4
document.addEventListener("DOMContentLoaded", function() {
    const searchIcon = document.getElementById("searchIcon")
    const searchContainer = document.getElementById("searchContainer")
    const searchInput = document.getElementById("searchInput")
    const headerLogo = document.querySelector(".header-logo")
    const headerIcons = document.querySelector(".header-icons")

    const submitInput = document.getElementById("submitInput")
    const filterDiv = document.getElementById("filterDiv")

    searchIcon.addEventListener("click", function() {
        searchContainer.classList.toggle("show")
        searchContainer.classList.toggle("show")
        searchIcon.classList.toggle("search")
        searchIcon.classList.toggle("search-outline")
        headerLogo.classList.toggle("hide")
        headerIcons.classList.toggle("hide")

        if (searchContainer.classList.contains("show")) {
            searchInput.style.display = "block";
            submitInput.style.display = "block";
            filterDiv.style.display = "block";
            searchIcon.setAttribute("name", "search");
            searchInput.classList.add("fullwidth");
        } else {
            searchInput.style.display = "none";
            submitInput.style.display = "none";
            filterDiv.style.display = "none";
            searchIcon.setAttribute("name", "search-outline");
            searchInput.classList.remove("fullwidth");
        }
    });
});