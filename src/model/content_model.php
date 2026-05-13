<?php

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