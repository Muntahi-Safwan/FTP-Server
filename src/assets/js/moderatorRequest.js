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

async function updateRequestStatus(id, status) {
    if (!confirm('Mark this request as ' + status + '?')) return false;

    try {
        const res = await fetch('../../controller/moderatorUpdateRequest.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ id: id, status: status, csrf: csrfToken })
        });
        const data = await res.json().catch(() => ({ ok: false, error: 'Server returned non-JSON response.' }));

        if (res.ok && data.ok) {
            showResponse(data.message || 'Request updated.', 'success');
            updateRowInPlace(id, data.status || status);
        } else {
            showResponse(data.error || 'Failed to update request.', 'error');
            clearResponse();
        }
    } catch (err) {
        showResponse('Network error: ' + err.message, 'error');
        clearResponse();
    }
    return false;
}

function updateRowInPlace(id, status) {
    const row = document.querySelector(`tr[data-row-id="${id}"]`);
    if (!row) {
        setTimeout(() => window.location.reload(), 800);
        return;
    }
    row.setAttribute('data-status', status);
    const statusCell = row.querySelector('.status-cell');
    if (statusCell) {
        statusCell.innerHTML = `<span class="badge badge-${status}">${status}</span>`;
    }
    const actionCell = row.querySelector('.action-cell');
    if (actionCell) {
        actionCell.innerHTML = '<span style="color: var(--text-muted); font-size: 13px;">&mdash;</span>';
    }
    clearResponse();
}
