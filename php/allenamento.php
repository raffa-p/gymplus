<?php
require_once(dirname(__FILE__) . "/config.php");
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    if(!isset($_SESSION["username"])){
        header("Location: ./log-in.php");
    }
    if($_SESSION["coach"]){
        header("Location: ./redirect.php");
    }         
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>ALLENAMENTO</title>
        <meta name="description" content="new workout page for user">
        <link rel="stylesheet" type="text/css" href="../style/allenamento.css">
        <script src="../js/allenamento.js"></script>
        <script src="../js/common.js"></script>
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
           <div class="sidebar-logo" id="sidebar-logo">
            <img class="logo" src="../images/gympluslogo.svg" alt="Logo">
           </div>
            <div class="sidebar-content" id="sidebar-content">
                <div class="sidebar-link">
                    <button type="button" id="home-redirect">
                        <img src="../icon/home-button.png" alt="home button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button type="button" id="visualizza-scheda-redirect">
                        <img src="../icon/sort.png" alt="rubrica button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button type="button" id="allenamento-redirect">
                        <img src="../icon/plus.png" alt="nuova scheda button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button type="button" id="profile-redirect">
                        <img src="../icon/user.png" alt="profilo button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button class="container-nested-menu" id="open-nested-menu">
                        <img src="../icon/social-media.png" alt="open nested menu button">
                    </button>
                    <div class="nested-menu slide-in-left" id="nested-menu">
                        <img class="nested-menu-arrows" src="../icon/triple-arrows.png" alt="triple arrows button">
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

        <div class="content" id="content">
            <div id="top-bar" class="card">
                <p id="giorno-scheda" data-valore-giorno=""></p>
            </div>
            <div class="card" id="esercizio-card">
                <div class="card-content">
                    <div class="card-title card-welcome">
                        <p class="card-title card-welcome-p" id="nome-esercizio"></p>
                    </div>
                    <div class="container-list">
                        <ul class="list"></ul>
                    </div>
                    <div class="container-button">
                        <button class="card-button" id="next-exercise">
                            Prossimo esercizio
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="modal"></div>
        <div class="loader-container"><div class="loader"></div></div>
        
    </main>


    </body>

</html>