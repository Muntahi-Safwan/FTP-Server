// src/assets/js/adminModerators.js

// ── JS Validation ──────────────────────────────
const form  = document.getElementById('modForm');
const pwd   = document.getElementById('pwd');
const pwd2  = document.getElementById('pwd2');
const pwdMsg = document.getElementById('pwdMsg');
const emailInput = form?.querySelector('input[name="email"]');
const emailMsg   = document.getElementById('emailMsg');

if (pwd2) {
    pwd2.addEventListener('input', () => {
        if (pwd.value !== pwd2.value) {
            pwdMsg.textContent = 'Passwords do not match';
            pwdMsg.style.color = 'var(--danger)';
        } else {
            pwdMsg.textContent = '✓ Passwords match';
            pwdMsg.style.color = 'var(--success)';
        }
    });
}

if (emailInput) {
    let timer;
    emailInput.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            const email = emailInput.value.trim();
            if (!email) return;
            fetch('index.php?ajax=check_email', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'user=' + encodeURIComponent(JSON.stringify({email}))
            })
            .then(r => r.json())
            .then(data => {
                if (data.exists) {
                    emailMsg.textContent = '✗ Email already registered';
                    emailMsg.style.color = 'var(--danger)';
                } else {
                    emailMsg.textContent = '✓ Email available';
                    emailMsg.style.color = 'var(--success)';
                }
            });
        }, 500);
    });
}

if (form) {
    form.addEventListener('submit', function(e) {
        const p1 = pwd.value;
        const p2 = pwd2.value;
        if (p1.length < 8) {
            e.preventDefault();
            pwdMsg.textContent = 'Password must be at least 8 characters';
            pwdMsg.style.color = 'var(--danger)';
            return;
        }
        if (p1 !== p2) {
            e.preventDefault();
            pwdMsg.textContent = 'Passwords do not match';
            pwdMsg.style.color = 'var(--danger)';
            return;
        }
    });
}

// ── AJAX Delete with Modal ─────────────────────
const modal      = document.getElementById('delModal');
const cancelBtn  = document.getElementById('cancelDel');
const confirmBtn = document.getElementById('confirmDel');
let pendingId    = null;
const csrf       = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

document.querySelectorAll('.delete-mod').forEach(btn => {
    btn.addEventListener('click', function() {
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
    fetch('index.php?ajax=delete_moderator', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + pendingId + '&csrf=' + encodeURIComponent(csrf)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById('mod-' + pendingId);
            if (row) row.style.transition = 'opacity 0.3s', row.style.opacity = '0', setTimeout(() => row.remove(), 300);
            showToast('Moderator deleted successfully', 'success');
        } else {
            showToast('Failed to delete moderator', 'error');
        }
    })
    .catch(() => showToast('Network error', 'error'))
    .finally(() => {
        modal.classList.remove('open');
        pendingId = null;
    });
});

function showToast(msg, type) {
    const div = document.createElement('div');
    div.className = 'alert alert-' + (type === 'success' ? 'success' : 'error');
    div.style.cssText = 'position:fixed;top:70px;right:20px;z-index:9999;min-width:280px;animation:fadeIn .3s';
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 3500);
}