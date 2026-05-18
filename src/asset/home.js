

document.addEventListener("DOMContentLoaded", function() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../controller/getRadio.php", true); ////////////////////////////change
    
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
var searchBox = document.getElementById('searhBox');

searchBox.onkeyup = function() {
    var keyword = searchBox.value.trim();
    sessionSet(keyword);
};

// STEP 2: Set the session variable
function sessionSet(keyword) {
    var selectedRadio = document.querySelector('input[name="category_radio"]:checked');
    
    if (selectedRadio) {
        var id = selectedRadio.value;
        var sessionXhr = new XMLHttpRequest();
        
        sessionXhr.open("POST", "../controller/set_session.php", true);  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!change
        sessionXhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        sessionXhr.onload = function() {
            // Once the session is updated on the server, trigger the search
            performSearch(keyword);
        };
        
        sessionXhr.send("id=" + id);
    } else {
        // If no radio is selected, just go straight to the search
        if (keyword != ""){
            performSearch(keyword);
        }
        
    }
}

// STEP 3: Perform the search and build the table
function performSearch(keyword) {
    var searchXhr = new XMLHttpRequest();
    searchXhr.open("GET", "../controller/search.php?keyword=" + encodeURIComponent(keyword), true); //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!change
    
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

                ank.href = `../controller/downloadd.php?src=${encodeURIComponent(data[i].file_path)}`; //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!change
                ank.innerText = "download";

                tdPath.appendChild(ank);
                tr.appendChild(tdPath);
                
                
                tr.appendChild(tdTitle);
                tr.appendChild(tdPath);
                tr.appendChild(tdCount);
                
                tbody.appendChild(tr);
            }
        }
    };
    
    searchXhr.send();
}

///////////////////////////////////////////////////////////////////////////////////////

function loadMainCategory(mainId) {
    var tabsDiv = document.getElementById("subcategory_tabs");
    var contentDiv = document.getElementById("content_grid");

    var xhrTabs = new XMLHttpRequest();
    xhrTabs.open("GET", "../controller/get_sub_tabs.php?parent_id=" + mainId, true);
    xhrTabs.onload = function() {
        if (xhrTabs.status === 200) {
            tabsDiv.innerHTML = xhrTabs.responseText;
        }
    };
    xhrTabs.send();
    ///////////////////////
    var xhrContent = new XMLHttpRequest();
    xhrContent.open("GET", "../controller/get_contents.php?main_id=" + mainId, true);

    xhrContent.onload = function() {
        if (xhrContent.status === 200) {
            var data = JSON.parse(xhrContent.responseText);
            
            contentDiv.innerHTML = "";
            
            if (data.length > 0) {
                var table = document.createElement("table");
                
                var tbody = document.createElement("tbody");
                
                for (var i = 0; i < data.length; i++) {
                    var tr = document.createElement("tr");
                    
                    var tdTitle = document.createElement("td");
                    tdTitle.textContent = data[i].title;
                    tr.appendChild(tdTitle);
                    
                    var tdDesc = document.createElement("td");
                    tdDesc.textContent = data[i].description;
                    tr.appendChild(tdDesc);
                    
                    var tdPath = document.createElement("td");
                    var ank = document.createElement("a");
                    ank.href = "../controller/downloadd.php?src=" + encodeURIComponent(data[i].file_path);
                    ank.innerText = "download";
                    
                    tdPath.appendChild(ank);
                    tr.appendChild(tdPath);
                    
                    tbody.appendChild(tr);
                }
                
                table.appendChild(tbody);
                contentDiv.appendChild(table);
            } else {
                var noData = document.createElement("p");
                noData.textContent = "No contents available in this category.";
                contentDiv.appendChild(noData);
            }
        }
    };

    xhrContent.send();


    ////////////
}


function filterBySub(subId) {
    var contentDiv = document.getElementById("content_grid");

    var xhrContent = new XMLHttpRequest();
    xhrContent.open("GET", "../controller/get_contents.php?sub_id=" + subId, true);

    xhrContent.onload = function() {
        if (xhrContent.status === 200) {
            var data = JSON.parse(xhrContent.responseText);
            
            contentDiv.innerHTML = "";
            
            if (data.length > 0) {
                var table = document.createElement("table");
                //table.border = "1"; 
                
                var tbody = document.createElement("tbody");
                
                for (var i = 0; i < data.length; i++) {
                    var tr = document.createElement("tr");
                    
                    var tdTitle = document.createElement("td");
                    tdTitle.textContent = data[i].title;
                    tr.appendChild(tdTitle);
                    
                    var tdDesc = document.createElement("td");
                    tdDesc.textContent = data[i].description;
                    tr.appendChild(tdDesc);
                    
                    var tdPath = document.createElement("td");
                    var ank = document.createElement("a");
                    ank.href = "../controller/downloadd.php?src=" + encodeURIComponent(data[i].file_path);
                    ank.innerText = "download";
                    
                    tdPath.appendChild(ank);
                    tr.appendChild(tdPath);
                    
                    tbody.appendChild(tr);
                }
                
                table.appendChild(tbody);
                contentDiv.appendChild(table);
            } else {
                var noData = document.createElement("p");
                noData.textContent = "No contents available in this category.";
                contentDiv.appendChild(noData);
            }
        }
    };

    xhrContent.send();

            
}