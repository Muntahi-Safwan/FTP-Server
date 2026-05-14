<?php
// src/controller/admin_controller.php – Task 2 (Self-contained, no missing functions)
require_once __DIR__ . '/../config/db.php';

// ========== Helper functions (defined here to avoid errors) ==========
function emailExists($pdo, $email) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function createUser($pdo, $name, $email, $password, $role) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, created_at) VALUES (?, ?, ?, ?, NOW())");
    return $stmt->execute([$name, $email, $hash, $role]);
}

function getTopLevelCategories($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name ASC");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getSubCategoriesByParent($pdo, $parentId) {
    $stmt = $pdo->prepare("SELECT id, name FROM categories WHERE parent_id = ? ORDER BY name ASC");
    $stmt->execute([$parentId]);
    return $stmt->fetchAll();
}

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
    $totalContents = $pdo->query("SELECT COUNT(*) FROM contents")->fetchColumn();
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
    $errors = [];
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (empty($name)) $errors[] = "Name required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
    if (strlen($password) < 8) $errors[] = "Password min 8 characters";
    if ($password !== $confirm) $errors[] = "Passwords do not match";
    if (emailExists($pdo, $email)) $errors[] = "Email already registered";

    if (empty($errors)) {
        createUser($pdo, $name, $email, $password, 'moderator');
        $_SESSION['flash'] = "✅ Moderator added successfully!";
    } else {
        $_SESSION['errors'] = $errors;
    }
    header("Location: index.php?page=admin/moderators");
    exit;
}

function deleteModeratorAjax() {
    adminGuard();
    global $pdo;
    $id = $_POST['id'] ?? 0;
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
    $errors = [];
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $catId = (int)($_POST['category_id'] ?? 0);
    $uploaderId = $_SESSION['user_id'];

    if (empty($title)) $errors[] = "Title required";
    if ($catId <= 0) $errors[] = "Select a valid category";

    $filePath = '';
    if (isset($_FILES['content_file']) && $_FILES['content_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['content_file'];
        $allowedExt = ['mp4','iso','exe','zip','pdf','jpg','png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt)) $errors[] = "Extension not allowed";
        if ($file['size'] > 50 * 1024 * 1024) $errors[] = "File too large (max 50MB)";
        if (empty($errors)) {
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
            $target = 'uploads/contents/' . $safeName;
            if (move_uploaded_file($file['tmp_name'], $target)) {
                $filePath = $target;
            } else {
                $errors[] = "Failed to move uploaded file";
            }
        }
    } else {
        $errors[] = "Please select a file";
    }

    if (empty($errors)) {
        addContent($pdo, $title, $desc, $filePath, $catId, $uploaderId);
        $_SESSION['flash'] = "✅ Content uploaded!";
        header("Location: index.php?page=admin/contents");
        exit;
    } else {
        $_SESSION['errors'] = $errors;
        header("Location: index.php?page=admin/add_content");
        exit;
    }
}

function showEditContentForm() {
    adminGuard();
    global $pdo;
    $id = (int)($_GET['id'] ?? 0);
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
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $catId = (int)($_POST['category_id'] ?? 0);
    $errors = [];

    if (empty($title)) $errors[] = "Title required";
    if ($catId <= 0) $errors[] = "Category required";

    $filePath = null;
    if (isset($_FILES['content_file']) && $_FILES['content_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['content_file'];
        $allowedExt = ['mp4','iso','exe','zip','pdf'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt)) $errors[] = "Extension not allowed";
        if ($file['size'] > 50 * 1024 * 1024) $errors[] = "File too large";
        if (empty($errors)) {
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
            $target = 'uploads/contents/' . $safeName;
            if (move_uploaded_file($file['tmp_name'], $target)) {
                $old = getContentById($pdo, $id);
                if ($old && file_exists($old['file_path'])) unlink($old['file_path']);
                $filePath = $target;
            } else {
                $errors[] = "Upload failed";
            }
        }
    }

    if (empty($errors)) {
        updateContent($pdo, $id, $title, $desc, $catId, $filePath);
        $_SESSION['flash'] = "✅ Content updated!";
    } else {
        $_SESSION['errors'] = $errors;
    }
    header("Location: index.php?page=admin/contents");
    exit;
}

function deleteContentAjax() {
    adminGuard();
    global $pdo;
    $id = $_POST['id'] ?? 0;
    $result = deleteContentById($pdo, $id);
    header('Content-Type: application/json');
    echo json_encode(['success' => $result]);
    exit;
}

function ajaxGetSubcategories() {
    global $pdo;
    $parentId = (int)($_GET['parent_id'] ?? 0);
    $subs = getSubCategoriesByParent($pdo, $parentId);
    header('Content-Type: application/json');
    echo json_encode($subs);
    exit;
}
?>