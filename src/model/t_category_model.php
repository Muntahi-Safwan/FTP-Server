<?php

require_once 'db.php';    


    function tamzSubtab($parent_id){
        $conn = getConnect();

        if ($parent_id !== "") {
            $sql = "SELECT id, name FROM categories WHERE parent_id = $parent_id";
            $result = mysqli_query($conn, $sql);
            return $result;
        }

        mysqli_close($conn);
    }

    //////////////////////////////////////////////


    function isParentIdNull($category_id) {
    $conn = getConnect();

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

function getFilter(){
    $conn = getConnect();

    $sql = "SELECT id, name FROM categories";
    $result = mysqli_query($conn, $sql);
    return $result;
    mysqli_close($conn);

}
?>