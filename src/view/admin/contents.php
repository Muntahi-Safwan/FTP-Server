<?php
// src/view/admin/contents.php
require_once __DIR__ . '/../../includes/admin_check.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/admin_model.php';
require_once __DIR__ . '/../../model/content_model.php';
require_once __DIR__ . '/../../model/category_model.php';
require_once __DIR__ . '/../../model/user_model.php';

$contents = getAllContentsWithUploader($pdo);
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
    <title>All Contents — FTP Server</title>
    <link rel="stylesheet" href="/FTP-Server/src/assets/css/admin.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>
    <?php require_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header">
            <div>
                <h1>All Contents</h1>
                <p class="lead">View, edit and delete all uploaded media files.</p>
            </div>
            <a href="<?= $base ?>?page=admin/add_content" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Upload New
            </a>
        </header>

        <div id="response"></div>

        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
        <?php endif; ?>

        <div class="toolbar">
            <input type="text" id="searchInput" placeholder="Search by title..." style="min-width:220px;">
        </div>

        <div class="table-wrap">
            <table class="data-table" id="contentTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Uploader</th>
                        <th>Downloads</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($contents)): ?>
                        <tr><td colspan="7" style="text-align:center;color:var(--text-faint);padding:32px;">No contents found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($contents as $c): ?>
                        <tr id="row-<?= $c['id'] ?>" data-title="<?= strtolower(htmlspecialchars($c['title'])) ?>">
                            <td style="color:var(--text-faint);">#<?= $c['id'] ?></td>
                            <td style="color:var(--text-strong);font-weight:600;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($c['title']) ?></td>
                            <td><span class="badge badge-pending"><?= htmlspecialchars($c['category_name']) ?></span></td>
                            <td><?= htmlspecialchars($c['uploader_name'] ?? '—') ?></td>
                            <td><?= $c['download_count'] ?></td>
                            <td style="color:var(--text-faint);"><?= date('d M Y', strtotime($c['uploaded_at'])) ?></td>
                            <td style="display:flex;gap:6px;align-items:center;">
                                <a href="<?= $base ?>?page=admin/edit_content&id=<?= $c['id'] ?>" class="btn btn-secondary btn-sm">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                <button class="btn btn-danger btn-sm del-btn" data-id="<?= $c['id'] ?>">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
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
            <h3>Delete Content?</h3>
            <p>The file will be permanently deleted from the server and database. This cannot be undone.</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" id="cancelDel">Cancel</button>
                <button class="btn btn-danger" id="confirmDel">Yes, Delete</button>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="/FTP-Server/src/assets/js/adminContents.js"></script>
</body>
</html>