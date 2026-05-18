<?php
require_once('../model/t_content_request_model.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    rejectRequest($id);
    
}


header("Location: ../view/reqShow.html");
exit(); 
?>