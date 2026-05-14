<?php
session_start();
include("config/db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISP FTP Server</title>

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: sans-serif;
            background-color: #eef2f7;
        }

        header{
            background: #1e293b;
            color: white;
            padding: 20px;
        }

        .menu{
            margin-top: 10px;
        }

        .menu a{
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }

        .container{
            width: 85%;
            margin: auto;
            padding: 30px 0;
        }

        .top-section{
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .top-section h1{
            margin-bottom: 10px;
        }

        .content-box{
            background: white;
            margin-bottom: 20px;
            padding: 20px;
            border-left: 5px solid #2563eb;
            border-radius: 8px;
        }

        .content-box h2{
            color: #1e293b;
            margin-bottom: 10px;
        }

        .content-box p{
            margin-bottom: 8px;
        }

        .download-btn{
            display: inline-block;
            padding: 10px 18px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .download-btn:hover{
            background: #1d4ed8;
        }

    </style>

</head>
<body>

<header>

    <h2>FTP Media Server</h2>

    <div class="menu">

        <a href="index.php">Home</a>

        <?php
        if(isset($_SESSION['role'])){

            if($_SESSION['role'] == "admin"){
                echo '<a href="views/admin/dashboard.php">Dashboard</a>';
                echo '<a href="views/admin/addContent.php">Upload Content</a>';
                echo '<a href="views/admin/moderators.php">Moderators</a>';
            }

            echo '<a href="logout.php">Logout</a>';

        }else{
            echo '<a href="login.php">Login</a>';
        }
        ?>

    </div>

</header>

<div class="container">

    <div class="top-section">

        <h1>Welcome to ISP FTP Content Service</h1>

        <p>
            Download Movies, Games, TV Series, Software and many more files.
        </p>

    </div>

    <?php

    $sql = "SELECT contents.*, categories.name AS categoryName
            FROM contents
            JOIN categories
            ON contents.category_id = categories.id
            ORDER BY contents.id DESC";

    $result = $conn->query($sql);

    if($result->num_rows > 0){

        while($data = $result->fetch_assoc()){
    ?>

        <div class="content-box">

            <h2>
                <?php echo htmlspecialchars($data['title']); ?>
            </h2>

            <p>
                <?php echo htmlspecialchars($data['description']); ?>
            </p>

            <p>
                <strong>Category:</strong>
                <?php echo $data['categoryName']; ?>
            </p>

            <a class="download-btn"
               href="<?php echo $data['file_path']; ?>">
               Download File
            </a>

        </div>

    <?php
        }

    }else{
        echo "<h3>No Content Available</h3>";
    }

    ?>

</div>

</body>
</html>