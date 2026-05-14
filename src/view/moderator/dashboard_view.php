<?php
session_start();
require_once __DIR__ . '/../../includes/moderator_check.php';
require_once __DIR__ . '/../../model/content_model.php';
require_once __DIR__ . '/../../model/request_model.php';

$contents = getAllContents();
$requests = getAllRequests();
$totalContents = is_array($contents) ? count($contents) : 0;
$totalRequests = is_array($requests) ? count($requests) : 0;
$pendingRequests = is_array($requests) ? array_filter($requests, fn($r) => ($r['status'] ?? '') === 'pending') : [];
$totalPending = is_array($pendingRequests) ? count($pendingRequests) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Moderator Dashboard — FTP Server</title>
    <link rel="stylesheet" href="../../assets/css/moderator.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>

    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-content">
        <h1>Moderator Dashboard</h1>
        <div id="response"></div>

        <div class="stats-grid">
            <div class="card">
                <div class="label">Total Contents</div>
                <div class="value"><?= $totalContents ?></div>
            </div>
            <div class="card">
                <div class="label">Total Requests</div>
                <div class="value"><?= $totalRequests ?></div>
            </div>
            <div class="card">
                <div class="label">Pending Requests</div>
                <div class="value"><?= $totalPending ?></div>
            </div>
        </div>

        <h2>Quick Actions</h2>
        <div class="quick-links">
            <a href="content_add_view.php" class="quick-link-card">
                <div class="icon">+</div>
                <div>
                    <div class="title">Add New Content</div>
                    <div class="desc">Upload media files to the server</div>
                </div>
            </a>
            <a href="content_list_view.php" class="quick-link-card">
                <div class="icon">&#9776;</div>
                <div>
                    <div class="title">View All Contents</div>
                    <div class="desc">Browse, search and delete contents</div>
                </div>
            </a>
            <a href="requests_view.php" class="quick-link-card">
                <div class="icon">&#9993;</div>
                <div>
                    <div class="title">Manage Requests</div>
                    <div class="desc">Review pending member requests</div>
                </div>
            </a>
        </div>
    </div>

    <?php require_once __DIR__ . '/../footer.php'; ?>
</body>
</html>
