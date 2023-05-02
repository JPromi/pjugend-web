function openAlert(alertID) {
    document.getElementById("alert").classList.remove("hidden");
    document.getElementById(alertID).classList.remove("hidden");
}

function closeAlerts() {
    document.getElementById("alert").classList.add("hidden");

    var windows = document.getElementsByClassName("window");
    for (var i = 0; i < windows.length; i++) {
        document.getElementsByClassName("window")[i].classList.add("hidden");
    }
}