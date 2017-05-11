function getSearch() {
    var searchInput = document.getElementById("search-input").value;
    var url = "http://www.omdbapi.com/?t=" + searchInput;
    makeRequest(url);
}

function makeRequest(url) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url, true); // true for asynchronous request
    xhr.send();
    xhr.addEventListener("readystatechange", processRequest, false);

    function processRequest(e) {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            showResponse(response);
        }
    }
}

function showResponse(response) {
    // process response data
    if (response.Title && response.Year && response.Type) {
        var title = response.Title;
        var year = response.Year.slice(0, 4); // slice for range of years
        var type = response.Type;

        // fill in form
        var form = document.getElementById("hidden-form");
        form.elements[0].value = title;
        form.elements[1].value = year;
        form.elements[2].value = type;

        // display 'box' div
        var box = document.getElementById("box");
        box.querySelector("h3").innerHTML = 
        title + " (" + year + " " + type + ")";
        box.querySelector("img").src = response.Poster;
        box.style.display = "block";
    } else {
        console.log("Bad search");
    }
}

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
    console.log(obj.value);
    var comment = obj.parentElement.querySelector("p").innerHTML;
    var textArea = commentArea.querySelector("textarea");
    textArea.innerHTML = comment;

    // set button value to entry_id
    var saveButton = commentArea.querySelector("button");
    saveButton.value = obj.value;
}