function addFormField() {
    //get last element
    try {
        var formID = document.getElementById('builder').lastElementChild.id;
    } catch {
        var formID = 0;
    };
    var formID = parseInt(formID) + 1;
    var formFields = document.getElementById("builder");
    var newField = document.createElement("div");

    //create form
    newField.classList.add("single");
    newField.setAttribute('id',formID);
    newField.innerHTML = `
            <div class="settings">
                <label>Type:</label>
                <select name="type[]" id="setting` + formID + `" onchange="changeContent('` + formID + `')">
                    <option value=""></option>
                    <option value="text">Text</option>
                    <option value="date">Date</option>
                    <option value="email">Email</option>
                    <option value="number">Number</option>
                    <!--<option value="checkbox">Checkbox</option>-->
                    <option value="stTitle">Titel</option>
                    <option value="stDesc">Beschreibung</option>
                </select>
                <input type="hidden" name="id[]" value="`+formID+`">
                <a onclick="removeField('`+formID+`')" title="Löschen">
                    <span class="material-symbols-outlined">
                    delete
                    </span>
                </a>
            </div>
            <div class="input" id="content` + formID + `">
                
            </div>
    `;
    formFields.appendChild(newField);
}

function removeField(formID) {
    document.getElementById(formID).remove();
}

function changeContent(elementID) {
    console.log(document.getElementById("setting"+ elementID).value);
    document.getElementById("content"+ elementID).innerHTML = contentBlock(document.getElementById("setting"+ elementID).value, elementID);
}

function contentBlock(block, blockID) {
    var content;
    const textBlock = ["text", "date", "email", "number"];

    // get content
    if(textBlock.includes(block)) {
        content = `
                <label><input type="text" name="text[]" id="" placeholder="Text"></label>
                <input type="text" placeholder="Input" disabled>
            `;
    } else if (block === "checkbox") {
        content = `
        <div id="checkboxes` + blockID + `">
            <label><input type="checkbox" disabled> <input type="input" name="text[]" placeholder="Name"></label>
            <label><input type="checkbox" disabled> <input type="input" name="text[]" placeholder="Name"></label>
            <label><input type="checkbox" disabled> <input type="input" name="text[]" placeholder="Name"></label>
            <label><input type="checkbox" disabled> <input type="input" name="text[]" placeholder="Name"></label>
            <label><input type="checkbox" disabled> <input type="input" name="text[]" placeholder="Name"></label>
        </div>
        `;
    }

    content = content + `
        <label class="important"><input type="checkbox" name="required[]" value="`+blockID+`"> Pflichtfeld</label>
    `;

    if(block === "") {
        content = "";
    } else  if (block === "stTitle") {
        content = `
        <label class="title"><input type="text" name="text[]" placeholder="Titel"></label>
        `;
    } else if (block === "stDesc") {
        content = `
        <label class="description"><textarea name="text[]" placeholder="Beschreibung"></textarea></label>
        `;
    }

    return content;
}

