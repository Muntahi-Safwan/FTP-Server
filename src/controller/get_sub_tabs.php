<?php
require_once '../model/t_category_model.php';
$parent_id= $_GET['parent_id'] ?? "";
$result = tamzSubtab($parent_id) ?? "";

if ($result !="" && mysqli_num_rows($result) > 0) {
        
        echo "<button class='tab-btn' onclick='loadMainCategory($parent_id)'>All</button>";
        
        while($row = mysqli_fetch_assoc($result)) {
            echo "<button class='tab-btn' onclick='filterBySub(" . $row['id'] . ")'>" . $row['name'] . "</button>";
        }
    } else {
        echo "<p class='no-data-msg'>No subcategories found.</p>";
    }

?>