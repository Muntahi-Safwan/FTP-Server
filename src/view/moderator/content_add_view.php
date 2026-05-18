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
                <p class="lead">Upload a media file and fill in the details below.</p>
            </div>
            <a href="dashboard_view.php" class="btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Back
            </a>
        </header>

        <div id="response"></div>

        <div class="form-card">
            <form id="addContentForm" method="post" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

                <div class="form-row">
                    <div class="form-group form-group-lg">
                        <label for="title">Content Title <span class="required">*</span></label>
                        <input type="text" id="title" name="title" class="form-control" maxlength="255" placeholder="Enter a clear, descriptive title" required>
                        <span class="field-error" data-error-for="title"></span>
                    </div>

                    <div class="form-group">
                        <label for="parentCategoryId">Category <span class="required">*</span></label>
                        <select id="parentCategoryId" name="parent_category_id" class="form-control" required>
                            <option value="">Select category</option>
                            <?php if ($categories): foreach ($categories as $cat): ?>
                                <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                        <span class="field-error" data-error-for="category_id"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description <span class="required">*</span></label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Briefly describe what this content is about..." required></textarea>
                    <span class="field-error" data-error-for="description"></span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="subCategoryId">Sub-category</label>
                        <select id="subCategoryId" name="category_id" class="form-control" disabled>
                            <option value="">Select a category first</option>
                        </select>
                        <small class="field-hint">Leave on "(use top-level)" if no sub-category fits.</small>
                    </div>

                    <div class="form-group">
                        <label>Max file size</label>
                        <div class="info-pill">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            200 MB limit
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Upload File <span class="required">*</span></label>
                    <label class="file-drop" id="fileDrop" for="contentFile">
                        <input type="file" id="contentFile" name="content_file" accept=".mp4,.mkv,.avi,.mov,.pdf,.zip,.rar,.7z,.exe,.iso,.mp3" required>
                        <div class="file-drop-main">
                            <svg class="file-drop-icon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            <span class="file-drop-text">Drop file here or click to browse</span>
                        </div>
                        <span class="file-drop-types">Supported: MP4, MKV, AVI, MOV, PDF, ZIP, RAR, 7Z, EXE, ISO, MP3</span>
                    </label>
                    <div class="file-selected" id="fileSelected" style="display:none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                        <span id="fileSelectedName"></span>
                        <button type="button" class="file-selected-clear" id="fileSelectedClear" title="Remove">&times;</button>
                    </div>
                    <span class="field-error" data-error-for="content_file"></span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Add Content
                    </button>
                    <a href="dashboard_view.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script src="../../assets/js/moderatorAddContent.js"></script>
</body>
</html>
