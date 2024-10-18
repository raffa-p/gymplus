document.addEventListener("DOMContentLoaded", function(){
    document.getElementById("home-redirect").addEventListener("click", function(){
        window.location = "http://localhost/php/coach-dash.php";
    });
});

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("log-out-btn").addEventListener("click", () => {
        window.location = "./log-out.php";
    });
});

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("info-btn").addEventListener("click", () => {
        window.location = "./documentazione.php";
    })
});

// new program redirect
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("new-routine-redirect").addEventListener("click", () => {
        window.location = "./nuova-scheda.php";
    });
});

// settings redirect
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("settings-btn").addEventListener("click", () => {
        window.location = "./impostazioni.php";
    })
});

// profile redirect
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("profile-redirect").addEventListener("click", () => {
        window.location = "./profilo_utente.php";
    })
});
// clients list redirect
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("clients-list-redirect").addEventListener("click", () => {
        window.location = "./rubrica.php";
    });
});
// redirect to specific client in nuova_scheda
document.addEventListener("DOMContentLoaded", () => {
    let bottoni_ = document.querySelectorAll(".specific-redirect");
    for (let bottone of bottoni_) {
        bottone.addEventListener("click", (e) => {
            document.cookie = "cliente-target=" + e.target.getAttribute("client-username");
            window.location = "./nuova-scheda.php";
        });
    }
});



/* responsive layout */
document.addEventListener("DOMContentLoaded", () => {
    if(window.innerWidth <= 768){
        document.getElementById("content").style.marginTop = document.getElementById("sidebar-logo").clientHeight + "px";
        document.getElementById("content").style.paddingBottom = document.getElementById("sidebar-content").clientHeight + "px";
    }
});