<?php

require_once __DIR__ . '/../includes/moderator_check.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../model/request_model.php';

header('Content-Type: application/json');

function respond($ok, $payload = []) {
    echo json_encode(array_merge(['ok' => $ok], $payload));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    respond(false, ['error' => 'Method not allowed.']);
}

$raw  = file_get_contents('php://input');
$json = $raw ? json_decode($raw, true) : null;
$body = is_array($json) ? $json : $_POST;

csrf_check($body['csrf'] ?? csrf_token_from_request());

$id     = (int) ($body['id']     ?? 0);
$status = trim((string) ($body['status'] ?? ''));

if ($id <= 0) {
    respond(false, ['error' => 'Invalid request id.']);
}
if (!in_array($status, ['fulfilled', 'rejected', 'pending'], true)) {
    respond(false, ['error' => 'Status must be pending, fulfilled, or rejected.']);
}
if (!getRequestById($pdo, $id)) {
    http_response_code(404);
    respond(false, ['error' => 'Request not found.']);
}

$ok = updateRequestStatus($pdo, $id, $status);
if (!$ok) {
    respond(false, ['error' => 'Failed to update request status.']);
}

respond(true, ['message' => 'Request status updated.', 'status' => $status]);
