<?php
require_once __DIR__ . '/../../includes/moderator_check.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/category_model.php';

$categories = getTopLevelCategories($pdo);
$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrf) ?>">
    <title>Add Content — FTP Server</title>
    <link rel="stylesheet" href="../../assets/css/moderator.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>

    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header">
            <div>
                <h1>Add New Content</h1>
                <p class="lead">Upload a media file. Choose a category, optionally pick a sub-category, then submit.</p>
            </div>
            <a href="dashboard_view.php" class="btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Back to dashboard
            </a>
        </header>

        <div id="response"></div>

        <div class="form-card">
            <form id="addContentForm" method="post" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

                <div class="form-group">
                    <label for="title">Content Title</label>
                    <input type="text" id="title" name="title" maxlength="255" placeholder="Enter content title">
                    <span class="field-error" data-error-for="title"></span>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Write a brief description..."></textarea>
                    <span class="field-error" data-error-for="description"></span>
                </div>

                <div class="form-group">
                    <label for="parentCategoryId">Category</label>
                    <select id="parentCategoryId" name="parent_category_id">
                        <option value="">-- Select Category --</option>
                        <?php if ($categories): foreach ($categories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <span class="field-error" data-error-for="category_id"></span>
                </div>

                <div class="form-group">
                    <label for="subCategoryId">Sub-category</label>
                    <select id="subCategoryId" name="category_id" disabled>
                        <option value="">-- Select a category first --</option>
                    </select>
                    <small style="color: var(--text-muted); display: block; margin-top: 6px; font-size: 12px;">
                        Leave on "(use top-level)" if no sub-category fits.
                    </small>
                </div>

                <div class="form-group">
                    <label for="contentFile">Upload File</label>
                    <input type="file" id="contentFile" name="content_file">
                    <small style="color: var(--text-muted); display: block; margin-top: 6px; font-size: 12px;">
                        Allowed: .mp4, .mkv, .avi, .mov, .pdf, .zip, .rar, .7z, .exe, .iso, .mp3 (max 200 MB)
                    </small>
                    <span class="field-error" data-error-for="content_file"></span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Add Content
                    </button>
                    <a href="dashboard_view.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="../../assets/js/moderatorAddContent.js"></script>
</body>
</html>
