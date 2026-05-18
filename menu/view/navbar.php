<?php
$role = $_SESSION['role'] ?? 'guest';
?>
<nav style="background:#1a1a2e; padding:12px 24px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
    <a href="index.php?page=home" style="color:#e94560; font-size:20px; font-weight:bold; text-decoration:none;">
        📁 FTP Server
    </a>
    <div style="display:flex; gap:16px; align-items:center; flex-wrap:wrap;">

        <?php if ($role === 'admin'): ?>
            <a href="index.php?page=home" style="color:#fff; text-decoration:none;">Home</a>
            <a href="index.php?page=profile" style="color:#fff; text-decoration:none;">Profile</a>
            <a href="index.php?page=admin" style="color:#e94560; text-decoration:none;">Admin Panel</a>
            <a href="index.php?page=logout" style="color:#aaa;  text-decoration:none;">Logout</a>

        <?php elseif ($role === 'moderator'): ?>
            <a href="index.php?page=home" style="color:#fff; text-decoration:none;">Home</a>
            <a href="index.php?page=profile" style="color:#fff; text-decoration:none;">Profile</a>
            <a href="index.php?page=moderator" style="color:#e94560; text-decoration:none;">Mod Panel</a>
            <a href="index.php?page=logout" style="color:#aaa;  text-decoration:none;">Logout</a>

        <?php else: ?>
            <!-- Guest/Member: browse only, no login link -->
            <a href="index.php?page=home" style="color:#fff; text-decoration:none;">Home</a>
        <?php endif; ?>

    </div>
</nav>

<!-- FTP-Server/src/view/navbar.php -->