// set cookie for storing exercises
function setSchedaCookie(esercizi = "temp", giorno = "-1"){
    document.cookie = "esercizi=" + JSON.stringify(esercizi) + ";";
    document.cookie = "giornoScheda=" + giorno + ";";
}

/* visualizza esercizi */
function visualizzaModal(){
    console.log("MODAL");
    
    const modal = document.createElement('div');
    modal.className = 'modal';

    const modalContent = document.createElement('div');
    modalContent.className = 'modal-content';

    const modalTitle = document.createElement('p');
    modalTitle.className = 'modal-title';
    modalTitle.textContent = 'Ben fatto!';

    const message = document.createElement('p');
    message.textContent = 'Allenamento di oggi concluso';

    const form = document.createElement('form');
    form.id = 'inviaFeedback';

    const label = document.createElement('label');
    label.setAttribute('for', 'commenti');
    label.textContent = 'Inserisci qui eventuali messaggi per il coach';

    const textarea = document.createElement('textarea');
    textarea.setAttribute('placeholder', 'Messaggia il tuo coach...');
    textarea.id = 'commenti';
    textarea.name = 'commenti';
    textarea.rows = '3';

    const button = document.createElement('button');
    button.type = 'button';
    button.id = 'inviaMessaggio';
    button.textContent = 'Invia feedback';

    form.appendChild(label);
    form.appendChild(textarea);
    form.appendChild(button);

    modalContent.appendChild(modalTitle);
    modalContent.appendChild(message);
    modalContent.appendChild(form);

    modal.appendChild(modalContent);

    document.getElementById('modal').appendChild(modal);

    
    document.getElementById("inviaMessaggio").addEventListener("click", () => {
        const messaggioCoach = document.getElementById("commenti").value;
        if(messaggioCoach === ''){ return; }

        const messageId = "invioCommentiCoach";
        fetch(
            '../php/gestione_DB.php',
            {
                method: 'POST',
                headers: {'Content-Type': 'application/json; charset=utf-8'},
                body: JSON.stringify(
                    {"messageId": messageId, 
                    "user" : getCookie("username"),
                    "commenti" : messaggioCoach
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
            window.alert("Feedback inviato correttamente");
        })
        .catch((error) => {
            window.alert(error.message);
        });

    });

}

function richiestaEsercizioAllenamento(){
    const messageId = "richiestaEsercizioAllenamento";

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
        setSchedaCookie(json["scheda"], json["giorno"]);
        if(json["scheda"].length < 1){ rimuoviLoaderEModal(); nessunaScheda(); return; }
        visualizzaEsercizio();
    })
    .catch((error) => {
        window.alert(error.message);
    });
}

function visualizzaEsercizio() {
    
    let esercizi = JSON.parse(getCookie("esercizi"));
    const giorno = JSON.parse(getCookie("giornoScheda"));
    
    if(esercizi.length === 0){ 
        visualizzaModal(); 
        return;
    }

    const giornoScheda = document.getElementById("giorno-scheda");
    giornoScheda.innerText = "Giorno: " + (parseInt(giorno) + 1);
    giornoScheda.setAttribute("data-valore-giorno", parseInt(giorno) + 1);

    const container = document.querySelector('.list');
    container.innerHTML = '';

    const nomeEsercizio = document.getElementById("nome-esercizio");
    nomeEsercizio.innerText = esercizi[0]["nome"];
    nomeEsercizio.setAttribute("id-esercizio", esercizi[0]["id_esercizio"]);

    for (let j = 1; j <= esercizi[0]["serie"]; j++) {
        let nuovaSchedaElement = document.createElement('li');
        nuovaSchedaElement.className = 'task';

        let serieErep = document.createElement('div');
        serieErep.className = 'serieErep';
        let seriePara = document.createElement('p');
        seriePara.textContent = 'Serie: ' + j;
        let repPara = document.createElement('p');
        repPara.textContent = 'Ripetizioni: ' + esercizi[0]["ripetizioni"];
        serieErep.appendChild(seriePara);
        serieErep.appendChild(repPara);

        let insertWeight = document.createElement('div');
        insertWeight.className = 'insert-weight';
        let label = document.createElement('label');
        label.setAttribute('for', 'insert-weight');
        label.textContent = 'Carico (Kg): ';
        let input = document.createElement('input');
        input.type = 'number';
        input.name = 'insert-weight';
        input.setAttribute('serie-corrispondente', j);

        insertWeight.appendChild(label);
        insertWeight.appendChild(input);

        nuovaSchedaElement.appendChild(serieErep);
        nuovaSchedaElement.appendChild(insertWeight);

        container.insertAdjacentElement("beforeend", nuovaSchedaElement);
    }


    // Rimuove l'esercizio corrente dall'array e aggiorna il cookie
    esercizi.shift();
    setSchedaCookie(esercizi, giorno);
    if(document.getElementById("nome-esercizio").textContent != ''){
        document.querySelector(".loader-container").style.display = 'none';
        document.getElementById("content").style.display = 'flex';
    }

}

document.addEventListener("DOMContentLoaded", () => {
    richiestaEsercizioAllenamento();

    document.getElementById("next-exercise").addEventListener("click", () => {
        // inserisco esercizio in allenamento
        const messageId = "inserimentoAllenamentoEsercizio";
        const weights = document.querySelectorAll('[name="insert-weight"]');
        const carichi = [];
        for (let i = 0; i < weights.length; i++) {
            carichi.push(weights[i].value);
        }

        fetch(
            '../php/gestione_DB.php',
            {
                method: 'POST',
                headers: {'Content-Type': 'application/json; charset=utf-8'},
                body: JSON.stringify(
                    {"messageId": messageId, 
                    "user" : getCookie("username"),
                    "esercizio" : document.getElementById("nome-esercizio").getAttribute("id-esercizio"),
                    "giorno" : parseInt(document.getElementById("giorno-scheda").getAttribute("data-valore-giorno")) - 1,
                    "carichi" : carichi
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

            // Controlla se ci sono più esercizi prima di visualizzare il prossimo esercizio
            let esercizi = JSON.parse(getCookie("esercizi"));
            if (esercizi.length === 0) {
                visualizzaModal();
            } else {
                visualizzaEsercizio();
            }
        })
        .catch((error) => {
            window.alert(error.message);
        });

    });
});



document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("inviaFeedback").addEventListener("submit", (e) =>{
        e.preventDefault();
    });
});


/** rimozione loader e modal*/
function rimuoviLoaderEModal(){
    document.getElementById("modal").remove();
    document.getElementsByClassName("loader-container")[0].remove();
    let container = document.getElementById("content");
    container.style.display = "flex";
    container.style.height = "auto";
    container.style.alignContent = "center";
    container.style.justifyContent = "space-around";
    container.style.padding = "1em 0";
}








/* Responsive layout */
function responsive(){
    if(window.innerWidth <= 768){
        document.getElementById("content").style.marginTop = document.getElementById("sidebar-logo").clientHeight + "px";
        document.getElementById("content").style.paddingBottom = document.getElementById("sidebar-content").clientHeight + "px";
    }
}
document.addEventListener("DOMContentLoaded", responsive);

document.addEventListener("DOMContentLoaded", () => {
    window.addEventListener("resize", responsive);
});
