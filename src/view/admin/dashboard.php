<?php
// src/view/admin/dashboard.php
require_once __DIR__ . '/../../includes/admin_check.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controller/admin_controller.php';

$totalContents   = $pdo->query("SELECT COUNT(*) FROM contents")->fetchColumn();
$totalModerators = $pdo->query("SELECT COUNT(*) FROM users WHERE role='moderator'")->fetchColumn();
$totalCategories = getTotalCategoriesCount($pdo);
$pendingRequests = getPendingRequestsCount($pdo);

$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$base = (strpos($script, '/src/view/') !== false) ? '../../../public/index.php' : 'index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard — FTP Server</title>

    <link rel="stylesheet" href="/FTP-Server/src/assets/css/admin.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>
    <?php require_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header">
            <div>
                <h1>Admin Dashboard</h1>
                <p class="lead">Welcome back, <strong style="color:var(--text-strong)"><?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></strong>. Full control panel.</p>
            </div>
            <a href="<?= $base ?>?page=admin/add_content" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Upload Content
            </a>
        </header>

        <!-- Stats -->
        <section class="stats-grid">
            <div class="card">
                <div class="card-head">
                    <span class="label">Total Contents</span>
                    <span class="icon-bubble is-accent">
                        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </span>
                </div>
                <div class="value"><?= $totalContents ?></div>
                <div class="trend">Files on the server</div>
            </div>

            <div class="card">
                <div class="card-head">
                    <span class="label">Moderators</span>
                    <span class="icon-bubble is-violet">
                        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </span>
                </div>
                <div class="value"><?= $totalModerators ?></div>
                <div class="trend">Active moderator accounts</div>
            </div>

            <div class="card">
                <div class="card-head">
                    <span class="label">Categories</span>
                    <span class="icon-bubble is-cyan">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    </span>
                </div>
                <div class="value"><?= $totalCategories ?></div>
                <div class="trend">All categories & sub-categories</div>
            </div>

            <div class="card">
                <div class="card-head">
                    <span class="label">Pending Requests</span>
                    <span class="icon-bubble is-amber">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 14"/></svg>
                    </span>
                </div>
                <div class="value"><?= $pendingRequests ?></div>
                <div class="trend">Awaiting review</div>
            </div>
        </section>

        <h2>Quick Actions</h2>
        <section class="quick-links">
            <a href="<?= $base ?>?page=admin/moderators" class="quick-link-card">
                <span class="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </span>
                <div class="body">
                    <div class="title">Manage Moderators</div>
                    <div class="desc">Add or remove moderator accounts</div>
                </div>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>

            <a href="<?= $base ?>?page=admin/contents" class="quick-link-card">
                <span class="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                </span>
                <div class="body">
                    <div class="title">All Contents</div>
                    <div class="desc">View, edit and delete all media files</div>
                </div>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>

            <a href="<?= $base ?>?page=admin/add_content" class="quick-link-card">
                <span class="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </span>
                <div class="body">
                    <div class="title">Upload Content</div>
                    <div class="desc">Add new media file to the server</div>
                </div>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>

            <a href="<?= $base ?>?page=admin/requests" class="quick-link-card">
                <span class="icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                </span>
                <div class="body">
                    <div class="title">Member Requests</div>
                    <div class="desc">Review pending content requests</div>
                </div>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </section>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
</body>
</html>