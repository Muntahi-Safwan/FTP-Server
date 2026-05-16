document.addEventListener("DOMContentLoaded", function() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "getres.php", true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            var container = document.getElementById("divSF");
            
            var radioAll = document.createElement("input");
            radioAll.type = "radio";
            radioAll.name = "category_radio";
            radioAll.value = 0;
            radioAll.id = "cat_0";
            
            var labelAll = document.createElement("label");
            labelAll.htmlFor = "cat_0";
            labelAll.textContent = "All";
            
            container.appendChild(radioAll);
            container.appendChild(labelAll);
            
            for (var i = 0; i < data.length; i++) {
                var item = data[i];
                
                var radio = document.createElement("input");
                radio.type = "radio";
                radio.name = "category_radio";
                radio.value = item.id;
                radio.id = "cat_" + item.id;
                
                var label = document.createElement("label");
                label.htmlFor = "cat_" + item.id;
                label.textContent = item.name;
                
                container.appendChild(radio);
                container.appendChild(label);
            }
        }
    };
    
    xhr.send();
});

let btnSet = document.getElementById('btnSearch');

// STEP 1: Button is clicked
btnSet.onclick = function() {
    var keyword = document.getElementById('searhBox').value.trim();
    
    if (keyword === "") {
        alert("Empty textbox");
        return;
    }
    
    // Pass the keyword to the session setter
    sessionSet(keyword);
};

// STEP 2: Set the session variable
function sessionSet(keyword) {
    var selectedRadio = document.querySelector('input[name="category_radio"]:checked');
    
    if (selectedRadio) {
        var id = selectedRadio.value;
        var sessionXhr = new XMLHttpRequest();
        
        sessionXhr.open("POST", "set_session.php", true);
        sessionXhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        sessionXhr.onload = function() {
            // Once the session is updated on the server, trigger the search
            performSearch(keyword);
        };
        
        sessionXhr.send("id=" + id);
    } else {
        // If no radio is selected, just go straight to the search
        performSearch(keyword);
    }
}

// STEP 3: Perform the search and build the table
function performSearch(keyword) {
    var searchXhr = new XMLHttpRequest();
    searchXhr.open("GET", "search.php?keyword=" + encodeURIComponent(keyword), true);
    
    searchXhr.onload = function() {
        if (searchXhr.status === 200) {
            var data = JSON.parse(searchXhr.responseText);
            var tbody = document.getElementById("tbod");
            tbody.innerHTML = ""; 
            
            for (let i = 0; i < data.length; i++) {
                let tr = document.createElement("tr");
                
                let tdTitle = document.createElement("td");
                tdTitle.textContent = data[i].title;
                
                let tdCount = document.createElement("td");
                tdCount.textContent ='('+ data[i].download_count +')';
                
                let tdPath = document.createElement("td");
                let ank = document.createElement("a");

                ank.href = `../controller/downladM.php?des=${encodeURIComponent(data[i].file_path)}`;
                ank.innerText = "download";

                tdPath.appendChild(ank);
                
                tr.appendChild(tdTitle);
                tr.appendChild(tdPath);
                tr.appendChild(tdCount);
                
                tbody.appendChild(tr);
            }
        }
    };
    
    searchXhr.send();
}