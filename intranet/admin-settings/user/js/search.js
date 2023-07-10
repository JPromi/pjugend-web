// search entry
function search(listID) {
    var input, filter, select, singleElement, name, i, txtValue;
    input = document.getElementById(event.target.id);

    filter = input.value.toUpperCase();
    select = document.getElementById(listID);
    singleElement = select.getElementsByClassName('entry');

    searchClassName = 'name';

    if (filter == "") {
        for (i = 0; i < singleElement.length; i++) {
            firstname = singleElement[i].getElementsByClassName(searchClassName)[0];
            lastname = singleElement[i].getElementsByClassName(searchClassName)[1];

            txtValue = firstname.textContent + ' ' + lastname.textContent;

            singleElement[i].classList.remove("none");
        }
    } else {
        for (i = 0; i < singleElement.length; i++) {
            firstname = singleElement[i].getElementsByClassName(searchClassName)[0];
            lastname = singleElement[i].getElementsByClassName(searchClassName)[1];

            txtValue = firstname.textContent + ' ' + lastname.textContent;

            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                singleElement[i].classList.remove("none");
            } else {
                singleElement[i].classList.add("none");
            }
        }
        
    }
}

function clearInput(searchbar) {
    document.getElementById(searchbar).value = "";

    document.querySelectorAll(".entry").forEach(element => {
        element.classList.remove("none");
    });
}