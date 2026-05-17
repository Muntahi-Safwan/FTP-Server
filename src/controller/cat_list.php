<?php
require_once('../model/t_category_model.php'); 

$categoryList = getFilter();
echo json_encode($categoryList);
?>