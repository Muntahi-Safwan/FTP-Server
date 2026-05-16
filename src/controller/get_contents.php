<?php

    require('../model/t_content_model.php');
    $main_id = $_GET['main_id'] ?? "";
    $sub_id = $_GET['sub_id'] ?? "";
    $result = tamzContent($main_id, $sub_id) ?? "";

    if ($result !== "") {
        if ($result && mysqli_num_rows($result) > 0) {
            
            while($row = mysqli_fetch_assoc($result)) {
                echo "<div class='content-item'>";
                echo "<h4 class='content-title'>" . htmlspecialchars($row['title']) . "</h4>";
                echo "<p class='content-desc'>" . htmlspecialchars($row['description']) . "</p>";
                echo "<small class='content-path'>" . htmlspecialchars($row['file_path']) . "</small>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-data-msg'>No contents available in this category.</p>";
        }
}


?>