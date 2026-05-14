<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="admin-dash">
    <div class="glass-hero">
        <h1>⚡ Admin Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></p>
    </div>
    <div class="stats">
        <div class="stat-card"><div class="num"><?= $totalContents ?></div><div class="label">📁 Contents</div></div>
        <div class="stat-card"><div class="num"><?= $totalModerators ?></div><div class="label">👥 Moderators</div></div>
        <div class="stat-card"><div class="num"><?= $totalCategories ?></div><div class="label">📂 Categories</div></div>
        <div class="stat-card"><div class="num"><?= $pendingRequests ?></div><div class="label">⏳ Pending Requests</div></div>
    </div>
    <div class="actions">
        <a href="index.php?page=admin/moderators">Manage Moderators</a>
        <a href="index.php?page=admin/contents">Manage Contents</a>
        <a href="index.php?page=admin/add_content">Upload Content</a>
    </div>
</div>
<style>
body{ background: radial-gradient(circle at 10% 30%, #0b0b1f, #010105); color:#fff; font-family: 'Segoe UI', sans-serif; margin:0; }
.admin-dash{ max-width:1200px; margin:40px auto; padding:20px; }
.glass-hero{ background: rgba(255,255,255,0.08); backdrop-filter: blur(12px); border-radius: 32px; padding: 30px; text-align:center; border-bottom: 2px solid #ff4d6d; margin-bottom:40px; }
.stats{ display:flex; gap:25px; justify-content:center; flex-wrap:wrap; margin-bottom:50px; }
.stat-card{ background: rgba(0,0,0,0.5); backdrop-filter: blur(8px); border-radius: 28px; padding: 30px; text-align:center; min-width:180px; border-left: 4px solid #ff4d6d; }
.stat-card .num{ font-size: 52px; font-weight:800; color:#ff4d6d; }
.stat-card .label{ font-size: 18px; margin-top:10px; }
.actions{ display:flex; gap:20px; justify-content:center; flex-wrap:wrap; }
.actions a{ background: linear-gradient(95deg, #ff4d6d, #b5179e); padding:12px 28px; border-radius: 60px; text-decoration:none; color:white; font-weight:bold; transition:0.3s; }
.actions a:hover{ transform: scale(1.05); box-shadow:0 0 15px #ff4d6d; }
</style>
<?php require_once __DIR__ . '/../footer.php'; ?>