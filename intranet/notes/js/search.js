//search folder
function searchFolder() {
    var input, filter, select, singleElement, name, i, txtValue;
    input = document.getElementById("searchFolder");
    filter = input.value.toUpperCase();
    select = document.getElementById("folder");
    singleElement = select.getElementsByClassName("single");
    for (i = 0; i < singleElement.length; i++) {
        name = singleElement[i].getElementsByTagName("h1")[0];
        txtValue = name.textContent || name.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            singleElement[i].style.display = "";
        } else {
            singleElement[i].style.display = "none";
        }
    }
}

function searchFolderInput() {
    var toggle = document.getElementById("searchFolder");
    if (toggle.style.display === "none") {
        toggle.style.display = "";
    } else {
        toggle.style.display = "none";
    }
}

//search note
function searchNote() {
    var input, filter, select, singleElement, name, i, txtValue;
    input = document.getElementById("searchNote");
    filter = input.value.toUpperCase();
    select = document.getElementById("notes");
    singleElement = select.getElementsByClassName("single");
    for (i = 0; i < singleElement.length; i++) {
        name = singleElement[i].getElementsByTagName("h1")[0];
        txtValue = name.textContent || name.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            singleElement[i].style.display = "";
        } else {
            singleElement[i].style.display = "none";
        }
    }
}

function searchNoteInput() {
    var toggle = document.getElementById("searchNote");
    if (toggle.style.display === "none") {
        toggle.style.display = "";
    } else {
        toggle.style.display = "none";
    }
}

//search username for sharenote
function searchShareUser() {
    var input, filter, select, singleElement, name, i, txtValue;
    input = document.getElementById("searchUser");
    filter = input.value.toUpperCase();
    select = document.getElementById("users");
    singleElement = select.getElementsByClassName("row");
    for (i = 0; i < singleElement.length; i++) {
        name = singleElement[i].getElementsByTagName("p")[0];
        txtValue = name.textContent || name.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            singleElement[i].style.display = "";
        } else {
            singleElement[i].style.display = "none";
        }
    }
}