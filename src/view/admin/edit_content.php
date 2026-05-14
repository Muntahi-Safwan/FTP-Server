<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="upload-wrap">
    <h2>✏️ Edit Content</h2>
    <form method="POST" action="index.php?page=admin/handle_edit_content" enctype="multipart/form-data" class="glass-upload">
        <input type="hidden" name="id" value="<?= $content['id'] ?>">
        <input type="text" name="title" value="<?= htmlspecialchars($content['title']) ?>" required>
        <textarea name="description" rows="4"><?= htmlspecialchars($content['description']) ?></textarea>
        <select name="category_id" required>
            <?php foreach(getTopLevelCategories($pdo) as $cat): ?>
                <optgroup label="<?= htmlspecialchars($cat['name']) ?>">
                <?php foreach(getSubCategories($pdo, $cat['id']) as $sub): ?>
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
<?php require_once __DIR__ . '/../footer.php'; ?>