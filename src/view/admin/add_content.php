<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="upload-wrap">
    <h2>🚀 Upload New Content</h2>
    <?php if(isset($_SESSION['errors'])): ?>
        <div class="alert error"><?= implode('<br>', $_SESSION['errors']); unset($_SESSION['errors']); ?></div>
    <?php endif; ?>
    <form method="POST" action="index.php?page=admin/handle_add_content" enctype="multipart/form-data" class="glass-upload">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="description" rows="4" placeholder="Description"></textarea>
        <select name="parent_category" id="parent_cat" required>
            <option value="">-- Main Category --</option>
            <?php foreach($topCategories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="category_id" id="sub_cat" required>
            <option value="">-- First select main category --</option>
        </select>
        <input type="file" name="content_file" accept=".mp4,.iso,.exe,.zip,.pdf" required>
        <button type="submit">Upload</button>
    </form>
</div>
<script>
document.getElementById('parent_cat').addEventListener('change', function() {
    let parent = this.value;
    let sub = document.getElementById('sub_cat');
    if(parent) {
        fetch('index.php?ajax=get_subcategories&parent_id=' + parent)
        .then(r => r.json())
        .then(data => {
            sub.innerHTML = '<option value="">-- Select Subcategory --</option>';
            data.forEach(s => { sub.innerHTML += `<option value="${s.id}">${s.name}</option>`; });
        });
    } else {
        sub.innerHTML = '<option value="">-- First select main category --</option>';
    }
});
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