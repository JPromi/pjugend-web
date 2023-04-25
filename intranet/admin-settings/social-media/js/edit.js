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

    var newLink = document.createElement("div");
    newLink.classList.add("single");
    newLink.setAttribute('data-id',(parseInt(linkID)+1));
    newLink.innerHTML = `<p></p>`;

    document.getElementById("links").appendChild(newLink);
}