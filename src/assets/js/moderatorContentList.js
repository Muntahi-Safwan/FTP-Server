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

function deleteContent(id) {
    if (!confirm("Are you sure you want to delete this content?")) return false;

    let data = {
        id: id,
    };
    let payload = JSON.stringify(data);

    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../controller/moderatorDeleteContent.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("data=" + encodeURIComponent(payload));

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let resp = this.responseText.trim();
            if (resp.includes('successfully')) {
                showResponse(resp, 'success');
                setTimeout(function () {
                    window.location.reload();
                }, 1200);
            } else {
                showResponse(resp, 'error');
                clearResponse();
            }
        }
    };

    return false;
}
