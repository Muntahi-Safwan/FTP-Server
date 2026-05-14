<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="mod-wrap">
    <h2>👑 Moderator Management</h2>
    <?php if(isset($_SESSION['flash'])): ?>
        <div class="alert success"><?= $_SESSION['flash']; unset($_SESSION['flash']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['errors'])): ?>
        <div class="alert error"><?= implode('<br>', $_SESSION['errors']); unset($_SESSION['errors']); ?></div>
    <?php endif; ?>
    <div class="glass-form">
        <h3>➕ Add New Moderator</h3>
        <form method="POST" action="index.php?page=admin/add_moderator" id="modForm">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password (min 8)" required>
            <input type="password" name="confirm" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
    <div class="table-wrap">
        <table class="modern-table">
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach($moderators as $m): ?>
                <tr id="mod-<?= $m['id'] ?>">
                    <td><?= $m['id'] ?></td>
                    <td><?= htmlspecialchars($m['name']) ?></td>
                    <td><?= htmlspecialchars($m['email']) ?></td>
                    <td><button class="delete-mod" data-id="<?= $m['id'] ?>">🗑 Delete</button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
document.querySelectorAll('.delete-mod').forEach(btn => {
    btn.onclick = function() {
        if(confirm('Delete this moderator? Their contents will be reassigned to admin.')) {
            fetch('index.php?ajax=delete_moderator', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id=' + this.dataset.id
            })
            .then(r => r.json())
            .then(data => { if(data.success) document.getElementById('mod-'+this.dataset.id).remove(); else alert('Error'); });
        }
    };
});
document.getElementById('modForm')?.addEventListener('submit', function(e) {
    let p1 = this.querySelector('input[name="password"]').value;
    let p2 = this.querySelector('input[name="confirm"]').value;
    if(p1 !== p2 || p1.length < 8) { e.preventDefault(); alert('Passwords must match and be at least 8 characters'); }
});
</script>
<style>
.mod-wrap{ max-width:1000px; margin:40px auto; padding:20px; }
.glass-form{ background: rgba(255,255,255,0.05); backdrop-filter: blur(8px); border-radius: 28px; padding:30px; margin-bottom:30px; }
.glass-form input{ width:100%; padding:12px; margin:10px 0; background:#111; border:1px solid #333; border-radius: 40px; color:white; }
.glass-form button{ background:#ff4d6d; border:none; padding:12px; border-radius: 40px; color:white; width:100%; cursor:pointer; }
.table-wrap{ background: rgba(0,0,0,0.5); border-radius: 24px; overflow-x:auto; }
.modern-table{ width:100%; border-collapse:collapse; color:white; }
.modern-table th, .modern-table td{ padding:12px; border-bottom:1px solid #333; }
.modern-table th{ background:#ff4d6d; color:black; }
.delete-mod{ background:#dc2626; border:none; padding:4px 12px; border-radius:20px; color:white; cursor:pointer; }
.alert{ padding:12px; border-radius: 20px; margin-bottom:20px; }
.success{ background:#10b98133; border-left:3px solid #10b981; }
.error{ background:#ef444433; border-left:3px solid #ef4444; }
</style>
<?php require_once __DIR__ . '/../footer.php'; ?>