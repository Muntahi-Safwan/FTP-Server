<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../model/user_model.php';

// ── Show register page ──────────────────────────────
function showRegister()
{
    require_once __DIR__ . '/../view/register.php';
}

//Handle register form submit
function handleRegister($pdo)
{
    $errors = [];
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm']  ?? '');
    $role     = trim($_POST['role']     ?? '');

    if (empty($name))
        $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Invalid email format.";
    if (strlen($password) < 8)
        $errors[] = "Password must be at least 8 characters.";
    if ($password !== $confirm)
        $errors[] = "Passwords do not match.";
    if (!in_array($role, ['admin', 'moderator']))
        $errors[] = "Please select a valid role.";
    if (emailExists($pdo, $email))
        $errors[] = "This email is already registered.";

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old']    = ['name' => $name, 'email' => $email, 'role' => $role];
        header("Location: index.php?page=register");
        exit;
    }

    createUser($pdo, $name, $email, $password, $role);
    $_SESSION['flash'] = "Registration successful! Please login.";
    header("Location: index.php?page=login");
    exit;
}

//Show login page
function showLogin()
{
    require_once __DIR__ . '/../view/login.php';
}

//Handle login form submit
function handleLogin($pdo)
{
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);

    $user = findUserByEmail($pdo, $email);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $_SESSION['errors'] = ["Invalid email or password."];
        header("Location: index.php?page=login");
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name']    = $user['name'];
    $_SESSION['role']    = $user['role'];

    if ($remember) {
        $token = bin2hex(random_bytes(32));
        setcookie('remember_token', $token, time() + (7 * 24 * 3600), '/');
    }

    header("Location: index.php?page=home");
    exit;
}

//Logout 
function handleLogout()
{
    session_destroy();
    setcookie('remember_token', '', time() - 3600, '/');
    header("Location: index.php?page=login");
    exit;
}
