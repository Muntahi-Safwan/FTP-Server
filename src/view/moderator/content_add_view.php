<?php
session_start();
require_once __DIR__ . '/../../includes/moderator_check.php';
require_once __DIR__ . '/../../model/category_model.php';

$categories = getTopLevelCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Content — FTP Server</title>
    <link rel="stylesheet" href="../../assets/css/moderator.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>

    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-content">
        <h1>Add New Content</h1>
        <div id="response"></div>

        <div class="form-card">
            <form id="addContentForm" method="post" onsubmit="return addContent()" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Content Title</label>
                    <input type="text" id="title" name="title" placeholder="Enter content title">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Write a brief description..."></textarea>
                </div>

                <div class="form-group">
                    <label for="categoryId">Category</label>
                    <select id="categoryId" name="categoryId">
                        <option value="">-- Select Category --</option>
                        <?php if ($categories): foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filePath">Upload File</label>
                    <input type="file" id="filePath" name="filePath">
                    <small style="color: var(--text-muted); display: block; margin-top: 6px; font-size: 12px;">Allowed: .mp4, .pdf, .exe, .zip, .rar, .iso, .mkv, .avi</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Content</button>
                    <a href="dashboard_view.php" class="btn" style="background: var(--bg-hover); color: var(--text-main); text-decoration: none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="../../assets/js/moderatorAddContent.js"></script>
</body>
</html>
