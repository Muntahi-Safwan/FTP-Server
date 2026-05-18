<?php
$role = $_SESSION['role'] ?? 'guest';

// ── Compute a URL prefix that points to public/index.php ──
// Works whether the current page is served by public/index.php directly
// or by a moderator view at src/view/moderator/*.php (loaded by file URL).
$script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
if (strpos($script, '/src/view/moderator/') !== false) {
    $base = '../../../public/index.php';
} elseif (strpos($script, '/src/view/') !== false) {
    $base = '../../public/index.php';
} else {
    $base = 'index.php';
}
?>
<nav style="background:#1a1a2e; padding:12px 24px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
    <a href="<?= htmlspecialchars($base) ?>?page=home" style="color:#e94560; font-size:20px; font-weight:bold; text-decoration:none;">
        📁 FTP Server
    </a>
    <div style="display:flex; gap:16px; align-items:center; flex-wrap:wrap;">

        <?php if ($role === 'admin'): ?>
            <a href="<?= htmlspecialchars($base) ?>?page=home" style="color:#fff; text-decoration:none;">Home</a>
            <a href="<?= htmlspecialchars($base) ?>?page=profile" style="color:#fff; text-decoration:none;">Profile</a>
            <a href="<?= htmlspecialchars($base) ?>?page=admin/dashboard" style="color:#e94560; text-decoration:none;">Admin Panel</a>
            <a href="<?= htmlspecialchars($base) ?>?page=logout" style="color:#aaa;  text-decoration:none;">Logout</a>

        <?php elseif ($role === 'moderator'): ?>
            <a href="<?= htmlspecialchars($base) ?>?page=home" style="color:#fff; text-decoration:none;">Home</a>
            <a href="<?= htmlspecialchars($base) ?>?page=profile" style="color:#fff; text-decoration:none;">Profile</a>
            <a href="<?= htmlspecialchars($base) ?>?page=moderator" style="color:#e94560; text-decoration:none;">Mod Panel</a>
            <a href="<?= htmlspecialchars($base) ?>?page=logout" style="color:#aaa;  text-decoration:none;">Logout</a>

        <?php else: ?>
            <!-- Guest/Member: browse only, no login link -->
            <a href="<?= htmlspecialchars($base) ?>?page=home" style="color:#fff; text-decoration:none;">Home</a>
            <a href="<?= htmlspecialchars($base) ?>?page=login"
                style="color:#fff; text-decoration:none; font-size:12px;">
                Staff Login
            </a>
        <?php endif; ?>

    </div>
</nav>

<!-- FTP-Server/src/view/navbar.php -->
