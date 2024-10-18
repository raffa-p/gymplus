<?php
require_once(dirname(__FILE__) . "/config.php");
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    if(!isset($_SESSION["username"])){
        header("Location: ./log-in.php");
    }
    if(!$_SESSION["coach"]){
        header("Location: ./redirect.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>HOME - COACH</title>
        <meta name="description" content="main page for coach">
        <link rel="stylesheet" type="text/css" href="../style/coach-dash.css">
        <script src="../js/common.js"></script>
        <script src="../js/coach-dash.js"></script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=PT+Sans+Narrow:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script>document.cookie = "username=<?php echo $_SESSION["username"]; ?>"</script>
    </head>

    <body>

    <main>

        <div class="sidebar card">
           <div class="sidebar-logo">
            <img class="logo" src="../images/gympluslogo.svg" alt="Logo">
           </div>
            <div class="sidebar-content">
                <div class="sidebar-link">
                    <button type="button" id="home-redirect">
                        <img src="../icon/home-button.png" alt="home button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button type="button" id="clients-list-redirect">
                        <img src="../icon/sort.png" alt="rubrica button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button type="button" id="new-routine-redirect">
                        <img src="../icon/plus.png" alt="nuova scheda button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button type="button" id="profile-redirect">
                        <img src="../icon/user.png" alt="profilo button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button class="container-nested-menu" id="open-nested-menu-cd">
                        <img src="../icon/social-media.png" alt="open nested menu button">
                    </button>
                    <div class="nested-menu" id="nested-menu">
                        <img class="nested-menu-arrows" src="../icon/triple-arrows.png" alt="triple arrows icon">
                        <div class="nested-menu-links">
                            <div class="nested-menu-element">
                            <button type="button" id="info-btn"><i class="material-icons">&#xe88f;</i>Info</button>
                            </div>
                            <div class="nested-menu-element">
                                <button type="button" id="settings-btn"><i class="material-icons">&#xe8b8;</i>Impostazioni</button>
                            </div>
                            <div class="nested-menu-element">
                                <button type="button" id="log-out-btn"><i class="material-icons">&#xe879;</i>Log out</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>

        <div class="content">
            <div class="card">
                <div class="card-content">
                    <div class="card-title card-welcome">
                        <p class="card-title card-welcome-p">
                            Ciao, <?php echo $_SESSION["nome_usr"] ?>
                        </p>
                        <img src="../icon/user.png" id="output" alt="profile image">
                    </div>
                    <p>
                        Visualizza i messaggi dai tuoi clienti.
                    </p>
                    <div class="container-button">
                        <button class="card-button" id="msg-btn">
                            Messaggi
                        </button>
                    </div>
                </div>
            </div>
            <div class="card">
                    <div class="card-content">
                        <img src="../icon/contact-list.png" class="card-icon" alt="rubrica icon">
                        <p class="card-title">
                            Lista clienti
                        </p>
                        <p>
                            Visualizza l'elenco dei tuoi clienti.
                        </p>
                        <div class="container-button">
                            <button class="card-button" id="clients-list-btn">
                                Rubrica
                            </button>
                        </div>
                    </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <img src="../icon/plus.png" class="card-icon" alt="nuova scheda icon">
                    <p class="card-title">
                        Programmazioni
                    </p>
                    <p>
                        Inserisci dei nuovi programmi.
                    </p>
                    <div class="container-button">
                        <button class="card-button" id="new-routine-redirect-btn">
                            Inserisci
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="messages" class="modal">
            <div class="modal-content" id="message-dash">
                <div class="message-dash-title">
                    <span>Messaggi</span>
                </div>
                <div class="message-list" id="message-list">
                    <?php
                        try {
                            // connection to DB
                            $connection_string = "mysql:host=" . DBHost . ";dbname=" . DBName;
                            $pdo = new PDO($connection_string, DBUsername, DBPassword);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
                            // Check for messages
                            $query_string = "SELECT COUNT(*) as numeroMessaggi
                                             FROM post_allenamento INNER JOIN users ON post_allenamento.user = users.username
                                             WHERE visto = 0 AND post_allenamento.coach = :coach_";
                            $query_string = $pdo->prepare($query_string);
                            $query_string->bindParam(':coach_', $_SESSION["username"]);
                            $query_string->execute();
                            $result = $query_string->fetch(PDO::FETCH_ASSOC);
        
                            if ($result['numeroMessaggi'] == 0) {
                                echo '<div class="no-message"><p><i class="material-icons">&#xe88e;</i> Nessun nuovo messaggio.</p></div>';
                            } else {
                                $query_string = "SELECT post_allenamento.timestamp, Nome, Cognome, commenti
                                                 FROM post_allenamento INNER JOIN users ON post_allenamento.user = users.username
                                                 WHERE visto = 0 AND post_allenamento.coach = :coach_";
                                $query_string = $pdo->prepare($query_string);
                                $query_string->bindParam(':coach_', $_SESSION["username"]);
                                $query_string->execute();
        
                                while ($result_check = $query_string->fetch(PDO::FETCH_ASSOC)) {
                                    if (!is_null($result_check["commenti"])) {
                                        echo '
                                        <div class="message-view" id="message-' . htmlspecialchars($result_check["timestamp"]) . '">
                                            <p>
                                                <small>DA: </small><span id="cognome">' 
                                                . htmlspecialchars($result_check["Cognome"]) . '</span> <span id="nome">' . htmlspecialchars($result_check["Nome"]) . 
                                            '</span></p>
                                            <p>
                                                <small>OGGETTO:</small> note di fine allenamento
                                            </p>
                                            <p id="nota">' . htmlspecialchars($result_check["commenti"]) .'</p>
                                            <div class="close-msg-container">
                                                <button type="button" class="close-msg" data-message-id="' . htmlspecialchars($result_check["timestamp"]) . '">Chiudi</button>
                                            </div>
                                        </div>';
                                    }
                                }
                            }
                        } catch (PDOException $e) {
                            http_response_code(503);
                            echo '<div class="no-message"><p>Errore nella connessione al database.</p><p>' . $e . '</p></div>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </main>


    </body>

</html>