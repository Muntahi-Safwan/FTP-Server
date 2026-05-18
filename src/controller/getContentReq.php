<?php
require_once('../model/t_content_request_model.php');
$data=showRequest();
echo json_encode($data);
?>