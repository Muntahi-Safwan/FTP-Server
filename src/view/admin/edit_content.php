<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="upload-wrap">
    <h2>✏️ Edit Content</h2>
    <?php if(isset($_SESSION['errors'])): ?>
        <div class="alert error"><?= implode('<br>', $_SESSION['errors']); unset($_SESSION['errors']); ?></div>
    <?php endif; ?>
    <form method="POST" action="index.php?page=admin/handle_edit_content" enctype="multipart/form-data" class="glass-upload">
        <input type="hidden" name="id" value="<?= $content['id'] ?>">
        <input type="text" name="title" value="<?= htmlspecialchars($content['title']) ?>" required>
        <textarea name="description" rows="4"><?= htmlspecialchars($content['description']) ?></textarea>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php foreach($topCategories as $cat): ?>
                <optgroup label="<?= htmlspecialchars($cat['name']) ?>">
                <?php
                $subs = getSubCategoriesByParent($pdo, $cat['id']);
                foreach($subs as $sub):
                ?>
                    <option value="<?= $sub['id'] ?>" <?= $sub['id'] == $content['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($sub['name']) ?></option>
                <?php endforeach; ?>
                </optgroup>
            <?php endforeach; ?>
        </select>
        <input type="file" name="content_file" accept=".mp4,.iso,.exe,.zip,.pdf">
        <small>Leave empty to keep current file</small>
        <button type="submit">Save Changes</button>
    </form>
</div>
<script>
document.querySelector('.glass-upload').addEventListener('submit', function(e) {
    let file = this.querySelector('input[type="file"]').files[0];
    if(file && file.size > 50 * 1024 * 1024) { e.preventDefault(); alert('File max 50MB'); }
});
</script>
<style>
.upload-wrap{ max-width:700px; margin:50px auto; }
.glass-upload{ background: rgba(0,0,0,0.6); backdrop-filter: blur(10px); border-radius: 32px; padding:40px; }
.glass-upload input, .glass-upload textarea, .glass-upload select{ width:100%; padding:12px; margin:10px 0; background:#0f0f1f; border:1px solid #333; border-radius: 40px; color:white; }
.glass-upload button{ background: linear-gradient(95deg, #ff4d6d, #b5179e); width:100%; padding:14px; border:none; border-radius: 40px; color:white; font-weight:bold; cursor:pointer; }
</style>
<?php require_once __DIR__ . '/../footer.php'; ?>