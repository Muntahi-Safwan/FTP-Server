<?php
require_once __DIR__ . '/../../includes/moderator_check.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/content_model.php';
require_once __DIR__ . '/../../model/category_model.php';

$uploaderId = (int) $_SESSION['user_id'];
$contents   = getContentsByUploader($pdo, $uploaderId);
$categories = getTopLevelCategories($pdo);
$csrf       = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrf) ?>">
    <title>My Contents — FTP Server</title>
    <link rel="stylesheet" href="../../assets/css/moderator.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>

    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header">
            <div>
                <h1>My Uploaded Contents</h1>
                <p class="lead">Search, filter, and manage the files you've contributed to the server.</p>
            </div>
            <a href="content_add_view.php" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Upload New
            </a>
        </header>

        <div id="response"></div>

        <div class="filter-bar">
            <input type="text" id="searchInput" placeholder="Search by title..." onkeyup="filterTable()">
            <select id="categoryFilter" onchange="filterTable()">
                <option value="">All Categories</option>
                <?php if ($categories): foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['name']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>

        <div class="table-wrap">
            <table id="contentsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Downloads</th>
                        <th>Uploaded At</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($contents && is_array($contents) && count($contents) > 0): foreach ($contents as $c): ?>
                    <tr>
                        <td><?= (int)$c['id'] ?></td>
                        <td><?= htmlspecialchars($c['title'] ?? '') ?></td>
                        <td data-category="<?= htmlspecialchars($c['category_name'] ?? '') ?>"><?= htmlspecialchars($c['category_name'] ?? '') ?></td>
                        <td><?= (int)($c['download_count'] ?? 0) ?></td>
                        <td><?= htmlspecialchars($c['uploaded_at'] ?? '') ?></td>
                        <td>
                            <a href="../../../public/<?= htmlspecialchars($c['file_path'] ?? '') ?>" target="_blank" rel="noopener">View</a>
                        </td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="return deleteContent(<?= (int)$c['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.35; margin-bottom:12px;"><path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-7l-2-2H5a2 2 0 0 0-2 2z"/></svg>
                            <div>No contents uploaded yet. <a href="content_add_view.php">Add your first one</a>.</div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="../../assets/js/moderatorContentList.js"></script>
    <script>
        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const catFilter = document.getElementById('categoryFilter').value.toLowerCase();
            const rows = document.querySelectorAll('#contentsTable tbody tr');

            rows.forEach(row => {
                const title = row.cells[1]?.textContent.toLowerCase() || '';
                const category = row.cells[2]?.getAttribute('data-category')?.toLowerCase() || '';

                const matchSearch = title.includes(search);
                const matchCat = catFilter === '' || category === catFilter;

                row.style.display = (matchSearch && matchCat) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
