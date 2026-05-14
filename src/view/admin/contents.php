<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="contents-wrap">
    <h2>📄 All Uploaded Contents</h2>
    <?php if(isset($_SESSION['flash'])): ?>
        <div class="alert success"><?= $_SESSION['flash']; unset($_SESSION['flash']); ?></div>
    <?php endif; ?>
    <table class="content-table">
        <thead><tr><th>ID</th><th>Title</th><th>Category</th><th>Uploader</th><th>Downloads</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach($contents as $c): ?>
            <tr id="row-<?= $c['id'] ?>">
                <td><?= $c['id'] ?></td>
                <td><?= htmlspecialchars($c['title']) ?></td>
                <td><?= htmlspecialchars($c['category_name']) ?></td>
                <td><?= htmlspecialchars($c['uploader_name']) ?></td>
                <td><?= $c['download_count'] ?></td>
                <td>
                    <a href="index.php?page=admin/edit_content&id=<?= $c['id'] ?>" class="edit-btn">✏️ Edit</a>
                    <button class="del-btn" data-id="<?= $c['id'] ?>">🗑 Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
document.querySelectorAll('.del-btn').forEach(btn => {
    btn.onclick = function() {
        if(confirm('Delete this content permanently?')) {
            fetch('index.php?ajax=delete_content', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id=' + this.dataset.id
            })
            .then(r => r.json())
            .then(data => { if(data.success) document.getElementById('row-'+this.dataset.id).remove(); else alert('Error'); });
        }
    };
});
</script>
<style>
.contents-wrap{ max-width:1300px; margin:40px auto; background: rgba(0,0,0,0.4); border-radius: 32px; padding:30px; }
.content-table{ width:100%; border-collapse:collapse; color:white; }
.content-table th, .content-table td{ padding:12px; border-bottom:1px solid #444; }
.content-table th{ background:#ff4d6d; color:black; }
.edit-btn{ background:#3b82f6; padding:4px 12px; border-radius:20px; text-decoration:none; color:white; margin-right:8px; }
.del-btn{ background:#ef4444; border:none; padding:4px 12px; border-radius:20px; color:white; cursor:pointer; }
</style>
<?php require_once __DIR__ . '/../footer.php'; ?>