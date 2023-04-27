//remove
function deleteLink(elementID) {
    document.querySelector('[data-id="'+elementID+'"]').remove();
}

//add link
function addLink() {

    try {
        var linkID = document.getElementById('links').lastElementChild.dataset.id;
    } catch {
        var linkID = 0;
    };

    newID = parseInt(linkID)+1;

    var newLink = document.createElement("a");
    newLink.classList.add("single");
    newLink.setAttribute('data-id',newID);
    //newLink.setAttribute('draggable', true);
    newLink.innerHTML = `
        <div class="btn">
            <span class="material-symbols-outlined" onclick="deleteLink('`+newID+`')">
            delete
            </span>
        </div>

        <div class="edit" id="edit1">
            <input type="hidden" name="id[]" value="">
            <input type="hidden" name="index_id[]" value="`+newID+`">
            <label>Titel: <input type="text" name="title[]" placeholder="Titel" class="name" value="" required></label>
            <label>Link: <input type="text" name="link[]" placeholder="Link" value="" required></label>
        </div>
    `;

    document.getElementById("links").appendChild(newLink);
}