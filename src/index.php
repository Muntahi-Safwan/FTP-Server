```php id="u9p3kr"
<?php

session_start();
include("src/config/db.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FTP Media Server</title>

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: Arial, sans-serif;
            background: #f1f5f9;
        }

        .navbar{
            background: #0f172a;
            padding: 15px 40px;
        }

        .navbar a{
            color: white;
            text-decoration: none;
            margin-right: 20px;
            font-size: 17px;
        }

        .navbar a:hover{
            color: #38bdf8;
        }

        .banner{
            text-align: center;
            padding: 50px;
            background: white;
            margin-bottom: 30px;
        }

        .banner h1{
            color: #0f172a;
            margin-bottom: 10px;
        }

        .container{
            width: 85%;
            margin: auto;
        }

        .content-card{
            background: white;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }

        .content-card h2{
            color: #1e293b;
            margin-bottom: 10px;
        }

        .content-card p{
            margin-bottom: 8px;
            color: #334155;
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

        .footer{
            text-align: center;
            padding: 20px;
            margin-top: 40px;
            background: #0f172a;
            color: white;
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

                echo '<a href="src/view/admin/dashboard.php">Dashboard</a>';

                echo '<a href="src/view/admin/addModerator.php">
                        Add Moderator
                      </a>';

                echo '<a href="src/view/admin/addContent.php">
                        Upload Content
                      </a>';
            }

            echo '<a href="logout.php">Logout</a>';

        }else{

            echo '<a href="login.php">Login</a>';
        }

        ?>

    </div>

    <!-- Banner -->

    <div class="banner">

        <h1>FTP ISP Media Content Server</h1>

        <p>
            Browse and download Movies, Games,
            TV Series, Software and more.
        </p>

    </div>

    <!-- Content Section -->

    <div class="container">

        <?php

        $sql = "SELECT contents.*,
                       categories.name AS category_name
                FROM contents
                JOIN categories
                ON contents.category_id = categories.id
                ORDER BY uploaded_at DESC";

        $result = $conn->query($sql);

        if($result->num_rows > 0){

            while($row = $result->fetch_assoc()){

        ?>

            <div class="content-card">

                <h2>
                    <?php echo htmlspecialchars($row['title']); ?>
                </h2>

                <p>
                    <?php
                    echo htmlspecialchars($row['description']);
                    ?>
                </p>

                <p>

                    <strong>Category:</strong>

                    <?php
                    echo $row['category_name'];
                    ?>

                </p>

                <p>

                    <strong>Total Downloads:</strong>

                    <?php
                    echo $row['download_count'];
                    ?>

                </p>

                <a class="download-btn"
                   href="<?php echo $row['file_path']; ?>">

                    Download

                </a>

            </div>

        <?php

            }

        }else{

            echo "<h2>No Contents Available</h2>";
        }

        ?>

    </div>

    <!-- Footer -->

    <div class="footer">

        <p>
            FTP Media Server Project | Web Technologies
        </p>

    </div>

</body>
</html>
```
