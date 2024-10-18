// open nested menu
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("open-nested-menu-cd").addEventListener("click", (e) => {
        e.stopPropagation();
        document.getElementById("nested-menu").classList.remove("slide-out-bottom"); 
        document.getElementById("nested-menu").classList.remove("slide-out-left"); 
        if(window.innerWidth <= 768){
            document.getElementById("nested-menu").classList.add("slide-in-bottom");
        }
        else{
            document.getElementById("nested-menu").classList.add("slide-in-left");  
        }
        document.getElementById("nested-menu").style.display = "grid";
        if(window.innerWidth <= 768){
            document.getElementById("nested-menu").classList.remove("slide-out-bottom");  
        }
        else{
            document.getElementById("nested-menu").classList.remove("slide-out-left");  
        }
        
    });
});

// close messages and nested menu
document.addEventListener("DOMContentLoaded", () => {
    document.onclick = function (e) {
        if(document.getElementById("messages").style.display !== ""){
            if (e.target.id !== "message-dash" && e.target.id === "messages") {
                if(window.innerWidth <= 768){ 
                    document.getElementById("message-dash").classList.add("slide-out-bottom");
                }
                else{ 
                    document.getElementById("message-dash").classList.add("slide-out-right");
                }
                const animationEndHandler = function () {
                    document.getElementById("messages").style.display = "";
                    document.getElementById("message-dash").removeEventListener("animationend", animationEndHandler);
                };
                document.getElementById("message-dash").addEventListener("animationend", animationEndHandler);
            }
        }
        if(document.getElementById("nested-menu").style.display != ""){
            if (e.target !== document.getElementById("nested-menu") && !document.getElementById("nested-menu").contains(e.target)) {
                document.getElementById("nested-menu").classList.remove("slide-in-bottom"); 
                document.getElementById("nested-menu").classList.remove("slide-in-left"); 
                if(window.innerWidth <= 768){
                    document.getElementById("nested-menu").classList.add("slide-out-bottom");
                }
                else{
                    document.getElementById("nested-menu").classList.add("slide-out-left");
                }
                const animationEndHandler = function () {
                    document.getElementById("nested-menu").style.display = "";
                    document.getElementById("nested-menu").removeEventListener("animationend", animationEndHandler);
                };
                document.getElementById("nested-menu").addEventListener("animationend", animationEndHandler);
            }
        }
    }
});


// open messages
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("msg-btn").addEventListener("click", () => {
        if(window.innerWidth <= 768){
            document.getElementById("message-dash").classList.add("slide-in-bottom");
        }
        else{
            document.getElementById("message-dash").classList.add("slide-in-right");  
        }
        document.getElementById("messages").style.display = "flex";
        if(window.innerWidth <= 768){
            document.getElementById("message-dash").classList.remove("slide-out-bottom");  
        }
        else{
            document.getElementById("message-dash").classList.remove("slide-out-right");  
        }
    });
});


// gestione messaggi
document.addEventListener("DOMContentLoaded", () => {
    let bottoni_chiudi = document.querySelectorAll(".close-msg");
    for (let bottone of bottoni_chiudi) {
        bottone.addEventListener("click", bottoneChiudiEvento);
    }
});

function bottoneChiudiEvento(e) {

    const messageId = "setVistoMessaggi";
    const targetMessage = e.target.getAttribute("data-message-id");
    
    fetch(
        '../php/gestione_DB.php',
        {
            method: 'POST',
            headers: {'Content-Type': 'application/json; charset=utf-8'},
            body: JSON.stringify(
                {
                    "messageId" : messageId,
                    "messaggio" : targetMessage
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
            document.querySelector('[data-message-id="' + targetMessage + '"]').parentElement.parentElement.remove();


        })
        .catch((error) => {
            window.alert(error.message);
        });
    controllaFineMessaggi();
}

function controllaFineMessaggi(){
    let messaggi = document.getElementsByClassName("message-view");
    console.log(messaggi.length);
    if(messaggi.length < 2){
        let noMessageInfo = document.createElement('div');
        noMessageInfo.className = 'no-message';

        let divContent = document.createElement('p');
        divContent.innerHTML = `
        <i class="material-icons">&#xe88e;</i> Nessun nuovo messaggio.
        `;

        noMessageInfo.appendChild(divContent);
        const messagePanelContent = document.getElementById("message-list");
        messagePanelContent.appendChild(noMessageInfo);
    }
}