<?php
require_once(dirname(__FILE__) . "/config.php");

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>LOG-IN</title>
        <meta name="description" content="log-in page">
        <link rel="stylesheet" type="text/css" href="../style/help-page.css">
        <meta charset="UTF-8">
        <script src="../js/help-page.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=PT+Sans+Narrow:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>
    <body>
        <form id='log-in-form'>
            <div class="card-log-in">
                <div class="card-content">
                    <img class="card-logo" src="../images/gympluslogo.svg" alt="Logo">
                    <input type="email" name="usr-email" id="usr-email" placeholder="Inserisci la tua mail" required>
                    <textarea name="usr-content" id="usr-content" rows="4" placeholder="Informazioni sulla richiesta di assistenza" required></textarea>
                    <input id="invia-btn" type="submit" value="Invia richiesta" class="button">
                </div>
            </div>
        </form>
    </body>
</html>