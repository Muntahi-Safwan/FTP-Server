<?php

function getConnect() {
    return mysqli_connect('localhost', 'root', '', 'ftp_server');
}

// Check if standard POST data was sent
if (isset($_POST['title'])) {
    $conn = getConnect();

    // Grab the data exactly how you normally would
    $requester_ip     = $_POST['requester_ip'];
    $content_title    = $_POST['title'];
    $category_requested = $_POST['category'];
    $message          = $_POST['message'];
    $status           = $_POST['status'];
    
    // Generate the current time
    $created_at       = date('Y-m-d H:i:s'); 

    // Insert into the database
    $sql = "INSERT INTO content_requests (requester_ip, content_title, category_requested, message, status, created_at) 
            VALUES ('$requester_ip', '$content_title', '$category_requested', '$message', '$status', '$created_at')";

    if (mysqli_query($conn, $sql)) {
        echo "The form has been submitted and inserted.";
    } else {
        echo "Database error: " . mysqli_error($conn);
    }

    mysqli_close($conn);

} else {
    echo "No data received.";
}

?>