var submitBtn = document.getElementById("submit");
submitBtn.onclick = function() {
    var title = document.getElementById("title").value.trim();
    var category = document.getElementById("cat").value.trim();
    var message = document.getElementById("messtege").value.trim();

    if (title === "") {
        alert("Please enter a Title.");
        return; 
    }

    if (category === "") {
        alert("Please select a Category.");
        return; 
    }

    if (message === "") {
        alert("Please enter a Message.");
        return; 
    }

    alert("Validation passed! The form is ready to be submitted.");
    
};