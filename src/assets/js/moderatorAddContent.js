(function () {
    const form = document.getElementById('addContentForm');
    if (!form) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    const parentSelect = document.getElementById('parentCategoryId');
    const subSelect    = document.getElementById('subCategoryId');

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

    function clearFieldErrors() {
        document.querySelectorAll('.field-error').forEach(s => s.textContent = '');
    }

    function setFieldError(name, message) {
        const span = document.querySelector(`.field-error[data-error-for="${name}"]`);
        if (span) span.textContent = message;
    }

    // ── Dependent sub-category dropdown ─────────────
    parentSelect.addEventListener('change', async function () {
        const parentId = this.value;
        subSelect.disabled = true;
        subSelect.innerHTML = '<option value="">Loading…</option>';

        if (!parentId) {
            subSelect.innerHTML = '<option value="">-- Select a category first --</option>';
            return;
        }

        try {
            const res = await fetch(`../../../public/index.php?ajax=subcategories&parent_id=${encodeURIComponent(parentId)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const subs = await res.json();
            subSelect.innerHTML = '';
            const useParent = document.createElement('option');
            useParent.value = parentId;
            useParent.textContent = '(use top-level)';
            subSelect.appendChild(useParent);
            if (Array.isArray(subs)) {
                subs.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = s.name;
                    subSelect.appendChild(opt);
                });
            }
            subSelect.disabled = false;
        } catch (e) {
            subSelect.innerHTML = '<option value="">(failed to load sub-categories)</option>';
        }
    });

    // ── Form submit ────────────────────────────────
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        clearFieldErrors();

        const title       = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const parentId    = parentSelect.value;
        const subValue    = subSelect.value;
        const fileInput   = document.getElementById('contentFile');

        // Client-side validation
        let firstError = null;
        if (!title) { setFieldError('title', 'Title is required.'); firstError ||= 'title'; }
        if (title.length > 255) { setFieldError('title', 'Title must be ≤ 255 chars.'); firstError ||= 'title'; }
        if (!description) { setFieldError('description', 'Description is required.'); firstError ||= 'description'; }
        if (!parentId) { setFieldError('category_id', 'Please pick a category.'); firstError ||= 'category_id'; }
        if (!fileInput.files || fileInput.files.length === 0) {
            setFieldError('content_file', 'Please choose a file.');
            firstError ||= 'content_file';
        }
        if (firstError) {
            showResponse('Please fix the highlighted errors.', 'error');
            return;
        }

        const categoryId = subValue || parentId;

        const fd = new FormData();
        fd.append('csrf', csrfToken);
        fd.append('title', title);
        fd.append('description', description);
        fd.append('category_id', categoryId);
        fd.append('content_file', fileInput.files[0]);

        showResponse('Uploading…', 'info');

        try {
            const res = await fetch('../../controller/moderatorAddContent.php', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: fd
            });
            const data = await res.json().catch(() => ({ ok: false, error: 'Server returned non-JSON response.' }));

            if (res.ok && data.ok) {
                showResponse(data.message || 'Content added successfully.', 'success');
                form.reset();
                subSelect.innerHTML = '<option value="">-- Select a category first --</option>';
                subSelect.disabled = true;
                clearResponse();
            } else {
                if (data.errors && typeof data.errors === 'object') {
                    Object.entries(data.errors).forEach(([k, v]) => setFieldError(k, v));
                    showResponse('Please fix the highlighted errors.', 'error');
                } else {
                    showResponse(data.error || 'Failed to add content.', 'error');
                }
            }
        } catch (err) {
            showResponse('Network error: ' + err.message, 'error');
        }
    });
})();
