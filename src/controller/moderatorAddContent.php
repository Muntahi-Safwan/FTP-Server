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

if (empty($_FILES['content_file']) || !isset($_FILES['content_file']['error']) || $_FILES['content_file']['error'] === UPLOAD_ERR_NO_FILE) {
    $errors['content_file'] = 'A file is required.';
} elseif ($_FILES['content_file']['error'] !== UPLOAD_ERR_OK) {
    $errors['content_file'] = 'File upload failed (code ' . $_FILES['content_file']['error'] . ').';
} else {
    $maxBytes = 200 * 1024 * 1024;
    if ($_FILES['content_file']['size'] > $maxBytes) {
        $errors['content_file'] = 'File must be under 200 MB.';
    }
    $allowedExt = ['mp4', 'mkv', 'avi', 'mov', 'pdf', 'zip', 'rar', '7z', 'exe', 'iso', 'mp3'];
    $allowedMime = [
        'video/mp4', 'video/x-matroska', 'video/x-msvideo', 'video/quicktime',
        'application/pdf', 'application/zip', 'application/x-zip-compressed',
        'application/x-rar-compressed', 'application/vnd.rar', 'application/x-7z-compressed',
        'application/x-msdownload', 'application/octet-stream', 'application/x-iso9660-image',
        'audio/mpeg',
    ];
    $ext = strtolower(pathinfo($_FILES['content_file']['name'], PATHINFO_EXTENSION));
    if (empty($errors['content_file']) && !in_array($ext, $allowedExt, true)) {
        $errors['content_file'] = 'File type .' . $ext . ' is not allowed.';
    }
    if (empty($errors['content_file'])) {
        $mime = function_exists('mime_content_type') ? mime_content_type($_FILES['content_file']['tmp_name']) : '';
        if ($mime && !in_array($mime, $allowedMime, true)) {
            $errors['content_file'] = 'File MIME type "' . $mime . '" is not allowed.';
        }
    }
}

if ($errors) {
    respond(false, ['errors' => $errors]);
}

$uploadDir = dirname(__DIR__, 2) . '/public/uploads/contents/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$ext      = strtolower(pathinfo($_FILES['content_file']['name'], PATHINFO_EXTENSION));
$filename = uniqid('content_', true) . '.' . $ext;

if (!move_uploaded_file($_FILES['content_file']['tmp_name'], $uploadDir . $filename)) {
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
