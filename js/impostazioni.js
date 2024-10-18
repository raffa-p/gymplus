// save edits
function salvaPassword(e){
    e.preventDefault();

    let modificaPasswordVecchia = document.getElementById("modifica-password-vecchia").value;
    let modificaPasswordNuova = document.getElementById("modifica-password-nuova").value;
    let modificaPasswordNuovaConferma = document.getElementById("modifica-password-nuova-conferma").value;
    
    const messageId = "aggiornaDatiAccesso";
    fetch(
        '../php/gestione_DB.php',
        {
            method: 'POST',
            headers: {'Content-Type': 'application/json; charset=utf-8'},
            body: JSON.stringify(
                    {"messageId": messageId, 
                    "data": {
                        "username" : getCookie("username"), 
                        "vecchia-password" : modificaPasswordVecchia, 
                        "nuova-password" : modificaPasswordNuova, 
                        "nuova-password-conferma" : modificaPasswordNuovaConferma,
                        }
                    }
                )
        })

    .then(response => response.json().then(json => {
        if (!response.ok) {
            throw new Error("Errore: " + json["esito"] + " Dettagli: " + JSON.stringify(json));
        }
        return json;
    }))
    .then(json => {
        console.log("Risposta del server:", json["esito"]);
        let confirm_msg = document.createElement('div');
        confirm_msg.className = 'alert-green';
        confirm_msg.id = 'alert-left';
        let confirm_text = document.createElement('p');
        confirm_text.textContent = 'Salvataggio completato';

        let close_button = document.createElement('button');
        close_button.type = 'button';
        close_button.id = 'close-alert';

        let close_icon = document.createElement('i');
        close_icon.className = 'material-icons';
        close_icon.innerHTML = '&#xe872;';

        close_button.appendChild(close_icon);

        confirm_msg.appendChild(confirm_text);
        confirm_msg.appendChild(close_button);
        
        let contentNode = document.querySelector('.right-column');
        contentNode.appendChild(confirm_msg);
        document.getElementById("close-alert").addEventListener("click", () => {
            document.getElementById("alert-left").remove();
        });
    })
    .catch((error) => {
        let esito="";
        if(error.message == 400){
            esito = "Password attuale errata.\nSi prega di riprovare";
        }
        else if(error.message === 500){
            esito = "Errore di comunicazione con il server\nRiprovare fra qualche minuto";
        }
        window.alert(esito);
    });
}



document.addEventListener("DOMContentLoaded", () => {
    // dati personali
    document.getElementById("personal-info-btn").addEventListener("click", (e) => {
        e.preventDefault();
        let modificaNome = document.getElementById("modifica-nome").value.trim();
        let modificaCognome = document.getElementById("modifica-cognome").value.trim();
        let modificaDataNascita = document.getElementById("modifica-data-nascita").value.trim();
        let modificaTelefono = document.getElementById("modifica-telefono").value.trim();

        const messageId = "aggiornaDatiPersonali";
        fetch(
            '../php/gestione_DB.php',
            {
                method: 'POST',
                headers: {'Content-Type': 'application/json; charset=utf-8'},
                body: JSON.stringify(
                        {"messageId": messageId, 
                        "data": {
                            "username" : getCookie("username"), 
                            "nuovo-nome" : modificaNome, 
                            "vecchio-nome" : getCookie("vecchio-nome"),
                            "nuovo-cognome" : modificaCognome, 
                            "vecchio-cognome" : getCookie("vecchio-cognome"),
                            "nuova-data-nascita" : modificaDataNascita,
                            "vecchia-data-nascita" : getCookie("vecchia-data-nascita"), 
                            "nuovo-telefono" : modificaTelefono, 
                            "vecchio-telefono" : getCookie("vecchio-telefono"),
                            }
                        }
                    )
            })

        .then(response => response.json().then(json => {
            if (!response.ok) {
                throw new Error("Errore: " + json["esito"] + " Dettagli: " + JSON.stringify(json));
            }
            return json;
        }))
        .then(json => {
            console.log("Risposta del server:", json["esito"]);
            let confirm_msg = document.createElement('div');
            confirm_msg.className = 'alert-green';
            confirm_msg.id = 'alert-left';
            let confirm_text = document.createElement('p');
            confirm_text.textContent = 'Salvataggio completato';

            let close_button = document.createElement('button');
            close_button.type = 'button';
            close_button.id = 'close-alert';

            let close_icon = document.createElement('i');
            close_icon.className = 'material-icons';
            close_icon.innerHTML = '&#xe872;'; 

            close_button.appendChild(close_icon);

            confirm_msg.appendChild(confirm_text);
            confirm_msg.appendChild(close_button);
            
            let contentNode = document.querySelector('.left-column');
            contentNode.appendChild(confirm_msg);
            document.getElementById("close-alert").addEventListener("click", () => {
                document.getElementById("alert-left").remove();
            });
        })
        .catch((error) => {
            window.alert(error.message);
        });
    });
});



document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("modifica-password-nuova-conferma").addEventListener("input", validate);
});
function validate(){
    if(document.getElementById("modifica-password-nuova").value === document.getElementById("modifica-password-nuova-conferma").value
        && document.getElementById("modifica-password-nuova-conferma").value !== ""){
            document.getElementById('message').style.display = "flex";
            document.getElementById('message').style.color = 'green';
            document.getElementById('message').innerText = 'Corretto';
            document.getElementById("access-info-btn").addEventListener("click", salvaPassword);
        }
        else{
        document.getElementById('message').style.display = "flex";
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerText = 'Le password non corrispondono';
        document.getElementById("access-info-btn").removeEventListener("click", salvaPassword);
    };
}

document.addEventListener("DOMContentLoaded", () => {
    if(window.innerWidth <= 768){
        document.getElementById("content").style.marginTop = document.getElementById("sidebar-logo").clientHeight + "px";
        document.getElementById("content").style.paddingBottom = document.getElementById("sidebar-content").clientHeight + "px";
    }
});