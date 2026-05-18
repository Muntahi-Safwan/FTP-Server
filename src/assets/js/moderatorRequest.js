var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

function showResponse(message, type) {
    var box = document.getElementById('response');
    if (!box) return;
    var cls = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-error' : 'alert-info');
    box.innerHTML = '<div class="alert ' + cls + '">' + message + '</div>';
}

function clearResponse(delay) {
    delay = delay || 3000;
    setTimeout(function () {
        var box = document.getElementById('response');
        if (box) box.innerHTML = '';
    }, delay);
}

function updateRequestStatus(id, status) {
    if (!confirm('Mark this request as ' + status + '?')) return false;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../controller/moderatorUpdateRequest.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) return;

        var data;
        try {
            data = JSON.parse(xhr.responseText);
        } catch (e) {
            data = { ok: false, error: 'Server returned non-JSON response.' };
        }

        if (xhr.status === 200 && data.ok) {
            showResponse(data.message || 'Request updated.', 'success');
            updateRowInPlace(id, data.status || status);
        } else {
            showResponse(data.error || 'Failed to update request.', 'error');
            clearResponse();
        }
    };
    xhr.onerror = function () {
        showResponse('Network error.', 'error');
        clearResponse();
    };
    xhr.send(JSON.stringify({ id: id, status: status, csrf: csrfToken }));
    return false;
}

function updateRowInPlace(id, status) {
    var row = document.querySelector('tr[data-row-id="' + id + '"]');
    if (!row) {
        setTimeout(function () { window.location.reload(); }, 800);
        return;
    }
    row.setAttribute('data-status', status);
    var statusCell = row.querySelector('.status-cell');
    if (statusCell) {
        statusCell.innerHTML = '<span class="badge badge-' + status + '">' + status + '</span>';
    }
    var actionCell = row.querySelector('.action-cell');
    if (actionCell) {
        actionCell.innerHTML = '<span style="color: var(--text-muted); font-size: 13px;">&mdash;</span>';
    }
    clearResponse();
}
