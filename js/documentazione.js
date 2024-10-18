document.getElementById("home-button").addEventListener("click", function(){
    window.location = "http://localhost/php/redirect.php";
});

document.addEventListener("DOMContentLoaded", () => {
    document.querySelector(".main-content").style.top = document.querySelector(".top-bar").offsetHeight + "px";
});