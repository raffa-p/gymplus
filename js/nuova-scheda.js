// insert new card day
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("newCard-day").addEventListener("click", () => {
        let nuovaSchedaElement = document.createElement('div');
        nuovaSchedaElement.className = 'giorno';

        let numeroGiorno = document.querySelectorAll(".giorno").length;
        if(numeroGiorno>5){ return; }

        let giornoEtichetta = document.createElement('div');
        giornoEtichetta.className = 'giorno-etichetta';

        let giornoEtichettaP = document.createElement('p');
        giornoEtichettaP.textContent = 'Giorno ' + (numeroGiorno + 1);

        giornoEtichetta.appendChild(giornoEtichettaP);

        let containerPlusMenus = document.createElement('div');
        containerPlusMenus.className = 'container-plus-menus';

        let addRemoveBar = document.createElement('div');
        addRemoveBar.className = 'add-remove-bar card';
        addRemoveBar.id = 'add-remove-bar';

        let addButtonContainer = document.createElement('div');
        addButtonContainer.className = 'container-button add-btn';

        let addButton = document.createElement('button');
        addButton.id = 'newCard-ex';

        let addImg = document.createElement('img');
        addImg.src = '../icon/add.png';
        addImg.alt = 'aggiungi esercizio button';

        addButton.appendChild(addImg);
        addButtonContainer.appendChild(addButton);

        let removeButtonContainer = document.createElement('div');
        removeButtonContainer.className = 'container-button add-btn';

        let removeButton = document.createElement('button');
        removeButton.id = 'deleteCard-ex';

        let removeImg = document.createElement('img');
        removeImg.src = '../icon/minus.png';
        removeImg.alt = 'rimuovi esercizio button';

        removeButton.appendChild(removeImg);
        removeButtonContainer.appendChild(removeButton);

        addRemoveBar.appendChild(addButtonContainer);
        addRemoveBar.appendChild(removeButtonContainer);
        containerPlusMenus.appendChild(addRemoveBar);
        nuovaSchedaElement.appendChild(giornoEtichetta);
        nuovaSchedaElement.appendChild(containerPlusMenus);

        let contentNode = document.querySelectorAll('.container-giorni');
        contentNode[0].insertAdjacentElement("beforeend", nuovaSchedaElement);

        
        // insert new card exercises
        document.querySelectorAll("#newCard-ex")[numeroGiorno].addEventListener("click", () => {
            const messageId = "richiestaEsercizi";
            fetch(
                '../php/gestione_DB.php',
                {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: JSON.stringify({"messageId" : messageId})
                })
                .then(response => response.json().then(json => {
                    if (!response.ok) {
                        throw new Error("Errore: " + json["esito"] + " Dettagli: " + JSON.stringify(json));
                    }
                    return json;
                }))
                .then(json => {
                    console.log("Risposta del server:", json["esito"]);
                    let nuovaSchedaElement = document.createElement('div');
                    nuovaSchedaElement.className = 'card created-card';
                    nuovaSchedaElement.id = 'card-nuovo-esercizio';

                    let cardContentElement = document.createElement('div');
                    cardContentElement.className = 'card-content';

                    let labelSelezione = document.createElement('label');
                    labelSelezione.setAttribute('for', 'selezione-esercizio');
                    labelSelezione.textContent = 'Seleziona esercizio';

                    let selectEsercizio = document.createElement('select');
                    selectEsercizio.id = 'selezione-esercizio';
                    selectEsercizio.name = 'selezione-esercizio';

                    let optionNull = document.createElement('option');
                    optionNull.value = 'null';
                    optionNull.textContent = '--';
                    selectEsercizio.appendChild(optionNull);

                    selectEsercizio.innerHTML += json["text"];

                    let datiEsercizioDiv = document.createElement('div');
                    datiEsercizioDiv.className = 'dati-esercizio';

                    let containerSerie = document.createElement('div');
                    containerSerie.className = 'container-dati';

                    let labelSerie = document.createElement('label');
                    labelSerie.setAttribute('for', 'n-serie');
                    labelSerie.textContent = 'Numero di serie';

                    let inputSerie = document.createElement('input');
                    inputSerie.type = 'number';
                    inputSerie.id = 'n-serie';
                    inputSerie.name = 'n-serie';
                    inputSerie.min = '1';

                    containerSerie.appendChild(labelSerie);
                    containerSerie.appendChild(inputSerie);

                    let containerRipetizioni = document.createElement('div');
                    containerRipetizioni.className = 'container-dati';

                    let labelRipetizioni = document.createElement('label');
                    labelRipetizioni.setAttribute('for', 'n-rep');
                    labelRipetizioni.textContent = 'Numero di ripetizioni';

                    let inputRipetizioni = document.createElement('input');
                    inputRipetizioni.type = 'number';
                    inputRipetizioni.id = 'n-rep';
                    inputRipetizioni.name = 'n-rep';
                    inputRipetizioni.min = '1';

                    containerRipetizioni.appendChild(labelRipetizioni);
                    containerRipetizioni.appendChild(inputRipetizioni);

                    datiEsercizioDiv.appendChild(containerSerie);
                    datiEsercizioDiv.appendChild(containerRipetizioni);

                    cardContentElement.appendChild(labelSelezione);
                    cardContentElement.appendChild(selectEsercizio);
                    cardContentElement.appendChild(datiEsercizioDiv);

                    nuovaSchedaElement.appendChild(cardContentElement);
                    let contentNode = document.querySelectorAll('.giorno')[numeroGiorno];
                    const newCardButton = document.querySelectorAll(".container-plus-menus")[numeroGiorno];
                    contentNode.insertBefore(nuovaSchedaElement, newCardButton);
        
                })
                .catch((error) => {
                    window.alert(error.message);
                });
        });
        // delete latest card exercises
        document.querySelectorAll("#deleteCard-ex")[numeroGiorno].addEventListener("click", () => {
            let ultimaScheda = document.querySelectorAll(".giorno")[numeroGiorno].querySelectorAll(".created-card");
            if(ultimaScheda.length > 0){
                ultimaScheda[ultimaScheda.length - 1].remove();
            }
        });

    });
});

// remove latest card day
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("deleteCard-day").addEventListener("click", () => {
        let ultimaScheda = document.querySelectorAll(".giorno");
        ultimaScheda[ultimaScheda.length - 1].remove();
    });
});






// invio scheda
function inputValidation(lista_esercizi, lista_serie, lista_rep){
    for(let i= 0; i< lista_serie.length; i++){
        if(lista_esercizi[i].value == "--") return false;
        if(isNaN(lista_serie[i].value.trim()) && !isFinite(lista_serie[i].value.trim())) return false;
        if(isNaN(lista_rep[i].value.trim()) && !isFinite(lista_rep[i].value.trim())) return false;
    }
    return true;
}

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("invio-scheda-btn").addEventListener("click", () => {
        let schedaCompleta = new Array();
        let annotazioni = document.getElementById("note-scheda").value.trim();

        let giorni = document.querySelectorAll(".giorno");
        for(let i = 0; i < giorni.length; i++){
            let giorno = giorni[i];
            let lista_esercizi = giorno.querySelectorAll("[name='selezione-esercizio']");
            let lista_serie = giorno.querySelectorAll("[name='n-serie']");
            let lista_rep = giorno.querySelectorAll("[name='n-rep']");
            
            if(!inputValidation(lista_esercizi, lista_serie, lista_rep)){
                let alert_msg = document.createElement('div');
                alert_msg.className = 'alert';
                alert_msg.id = 'alert';
                let alert_text = document.createElement('p');
                alert_text.textContent = 'Sono presenti valori non validi. Correggili e riprova';

                let close_button = document.createElement('button');
                close_button.type = 'button';
                close_button.id = 'close-alert';

                let close_icon = document.createElement('i');
                close_icon.className = 'material-icons';
                close_icon.innerHTML = '&#xe872;'; 

                close_button.appendChild(close_icon);

                alert_msg.appendChild(alert_text);
                alert_msg.appendChild(close_button);
                
                let contentNode = document.querySelector('.content');
                contentNode.appendChild(alert_msg);
                document.getElementById("close-alert").addEventListener("click", () => {
                    document.getElementById("alert").remove();
                });
                return;
            }

            // lista_esercizi[i].value -> restituisce l'id dell'esercizio
            let array = new Array();
            for(let i= 0; i< lista_serie.length; i++){
                array.push({"id_esercizio" : lista_esercizi[i].value, 
                            "n-serie" : (lista_serie[i].value.trim() === ''? 0 : lista_serie[i].value.trim()),
                            "n-rep" : (lista_rep[i].value.trim() === ''? 0 : lista_rep[i].value.trim())});
                            
            }
            schedaCompleta.push(array);
        }
        
        
        const messageId = "invioScheda";
        const cliente = document.getElementById("seleziona-cliente").value;
        if(cliente == "--"){
            cliente = undefined;
        }
        fetch(
            '../php/gestione_DB.php',
            {
                method: 'POST',
                headers: {'Content-Type': 'application/json; charset=utf-8'},
                body: JSON.stringify(
                        {"messageId": messageId, 
                        "data": {
                            "annotazioni" : annotazioni, 
                            "esercizi" : schedaCompleta, 
                            "coach" : getCookie("username"),
                            "cliente" : cliente,
                            "giorni" : (document.querySelectorAll(".giorno").length + 1)
                            }
                        }
                    )
            })
        .then(response => response.json().then(json => {
            if (!response.ok) {
                throw new Error("Errore: " + json["esito"] + " Dettagli: " + json["dettagli"]);
            }
            return json;
        }))
        .then(json => {
            console.log("Risposta del server:", json["esito"]);
            let confirm_msg = document.createElement('div');
            confirm_msg.className = 'alert';
            confirm_msg.id = 'alert';
            let confirm_text = document.createElement('p');
            confirm_text.textContent = 'Scheda inviata correttamente';

            let close_button = document.createElement('button');
            close_button.type = 'button';
            close_button.id = 'close-alert';

            let close_icon = document.createElement('i');
            close_icon.className = 'material-icons';
            close_icon.innerHTML = '&#xe872;'; 

            close_button.appendChild(close_icon);

            confirm_msg.appendChild(confirm_text);
            confirm_msg.appendChild(close_button);

            
            let contentNode = document.querySelector('.content');
            contentNode.appendChild(confirm_msg);
            document.getElementById("close-alert").addEventListener("click", () => {
                document.getElementById("alert").remove();
            });
        })
        .catch((error) => {
            window.alert(error.message);
        });


    });
});





/* responsive layout */
document.addEventListener("DOMContentLoaded", () => {
    if(window.innerWidth <= 768){
        document.getElementById("content").style.marginTop = document.getElementById("sidebar-logo").clientHeight + "px";
        document.getElementById("add-remove-bar").style.bottom = document.getElementsByClassName("sidebar-content")[0].clientHeight + "px";
        document.getElementById("content").style.paddingBottom = (document.getElementById("sidebar-content").clientHeight + document.getElementById("add-remove-bar").clientHeight + 2) + "px";
    }
    else{
        document.getElementById("content").style.paddingLeft = document.getElementById("sidebar").clientWidth + "px";
    }
});