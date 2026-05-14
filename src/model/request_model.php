<?php

function getAllRequests($pdo, $status = null) {
    if ($status !== null && $status !== '') {
        $stmt = $pdo->prepare(
            "SELECT * FROM content_requests WHERE status = ? ORDER BY created_at DESC"
        );
        $stmt->execute([$status]);
    } else {
        $stmt = $pdo->prepare(
            "SELECT * FROM content_requests ORDER BY created_at DESC"
        );
        $stmt->execute();
    }
    return $stmt->fetchAll();
}

function getRequestById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM content_requests WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function updateRequestStatus($pdo, $id, $status) {
    $allowed = ['pending', 'fulfilled', 'rejected'];
    if (!in_array($status, $allowed, true)) {
        return false;
    }
    $stmt = $pdo->prepare("UPDATE content_requests SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    return $stmt->rowCount() > 0;
}

function countRequestsByStatus($pdo, $status) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM content_requests WHERE status = ?");
    $stmt->execute([$status]);
    return (int) $stmt->fetchColumn();
}
