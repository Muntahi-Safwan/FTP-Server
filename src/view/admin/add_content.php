<?php
// src/view/admin/add_content.php
require_once __DIR__ . '/../../includes/admin_check.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controller/admin_controller.php';

$topCategories = getTopLevelCategories($pdo);
$csrf = csrf_token();

$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$base = (strpos($script, '/src/view/') !== false) ? '../../../public/index.php' : 'index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrf) ?>">
    <title>Upload Content — FTP Server</title>
    <link rel="stylesheet" href="/FTP-Server/src/assets/css/admin.css">
</head>
<body>
    <?php require_once __DIR__ . '/../navbar.php'; ?>
    <?php require_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header">
            <div>
                <h1>Upload New Content</h1>
                <p class="lead">Add a new media file to the FTP server.</p>
            </div>
        </header>

        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-error"><?= implode('<br>', array_map('htmlspecialchars', $_SESSION['errors'])); unset($_SESSION['errors']); ?></div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="<?= $base ?>?page=admin/handle_add_content" enctype="multipart/form-data" id="uploadForm">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

                <div class="form-group">
                    <label>Title <span style="color:var(--accent)">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Enter content title" required>
                    <small id="titleErr" style="color:var(--danger);font-size:12px;"></small>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" placeholder="Optional description..."></textarea>
                </div>

                <div class="form-group">
                    <label>Main Category <span style="color:var(--accent)">*</span></label>
                    <select name="parent_category" id="parent_cat" class="form-control" required>
                        <option value="">— Select Main Category —</option>
                        <?php foreach ($topCategories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Sub-Category <span style="color:var(--accent)">*</span></label>
                    <select name="category_id" id="sub_cat" class="form-control" required>
                        <option value="">— First select main category —</option>
                    </select>
                    <small id="catLoader" style="display:none;color:var(--text-muted);font-size:12px;">Loading...</small>
                </div>

                <div class="form-group">
                    <label>File <span style="color:var(--accent)">*</span></label>
                    <label class="file-drop" id="fileDrop" for="fileInput">
                        <input type="file" id="fileInput" name="content_file" accept=".mp4,.iso,.exe,.zip,.pdf,.jpg,.png" required>
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--accent);margin-bottom:8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <p id="fileLabel" style="margin:0;font-size:13.5px;">Click or drag file here</p>
                        <p style="margin:4px 0 0;font-size:12px;color:var(--text-faint);">Allowed: .mp4, .iso, .exe, .zip, .pdf, .jpg, .png — Max 50MB</p>
                    </label>
                    <small id="fileErr" style="color:var(--danger);font-size:12px;"></small>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Upload Content
                </button>
            </form>
        </div>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>
    <script>
    // Subcategory AJAX
    document.getElementById('parent_cat').addEventListener('change', function () {
        const parent = this.value;
        const sub    = document.getElementById('sub_cat');
        const loader = document.getElementById('catLoader');
        if (!parent) {
            sub.innerHTML = '<option value="">— First select main category —</option>';
            return;
        }
        loader.style.display = 'block';
        fetch('index.php?ajax=get_subcategories&parent_id=' + parent)
            .then(r => r.json())
            .then(data => {
                sub.innerHTML = '<option value="">— Select Sub-category —</option>';
                data.forEach(s => {
                    sub.innerHTML += `<option value="${s.id}">${s.name}</option>`;
                });
            })
            .finally(() => loader.style.display = 'none');
    });

    // File drag & drop UI
    const fileDrop  = document.getElementById('fileDrop');
    const fileInput = document.getElementById('fileInput');
    const fileLabel = document.getElementById('fileLabel');
    const fileErr   = document.getElementById('fileErr');

    fileInput.addEventListener('change', function () {
        if (this.files[0]) {
            fileLabel.textContent = '📎 ' + this.files[0].name;
        }
    });

    ['dragover','dragenter'].forEach(ev => fileDrop.addEventListener(ev, e => { e.preventDefault(); fileDrop.classList.add('dragover'); }));
    ['dragleave','drop'].forEach(ev => fileDrop.addEventListener(ev, () => fileDrop.classList.remove('dragover')));
    fileDrop.addEventListener('drop', e => {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        if (file) { fileInput.files = e.dataTransfer.files; fileLabel.textContent = '📎 ' + file.name; }
    });

    // JS Validation
    document.getElementById('uploadForm').addEventListener('submit', function (e) {
        let ok = true;
        const title = this.querySelector('[name="title"]').value.trim();
        const titleErr = document.getElementById('titleErr');
        if (!title) { titleErr.textContent = 'Title is required'; ok = false; } else { titleErr.textContent = ''; }

        const file = fileInput.files[0];
        const allowed = ['mp4','iso','exe','zip','pdf','jpg','png'];
        if (!file) {
            fileErr.textContent = 'Please select a file';
            ok = false;
        } else {
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowed.includes(ext)) { fileErr.textContent = 'File type not allowed'; ok = false; }
            else if (file.size > 50 * 1024 * 1024) { fileErr.textContent = 'File exceeds 50MB limit'; ok = false; }
            else { fileErr.textContent = ''; }
        }
        if (!ok) e.preventDefault();
    });
    </script>
</body>
</html>