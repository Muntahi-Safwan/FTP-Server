document.addEventListener("DOMContentLoaded", function() {
    
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "get_cat_list.php", true);
    
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

    // IP 
    let url = window.location.href;
    let arr = url.split("//");
    url = arr[1];
    arr = url.split("/");
    url = arr[0];

    // Format the data perfectly for PHP's $_POST array
    // encodeURIComponent ensures special characters (like spaces in the title) don't break the string
    var params = "title=" + encodeURIComponent(title) + 
                 "&category=" + encodeURIComponent(category) + 
                 "&message=" + encodeURIComponent(message) + 
                 "&requester_ip=" + encodeURIComponent(url) + 
                 "&status=pending";

    // Set up the XMLHttpRequest
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "contentReqInsert.php", true);
    
    // THIS is the exact header needed so PHP can read it via $_POST
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (xhr.status == 200) {
            // This will alert "The form has been submitted and inserted." from your PHP file
            alert(xhr.responseText); 
            
            // Optional: clear out the fields so the user knows it worked
            document.getElementById("title").value = "";
            document.getElementById("messtege").value = "";
        } else {
            alert("Oops! Something went wrong with the server.");
        }
    };

    // Send the formatted string
    xhr.send(params);
};