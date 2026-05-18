<?php
// Make sure this file contains your getConnect() and showtable() functions
require('../model/t_content_request_model.php');

// Check if standard POST data was sent
if (isset($_POST['title'])) {

    // Grab the data exactly how you normally would
    $requester_ip     = $_POST['requester_ip'];
    $content_title    = $_POST['title'];
    $category_requested = $_POST['category'];
    $message          = $_POST['message'];
    $status           = $_POST['status'];
    
    // Generate the current time
    $created_at       = date('Y-m-d H:i:s'); 

    // Assuming showtable() is defined in your required model file
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