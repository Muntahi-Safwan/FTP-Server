<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../src/config/db.php';
require_once __DIR__ . '/../src/controller/auth_controller.php';


// ── AJAX: check email availability ──────────────────
if (isset($_GET['ajax']) && $_GET['ajax'] === 'check_email') {
    header('Content-Type: application/json');

    // Read the JSON string sent as POST body
    $raw  = $_POST['user'] ?? '{}';
    $data = json_decode($raw, true);

    $email  = trim($data['email'] ?? '');
    $exists = emailExists($pdo, $email) ? true : false;

    echo json_encode(['exists' => $exists]);
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
        require_once __DIR__ . '/../src/view/adminView.php';
        break;
    case 'modView':
        require_once __DIR__ . '/../src/view/adminView.php';
        break;

    case 'home':
    default:
        showHome($pdo);
        break;
}
?>


<!-- FTP-Server/public/index.php -->