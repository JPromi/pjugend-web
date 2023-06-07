function menu(menuID) {
    document.getElementById("menu-" + menuID).classList.toggle("hidden");
    document.getElementById("nav").classList.remove("select");

    document.getElementById("menuBack-" + menuID).classList.toggle("hidden");
}