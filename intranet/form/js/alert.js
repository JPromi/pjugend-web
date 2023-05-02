/* alert */
function alertadd(elementId) {
    document.getElementById("alert").classList.remove("disabled"); 
    document.getElementById("back").classList.remove("disabled");
    
    document.getElementById(elementId).classList.remove("disabled");
}

function removealert() {
    document.getElementById("alert").classList.add("disabled");
    document.getElementById("back").classList.add("disabled");

    var boxes = document.getElementsByClassName("alertbox");
    for (var i = 0; i < boxes.length; i++) {
        document.getElementsByClassName("alertbox")[i].classList.add("disabled");
    }
}
