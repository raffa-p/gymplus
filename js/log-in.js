document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("close-alert").addEventListener("click", () => {
        document.getElementById("alert").remove();
    });
});
