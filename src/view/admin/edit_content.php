<?php
// src/view/admin/edit_content.php
require_once __DIR__ . '/../../includes/admin_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../model/category_model.php';

$id = (int)($_GET['id'] ?? 0);

// Direct query to get content
$stmt = $pdo->prepare("SELECT * FROM contents WHERE id = ?");
$stmt->execute([$id]);
$content = $stmt->fetch();

if (!$content) {
    header("Location: /FTP-Server/public/index.php?page=admin/contents");
    exit;
}

// Get all categories flat list
$allCats = $pdo->query("SELECT * FROM categories ORDER BY parent_id ASC, name ASC")->fetchAll();

$csrf = function_exists('csrf_token') ? csrf_token() : '';
$base = 'index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Content — FTP Server</title>
    <link rel="stylesheet" href="/FTP-Server/src/assets/css/admin.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>
    <?php require_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header">
            <div>
                <h1>Edit Content</h1>
                <p class="lead">Update content details or replace the file.</p>
            </div>
            <a href="<?= $base ?>?page=admin/contents" class="btn btn-secondary">← Back to Contents</a>
        </header>

        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <?= implode('<br>', array_map('htmlspecialchars', $_SESSION['errors'])); ?>
                <?php unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="<?= $base ?>?page=admin/handle_edit_content" enctype="multipart/form-data" id="editForm">
                <input type="hidden" name="id" value="<?= $content['id'] ?>">
                <?php if ($csrf): ?>
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                <?php endif; ?>

                <!-- Title -->
                <div class="form-group">
                    <label>Title <span style="color:var(--accent)">*</span></label>
                    <input type="text" name="title" class="form-control"
                           value="<?= htmlspecialchars($content['title']) ?>" required>
                    <small id="titleErr" style="color:var(--danger);font-size:12px;"></small>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control"><?= htmlspecialchars($content['description'] ?? '') ?></textarea>
                </div>

                <!-- Category — flat list with optgroup -->
                <div class="form-group">
                    <label>Category <span style="color:var(--accent)">*</span></label>
                    <select name="category_id" class="form-control" required>
                        <option value="">— Select Category —</option>
                        <?php
                        // Separate parents and children
                        $parents  = [];
                        $children = [];
                        foreach ($allCats as $cat) {
                            if ($cat['parent_id'] === null || $cat['parent_id'] == 0) {
                                $parents[] = $cat;
                            } else {
                                $children[$cat['parent_id']][] = $cat;
                            }
                        }

                        foreach ($parents as $parent):
                            // If this parent has children, show as optgroup
                            if (!empty($children[$parent['id']])):
                        ?>
                            <optgroup label="<?= htmlspecialchars($parent['name']) ?>">
                                <?php foreach ($children[$parent['id']] as $sub): ?>
                                    <option value="<?= $sub['id'] ?>"
                                        <?= $sub['id'] == $content['category_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($sub['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php else: ?>
                            <!-- Parent has no children, show as direct option -->
                            <option value="<?= $parent['id'] ?>"
                                <?= $parent['id'] == $content['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($parent['name']) ?>
                            </option>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </select>
                </div>

                <!-- Replace File -->
                <div class="form-group">
                    <label>Replace File <span style="color:var(--text-faint)">(optional)</span></label>
                    <label class="file-drop" for="fileInput2">
                        <input type="file" id="fileInput2" name="content_file"
                               accept=".mp4,.iso,.exe,.zip,.pdf,.jpg,.png">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                             style="color:var(--accent);margin-bottom:6px;">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        <p id="fileLabel2" style="margin:0;font-size:13.5px;">
                            Leave empty to keep current file
                        </p>
                        <p style="margin:4px 0 0;font-size:12px;color:var(--text-faint);">
                            Current: <?= htmlspecialchars(basename($content['file_path'] ?? 'No file')) ?>
                        </p>
                    </label>
                    <small id="fileErr2" style="color:var(--danger);font-size:12px;"></small>
                </div>

                <button type="submit" class="btn btn-primary"
                        style="width:100%;justify-content:center;padding:12px;">
                    Save Changes
                </button>
            </form>
        </div>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>

    <script>
    // File name preview
    document.getElementById('fileInput2').addEventListener('change', function () {
        if (this.files[0]) {
            document.getElementById('fileLabel2').textContent = '📎 ' + this.files[0].name;
        }
    });

    // JS Validation
    document.getElementById('editForm').addEventListener('submit', function (e) {
        let ok = true;
        const title    = this.querySelector('[name="title"]').value.trim();
        const titleErr = document.getElementById('titleErr');
        const fileErr2 = document.getElementById('fileErr2');
        const file     = document.getElementById('fileInput2').files[0];

        if (!title) {
            titleErr.textContent = 'Title is required';
            ok = false;
        } else {
            titleErr.textContent = '';
        }

        if (file) {
            const allowed = ['mp4','iso','exe','zip','pdf','jpg','png'];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowed.includes(ext)) {
                fileErr2.textContent = 'File type not allowed';
                ok = false;
            } else if (file.size > 50 * 1024 * 1024) {
                fileErr2.textContent = 'File exceeds 50MB';
                ok = false;
            } else {
                fileErr2.textContent = '';
            }
        }

        if (!ok) e.preventDefault();
    });
    </script>
</body>
</html>