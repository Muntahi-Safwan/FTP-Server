<?php
require_once __DIR__ . '/../../includes/moderator_check.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/content_model.php';
require_once __DIR__ . '/../../model/request_model.php';

$uploaderId   = (int) $_SESSION['user_id'];
$myContents   = getContentsByUploader($pdo, $uploaderId);
$allRequests  = getAllRequests($pdo);
$topContents  = getHighlightedContents($pdo, 3);

$totalMyContents  = is_array($myContents) ? count($myContents) : 0;
$totalRequests    = is_array($allRequests) ? count($allRequests) : 0;
$totalPending     = is_array($allRequests)
    ? count(array_filter($allRequests, fn($r) => ($r['status'] ?? '') === 'pending'))
    : 0;

$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrf) ?>">
    <title>Moderator Dashboard — FTP Server</title>
    <link rel="stylesheet" href="../../assets/css/moderator.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header">
            <div>
                <h1>Moderator Dashboard</h1>
                <p class="lead">Welcome back, <strong style="color: var(--text-strong);"><?= htmlspecialchars($_SESSION['name'] ?? 'Moderator') ?></strong>. Here's what's happening on the server today.</p>
            </div>
            <a href="content_add_view.php" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Upload New
            </a>
        </header>

        <div id="response"></div>

        <section class="stats-grid">
            <div class="card">
                <div class="card-head">
                    <span class="label">My Uploads</span>
                    <span class="icon-bubble is-accent">
                        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </span>
                </div>
                <div class="value"><?= $totalMyContents ?></div>
                <div class="trend">Files you have contributed</div>
            </div>

            <div class="card">
                <div class="card-head">
                    <span class="label">Total Requests</span>
                    <span class="icon-bubble is-violet">
                        <svg viewBox="0 0 24 24"><path d="M4 6h16v12H4z"/><polyline points="4 6 12 13 20 6"/></svg>
                    </span>
                </div>
                <div class="value"><?= $totalRequests ?></div>
                <div class="trend">All-time member requests</div>
            </div>

            <div class="card">
                <div class="card-head">
                    <span class="label">Pending Requests</span>
                    <span class="icon-bubble is-amber">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 14"/></svg>
                    </span>
                </div>
                <div class="value"><?= $totalPending ?></div>
                <div class="trend">Awaiting your response</div>
            </div>
        </section>

        <section class="dashboard-section">
            <div class="section-head">
                <h2>Most Downloaded</h2>
                <a href="content_list_view.php" class="btn btn-sm">View all</a>
            </div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th style="width:110px">Downloads</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($topContents && count($topContents) > 0): foreach ($topContents as $i => $c): ?>
                        <tr>
                            <td><span class="rank rank-<?= $i + 1 ?>"><?= $i + 1 ?></span></td>
                            <td><?= htmlspecialchars($c['title'] ?? '') ?></td>
                            <td><?= htmlspecialchars($c['category_name'] ?? '—') ?></td>
                            <td><?= (int)($c['download_count'] ?? 0) ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="4" class="empty-state" style="padding: 28px;">No downloads yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="dashboard-section">
            <h2>Quick Actions</h2>
            <div class="action-bar">
                <a href="content_add_view.php" class="action-item">
                    <span class="action-icon is-accent">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </span>
                    <span class="action-label">Add Content</span>
                </a>
                <a href="content_list_view.php" class="action-item">
                    <span class="action-icon is-violet">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                    </span>
                    <span class="action-label">All Contents</span>
                </a>
                <a href="requests_view.php" class="action-item">
                    <span class="action-icon is-cyan">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    </span>
                    <span class="action-label">Manage Requests</span>
                </a>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
</body>
</html>
