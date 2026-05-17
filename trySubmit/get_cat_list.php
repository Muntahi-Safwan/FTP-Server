<?php

function getConnect() {
    return mysqli_connect('localhost', 'root', '', 'ftp_server');
}

function getFilter(){
    $conn = getConnect();
    $sql = "SELECT id, name FROM categories";
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

$categoryList = getFilter();
echo json_encode($categoryList);

?>