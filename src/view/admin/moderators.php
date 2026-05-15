<?php
// src/view/admin/moderators.php
require_once __DIR__ . '/../../includes/admin_check.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controller/admin_controller.php';

$moderators = getAllModerators($pdo);
$csrf = csrf_token();

$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$base = (strpos($script, '/src/view/') !== false) ? '../../../public/index.php' : 'index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrf) ?>">
    <title>Manage Moderators — FTP Server</title>
    <link rel="stylesheet" href="/FTP-Server/src/assets/css/admin.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>
    <?php require_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header">
            <div>
                <h1>Moderator Management</h1>
                <p class="lead">Add or remove moderator accounts. Deleted moderator's contents are reassigned to admin.</p>
            </div>
        </header>

        <div id="response"></div>

        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-error"><?= implode('<br>', array_map('htmlspecialchars', $_SESSION['errors'])); unset($_SESSION['errors']); ?></div>
        <?php endif; ?>

        <!-- Add Moderator Form -->
        <h2>Add New Moderator</h2>
        <div class="form-card" style="margin-bottom:32px;">
            <form method="POST" action="<?= $base ?>?page=admin/add_moderator" id="modForm">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                    <small id="emailMsg" style="font-size:12px;margin-top:4px;display:block;"></small>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="pwd" class="form-control" placeholder="Minimum 8 characters" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm" id="pwd2" class="form-control" placeholder="Re-enter password" required>
                    <small id="pwdMsg" style="font-size:12px;margin-top:4px;display:block;color:var(--danger);"></small>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">Register Moderator</button>
            </form>
        </div>

        <!-- Moderator List -->
        <h2>All Moderators</h2>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($moderators)): ?>
                        <tr><td colspan="4" style="text-align:center;color:var(--text-faint);padding:32px;">No moderators found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($moderators as $m): ?>
                        <tr id="mod-<?= $m['id'] ?>">
                            <td style="color:var(--text-faint);">#<?= $m['id'] ?></td>
                            <td style="color:var(--text-strong);font-weight:600;"><?= htmlspecialchars($m['name']) ?></td>
                            <td><?= htmlspecialchars($m['email']) ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-mod" data-id="<?= $m['id'] ?>">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Confirm Modal -->
    <div class="modal-overlay" id="delModal">
        <div class="modal-box">
            <h3>Delete Moderator?</h3>
            <p>This moderator's uploaded contents will be reassigned to admin. This action cannot be undone.</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" id="cancelDel">Cancel</button>
                <button class="btn btn-danger" id="confirmDel">Yes, Delete</button>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="/FTP-Server/src/assets/js/adminModerators.js"></script>
</body>
</html>