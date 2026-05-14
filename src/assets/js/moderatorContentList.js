const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

function showResponse(message, type) {
    const box = document.getElementById('response');
    if (!box) return;
    const cls = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-error' : 'alert-info');
    box.innerHTML = `<div class="alert ${cls}">${message}</div>`;
}

function clearResponse(delay = 3000) {
    setTimeout(() => {
        const box = document.getElementById('response');
        if (box) box.innerHTML = '';
    }, delay);
}

async function deleteContent(id) {
    if (!confirm('Are you sure you want to delete this content?')) return false;

    try {
        const res = await fetch('../../controller/moderatorDeleteContent.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ id: id, csrf: csrfToken })
        });
        const data = await res.json().catch(() => ({ ok: false, error: 'Server returned non-JSON response.' }));

        if (res.ok && data.ok) {
            showResponse(data.message || 'Content deleted.', 'success');
            setTimeout(() => window.location.reload(), 800);
        } else {
            showResponse(data.error || 'Failed to delete content.', 'error');
            clearResponse();
        }
    } catch (err) {
        showResponse('Network error: ' + err.message, 'error');
        clearResponse();
    }
    return false;
}
