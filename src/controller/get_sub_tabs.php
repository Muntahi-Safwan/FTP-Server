<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "ftp_server");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['parent_id'])) {
    $parent_id = (int)$_GET['parent_id']; 

    $sql = "SELECT id, name FROM categories WHERE parent_id = $parent_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Create an "All" button to reset the view
        echo "<button class='tab-btn' onclick='loadMainCategory($parent_id)'>All</button>";
        
        // Loop through and create a button for each subcategory
        while($row = $result->fetch_assoc()) {
            echo "<button class='tab-btn' onclick='filterBySub(" . $row['id'] . ")'>" . $row['name'] . "</button>";
        }
    } else {
        echo "<p class='no-data-msg'>No subcategories found.</p>";
    }
}

$conn->close();
?>