<?php
session_start();
require_once __DIR__ . '/src/config/db.php';

$page = $_GET['page'] ?? 'home';
$ajax = $_GET['ajax'] ?? null;

// AJAX endpoints (Task 2)
if ($ajax) {
    require_once __DIR__ . '/src/controller/admin_controller.php';
    switch ($ajax) {
        case 'get_subcategories':
            ajaxGetSubcategories();
            break;
        case 'delete_moderator':
            deleteModeratorAjax();
            break;
        case 'delete_content':
            deleteContentAjax();
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Invalid AJAX endpoint']);
    }
    exit;
}

// Regular routing
switch ($page) {
    case 'login':
        require_once __DIR__ . '/src/controller/auth_controller.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            handleLogin($pdo);
        else
            showLogin();
        break;
    case 'register':
        require_once __DIR__ . '/src/controller/auth_controller.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            handleRegister($pdo);
        else
            showRegister();
        break;
    case 'logout':
        require_once __DIR__ . '/src/controller/auth_controller.php';
        handleLogout();
        break;
    case 'admin/dashboard':
        require_once __DIR__ . '/src/controller/admin_controller.php';
        showAdminDashboard();
        break;
    case 'admin/moderators':
        require_once __DIR__ . '/src/controller/admin_controller.php';
        showModerators();
        break;
    case 'admin/add_moderator':
        require_once __DIR__ . '/src/controller/admin_controller.php';
        addModerator();
        break;
    case 'admin/contents':
        require_once __DIR__ . '/src/controller/admin_controller.php';
        showAdminContents();
        break;
    case 'admin/add_content':
        require_once __DIR__ . '/src/controller/admin_controller.php';
        showAddContentForm();
        break;
    case 'admin/handle_add_content':
        require_once __DIR__ . '/src/controller/admin_controller.php';
        handleAddContent();
        break;
    case 'admin/edit_content':
        require_once __DIR__ . '/src/controller/admin_controller.php';
        showEditContentForm();
        break;
    case 'admin/handle_edit_content':
        require_once __DIR__ . '/src/controller/admin_controller.php';
        handleEditContent();
        break;
    case 'home':
    default:
        require_once __DIR__ . '/src/controller/home_controller.php';
        if (isset($_GET['cat']) && is_numeric($_GET['cat']))
            showCategory($pdo, (int)$_GET['cat']);
        else
            showHome($pdo);
        break;
}
?>