<?php
    session_start();
    echo $_SESSION['filter_search'];
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="divSF"></div>
    <button id='btnSet' onclick="updateSessionFilter()">Set</button>

    <script src="script.js"></script>
    
</body>
</html>