<?php
// src/controller/admin_controller.php – Task 2 (23-52092-2)

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../model/user_model.php';
require_once __DIR__ . '/../model/category_model.php';
require_once __DIR__ . '/../model/content_model.php';
require_once __DIR__ . '/../model/admin_model.php';
require_once __DIR__ . '/../model/request_model.php';

// ========== ADMIN GUARD ==========
function adminGuard() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: index.php?page=home");
        exit;
    }
}

// ========== DASHBOARD ==========
function showAdminDashboard() {
    adminGuard();
    global $pdo;
    $totalContents   = $pdo->query("SELECT COUNT(*) FROM contents")->fetchColumn();
    $totalModerators = $pdo->query("SELECT COUNT(*) FROM users WHERE role='moderator'")->fetchColumn();
    $totalCategories = getTotalCategoriesCount($pdo);
    $pendingRequests = getPendingRequestsCount($pdo);
    require_once __DIR__ . '/../view/admin/dashboard.php';
}

// ========== MODERATORS ==========
function showModerators() {
    adminGuard();
    global $pdo;
    $moderators = getAllModerators($pdo);
    require_once __DIR__ . '/../view/admin/moderators.php';
}

function addModerator() {
    adminGuard();
    global $pdo;
    $errors   = [];
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (empty($name))                              $errors[] = "Name required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
    if (strlen($password) < 8)                     $errors[] = "Password min 8 characters";
    if ($password !== $confirm)                    $errors[] = "Passwords do not match";
    if (emailExists($pdo, $email))                 $errors[] = "Email already registered";

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, created_at) VALUES (?, ?, ?, 'moderator', NOW())");
        $stmt->execute([$name, $email, $hash]);
        $_SESSION['flash'] = "Moderator added successfully!";
    } else {
        $_SESSION['errors'] = $errors;
    }
    header("Location: index.php?page=admin/moderators");
    exit;
}

function deleteModeratorAjax() {
    adminGuard();
    global $pdo;
    $id     = (int)($_POST['id'] ?? 0);
    $result = deleteUserById($pdo, $id);
    header('Content-Type: application/json');
    echo json_encode(['success' => $result]);
    exit;
}

// ========== CONTENTS ==========
function showAdminContents() {
    adminGuard();
    global $pdo;
    $contents = getAllContentsWithUploader($pdo);
    require_once __DIR__ . '/../view/admin/contents.php';
}

function showAddContentForm() {
    adminGuard();
    global $pdo;
    $topCategories = getTopLevelCategories($pdo);
    require_once __DIR__ . '/../view/admin/add_content.php';
}

function handleAddContent() {
    adminGuard();
    global $pdo;
    $errors     = [];
    $title      = trim($_POST['title'] ?? '');
    $desc       = trim($_POST['description'] ?? '');
    $catId      = (int)($_POST['category_id'] ?? 0);
    $uploaderId = $_SESSION['user_id'];

    if (empty($title))  $errors[] = "Title required";
    if ($catId <= 0)    $errors[] = "Select a valid category";

    $filePath = '';
    if (isset($_FILES['content_file']) && $_FILES['content_file']['error'] === UPLOAD_ERR_OK) {
        $file       = $_FILES['content_file'];
        $allowedExt = ['mp4','iso','exe','zip','pdf','jpg','png'];
        $ext        = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt))        $errors[] = "Extension not allowed";
        if ($file['size'] > 50 * 1024 * 1024)    $errors[] = "File too large (max 50MB)";

        if (empty($errors)) {
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
            $uploadDir = __DIR__ . '/../../public/uploads/contents/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $target = $uploadDir . $safeName;
            if (move_uploaded_file($file['tmp_name'], $target))
                $filePath = 'uploads/contents/' . $safeName;
            else
                $errors[] = "Failed to move uploaded file";
        }
    } else {
        $errors[] = "Please select a file";
    }

    if (empty($errors)) {
        createContent($pdo, $title, $desc, $catId, $filePath, $uploaderId);
        $_SESSION['flash'] = "Content uploaded!";
        header("Location: index.php?page=admin/contents");
    } else {
        $_SESSION['errors'] = $errors;
        header("Location: index.php?page=admin/add_content");
    }
    exit;
}

function showEditContentForm() {
    adminGuard();
    global $pdo;
    $id      = (int)($_GET['id'] ?? 0);
    $content = getContentById($pdo, $id);
    if (!$content) {
        $_SESSION['errors'] = ["Content not found"];
        header("Location: index.php?page=admin/contents");
        exit;
    }
    $topCategories = getTopLevelCategories($pdo);
    require_once __DIR__ . '/../view/admin/edit_content.php';
}

function handleEditContent() {
    adminGuard();
    global $pdo;
    $id    = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $catId = (int)($_POST['category_id'] ?? 0);
    $errors = [];

    if (empty($title)) $errors[] = "Title required";
    if ($catId <= 0)   $errors[] = "Category required";

    $filePath = null;
    if (isset($_FILES['content_file']) && $_FILES['content_file']['error'] === UPLOAD_ERR_OK) {
        $file       = $_FILES['content_file'];
        $allowedExt = ['mp4','iso','exe','zip','pdf','jpg','png'];
        $ext        = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt))      $errors[] = "Extension not allowed";
        if ($file['size'] > 50 * 1024 * 1024) $errors[] = "File too large";

        if (empty($errors)) {
            $safeName  = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
            $uploadDir = __DIR__ . '/../../public/uploads/contents/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $target = $uploadDir . $safeName;
            if (move_uploaded_file($file['tmp_name'], $target)) {
                $old = getContentById($pdo, $id);
                if ($old) {
                    $oldPath = __DIR__ . '/../../public/' . $old['file_path'];
                    if (file_exists($oldPath)) unlink($oldPath);
                }
                $filePath = 'uploads/contents/' . $safeName;
            } else {
                $errors[] = "Upload failed";
            }
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE contents SET title=?, description=?, category_id=?, file_path=COALESCE(?, file_path) WHERE id=?");
$stmt->execute([$title, $desc, $catId, $filePath, $id]);
        $_SESSION['flash'] = "Content updated!";
    } else {
        $_SESSION['errors'] = $errors;
    }
    header("Location: index.php?page=admin/contents");
    exit;
}

function deleteContentAjax() {
    adminGuard();
    global $pdo;
    $id     = (int)($_POST['id'] ?? 0);
    $result = deleteContent($pdo, $id, 1);
    header('Content-Type: application/json');
    echo json_encode(['success' => $result]);
    exit;
}

function ajaxGetSubcategories() {
    global $pdo;
    $parentId = (int)($_GET['parent_id'] ?? 0);
    $subs     = $parentId > 0 ? getSubCategories($pdo, $parentId) : [];
    header('Content-Type: application/json');
    echo json_encode($subs);
    exit;
}