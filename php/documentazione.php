<?php 

if(session_status() == PHP_SESSION_NONE){
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Documentazione</title>
    <meta name="description" content="main page">
        <link rel="stylesheet" type="text/css" href="../style/documentazione.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=PT+Sans+Narrow:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    
    <div class="top-bar">
        <img class="logo" src="../images/logosvgfinito(senzasfondo).svg" alt="Logo-produzione">
        <p>x</p>
        <img class="logo" src="../images/gympluslogo.svg" alt="Logo-gymplus">
    </div>


    <main>
        <div class="main-content">
            <div class="main-content-title">
                <p>Documentazione</p>
            </div>
            <p class="section"><i class="material-icons">&#xe037;</i>Struttura del database:</p>
            <ul>
                <li>allenamento(<u>user</u>, <u>scheda</u>, <u>giorno</u>, <u>esercizio</u>, <u>serie</u>, carico, <u>timestamp</u>)</li>
                <li>clientela(<u>coach</u>, <u>cliente</u>)</li>
                <li>dettagli_coach(<u>coach</u>, <u>codice</u>)</li>
                <li>dettagli_user(<u>username</u>, foto_profilo)</li>
                <li>esercizi(<u>id_esercizio</u>, nome, gruppo_muscolare)</li>
                <li>post_allenamento(<u>coach</u>, <u>timestamp</u>, <u>user</u>, commenti, visto)</li>
                <li>scheda(<u>id_scheda</u>, <u>coach</u>, <u>user</u>, data_rilascio, annotazioni)</li>
                <li>scheda_dettaglio(<u>id_scheda</u>, <u>id_esercizio</u>, n_serie, n_reps, giorno, <u>id</u>)</li>
                <li>to_do_list(<u>id</u>, <u>user</u>, task)</li>
                <li>users(<u>username</u>, password, coach, nome, cognome, data_nascita, telefono)</li>
                
            </ul>

            <p class="section"><i class="material-icons">&#xe037;</i>Descrizione progetto:</p>
            <ul>
                <li>Questo sito nasce con lo scopo di essere utilizzato come diario per i propri allenamenti in palestra</li>
                <li>Si propone di mettere in contatto i personal trainer con i propri clienti</li>
                <li>L'utente-cliente puo' registrarsi inserendo il codice fornitogli dal personal trainer per stabilire una relazione all'interno del sito</li>
                <li>L'utente-cliente puo' visualizzare le schede create dal personal trainer, visualizzare i progressi, avere una gestione automatica degli allenamenti con la possibilita' di inviare dei feedback al proprio coach</li>
                <li>L'utente-coach puo' registrarsi selezionando l'opzione coach relativa; in tale occasione gli viene attribuito un codice da condividere con i propri clienti</li>
                <li>L'utente coach puo' visualizzare la lista dei propri clienti, inserire nuove sche di allenamento, visualizzare i messaggi da parte dei clienti</li>
                <li>Sia il cliente che il coach hanno a disposizione una pagina con il proprio profilo in cui e' possibile modifcare l'immagine del profilo e aggiungere dei promemoria</li>
                <li>Nella pagina del profilo, per l'utente-coach e' anche possibile visualizzare il proprio codice personale</li>
                <li>Sia il cliente che il coach hanno a disposizione una pagina di impostazioni in cui hanno la possibilita' di cambiare i propri dati personali e le informazioni di accesso</li>                
            </ul>
            
            <button id="home-button">Home</button>
        </div>

    </main>

    <script src="../js/documentazione.js"></script>

</body>
</html>