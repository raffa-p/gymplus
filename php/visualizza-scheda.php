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
        <title>PROGRAMMA IN CORSO</title>
        <meta name="description" content="training schedule page for client">
        <link rel="stylesheet" type="text/css" href="../style/visualizza-scheda.css">
        <script src="../js/visualizza-scheda.js"></script>
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
                        <img src="../icon/sort.png" alt="visualizza scheda button">
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
                        <img src="../icon/social-media.png" alt="nested menu button">
                    </button>
                    <div class="nested-menu slide-in-left" id="nested-menu">
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

        <div class="content" id="content">
            <!-- ANNOTAZIONI -->
            <div class="card" id="annotazioni-container">
                <div class="card-content">
                    <p class="card-title">
                        Annotazioni per il cliente
                    </p>
                    <p id="annotazioni"></p>
                </div>
            </div>

            
            
            <!-- ESERCIZI -->
            <div class="container-giorni"></div>

            
        </div>
        
    </main>


    </body>

</html>