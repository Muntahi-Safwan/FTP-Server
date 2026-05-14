<?php
session_start();
require_once __DIR__ . '/../../includes/moderator_check.php';
require_once __DIR__ . '/../../model/content_model.php';
require_once __DIR__ . '/../../model/category_model.php';

$contents = getAllContents();
$categories = getTopLevelCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Contents — FTP Server</title>
    <link rel="stylesheet" href="../../assets/css/moderator.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>

    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-content">
        <h1>All Uploaded Contents</h1>
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
                        <th>Uploader</th>
                        <th>Downloads</th>
                        <th>Uploaded At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($contents && is_array($contents)): foreach ($contents as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= htmlspecialchars($c['title'] ?? '') ?></td>
                        <td data-category="<?= htmlspecialchars($c['category_name'] ?? '') ?>"><?= htmlspecialchars($c['category_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['uploader_id'] ?? '') ?></td>
                        <td><?= $c['download_count'] ?? 0 ?></td>
                        <td><?= $c['uploaded_at'] ?? '' ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="return deleteContent('<?= $c['id'] ?>')">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="icon">&#128194;</div>
                            <div>No contents found.</div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

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
