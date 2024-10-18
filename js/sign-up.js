function validate(){
    if(document.getElementById("usr-psw").value === document.getElementById("usr-psw-check").value){
        document.getElementById('message').style.color = 'green';
        document.getElementById('message').innerText = 'Corretto';
    }
    else{
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerText = 'Le password non corrispondono';
    };
}

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("close-alert").addEventListener("click", () => {
        document.getElementById("alert").remove();
    })
});

// modal
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("close-modal").addEventListener("click", () => {
        document.getElementById("myModal").style.display = "none";
    })
});

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("send-to-log-in").addEventListener("click", () => {
        window.location = "log-in.php";
    })
})