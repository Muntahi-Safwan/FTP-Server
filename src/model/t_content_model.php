<?php
require_once 'db.php';

function tamzContent($main_id, $sub_id) {
    $conn = getConnect();

    $sql = "";

    if ($main_id !== "") {
        $main_id = (int)$_GET['main_id'];
        
        $sql = "SELECT contents.* FROM contents 
                JOIN categories ON contents.category_id = categories.id 
                WHERE categories.parent_id = $main_id";

    } else if ($sub_id !== "") {
        $sub_id = (int)$_GET['sub_id'];
        $sql = "SELECT * FROM contents WHERE category_id = $sub_id";
    }

    $result = mysqli_query($conn, $sql);
    return $result;

    mysqli_close($conn);
}

///////////////////////////////////////////////////////////

function searchResult($choice, $keyword) {
    $conn = getConnect();

    $keyword = mysqli_real_escape_string($conn, $keyword);
    $filter_search = isset($_SESSION['filter_search']) ? (int)$_SESSION['filter_search'] : 0;

    $base_sql = "SELECT contents.title, contents.download_count, contents.file_path 
                 FROM contents 
                 LEFT JOIN categories ON contents.category_id = categories.id 
                 WHERE contents.title LIKE '%$keyword%'";

    if ($choice == 0) {
        $sql = $base_sql;
    } else if ($choice == 1) {
        $sql = $base_sql . " AND (contents.category_id = $filter_search OR categories.parent_id = $filter_search)";
    } else if ($choice == 2) {
        $sql = $base_sql . " AND contents.category_id = $filter_search";
    }

    $result = mysqli_query($conn, $sql);
    $data = array();

    if ($result && mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    mysqli_close($conn);
    return $data;
}


function downloadInc($path) {
    $conn = getConnect();
    $sql = "UPDATE contents SET download_count = download_count + 1 WHERE file_path = '$path'";
    mysqli_query($conn, $sql);
    
    mysqli_close($conn);
}



?>