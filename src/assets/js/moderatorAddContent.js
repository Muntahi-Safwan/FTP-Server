(function () {
    const form = document.getElementById('addContentForm');
    if (!form) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    const parentSelect = document.getElementById('parentCategoryId');
    const subSelect    = document.getElementById('subCategoryId');
    const fileInput    = document.getElementById('contentFile');
    const fileDrop     = document.getElementById('fileDrop');
    const fileSelected = document.getElementById('fileSelected');
    const fileSelectedName = document.getElementById('fileSelectedName');
    const fileSelectedClear = document.getElementById('fileSelectedClear');

    function showResponse(message, type) {
        const box = document.getElementById('response');
        if (!box) return;
        const cls = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-error' : 'alert-info');
        box.innerHTML = '<div class="alert ' + cls + '">' + message + '</div>';
    }

    function clearResponse(delay) {
        delay = delay || 3000;
        setTimeout(function () {
            const box = document.getElementById('response');
            if (box) box.innerHTML = '';
        }, delay);
    }

    function clearFieldErrors() {
        document.querySelectorAll('.field-error').forEach(function (s) { s.textContent = ''; });
    }

    function setFieldError(name, message) {
        const span = document.querySelector('.field-error[data-error-for="' + name + '"]');
        if (span) span.textContent = message;
    }

    // ── Dependent sub-category dropdown ─────────────
    parentSelect.addEventListener('change', function () {
        const parentId = this.value;
        subSelect.disabled = true;
        subSelect.innerHTML = '<option value="">Loading…</option>';

        if (!parentId) {
            subSelect.innerHTML = '<option value="">Select a category first</option>';
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../../../public/index.php?ajax=subcategories&parent_id=' + encodeURIComponent(parentId), true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) return;
            if (xhr.status === 200) {
                try {
                    var subs = JSON.parse(xhr.responseText);
                    subSelect.innerHTML = '';
                    var useParent = document.createElement('option');
                    useParent.value = parentId;
                    useParent.textContent = '(use top-level)';
                    subSelect.appendChild(useParent);
                    if (Array.isArray(subs)) {
                        subs.forEach(function (s) {
                            var opt = document.createElement('option');
                            opt.value = s.id;
                            opt.textContent = s.name;
                            subSelect.appendChild(opt);
                        });
                    }
                    subSelect.disabled = false;
                } catch (e) {
                    subSelect.innerHTML = '<option value="">(failed to load sub-categories)</option>';
                }
            } else {
                subSelect.innerHTML = '<option value="">(failed to load sub-categories)</option>';
            }
        };
        xhr.send();
    });

    // ── File drag & drop UI ─────────────────────────
    function showFile(name) {
        if (!fileSelected || !fileSelectedName) return;
        fileSelectedName.textContent = name;
        fileSelected.style.display = 'flex';
    }

    function hideFile() {
        if (!fileSelected) return;
        fileSelected.style.display = 'none';
        fileInput.value = '';
    }

    if (fileInput && fileDrop) {
        fileInput.addEventListener('change', function () {
            if (this.files[0]) showFile(this.files[0].name);
            else hideFile();
        });

        if (fileSelectedClear) {
            fileSelectedClear.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                hideFile();
            });
        }

        ['dragover', 'dragenter'].forEach(function (ev) {
            fileDrop.addEventListener(ev, function (e) { e.preventDefault(); fileDrop.classList.add('dragover'); });
        });

        ['dragleave', 'drop'].forEach(function (ev) {
            fileDrop.addEventListener(ev, function () { fileDrop.classList.remove('dragover'); });
        });

        fileDrop.addEventListener('drop', function (e) {
            e.preventDefault();
            var file = e.dataTransfer.files[0];
            if (file) {
                var dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                showFile(file.name);
            }
        });
    }

    // ── Form submit ────────────────────────────────
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearFieldErrors();

        var title       = document.getElementById('title').value.trim();
        var description = document.getElementById('description').value.trim();
        var parentId    = parentSelect.value;
        var subValue    = subSelect.value;
        var file        = fileInput.files[0];

        // Client-side validation
        var firstError = null;
        if (!title) { setFieldError('title', 'Title is required.'); firstError = firstError || 'title'; }
        if (title.length > 255) { setFieldError('title', 'Title must be ≤ 255 chars.'); firstError = firstError || 'title'; }
        if (!description) { setFieldError('description', 'Description is required.'); firstError = firstError || 'description'; }
        if (!parentId) { setFieldError('category_id', 'Please pick a category.'); firstError = firstError || 'category_id'; }

        var allowed = ['mp4','mkv','avi','mov','pdf','zip','rar','7z','exe','iso','mp3'];
        if (!file) {
            setFieldError('content_file', 'Please choose a file.');
            firstError = firstError || 'content_file';
        } else {
            var ext = file.name.split('.').pop().toLowerCase();
            if (allowed.indexOf(ext) === -1) {
                setFieldError('content_file', 'File type not allowed.');
                firstError = firstError || 'content_file';
            } else if (file.size > 200 * 1024 * 1024) {
                setFieldError('content_file', 'File exceeds 200MB limit.');
                firstError = firstError || 'content_file';
            }
        }

        if (firstError) {
            showResponse('Please fix the highlighted errors.', 'error');
            return;
        }

        var categoryId = subValue || parentId;

        var fd = new FormData();
        fd.append('csrf', csrfToken);
        fd.append('title', title);
        fd.append('description', description);
        fd.append('category_id', categoryId);
        fd.append('content_file', file);

        showResponse('Uploading…', 'info');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../../controller/moderatorAddContent.php', true);
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
                showResponse(data.message || 'Content added successfully.', 'success');
                form.reset();
                subSelect.innerHTML = '<option value="">Select a category first</option>';
                subSelect.disabled = true;
                hideFile();
                clearResponse();
            } else {
                if (data.errors && typeof data.errors === 'object') {
                    Object.entries(data.errors).forEach(function (entry) {
                        setFieldError(entry[0], entry[1]);
                    });
                    showResponse('Please fix the highlighted errors.', 'error');
                } else {
                    showResponse(data.error || 'Failed to add content.', 'error');
                }
            }
        };
        xhr.send(fd);
    });
})();
