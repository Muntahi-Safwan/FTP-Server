<?php
session_start();
require('../model/t_content_model.php'); //////////////////////////////////change
require('../model/t_category_model.php');

$choice = sqlChoice();
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";

$result = searchResult($choice, $keyword); //////////////////////////////////change

header('Content-Type: application/json'); 
echo json_encode($result);

function sqlChoice() {
    $choice = 0; 
    
    if (!isset($_SESSION['filter_search']) || $_SESSION['filter_search'] == 0) {
        $choice = 0;
    } else if (isParentIdNull($_SESSION['filter_search'])) { //////////////////////////////////change
        $choice = 1;
    } else {
        $choice = 2;
    }
    
    return $choice;
}
?>