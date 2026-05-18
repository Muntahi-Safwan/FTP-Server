<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../src/config/db.php';
require_once __DIR__ . '/../src/controller/auth_controller.php';
require_once __DIR__ . '/../src/controller/home_controller.php';
require_once __DIR__ . '/../src/controller/profile_controller.php';

// ── AJAX: check email availability ──────────────────
if (isset($_GET['ajax']) && $_GET['ajax'] === 'check_email') {
    header('Content-Type: application/json');
    $raw  = $_POST['user'] ?? '{}';
    $data = json_decode($raw, true);
    $email  = trim($data['email'] ?? '');
    $exists = emailExists($pdo, $email) ? true : false;
    echo json_encode(['exists' => $exists]);
    exit;
}

// ── AJAX: sub-categories for dependent dropdown ─────
if (isset($_GET['ajax']) && $_GET['ajax'] === 'subcategories') {
    require_once __DIR__ . '/../src/model/category_model.php';
    header('Content-Type: application/json');
    $parentId = (int) ($_GET['parent_id'] ?? 0);
    $rows = $parentId > 0 ? getSubCategories($pdo, $parentId) : [];
    echo json_encode($rows ?: []);
    exit;
}

// ── AJAX: Admin delete moderator ─────────────────────
if (isset($_GET['ajax']) && $_GET['ajax'] === 'delete_moderator') {
    requireRole('admin');
    require_once __DIR__ . '/../src/controller/admin_controller.php';
    deleteModeratorAjax();
    exit;
}

// ── AJAX: Admin delete content ───────────────────────
if (isset($_GET['ajax']) && $_GET['ajax'] === 'delete_content') {
    requireRole('admin');
    require_once __DIR__ . '/../src/controller/admin_controller.php';
    deleteContentAjax();
    exit;
}

// ── AJAX: Admin get subcategories ────────────────────
if (isset($_GET['ajax']) && $_GET['ajax'] === 'get_subcategories') {
    require_once __DIR__ . '/../src/controller/admin_controller.php';
    ajaxGetSubcategories();
    exit;
}

// ── AJAX: Admin update request status ───────────────
if (isset($_GET['ajax']) && $_GET['ajax'] === 'update_request_status') {
    requireRole('admin');
    require_once __DIR__ . '/../src/model/request_model.php';
    header('Content-Type: application/json');
    $id     = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if (!in_array($status, ['fulfilled', 'rejected'])) {
        echo json_encode(['ok' => false]);
        exit;
    }
    $stmt = $pdo->prepare("UPDATE content_requests SET status=? WHERE id=?");
    $ok   = $stmt->execute([$status, $id]);
    echo json_encode(['ok' => $ok]);
    exit;
}

// ── Page Router ──────────────────────────────────────
$page   = $_GET['page']   ?? 'home';
$action = $_GET['action'] ?? '';

switch ($page) {

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            handleRegister($pdo);
        else
            showRegister();
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            handleLogin($pdo);
        else
            showLogin();
        break;

    case 'logout':
        handleLogout();
        break;

    case 'profile':
        if ($action === 'update')
            handleUpdateProfile($pdo);
        elseif ($action === 'password')
            handleChangePassword($pdo);
        else
            showProfile($pdo);
        break;

    case 'category':
        $catId = intval($_GET['id'] ?? 0);
        showCategory($pdo, $catId);
        break;

    case 'adminView':
        requireRole('admin');
        require_once __DIR__ . '/../src/view/adminView.php';
        break;

    case 'modView':
        requireRole('moderator');
        require_once __DIR__ . '../../src/view/adminView.php';
    // ── Admin Routes ─────────────────────────────────

    case 'admin/dashboard':
        requireRole('admin');
        require_once __DIR__ . '/../src/controller/admin_controller.php';
        require_once __DIR__ . '/../src/view/admin/dashboard.php';
        break;

    case 'admin/moderators':
        requireRole('admin');
        require_once __DIR__ . '/../src/controller/admin_controller.php';
        require_once __DIR__ . '/../src/view/admin/moderators.php';
        break;

    case 'admin/add_moderator':
        requireRole('admin');
        require_once __DIR__ . '/../src/controller/admin_controller.php';
        addModerator();
        break;

    case 'admin/contents':
        requireRole('admin');
        require_once __DIR__ . '/../src/controller/admin_controller.php';
        require_once __DIR__ . '/../src/view/admin/contents.php';
        break;

    case 'admin/add_content':
        requireRole('admin');
        require_once __DIR__ . '/../src/controller/admin_controller.php';
        require_once __DIR__ . '/../src/view/admin/add_content.php';
        break;

    case 'admin/handle_add_content':
        requireRole('admin');
        require_once __DIR__ . '/../src/controller/admin_controller.php';
        handleAddContent();
        break;

    case 'admin/edit_content':
        requireRole('admin');
        require_once __DIR__ . '/../src/controller/admin_controller.php';
        require_once __DIR__ . '/../src/view/admin/edit_content.php';
        break;

    case 'admin/handle_edit_content':
        requireRole('admin');
        require_once __DIR__ . '/../src/controller/admin_controller.php';
        handleEditContent();
        break;

    case 'admin/requests':
        requireRole('admin');
        require_once __DIR__ . '/../src/controller/admin_controller.php';
        require_once __DIR__ . '/../src/view/admin/requests.php';
        break;

    // ── Moderator Route ──────────────────────────────

    case 'moderator':
        requireRole('moderator');
        header('Location: ../src/view/moderator/dashboard_view.php');
        exit;

    case 'home':
    default:
        showHome($pdo);
        break;
}
?>

<!-- FTP-Server/public/index.php -->