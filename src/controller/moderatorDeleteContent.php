<?php

require_once __DIR__ . '/../includes/moderator_check.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../model/content_model.php';

header('Content-Type: application/json');

function respond($ok, $payload = []) {
    echo json_encode(array_merge(['ok' => $ok], $payload));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    respond(false, ['error' => 'Method not allowed.']);
}

$raw   = file_get_contents('php://input');
$json  = $raw ? json_decode($raw, true) : null;
$body  = is_array($json) ? $json : $_POST;

csrf_check($body['csrf'] ?? csrf_token_from_request());

$id = (int) ($body['id'] ?? 0);
if ($id <= 0) {
    respond(false, ['error' => 'Invalid content id.']);
}

$content = getContentById($pdo, $id);
if (!$content) {
    http_response_code(404);
    respond(false, ['error' => 'Content not found.']);
}

$deleted = deleteContentById($pdo, $id);
if (!$deleted) {
    respond(false, ['error' => 'Failed to delete content.']);
}

$absPath = dirname(__DIR__, 2) . '/public/' . $content['file_path'];
if (is_file($absPath)) {
    @unlink($absPath);
}

respond(true, ['message' => 'Content deleted successfully.']);
