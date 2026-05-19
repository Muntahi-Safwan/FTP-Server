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
    <link rel="stylesheet" href="../asset/home.css">
    </head>
<body>
    <div id="headPart">
        <a href="login.php" id="login"><b>Log In</b></a> 
        <a href="ContentReq.html" id="Conreq">Request Content</a> 
        <a href="reqShow.php" id="req">Check Request</a>

    <div id="divSF"></div>
    </div>
    <input type="text" id="searhBox" value="">
    <table>
        <tbody id="tbod">

        </tbody>
    </table>
   
    
<div class="main-container">
    <div id="sidebar" class="sidebar">
        <h3>Gallery</h3>
        <button class="sidebar-btn" onclick="loadMainCategory(1)">Movies</button>
        <button class="sidebar-btn" onclick="loadMainCategory(2)">Software</button>
        <button class="sidebar-btn" onclick="loadMainCategory(3)">TV Series</button>
        <button class="sidebar-btn" onclick="loadMainCategory(4)">Games</button>
    </div>

    <div class="content-area">
        
        <div id="subcategory_tabs" class="tabs-container"></div>

        <div id="content_grid" class="grid-container">
            <p>Select a category to view content.</p>
        </div>
        
    </div>
</div>

<script src="../asset/home.js"></script>

</body>
</html>