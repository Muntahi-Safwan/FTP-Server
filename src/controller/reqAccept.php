<?php
require_once('../model/t_content_request_model.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    acceptRequest($id);
   
}

header("Location: ../view/reqShow.html");
exit(); 
?>