function renameList() {
    // displays input when rename button is clicked
    var rename = document.querySelectorAll(".rename");
    for (var x=0; x<rename.length; x++) {
            rename[x].style.display = "inline";
    }
}

function editComment(obj) {
    // display comment area
    var commentArea = document.getElementById("comment-area");
    commentArea.style.display = "block";
    // copy comment to textarea
    var comment = obj.parentElement.querySelector("p").innerHTML;
    var textArea = commentArea.querySelector("textarea");
    textArea.innerHTML = comment;
    // set button value to entry_id
    var saveButton = commentArea.querySelector(".button");
    saveButton.value = obj.value;
}

function closeModal() {
    var commentArea = document.getElementById("comment-area");
    commentArea.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
var commentArea = document.getElementById("comment-area");
window.onclick = function(event) {
    if (event.target == commentArea) {
        commentArea.style.display = "none";
    }
}