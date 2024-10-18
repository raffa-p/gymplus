document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("invia-btn").addEventListener("click", (e) => {
        e.preventDefault();
        const messageId = "helpRequest";
        return fetch('../php/gestione_DB.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json; charset=utf-8' },
            body: JSON.stringify({
                "messageId": messageId,
                "user": document.getElementById("usr-email").value,
                "content" : document.getElementById("usr-content").value
            })
        })
        .then(response => response.json().then(json => {
            if (!response.ok) {
                throw new Error("Errore: " + json["esito"]);
            }
            return json;
        }))
        .then(json => {
            console.log("Risposta del server:", json["esito"]);
            const existingAlert = document.getElementById("alert");
            if (existingAlert) {
                existingAlert.remove();
            }
            let confirm_msg = document.createElement('div');
            confirm_msg.className = 'alert';
            confirm_msg.id = 'alert';
            let confirm_text = document.createElement('p');
            confirm_text.textContent = 'Richiesta inviata correttamente';
            
            let close_button = document.createElement('button');
            close_button.type = 'button';
            close_button.id = 'close-alert';
            
            let close_icon = document.createElement('i');
            close_icon.className = 'material-icons';
            close_icon.innerHTML = '&#xe872;'; 
            
            close_button.appendChild(close_icon);
            
            confirm_msg.appendChild(confirm_text);
            confirm_msg.appendChild(close_button);
            
            let contentNode = document.querySelector('body');
            contentNode.appendChild(confirm_msg);
            document.getElementById("close-alert").addEventListener("click", () => {
                document.getElementById("alert").remove();
            });
        })
        .catch(error => {
            console.log(error.message);
            return null;
        });
    });
});
