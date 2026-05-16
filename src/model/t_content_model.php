<?php
require_once 'db.php';

function getHighlightedContents($pdo, $limit = 6) {
    $stmt = $pdo->prepare(
        "SELECT c.*, cat.name AS category_name
         FROM contents c
         LEFT JOIN categories cat ON c.category_id = cat.id
         ORDER BY c.download_count DESC, c.uploaded_at DESC
         LIMIT ?"
    );
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getContentsByCategory($pdo, $categoryId) {
    $stmt = $pdo->prepare(
        "SELECT c.*, cat.name AS category_name
         FROM contents c
         LEFT JOIN categories cat ON c.category_id = cat.id
         WHERE c.category_id = ? OR cat.parent_id = ?
         ORDER BY c.uploaded_at DESC"
    );
    $stmt->execute([$categoryId, $categoryId]);
    return $stmt->fetchAll();
}
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
?>