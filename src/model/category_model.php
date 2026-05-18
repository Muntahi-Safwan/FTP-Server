<?php

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
