<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "ftp_server");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "";

// Check if we are searching by a MAIN category (Sidebar click)
if (isset($_GET['main_id'])) {
    $main_id = (int)$_GET['main_id'];
    // Join categories table to find all contents under this parent ID
    $sql = "SELECT contents.* FROM contents 
            JOIN categories ON contents.category_id = categories.id 
            WHERE categories.parent_id = $main_id";

// Check if we are filtering by a SUB category (Tab click)
} else if (isset($_GET['sub_id'])) {
    $sub_id = (int)$_GET['sub_id'];
    // Direct match for the category_id
    $sql = "SELECT * FROM contents WHERE category_id = $sub_id";
}

// If a query was set, execute it and display results
if ($sql !== "") {
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='content-item'>";
            echo "<h4 class='content-title'>" . htmlspecialchars($row['title']) . "</h4>";
            echo "<p class='content-desc'>" . htmlspecialchars($row['description']) . "</p>";
            // Display the file path 
            echo "<small class='content-path'>" . htmlspecialchars($row['file_path']) . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p class='no-data-msg'>No contents available in this category.</p>";
    }
}

$conn->close();
?>