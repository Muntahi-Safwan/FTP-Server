function showResponse(message, type) {
    const box = document.getElementById("response");
    const alertClass = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-error' : 'alert-info');
    box.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;
}

function clearResponse(delay = 3000) {
    setTimeout(() => {
        document.getElementById("response").innerHTML = "";
    }, delay);
}

function addContent() {
    let title = document.getElementById("title").value.trim();
    let description = document.getElementById("description").value.trim();
    let categoryId = document.getElementById("categoryId").value;
    let filePath = document.getElementById("filePath").value;

    if (title === '') {
        showResponse('Title is required.', 'error');
        return false;
    }
    if (description === '') {
        showResponse('Description is required.', 'error');
        return false;
    }
    if (categoryId === '') {
        showResponse('Please select a category.', 'error');
        return false;
    }
    if (filePath === '') {
        showResponse('Please choose a file to upload.', 'error');
        return false;
    }

    let data = {
        title: title,
        description: description,
        categoryId: categoryId,
        filePath: filePath
    };

    let content = JSON.stringify(data);

    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../controller/moderatorAddContent.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("content=" + encodeURIComponent(content));

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let resp = this.responseText.trim();
            if (resp.includes('successfully')) {
                showResponse(resp, 'success');
                document.querySelector("form").reset();
            } else {
                showResponse(resp, 'error');
            }
            clearResponse();
        }
    };

    return false;
}
