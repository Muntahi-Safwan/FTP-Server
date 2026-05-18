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

    function showRequest(){
        $conn = getConnect();

        
        $sql = "SELECT * FROM content_requests";
        $result = mysqli_query($conn, $sql);

        $data = array();

        if ($result && mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;

        mysqli_close($conn);
    }
    function acceptRequest($id){
         $conn = getConnect();
    
        
        $sql = "UPDATE content_requests SET status = 'fulfilled' WHERE id = '$id'";
        mysqli_query($conn, $sql);
        
        mysqli_close($conn);

    }
    function rejectRequest($id){
        $conn = getConnect();
    
        
        $sql = "UPDATE content_requests SET status = 'rejected' WHERE id = '$id'";
        mysqli_query($conn, $sql);
        
        mysqli_close($conn);
        
    }

?>