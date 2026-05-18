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

    let url = window.location.href;
    let arr = url.split("//");
    url = arr[1];
    arr = url.split("/");
    url = arr[0];

    var params = "title=" + encodeURIComponent(title) + 
                 "&category=" + encodeURIComponent(category) + 
                 "&message=" + encodeURIComponent(message) + 
                 "&requester_ip=" + encodeURIComponent(url) + 
                 "&status=pending";

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../controller/contentReqInsert.php", true);
    
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (xhr.status == 200) {
            alert(xhr.responseText); 
            
            document.getElementById("title").value = "";
            document.getElementById("messtege").value = "";
        } else {
            alert("Oops! Something went wrong with the server.");
        }
    };

    xhr.send(params);
};