<?php
function getConnect() {
    return mysqli_connect('localhost', 'root', '', 'ftp_server');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn = getConnect();
    
    // Update the status to 'fulfilled'
    $sql = "UPDATE content_requests SET status = 'fulfilled' WHERE id = '$id'";
    mysqli_query($conn, $sql);
    
    mysqli_close($conn);
}

// Redirect back to the HTML file
header("Location: reqShow.html");
exit(); // Always use exit after a header redirect
?>