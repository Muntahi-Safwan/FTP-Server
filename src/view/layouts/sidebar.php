<?php
$currentFile = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <a href="dashboard_view.php" class="<?= $currentFile === 'dashboard_view.php' ? 'active' : '' ?>">Dashboard</a>
    <a href="content_add_view.php" class="<?= $currentFile === 'content_add_view.php' ? 'active' : '' ?>">Add Content</a>
    <a href="content_list_view.php" class="<?= $currentFile === 'content_list_view.php' ? 'active' : '' ?>">View Contents</a>
    <a href="requests_view.php" class="<?= $currentFile === 'requests_view.php' ? 'active' : '' ?>">Requests</a>
</div>
