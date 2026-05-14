<?php
require_once __DIR__ . '/../config/db.php';

function dbConnection() {
    global $pdo;
    return $pdo;
}

function getAllRequests($status = null) {
    $pdo = dbConnection();
    if (!$pdo) return false;
    // TODO: fetch all requests, optionally filtered by status
}

function getRequestById($id) {
    $pdo = dbConnection();
    if (!$pdo) return false;
    // TODO: fetch single request by id
}

function updateRequestStatus($id, $status) {
    $pdo = dbConnection();
    if (!$pdo) return false;
    // TODO: update request status (pending/fulfilled/rejected)
}
