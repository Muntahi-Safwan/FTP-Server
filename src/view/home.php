<?php

if(!isset($_COOKIE["user_id"])) {
    $user_id = time() . "user";
    setcookie("user_id", $user_id, time() + (86400 * 365), "/"); 
    echo "User ID: " . $user_id; 
} 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FTP Server Content</title>
    </head>
<body>
   
    

<div class="main-container">
    <div id="sidebar" class="sidebar">
        <h3>Categories</h3>
        <button class="sidebar-btn" onclick="loadMainCategory(1)">Movies</button>
        <button class="sidebar-btn" onclick="loadMainCategory(2)">Software</button>
        <button class="sidebar-btn" onclick="loadMainCategory(3)">TV Series</button>
        <button class="sidebar-btn" onclick="loadMainCategory(4)">Games</button>
    </div>

    <div class="content-area">
        
        <div id="subcategory_tabs" class="tabs-container"></div>

        <div id="content_grid" class="grid-container">
            <p>Select a category from the left to view content.</p>
        </div>
        
    </div>
</div>

<script src="../public/home.js"></script>

</body>
</html>