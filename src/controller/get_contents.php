<?php
require('../model/t_content_model.php');
$main_id = isset($_GET['main_id']) ? $_GET['main_id'] : "";
$sub_id = isset($_GET['sub_id']) ? $_GET['sub_id'] : "";
$result = tamzContent($main_id, $sub_id);

$data = array();

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
}

echo json_encode($data);
?>