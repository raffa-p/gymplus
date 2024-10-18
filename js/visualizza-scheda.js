document.addEventListener("DOMContentLoaded", function(){
    document.getElementById("home-redirect").addEventListener("click", function(){
        window.location = "http://localhost/php/coach-dash.php";
    });
    document.getElementById("log-out-btn").addEventListener("click", () => {
        window.location = "./log-out.php";
    });
    document.getElementById("info-btn").addEventListener("click", () => {
        window.location = "./documentazione.php";
    });
    document.getElementById("new-routine-redirect").addEventListener("click", () => {
        window.location = "./allenamento.php";
    });
    document.getElementById("settings-btn").addEventListener("click", () => {
        window.location = "./impostazioni.php";
    });
    document.getElementById("profile-redirect").addEventListener("click", () => {
        window.location = "./profilo_utente.php";
    });
    document.getElementById("visualizza-scheda-redirect").addEventListener("click", () => {
        window.location = "./visualizza-scheda.php";
    });
});




/* visualizzazione scheda completa */
document.addEventListener("DOMContentLoaded", () => {
    const messageId = "richiestaScheda";
    fetch(
        '../php/gestione_DB.php',
        {
            method: 'POST',
            headers: {'Content-Type': 'application/json; charset=utf-8'},
            body: JSON.stringify(
                {"messageId": messageId, 
                 "user" : getCookie("username")
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
        visualizzaSchedaCompleta(json["scheda"], json["annotazioni"]);
    })
    .catch((error) => {
        window.alert(error.message);
    });
});

function visualizzaSchedaCompleta(scheda, annotazioni) {
    if(scheda.length < 1){ nessunaScheda(); return; }
    // annotazioni
    if (annotazioni) {
        document.getElementById("annotazioni").innerText = annotazioni;
    }

    // giorni
    const containerGiorni = document.querySelector('.container-giorni');
    for (let giorno in scheda) {
        if (giorno !== "annotazioni") {
            let nuovaSchedaElement = document.createElement('div');
            nuovaSchedaElement.className = 'giorno';

            let giornoEtichetta = document.createElement('div');
            giornoEtichetta.className = 'giorno-etichetta';

            let giornoEtichettaP = document.createElement('p');
            giornoEtichettaP.textContent = 'Giorno ' + (parseInt(giorno) + 1);

            giornoEtichetta.appendChild(giornoEtichettaP);
            nuovaSchedaElement.appendChild(giornoEtichetta);

            for (let esercizio of scheda[giorno]) {
                let nuovaScheda = document.createElement('div');
                nuovaScheda.className = 'card';

                let cardContent = document.createElement('div');
                cardContent.className = 'card-content';

                let nomeEsercizio = document.createElement('p');
                nomeEsercizio.className = 'nome-esercizio';
                nomeEsercizio.textContent = esercizio["nome"];

                let containerDati = document.createElement('div');
                containerDati.className = 'container-dati';

                let serie = document.createElement('p');
                serie.textContent = 'Serie: ' + esercizio["serie"];

                let ripetizioni = document.createElement('p');
                ripetizioni.textContent = 'Ripetizioni: ' + esercizio["ripetizioni"];

                containerDati.appendChild(serie);
                containerDati.appendChild(ripetizioni);

                cardContent.appendChild(nomeEsercizio);
                cardContent.appendChild(containerDati);

                nuovaScheda.appendChild(cardContent);

                nuovaSchedaElement.appendChild(nuovaScheda);
            }

            containerGiorni.appendChild(nuovaSchedaElement);
        }
    }
}


/** se non viene trovata nessuna scheda */
function nessunaScheda(){
    const container = document.getElementById("content");
    while(container.firstChild){
        container.removeChild(container.firstChild);
    }
    let content = document.createElement("div");
    content.className = "card";
    let pContent = document.createElement("p");
    pContent.className = "card-title";
    pContent.style = "width: auto; padding: 2em"
    pContent.innerText = "Nessuna scheda in corso";
    content.appendChild(pContent);
    container.appendChild(content);
}














/* responsive layout */
document.addEventListener("DOMContentLoaded", () => {
    if(window.innerWidth <= 768){
        document.getElementById("content").style.marginTop = document.getElementById("sidebar-logo").clientHeight + "px";
        document.getElementById("content").style.paddingBottom = document.getElementById("sidebar-content").clientHeight + "px";
    }
});
