function removeLink(linkID) {
    document.getElementById("link" + linkID).remove();
}

function addLink() {
    //last element
    var lastChild = document.getElementById('links').lastElementChild;
    var lastID = lastChild.id.replace("link", "");
    var newID = parseInt(lastID) + 1;

    let div = document.createElement("div");
    
    const links = document.getElementById("links");
    var codeblock = `
    <div class="single" id="link` + newID + `">
        <a onclick="removeLink(` + newID + `)">
            <span class="material-symbols-outlined">
            remove
            </span>
        </a>
        <input type="text" placeholder="Titel" name="linkTitle[]"">
        <input type="text" placeholder="Link" name="link[]" class="link"">
    </div>
    `;
    links.innerHTML += codeblock;

}