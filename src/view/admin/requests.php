<?php
// src/view/admin/requests.php
require_once __DIR__ . '/../../includes/admin_check.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/request_model.php';

$requests = getAllRequests($pdo);
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
    <title>Member Requests — FTP Server</title>
    <link rel="stylesheet" href="/FTP-Server/src/assets/css/admin.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>
    <?php require_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header">
            <div>
                <h1>Member Requests</h1>
                <p class="lead">Review and update content requests submitted by members.</p>
            </div>
        </header>

        <div id="response"></div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Content Requested</th>
                        <th>Category</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($requests)): ?>
                        <tr><td colspan="7" style="text-align:center;color:var(--text-faint);padding:32px;">No requests found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($requests as $r): ?>
                        <tr id="req-<?= $r['id'] ?>">
                            <td style="color:var(--text-faint);">#<?= $r['id'] ?></td>
                            <td style="color:var(--text-strong);font-weight:600;"><?= htmlspecialchars($r['content_title']) ?></td>
                            <td><?= htmlspecialchars($r['category_requested'] ?? '—') ?></td>
                            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($r['message'] ?? '') ?></td>
                            <td><span class="badge badge-<?= htmlspecialchars($r['status']) ?>"><?= ucfirst($r['status']) ?></span></td>
                            <td style="color:var(--text-faint);"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
                            <td style="display:flex;gap:6px;">
                                <?php if ($r['status'] === 'pending'): ?>
                                    <button class="btn btn-sm is-success update-req" data-id="<?= $r['id'] ?>" data-status="fulfilled" style="background:var(--success-soft);color:var(--success);border:1px solid rgba(34,197,94,.25);">Fulfill</button>
                                    <button class="btn btn-sm btn-danger update-req" data-id="<?= $r['id'] ?>" data-status="rejected">Reject</button>
                                <?php else: ?>
                                    <span style="color:var(--text-faint);font-size:12px;">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script>
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    document.querySelectorAll('.update-req').forEach(btn => {
        btn.addEventListener('click', function () {
            const id     = this.dataset.id;
            const status = this.dataset.status;
            fetch('index.php?ajax=update_request_status', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id=' + id + '&status=' + status + '&csrf=' + encodeURIComponent(csrf)
            })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    const badge = document.querySelector('#req-' + id + ' .badge');
                    if (badge) {
                        badge.className = 'badge badge-' + status;
                        badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                    }
                    const cell = this.parentElement;
                    cell.innerHTML = '<span style="color:var(--text-faint);font-size:12px;">—</span>';
                }
            });
        });
    });
    </script>
</body>
</html>