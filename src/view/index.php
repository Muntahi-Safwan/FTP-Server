<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>FTP Server</title>

    <style>

        body{
            font-family: Arial;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }

        .navbar{
            background: black;
            padding: 15px;
        }

        .navbar a{
            color: white;
            text-decoration: none;
            margin-right: 20px;
            font-size: 18px;
        }

        .container{
            width: 90%;
            margin: auto;
            margin-top: 30px;
        }

        .card{
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        h1{
            color: #333;
        }

        .btn{
            background: green;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

    </style>
</head>
<body>

    <!-- Navbar -->

    <div class="navbar">

        <a href="index.php">Home</a>

        <?php
        if(isset($_SESSION['role'])){

            if($_SESSION['role'] == 'admin'){
                ?>

                <a href="views/admin/dashboard.php">
                    Dashboard
                </a>

                <a href="views/admin/moderators.php">
                    Manage Moderators
                </a>

                <a href="views/admin/contents.php">
                    Manage Contents
                </a>

                <a href="logout.php">
                    Logout
                </a>

                <?php
            }

        }else{
            ?>

            <a href="login.php">Login</a>

            <?php
        }
        ?>

    </div>

    <!-- Main Section -->

    <div class="container">

        <h1>FTP Media Content Server</h1>

        <p>
            Browse Movies, Software, TV Series, Games and more.
        </p>

        <?php

        include('config/db.php');

        $sql = "SELECT contents.*, categories.name AS category_name
                FROM contents
                JOIN categories
                ON contents.category_id = categories.id
                ORDER BY uploaded_at DESC";

        $result = $conn->query($sql);

        while($row = $result->fetch_assoc()){
        ?>

        <div class="card">

            <h2>
                <?php echo $row['title']; ?>
            </h2>

            <p>
                <?php echo $row['description']; ?>
            </p>

            <p>
                Category:
                <?php echo $row['category_name']; ?>
            </p>

            <a class="btn"
               href="<?php echo $row['file_path']; ?>">
               Download
            </a>

        </div>

        <?php
        }
        ?>

    </div>

</body>
</html>