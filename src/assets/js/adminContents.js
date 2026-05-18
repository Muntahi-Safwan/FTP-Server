// src/assets/js/adminContents.js

const modal      = document.getElementById('delModal');
const cancelBtn  = document.getElementById('cancelDel');
const confirmBtn = document.getElementById('confirmDel');
const csrf       = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
let pendingId    = null;

// Delete buttons
document.querySelectorAll('.del-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        pendingId = this.dataset.id;
        modal.classList.add('open');
    });
});

cancelBtn?.addEventListener('click', () => {
    modal.classList.remove('open');
    pendingId = null;
});

confirmBtn?.addEventListener('click', () => {
    if (!pendingId) return;
    fetch('index.php?ajax=delete_content', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + pendingId + '&csrf=' + encodeURIComponent(csrf)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById('row-' + pendingId);
            if (row) {
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);
            }
            showToast('Content deleted successfully', 'success');
        } else {
            showToast('Failed to delete content', 'error');
        }
    })
    .catch(() => showToast('Network error', 'error'))
    .finally(() => {
        modal.classList.remove('open');
        pendingId = null;
    });
});

// Live search filter
document.getElementById('searchInput')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#contentTable tbody tr').forEach(row => {
        const title = row.dataset.title ?? '';
        row.style.display = title.includes(q) ? '' : 'none';
    });
});

function showToast(msg, type) {
    const div = document.createElement('div');
    div.className = 'alert alert-' + (type === 'success' ? 'success' : 'error');
    div.style.cssText = 'position:fixed;top:70px;right:20px;z-index:9999;min-width:280px;';
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 3500);
}