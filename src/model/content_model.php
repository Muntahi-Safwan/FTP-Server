<?php

function getHighlightedContents($pdo, $limit = 6)
{
    $stmt = $pdo->prepare(
        "SELECT c.*, cat.name AS category_name
         FROM contents c
         LEFT JOIN categories cat ON c.category_id = cat.id
         ORDER BY c.download_count DESC, c.uploaded_at DESC
         LIMIT ?"
    );
    $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getContentsByCategory($pdo, $categoryId)
{
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

function getAllContents($pdo, $filters = [])
{
    $sql = "SELECT c.*, cat.name AS category_name, u.name AS uploader_name
            FROM contents c
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN users u ON c.uploader_id = u.id";

    $where = [];
    $params = [];

    if (!empty($filters['category_id'])) {
        $where[] = "(c.category_id = ? OR cat.parent_id = ?)";
        $params[] = $filters['category_id'];
        $params[] = $filters['category_id'];
    }
    if (!empty($filters['search'])) {
        $where[] = "(c.title LIKE ? OR c.description LIKE ?)";
        $params[] = '%' . $filters['search'] . '%';
        $params[] = '%' . $filters['search'] . '%';
    }
    if ($where) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    $sql .= " ORDER BY c.uploaded_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getContentById($pdo, $id)
{
    $stmt = $pdo->prepare(
        "SELECT c.*, cat.name AS category_name, u.name AS uploader_name
         FROM contents c
         LEFT JOIN categories cat ON c.category_id = cat.id
         LEFT JOIN users u ON c.uploader_id = u.id
         WHERE c.id = ?"
    );
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createContent($pdo, $title, $description, $categoryId, $filePath, $uploaderId)
{
    $stmt = $pdo->prepare(
        "INSERT INTO contents (title, description, file_path, category_id, uploader_id, download_count, uploaded_at)
         VALUES (?, ?, ?, ?, ?, 0, NOW())"
    );
    $ok = $stmt->execute([$title, $description, $filePath, $categoryId, $uploaderId]);
    return $ok ? (int) $pdo->lastInsertId() : false;
}

function deleteContent($pdo, $id, $uploaderId)
{
    $stmt = $pdo->prepare("DELETE FROM contents WHERE id = ? AND uploader_id = ?");
    $stmt->execute([$id, $uploaderId]);
    return $stmt->rowCount() > 0;
}

function deleteContentById($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM contents WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0;
}

function getContentsByUploader($pdo, $uploaderId, $filters = [])
{
    $sql = "SELECT c.*, cat.name AS category_name
            FROM contents c
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE c.uploader_id = ?";
    $params = [$uploaderId];

    if (!empty($filters['category_id'])) {
        $sql .= " AND (c.category_id = ? OR cat.parent_id = ?)";
        $params[] = $filters['category_id'];
        $params[] = $filters['category_id'];
    }
    if (!empty($filters['search'])) {
        $sql .= " AND (c.title LIKE ? OR c.description LIKE ?)";
        $params[] = '%' . $filters['search'] . '%';
        $params[] = '%' . $filters['search'] . '%';
    }
    $sql .= " ORDER BY c.uploaded_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
