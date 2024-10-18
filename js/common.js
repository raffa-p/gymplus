document.addEventListener("DOMContentLoaded", () => {
    let linkAdd = "./";
    if(document.URL === "http://localhost/index.php" || document.URL === "http://localhost/"){
        linkAdd += "php/";
    }
    if(document.getElementById("new-routine-redirect")){
        document.getElementById("new-routine-redirect").addEventListener("click", () => {
            document.cookie = "cliente-target = none";
            window.location = "./nuova-scheda.php";
        });
    }
    if(document.getElementById("new-routine-redirect-btn")){
        document.getElementById("new-routine-redirect-btn").addEventListener("click", () => {
            document.cookie = "cliente-target = none";
            window.location = "./nuova-scheda.php";
        });
    }
    if(document.getElementById("clients-list-redirect")){
        document.getElementById("clients-list-redirect").addEventListener("click", () => {
            window.location = "./rubrica.php";
        });
    }
    if(document.getElementById("clients-list-btn")){
        document.getElementById("clients-list-btn").addEventListener("click", () => {
            window.location = "./rubrica.php";
        });
    }
    if(document.getElementById("allenamento-redirect")){
        document.getElementById("allenamento-redirect").addEventListener("click", () => {
            window.location = linkAdd + "allenamento.php";
        });
    }
    if(document.getElementById("allenamento-btn")){
        document.getElementById("allenamento-btn").addEventListener("click", () => {
            window.location = linkAdd + "allenamento.php";
        });
    }
    if(document.getElementById("visualizza-scheda-redirect")){
        document.getElementById("visualizza-scheda-redirect").addEventListener("click", () => {
            window.location = linkAdd + "visualizza-scheda.php";
        });
    }
    if(document.getElementById("visualizza-scheda-btn")){
        document.getElementById("visualizza-scheda-btn").addEventListener("click", () => {
            window.location = linkAdd + "visualizza-scheda.php";
        });
    }
    if(document.getElementById("home-redirect")){
        document.getElementById("home-redirect").addEventListener("click", () => {
            window.location = linkAdd + "redirect.php";
        });
    }
    if(document.getElementById("log-out-btn")){
        document.getElementById("log-out-btn").addEventListener("click", () => {
            window.location = linkAdd + "log-out.php";
        });
    }
    if(document.getElementById("info-btn")){
        document.getElementById("info-btn").addEventListener("click", () => {
            window.location = linkAdd + "documentazione.php";
        });
    }
    if(document.getElementById("settings-btn")){
        document.getElementById("settings-btn").addEventListener("click", () => {
            window.location = linkAdd + "impostazioni.php";
        });
    }
    if(document.getElementById("profile-redirect")){
        document.getElementById("profile-redirect").addEventListener("click", () => {
            window.location = linkAdd + "profilo_utente.php";
        });
    }
});



// open nested menu
document.addEventListener("DOMContentLoaded", () => {
    if(!document.getElementById("open-nested-menu")){
        return;
    }
    document.getElementById("open-nested-menu").addEventListener("click", (e) => {
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

// close nested menu
document.addEventListener("DOMContentLoaded", () => {
    document.onclick = function (e) {
        if(!document.getElementById("nested-menu")){
            return;
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


// get specified cookie
function getCookie(wanted) {
    wanted += "=";
    let cookieList = decodeURIComponent(document.cookie).split(';');
    for(let i = 0; i < cookieList.length; i++) {
      let c = cookieList[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(wanted) == 0) {
        return c.substring(wanted.length, c.length);
      }
    }
    return "";
}

/** nessuna scheda trovata */
function nessunaScheda(){
    const container = document.getElementById("content");
    while(container.firstChild){
        container.removeChild(container.firstChild);
    }
    let content = document.createElement("div");
    content.className = "card";
    let pContent = document.createElement("p");
    pContent.className = "card-title";
    pContent.style = "width: auto; padding: 2em; font-size: 2rem"
    pContent.innerText = "Nessuna scheda in corso";
    content.appendChild(pContent);
    container.appendChild(content);
}


/* retrieve profile picture */
document.addEventListener("DOMContentLoaded", () => {
    const messageId = "retrieveProfilePic";
    fetch('../php/gestione_DB.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json; charset=utf-8'},
        body: JSON.stringify({
            "messageId": messageId,
            "user": getCookie("username")
        })
    })
    .then(response => response.json().then(json => {
        if (!response.ok) {
            console.log(json["pic"]);
            throw new Error("Errore: " + json["esito"] + " Dettagli: " + JSON.stringify(json));
        }
        return json;
    }))
    .then(json => {
        console.log("Risposta del server:", json["esito"]);
        if (json["esito"] === "OK") {
            document.getElementById("output").src = json["pic"];
        } else {
            console.error("Errore nel recupero dell'immagine: " + (json["message"] || "Nessun messaggio di errore"));
        }
    })
    .catch((error) => {
        console.error("Immagine inesistente oppure errore nel recupero dell'immagine:", error.message);
    });
});