<?php
// src/model/admin_model.php

function getAllModerators($pdo) {
    $stmt = $pdo->prepare("SELECT id, name, email, profile_picture FROM users WHERE role = 'moderator' ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

function deleteUserById($pdo, $userId) {
    $stmt = $pdo->prepare("UPDATE contents SET uploader_id = 1 WHERE uploader_id = ?");
    $stmt->execute([$userId]);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'moderator'");
    return $stmt->execute([$userId]);
}

function getAllContentsWithUploader($pdo) {
    $stmt = $pdo->prepare("
        SELECT c.*, cat.name AS category_name, u.name AS uploader_name
        FROM contents c
        JOIN categories cat ON c.category_id = cat.id
        LEFT JOIN users u ON c.uploader_id = u.id
        ORDER BY c.uploaded_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getContentById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM contents WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addContent($pdo, $title, $desc, $filePath, $catId, $uploaderId) {
    $stmt = $pdo->prepare("
        INSERT INTO contents (title, description, file_path, category_id, uploader_id, download_count, uploaded_at)
        VALUES (?, ?, ?, ?, ?, 0, NOW())
    ");
    return $stmt->execute([$title, $desc, $filePath, $catId, $uploaderId]);
}

function updateContent($pdo, $id, $title, $desc, $catId, $filePath = null) {
    if ($filePath) {
        $stmt = $pdo->prepare("UPDATE contents SET title=?, description=?, category_id=?, file_path=? WHERE id=?");
        return $stmt->execute([$title, $desc, $catId, $filePath, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE contents SET title=?, description=?, category_id=? WHERE id=?");
        return $stmt->execute([$title, $desc, $catId, $id]);
    }
}

function deleteContentById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT file_path FROM contents WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetchColumn();
    if ($file && file_exists($file)) unlink($file);
    $stmt = $pdo->prepare("DELETE FROM contents WHERE id = ?");
    return $stmt->execute([$id]);
}

function getPendingRequestsCount($pdo) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM content_requests WHERE status = 'pending'");
    $stmt->execute();
    return $stmt->fetchColumn();
}

function getTotalCategoriesCount($pdo) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories");
    $stmt->execute();
    return $stmt->fetchColumn();
}
?>