<?php

require('../model/t_content_request_model.php');


if (isset($_POST['title'])) {

    
    $requester_ip     = $_POST['requester_ip'];
    $content_title    = $_POST['title'];
    $category_requested = $_POST['category'];
    $message          = $_POST['message'];
    $status           = $_POST['status'];
    
    
    $created_at       = date('Y-m-d H:i:s'); 

    
    $flag = showtable($requester_ip, $content_title, $category_requested, $message, $status, $created_at);
    
    if($flag){
        echo "Inserted Succesfully";
    }else{
        echo "failed to insert";
    }

} else {
    echo "No data received.";
}

?>