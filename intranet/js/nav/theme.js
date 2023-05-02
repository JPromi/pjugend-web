//darkmode
if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    document.getElementById("main").classList.add("darkmode");
} else {
    document.getElementById("main").classList.remove("darkmode");
}