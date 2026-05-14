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

function updateRequestStatus(id, status) {
    if (!confirm("Are you sure you want to mark this request as " + status + "?")) return false;

    let data = {
        id: id,
        status: status
    };
    let payload = JSON.stringify(data);

    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../controller/moderatorUpdateRequest.php", true);
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
