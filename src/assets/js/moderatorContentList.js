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

function deleteContent(id) {
    if (!confirm('Are you sure you want to delete this content?')) return false;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../controller/moderatorDeleteContent.php', true);
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
            showResponse(data.message || 'Content deleted.', 'success');
            setTimeout(function () { window.location.reload(); }, 800);
        } else {
            showResponse(data.error || 'Failed to delete content.', 'error');
            clearResponse();
        }
    };
    xhr.onerror = function () {
        showResponse('Network error.', 'error');
        clearResponse();
    };
    xhr.send(JSON.stringify({ id: id, csrf: csrfToken }));
    return false;
}
