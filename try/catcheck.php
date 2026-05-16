<?php

function searchResult($choice, $keyword) {
    $conn = mysqli_connect('localhost', 'root', '', 'ftp_server');
    
    if (!$conn) {
        return [];
    }

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

function isParentIdNull($category_id) {
    $conn = mysqli_connect('localhost', 'root', '', 'ftp_server');
    
    if (!$conn) {
        return false;
    }

    $id = (int)$category_id;
    $sql = "SELECT parent_id FROM categories WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    $is_null = false;
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (is_null($row['parent_id'])) {
            $is_null = true;
        }
    }
    
    mysqli_close($conn);
    return $is_null;
} 

?>