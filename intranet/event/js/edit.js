/* links */

function removeLink(linkID) {
    document.getElementById("link" + linkID).remove();
}

function addLink() {
    //last element
    var lastChild = document.getElementById('links').lastElementChild;
    var lastID = lastChild.id.replace("link", "");
    if(lastID === "") {
        lastID = 0;
    }
    var newID = parseInt(lastID) + 1;
    
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

/* alert */
function alertadd(elementId) {
    document.getElementById("alert").classList.remove("disabled"); 
    document.getElementById("back").classList.remove("disabled");
    
    document.getElementById(elementId).classList.remove("disabled");
}

function removealert() {
    document.getElementById("alert").classList.add("disabled");
    document.getElementById("back").classList.add("disabled");

    var boxes = document.getElementsByClassName("alertbox");
    for (var i = 0; i < boxes.length; i++) {
        document.getElementsByClassName("alertbox")[i].classList.add("disabled");
    }
}

/* image */
function CoverimagePreview() {
        cover.src=URL.createObjectURL(event.target.files[0]);
}

function coverDelete(cdn) {
    if (document.getElementById('coverDel').checked){
        cover.src = 'https://' + cdn + '/event/placeholder/image.png';
    } else {
        cover.src = document.querySelector("#cover").dataset.originalFile;
    }    
}