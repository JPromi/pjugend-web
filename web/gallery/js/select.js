//
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
        document.getElementById("gallery").classList.add("selected");
    } else {
        document.getElementById("checkedSettings").classList.add("hidden");
        document.getElementById("gallery").classList.remove("selected");
    }
}));


//on touch
const images = document.querySelectorAll('.image');
images.forEach(el => {
    
    onLongPress(el, function(element) {
        el.querySelector('input').checked = true;
    });

});

//function
function onLongPress(element, callback) {
    var timeoutId;

    element.addEventListener('touchstart', function(e) {
        timeoutId = setTimeout(function() {
            timeoutId = null;
            e.stopPropagation();
            callback(e.target);
        }, 500);
    });

    element.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    element.addEventListener('touchend', function () {
        if (timeoutId) clearTimeout(timeoutId);
    });

    element.addEventListener('touchmove', function () {
        if (timeoutId) clearTimeout(timeoutId);
    });
}