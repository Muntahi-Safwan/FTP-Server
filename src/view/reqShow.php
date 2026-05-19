<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || $_SESSION['role'] !== 'moderator') {
        header("Location: homePage.php");
        exit;
    }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Requests</title>
    <link rel="stylesheet" href="../asset/home.css">
</head>
<body>
    
    <div id="headPart">
        <a href="homePage.php"><b>Home</b></a>
    </div>

    <div class="content-area">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>REQUESTER_IP</th>
                    <th>CONTENT_TITLE</th>
                    <th>CATEGORY</th>
                    <th>MESSAGE</th>
                    <th>STATUS</th>
                    <th>TIME</th>
                    <th>ACCEPT</th>
                    <th>REJECT</th>
                </tr>
            </thead>
            <tbody id="tconBody">

            </tbody>
        </table>
    </div>

    <script src="../asset/reqShow.js"></script>
</body>
</html>