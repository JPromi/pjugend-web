function removeType(elementID) {
    document.getElementById(elementID).remove();
}

function newType() {
    var lastChild = document.getElementById('types').lastElementChild;

    if(lastChild == null) {
        var lastID = 0;
    } else {
        var lastID = parseInt(lastChild.id);
    }

    var newID = lastID + 1;
    console.log(newID);

    const types = document.getElementById("types");
    var codeblock = `
    <div class="single" id="` + newID + `">
        <input type="hidden" name="typeID[]" value="">
        <input type="text" placeholder="Titel" name="type[]">
        <a href="javascript:void(0)" onclick="removeType('` + newID + `')">
            <span class="material-symbols-outlined">
            delete
            </span>
        </a>
    </div>
    `;
    types.innerHTML += codeblock;
}