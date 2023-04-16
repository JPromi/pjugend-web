//delete button
function deleteNote() {
    var deleteSet = document.getElementById("deleteSetNote");
    var deleteFinal = document.getElementById("deleteNote");
    
    deleteSet.style.display = "none";
    deleteFinal.style.display = "";
}

function deleteNoteUndo() {
    var deleteSet = document.getElementById("deleteSetNote");
    var deleteFinal = document.getElementById("deleteNote");
    
    deleteSet.style.display = "";
    deleteFinal.style.display = "none";
}

//send to form
function setSendNoteForm() {
    var result = document.getElementById("editorText").innerHTML;
    document.getElementById('TextToForm').value = result;
    countChar();
}

//special character
var commandButtons = document.querySelectorAll(".specText a");

for (var i = 0; i < commandButtons.length; i++) {
    commandButtons[i].addEventListener("mousedown", function (e) {
        e.preventDefault();
        var commandName = e.target.getAttribute("data-command");
        if (commandName === "html") {
            var commandArgument = e.target.getAttribute("data-command-argument");
            document.execCommand('formatBlock', false, commandArgument);
        } else {
            document.execCommand(commandName, false);
        }
    });
}

//character
function countChar() {
    var textbox = document.getElementById('TextToForm');
    var output = document.getElementById('charcount'); 
    var len = textbox.value.length;
      //console.log(len);
    output.innerHTML = len;
  };

//past only plain text
var ce = document.querySelector('[contenteditable]')
ce.addEventListener('paste', function (e) {
    e.preventDefault()
    var text = e.clipboardData.getData('text/plain')
    document.execCommand('insertText', false, text)
})