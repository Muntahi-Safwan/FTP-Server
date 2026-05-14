<?php
require_once __DIR__ . '/../config/db.php';

function dbConnection() {
    global $pdo;
    return $pdo;
}

function getHighlightedContents($limit = 6) {
    $pdo = dbConnection();
    if (!$pdo) return false;

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

function getContentsByCategory($categoryId) {
    $pdo = dbConnection();
    if (!$pdo) return false;

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

function getAllContents($filters = []) {
    $pdo = dbConnection();
    if (!$pdo) return false;
    // TODO: fetch all contents with optional category filter
}

function getContentById($id) {
    $pdo = dbConnection();
    if (!$pdo) return false;
    // TODO: fetch single content by id
}

function createContent($title, $description, $categoryId, $filePath, $uploaderId) {
    $pdo = dbConnection();
    if (!$pdo) return false;
    // TODO: insert new content record
}

function deleteContent($id) {
    $pdo = dbConnection();
    if (!$pdo) return false;
    // TODO: delete content by id
}

function getContentsByUploader($uploaderId) {
    $pdo = dbConnection();
    if (!$pdo) return false;
    // TODO: fetch contents uploaded by specific moderator
}
