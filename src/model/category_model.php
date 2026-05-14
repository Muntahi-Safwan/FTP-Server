<?php
require_once __DIR__ . '/../config/db.php';

function dbConnection() {
    global $pdo;
    return $pdo;
}

function getTopLevelCategories() {
    $pdo = dbConnection();
    if (!$pdo) return false;

    $stmt = $pdo->prepare(
        "SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name ASC"
    );
    $stmt->execute();
    return $stmt->fetchAll();
}

function getSubCategories($parentId) {
    $pdo = dbConnection();
    if (!$pdo) return false;

    $stmt = $pdo->prepare(
        "SELECT * FROM categories WHERE parent_id = ? ORDER BY name ASC"
    );
    $stmt->execute([$parentId]);
    return $stmt->fetchAll();
}

function getCategoryById($id) {
    $pdo = dbConnection();
    if (!$pdo) return false;

    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}
