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
<html lang="it">
    <head>
        <title>RUBRICA</title>
        <meta name="description" content="clients list page for coach">
        <link rel="stylesheet" type="text/css" href="../style/rubrica.css">
        <script src="../js/rubrica.js"></script>
        <script src="../js/common.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                    <button class="container-nested-menu" id="open-nested-menu">
                        <img src="../icon/social-media.png" alt="open nested menu button">
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
            <div class="card" id="rubrica">
                <div class="card-content">
                    <p class="card-title card-welcome-p">Rubrica</p>
                    <hr>
                    <div class="container-list">
                        <ul class="clients-list">
                            <?php
                                try{
                                    // connection to DB
                                    $connection_string = "mysql:host=" . DBHost . ";dbname=" . DBName;
                                    $pdo = new PDO($connection_string, DBUsername, DBPassword);
                            
                                    $query = "SELECT users.cognome, users.nome, users.username
                                                FROM clientela INNER JOIN users ON clientela.cliente = users.username
                                                WHERE clientela.coach = :usr";
                                    $query = $pdo->prepare($query);
                                    $query->bindValue(":usr", $_SESSION["username"]);
                                    $query->execute();
                                    while(!empty($r = $query->fetch())){
                                        echo '<li>
                                                    <p>'. $r["cognome"] . ' ' . $r["nome"] . '</p>
                                                    <button type="button" class="card-button specific-redirect" client-username="'. $r["username"] .'">Crea scheda</button>
                                                </li><hr>';
                                    }
                                }
                                catch(PDOException){
                                    http_response_code(503);
                                }



                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
        
    </main>


    </body>

</html>