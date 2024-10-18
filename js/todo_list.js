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

// create todo
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("add-task").addEventListener("click", (e) => {
        //e.preventDefault();
        const newTask = document.getElementById("new-task");
        if(newTask.value == ""){
            return;
        }
        const messageId = "nuovoTask";
        fetch(
            '../php/gestione_DB.php',
            {
                method: 'POST',
                headers: {'Content-Type': 'application/json; charset=utf-8'},
                body: JSON.stringify(
                        {"messageId": messageId, 
                        "user" : getCookie("username"),
                        "data": newTask.value
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
            newTask.value = "";
        })
        .catch((error) => {
            window.alert(error.message);
        });

        

    });
});

// check tasks
document.addEventListener("DOMContentLoaded", () => {
    let bottoni_chiudi = document.querySelectorAll(".check-task");
    for (let bottone of bottoni_chiudi) {
        bottone.addEventListener("click", bottoneChiudiEvento);
    }
});

function bottoneChiudiEvento(e) {

    let selectedTask = e.target.parentElement.getAttribute("data-task-id");
    const messageId = "checkTask";
    
    fetch(
        '../php/gestione_DB.php',
        {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: JSON.stringify({"messageId" : messageId,
                   "task": selectedTask
                })
        })
    .then(response => response.json().then(json => {
        if (!response.ok) {
            throw new Error("Errore: " + json["esito"] + " Dettagli: " + JSON.stringify(json));
        }
        return json;
    }))
    .then(json => {
        console.log("Risposta del server:", json["esito"]);
        e.target.parentElement.parentElement.remove();
    })
    .catch((error) => {
        window.alert(error.message);
    });
}