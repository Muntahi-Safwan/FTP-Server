<?php
session_start();
require_once __DIR__ . '/../../includes/moderator_check.php';
require_once __DIR__ . '/../../model/request_model.php';

$requests = getAllRequests();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Content Requests — FTP Server</title>
    <link rel="stylesheet" href="../../assets/css/moderator.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>

    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-content">
        <h1>Content Requests</h1>
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
                    <?php if ($requests && is_array($requests)): foreach ($requests as $r): ?>
                    <tr data-status="<?= htmlspecialchars($r['status'] ?? 'pending') ?>">
                        <td><?= $r['id'] ?></td>
                        <td><?= htmlspecialchars($r['requester_ip'] ?? $r['member_name'] ?? 'Guest') ?></td>
                        <td><?= htmlspecialchars($r['content_title'] ?? $r['request_text'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['category_requested'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['message'] ?? '') ?></td>
                        <td>
                            <span class="badge badge-<?= htmlspecialchars($r['status'] ?? 'pending') ?>">
                                <?= htmlspecialchars($r['status'] ?? 'pending') ?>
                            </span>
                        </td>
                        <td><?= $r['created_at'] ?? '' ?></td>
                        <td>
                            <?php if (($r['status'] ?? 'pending') === 'pending'): ?>
                                <button class="btn btn-success btn-sm" onclick="return updateRequestStatus('<?= $r['id'] ?>', 'fulfilled')">Fulfill</button>
                                <button class="btn btn-danger btn-sm" onclick="return updateRequestStatus('<?= $r['id'] ?>', 'rejected')">Reject</button>
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size: 13px;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="8" class="empty-state">
                            <div class="icon">&#9993;</div>
                            <div>No requests found.</div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

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
