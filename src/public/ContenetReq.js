document.addEventListener("DOMContentLoaded", function() {
    
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../controller/cat_list.php", true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            var selectCat = document.getElementById("cat");
            
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement("option");
                option.value = data[i].name;
                option.textContent = data[i].name;
                
                selectCat.appendChild(option);
            }
        }
    };
    xhr.send();
});

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