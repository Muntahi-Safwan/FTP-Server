<?php

require_once 'db.php';    
function getTopLevelCategories($pdo) {
        $stmt = $pdo->prepare(
            "SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getSubCategories($pdo, $parentId) {
        $stmt = $pdo->prepare(
            "SELECT * FROM categories WHERE parent_id = ? ORDER BY name ASC"
        );
        $stmt->execute([$parentId]);
        return $stmt->fetchAll();
    }

    function getCategoryById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    function tamzSubtab($parent_id){
        $conn = getConnect();

        if ($parent_id !== "") {
            $sql = "SELECT id, name FROM categories WHERE parent_id = $parent_id";
            $result = mysqli_query($conn, $sql);
            return $result;
        }

        mysqli_close($conn);
    }
?>