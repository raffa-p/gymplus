<?php
require_once(dirname(__FILE__) . "/config.php");
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    if(!isset($_SESSION["username"])){
        header("Location: ./php/log-in.php");
    }
    if(!$_SESSION["coach"]){
        header("Location: ./php/redirect.php");
    }

?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>PROGRAMMAZIONI</title>
        <meta name="description" content="page for inserting new training routines">
        <link rel="stylesheet" type="text/css" href="../style/nuova-scheda.css">
        <script src="../js/nuova-scheda.js"></script>
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
        <div class="sidebar card" id="sidebar">
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
                    <button type="button" id="clients-list-redirect">
                        <img src="../icon/sort.png" alt=" rubrica button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button type="button" id="nuova-scheda-redirect">
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

        <div class="content" id="content">
            <div id="top-bar" class="card">
                <div class="scelta-cliente">
                    <label for="seleziona-cliente">Seleziona il cliente</label>
                    <select id="seleziona-cliente" name="lista-clienti">
                        <?php
                            if($_COOKIE["cliente-target"] != "none"){
                                try{
                                    // connection to DB
                                    $connection_string = "mysql:host=" . DBHost . ";dbname=" . DBName;
                                    $pdo = new PDO($connection_string, DBUsername, DBPassword);
                                    // check result set
                                    $query = "SELECT cognome, nome, username
                                                FROM users 
                                                WHERE username = :clt";
                                    $query = $pdo->prepare($query);
                                    $query->bindValue(":clt", $_COOKIE["cliente-target"]);
                                    $query->execute();
                                    $r = $query->fetch();
                                    echo '<option value="' . $r["username"] . '">' . $r["cognome"] . ' ' . $r["nome"] . '</option>';
                                    
                                }
                                catch(PDOException){
                                    http_response_code(503);
                                }
                            }
                            else{
                                echo '<option value="null">--</option>';
                                try{
                                    // connection to DB
                                    $connection_string = "mysql:host=" . DBHost . ";dbname=" . DBName;
                                    $pdo = new PDO($connection_string, DBUsername, DBPassword);
                                    // check result set
                                    $query = "SELECT cognome, nome, username
                                                FROM clientela INNER JOIN users ON clientela.cliente = users.username
                                                WHERE clientela.coach = :co";
                                    $query = $pdo->prepare($query);
                                    $query->bindValue(":co", $_SESSION["username"]);
                                    $query->execute();
                                    while(!empty($r = $query->fetch())){
                                        echo '<option value="' . $r["username"] . '">' . $r["cognome"] . ' ' . $r["nome"] . '</option>';
                                    }
                                }
                                catch(PDOException){
                                    http_response_code(503);
                                }
                            }
                        ?>
                    </select>    
                </div>
                <div class="invio-scheda">
                    <button type="submit" id="invio-scheda-btn">INVIA</button>
                </div>
            </div>
            <div class="card" id="annotazioni">
                <div class="card-content">
                    <label class="card-title" for="note-scheda">
                        Annotazioni per il cliente
                    </label>
                    <textarea id="note-scheda" placeholder="Inserisci le note per il cliente" name="annotazioni scheda" rows="3"></textarea>
                </div>
            </div>

            <div class="container-giorni"></div>

            <!-- PER AGGIUNGERE I GIORNI -->
            <div id="container-plus-menus-days" class="card">
                <div class="etichetta-btn card-content">
                    <p>Aggiungi o rimuovi giorni</p>
                </div>
                <div class="add-remove-bar" id="add-remove-bar">
                    <div class="container-button add-btn">
                        <button id="newCard-day">
                            <img src="../icon/add.png" alt="aggiungi giorno button">
                        </button>
                    </div>
                    <div class="container-button add-btn">
                        <button id="deleteCard-day">
                            <img src="../icon/minus.png" alt="rimuovi giorno button">
                        </button>
                    </div>
                </div>
            </div>
                        



        </div>
        
    </main>

    </body>

</html>


