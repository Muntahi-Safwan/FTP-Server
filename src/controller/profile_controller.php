<?php
require_once __DIR__ . '../../config/db.php';
require_once __DIR__ . '../../model/user_model.php';

// ── Guard: must be logged in ────────────────────────
function requireAuth()
{
    if (empty($_SESSION['user_id'])) {
        header("Location: index.php?page=login");
        exit;
    }
}

// ── Show profile page ───────────────────────────────
function showProfile($pdo)
{
    requireAuth();
    /** @var array $user */
    $user = findUserById($pdo, $_SESSION['user_id']);
    require_once __DIR__ . '/../view/profile.php';
}
function requireRole($requiredRole)
{
    if (empty($_SESSION['user_id'])) {
        header("Location: index.php?page=login");
        exit;
    }
    if ($_SESSION['role'] !== $requiredRole) {
        http_response_code(403);
        die("403 Forbidden — You do not have permission to access this page.");
    }
}
// ── Handle profile update ───────────────────────────
function handleUpdateProfile($pdo)
{
    requireAuth();
    $errors  = [];
    $id      = $_SESSION['user_id'];
    $name    = trim($_POST['name']  ?? '');
    $email   = trim($_POST['email'] ?? '');

    if (empty($name))
        $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Invalid email format.";

    // ── Profile picture upload ──────────────────────
    $picture = null;
    if (!empty($_FILES['profile_picture']['name'])) {

        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        $mime    = mime_content_type($_FILES['profile_picture']['tmp_name']);

        if (!in_array($mime, $allowed)) {
            $errors[] = "Only JPG, PNG, GIF images are allowed.";
        } elseif ($_FILES['profile_picture']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Image must be under 2MB.";
        } else {

            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/profile/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $ext      = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;

            // Save to disk
            move_uploaded_file(
                $_FILES['profile_picture']['tmp_name'],
                $uploadDir . $filename

            );


            // Save to DB — relative to public/
            $picture = 'uploads/profile/' . $filename;
            // = uploads/filename.jpg
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: index.php?page=profile");
        exit;
    }

    updateUserProfile($pdo, $id, $name, $email, $picture);
    $_SESSION['name']  = $name;
    $_SESSION['flash'] = "Profile updated successfully!";
    header("Location: index.php?page=profile");
    exit;
}

// ── Handle password change ──────────────────────────
function handleChangePassword($pdo)
{
    requireAuth();
    $errors  = [];
    $id      = $_SESSION['user_id'];
    $current = trim($_POST['current_password'] ?? '');
    $new     = trim($_POST['new_password']     ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    /** @var array $user */
    $user = findUserById($pdo, $id);

    if (!password_verify($current, $user['password_hash']))
        $errors[] = "Current password is incorrect.";
    if (strlen($new) < 8)
        $errors[] = "New password must be at least 8 characters.";
    if ($new !== $confirm)
        $errors[] = "Passwords do not match.";

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: index.php?page=profile");
        exit;
    }

    updateUserPassword($pdo, $id, $new);
    $_SESSION['flash'] = "Password changed successfully!";
    header("Location: index.php?page=profile");
    exit;
}
