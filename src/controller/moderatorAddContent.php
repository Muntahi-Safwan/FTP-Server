<?php

require_once __DIR__ . '/../includes/moderator_check.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../model/content_model.php';
require_once __DIR__ . '/../model/category_model.php';

header('Content-Type: application/json');

function respond($ok, $payload = []) {
    echo json_encode(array_merge(['ok' => $ok], $payload));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    respond(false, ['error' => 'Method not allowed.']);
}

csrf_check(csrf_token_from_request());

$title       = trim($_POST['title']        ?? '');
$description = trim($_POST['description']  ?? '');
$categoryId  = (int) ($_POST['category_id'] ?? 0);

$errors = [];

if ($title === '' || strlen($title) > 255) {
    $errors['title'] = 'Title is required (max 255 chars).';
}
if ($description === '') {
    $errors['description'] = 'Description is required.';
}
if ($categoryId <= 0 || !getCategoryById($pdo, $categoryId)) {
    $errors['category_id'] = 'Please select a valid category.';
}

$file = $_FILES['content_file'] ?? null;
if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
    $errors['content_file'] = 'A file is required.';
} elseif ($file['error'] !== UPLOAD_ERR_OK) {
    $errors['content_file'] = 'File upload failed (code ' . $file['error'] . ').';
} elseif ($file['size'] > 200 * 1024 * 1024) {
    $errors['content_file'] = 'File must be under 200 MB.';
} else {
    $parts = explode('.', $file['name']);
    $ext   = strtolower($parts[count($parts) - 1]);
    $allowed = ['mp4', 'mkv', 'avi', 'mov', 'pdf', 'zip', 'rar', '7z', 'exe', 'iso', 'mp3'];
    if (!in_array($ext, $allowed, true)) {
        $errors['content_file'] = 'File type .' . $ext . ' is not allowed.';
    }
}

if ($errors) {
    respond(false, ['errors' => $errors]);
}

$uploadDir = dirname(__DIR__, 2) . '/public/uploads/contents/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$parts    = explode('.', $file['name']);
$ext      = strtolower($parts[count($parts) - 1]);
$filename = uniqid('content_', true) . '.' . $ext;

if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
    respond(false, ['error' => 'Failed to save uploaded file.']);
}

$relativePath = 'uploads/contents/' . $filename;
$uploaderId   = (int) $_SESSION['user_id'];

$newId = createContent($pdo, $title, $description, $categoryId, $relativePath, $uploaderId);

if (!$newId) {
    @unlink($uploadDir . $filename);
    respond(false, ['error' => 'Failed to save content record.']);
}

respond(true, ['id' => $newId, 'message' => 'Content added successfully.']);
