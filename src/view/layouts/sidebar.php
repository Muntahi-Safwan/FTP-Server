<?php
$currentFile = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-section">Workspace</div>

    <a href="dashboard_view.php" class="<?= $currentFile === 'dashboard_view.php' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
        Dashboard
    </a>

    <div class="sidebar-section">Content</div>

    <a href="content_add_view.php" class="<?= $currentFile === 'content_add_view.php' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        Add Content
    </a>

    <a href="content_list_view.php" class="<?= $currentFile === 'content_list_view.php' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24"><path d="M3 7h18"/><path d="M3 12h18"/><path d="M3 17h12"/></svg>
        My Contents
    </a>

    <div class="sidebar-section">Members</div>

    <a href="requests_view.php" class="<?= $currentFile === 'requests_view.php' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24"><path d="M4 6h16v12H4z"/><polyline points="4 6 12 13 20 6"/></svg>
        Requests
    </a>
</aside>
