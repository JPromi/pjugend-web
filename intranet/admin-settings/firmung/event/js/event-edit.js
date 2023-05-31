function checkRegistration(event) {
    var regStatus = event;
    var parent = event.parentElement.parentElement;

    var textrea = parent.children[4].children[0];
    var present = parent.children[2].children[0];

    if(regStatus.checked) {
        textrea.disabled = false;
        present.disabled = false;
    } else {
        textrea.disabled = true;
        present.disabled = true;
    }
}