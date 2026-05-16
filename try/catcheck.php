<?php
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
