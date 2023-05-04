const checkboxes = document.querySelectorAll('.imageCheckbox');

var anythingChecked = 0;
var maxElements = 0;

checkboxes.forEach(el => el.addEventListener('click', event => {

    var checkboxess = document.querySelectorAll(".imageCheckbox");
    var checkLength = checkboxess.length;
    var maxElements = checkLength;

    for (i = 0; i < checkboxess.length; i++) {
        if (checkboxess[i].checked) {
            checkLength--;
        }
    }

    if(checkLength < maxElements) {
        document.getElementById("checkedSettings").classList.remove("hidden");
    } else {
        document.getElementById("checkedSettings").classList.add("hidden");
    }
}));