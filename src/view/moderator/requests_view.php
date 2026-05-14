<?php
require_once __DIR__ . '/../../includes/moderator_check.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/request_model.php';

$requests = getAllRequests($pdo);
$csrf     = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrf) ?>">
    <title>Content Requests — FTP Server</title>
    <link rel="stylesheet" href="../../assets/css/moderator.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>

    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header">
            <div>
                <h1>Content Requests</h1>
                <p class="lead">Review pending requests from members. Mark each as fulfilled or rejected.</p>
            </div>
        </header>

        <div id="response"></div>

        <div class="filter-bar">
            <select id="statusFilter" onchange="filterRequests()">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="fulfilled">Fulfilled</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <div class="table-wrap">
            <table id="requestsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Requester</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($requests && is_array($requests) && count($requests) > 0): foreach ($requests as $r): ?>
                    <tr data-status="<?= htmlspecialchars($r['status'] ?? 'pending') ?>" data-row-id="<?= (int)$r['id'] ?>">
                        <td><?= (int)$r['id'] ?></td>
                        <td><?= htmlspecialchars($r['requester_ip'] ?? 'Guest') ?></td>
                        <td><?= htmlspecialchars($r['content_title'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['category_requested'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['message'] ?? '') ?></td>
                        <td class="status-cell">
                            <span class="badge badge-<?= htmlspecialchars($r['status'] ?? 'pending') ?>">
                                <?= htmlspecialchars($r['status'] ?? 'pending') ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($r['created_at'] ?? '') ?></td>
                        <td class="action-cell">
                            <?php if (($r['status'] ?? 'pending') === 'pending'): ?>
                                <button class="btn btn-success btn-sm" onclick="return updateRequestStatus(<?= (int)$r['id'] ?>, 'fulfilled')">Fulfill</button>
                                <button class="btn btn-danger btn-sm" onclick="return updateRequestStatus(<?= (int)$r['id'] ?>, 'rejected')">Reject</button>
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size: 13px;">&mdash;</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="8" class="empty-state">
                            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.35; margin-bottom:12px;"><path d="M4 6h16v12H4z"/><polyline points="4 6 12 13 20 6"/></svg>
                            <div>No requests yet.</div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="../../assets/js/moderatorRequest.js"></script>
    <script>
        function filterRequests() {
            const status = document.getElementById('statusFilter').value.toLowerCase();
            const rows = document.querySelectorAll('#requestsTable tbody tr');

            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status')?.toLowerCase() || '';
                row.style.display = (status === '' || rowStatus === status) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
