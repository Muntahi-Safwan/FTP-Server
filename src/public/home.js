// 1. Function to load tabs and all content for a Main Category
function loadMainCategory(mainId) {
    var tabsDiv = document.getElementById("subcategory_tabs");
    var contentDiv = document.getElementById("content_grid");

    // Fetch Subcategory Buttons using XMLHttpRequest
    var xhrTabs = new XMLHttpRequest();
    xhrTabs.open("GET", "../controller/get_sub_tabs.php?parent_id=" + mainId, true);
    xhrTabs.onload = function() {
        if (xhrTabs.status === 200) {
            tabsDiv.innerHTML = xhrTabs.responseText;
        }
    };
    xhrTabs.send();

    // Fetch ALL content for this Main Category
    var xhrContent = new XMLHttpRequest();
    xhrContent.open("GET", "../controller/get_contents.php?main_id=" + mainId, true);
    xhrContent.onload = function() {
        if (xhrContent.status === 200) {
            contentDiv.innerHTML = xhrContent.responseText;
        }
    };
    xhrContent.send();
}

// 2. Function to filter content when a Subcategory tab is clicked
function filterBySub(subId) {
    var contentDiv = document.getElementById("content_grid");

    // Fetch ONLY content for this Subcategory using XMLHttpRequest
    var xhrContent = new XMLHttpRequest();
    xhrContent.open("GET", "../controller/get_contents.php?sub_id=" + subId, true);
    xhrContent.onload = function() {
        if (xhrContent.status === 200) {
            contentDiv.innerHTML = xhrContent.responseText;
        }
    };
    xhrContent.send();
}