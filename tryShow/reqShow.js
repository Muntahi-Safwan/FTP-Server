document.addEventListener("DOMContentLoaded", function() {
    
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "getContentReq.php", true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            var tbody = document.getElementById("tconBody");
            
            for (var i = 0; i < data.length; i++) {
                var tr = document.createElement("tr");
                
                var tdId = document.createElement("td");
                tdId.textContent = data[i].id;
                tr.appendChild(tdId);
                
                var tdIp = document.createElement("td");
                tdIp.textContent = data[i].requester_ip;
                tr.appendChild(tdIp);
                
                var tdTitle = document.createElement("td");
                tdTitle.textContent = data[i].content_title;
                tr.appendChild(tdTitle);
                
                var tdCat = document.createElement("td");
                tdCat.textContent = data[i].category_requested;
                tr.appendChild(tdCat);
                
                var tdMsg = document.createElement("td");
                tdMsg.textContent = data[i].message;
                tr.appendChild(tdMsg);
                
                var tdStatus = document.createElement("td");
                tdStatus.textContent = data[i].status;
                tr.appendChild(tdStatus);
                
                var tdTime = document.createElement("td");
                tdTime.textContent = data[i].created_at;
                tr.appendChild(tdTime);
                
                var tdAccept = document.createElement("td"); 
                var acpt = document.createElement("a");
                acpt.href = "reqAccept.php?id=" + data[i].id;
                acpt.innerText = 'ACCEPT';
                tdAccept.appendChild(acpt); 
                tr.appendChild(tdAccept);
                
                var tdReject = document.createElement("td");
                var reject = document.createElement("a");
                reject.href = "reqReject.php?id=" + data[i].id;
                reject.innerText = 'REJECT';
                tdReject.appendChild(reject);
                tr.appendChild(tdReject);
                
                tbody.appendChild(tr);
            }
        } else {
            console.error("Failed to fetch data from the server.");
        }
    };
    
    xhr.send();
});