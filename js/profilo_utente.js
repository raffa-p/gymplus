/* edit profile picture */
function loadPic(event) {
    let image = document.getElementById("output");
    const pic = event.target.files[0];
    image.src = URL.createObjectURL(pic);

    const messageId ="updateProfilePic";
    const reader = new FileReader();
    reader.onloadend = () => {
        fetch('../php/gestione_DB.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json; charset=utf-8'},
            body: JSON.stringify({
                "messageId": messageId, 
                "user": getCookie("username"),
                "pic": reader.result 
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
        })
        .catch((error) => {
            window.alert(error.message);
        });
    }
    reader.readAsDataURL(pic);
}




// create todo
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("add-task").addEventListener("click", (e) => {
        e.preventDefault();
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
            retrieveTasks();
        })
        .catch((error) => {
            window.alert(error.message);
        });


    });
});

// retrieve tasks
function retrieveTasks(){
    const messageId = "visualizzaTask";
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
            const taskList = document.getElementById("task-list");
            taskList.innerHTML = ""; 
            taskList.innerHTML = json["tasks"];
            let bottoni_chiudi = document.querySelectorAll(".check-task");
            for (let bottone of bottoni_chiudi) {
                bottone.addEventListener("click", bottoneChiudiEvento);
            }
        })
        .catch((error) => {
            window.alert(error.message);
        });
}


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

  


document.addEventListener("DOMContentLoaded", () => {
    if(window.innerWidth <= 768){
        document.getElementById("content").style.marginTop = document.getElementById("sidebar-logo").clientHeight + "px";
        document.getElementById("content").style.paddingBottom = document.getElementById("sidebar-content").clientHeight + "px";
    }
});

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("copy-button").addEventListener("click", () => {
        navigator.clipboard.writeText(document.getElementById("codice-coach-text").innerText);
    });
});
