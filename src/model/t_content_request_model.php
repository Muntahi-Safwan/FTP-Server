<?php 
    require('db.php');
    function showtable($requester_ip, $content_title, $category_requested, $message, $status, $created_at){
        $conn=getConnect();
        $sql = "INSERT INTO content_requests (requester_ip, content_title, category_requested, message, status, created_at) 
                VALUES ('$requester_ip', '$content_title', '$category_requested', '$message', '$status', '$created_at')";

        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            return false;
        }

        mysqli_close($conn);
    }

?>