

//////////////////////////////

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

    var xhrContent = new XMLHttpRequest();
    xhrContent.open("GET", "../controller/get_contents.php?main_id=" + mainId, true);
    xhrContent.onload = function() {
        if (xhrContent.status === 200) {
            contentDiv.innerHTML = xhrContent.responseText;
        }
    };
    xhrContent.send();
}


function filterBySub(subId) {
    var contentDiv = document.getElementById("content_grid");

    var xhrContent = new XMLHttpRequest();
    xhrContent.open("GET", "../controller/get_contents.php?sub_id=" + subId, true);
    xhrContent.onload = function() {
        if (xhrContent.status === 200) {
            contentDiv.innerHTML = xhrContent.responseText;
        }
    };
    xhrContent.send();
}