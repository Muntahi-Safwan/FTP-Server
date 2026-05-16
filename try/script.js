document.addEventListener("DOMContentLoaded", function() {
    
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "getres.php", true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            var container = document.getElementById("divSF");
            var radio = document.createElement("input");
                radio.type = "radio";
                radio.name = "category_radio"; // This groups them together
                radio.value = 0;
                radio.id = "cat_" + 0;
                
                // Create the label for the category name
                var label = document.createElement("label");
                label.htmlFor = "cat_" + 0;
                label.textContent = "All";
                
                // Append to container with a line break
                container.appendChild(radio);
                container.appendChild(label);
            
            for (var i = 0; i < data.length; i++) {
                var item = data[i];
                
                // Create the radio button
                var radio = document.createElement("input");
                radio.type = "radio";
                radio.name = "category_radio"; // This groups them together
                radio.value = item.id;
                radio.id = "cat_" + item.id;
                
                // Create the label for the category name
                var label = document.createElement("label");
                label.htmlFor = "cat_" + item.id;
                label.textContent = item.name;
                
                // Append to container with a line break
                container.appendChild(radio);
                container.appendChild(label);
               // container.appendChild(document.createElement("br"));
            }
        }
    };
    
    xhr.send();
});
let btnSet=document.getElementById('btnSet');
btnSet.onclick=function () {
    // Select the single radio button that is currently checked
    var selectedRadio = document.querySelector('input[name="category_radio"]:checked');
    
    // Only send the request if a radio button was actually selected
    if (selectedRadio) {
        var id = selectedRadio.value;
        
        var sessionXhr = new XMLHttpRequest();
        sessionXhr.open("POST", "set_session.php", true);
        sessionXhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        sessionXhr.send("id=" + id);
    }
}