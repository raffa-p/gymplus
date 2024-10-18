<?php
    require_once(dirname(__FILE__) . "/php/config.php");
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    if(!isset($_SESSION["username"])){
        header("Location: ./php/log-in.php");
    }

    if($_SESSION["coach"]){
        header("Location: ./php/redirect.php");
    }

?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>HOME</title>
        <meta name="description" content="main page">
        <link rel="stylesheet" type="text/css" href="style/index.css">
        <script src="js/index.js"></script>
        <script src="js/common.js"></script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=PT+Sans+Narrow:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script src="https://www.gstatic.com/charts/loader.js"></script>
        <script>document.cookie = "username=<?php echo $_SESSION["username"]; ?>"</script>
    </head>

    <body>

    <main>

        <div class="sidebar card">
           <div class="sidebar-logo" id="sidebar-logo">
            <img class="logo" src="images/gympluslogo.svg" alt="Logo">
           </div>
            <div class="sidebar-content" id="sidebar-content">
                <div class="sidebar-link">
                    <button type="button" id="home-redirect">
                        <img src="icon/home-button.png" alt="home button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button type="button" id="visualizza-scheda-redirect">
                        <img src="icon/sort.png" alt="visualizza scheda button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button type="button" id="allenamento-redirect">
                        <img src="icon/plus.png" alt="nuova scheda button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button type="button" id="profile-redirect">
                        <img src="icon/user.png" alt="prifle redirect button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button class="container-nested-menu" id="open-nested-menu">
                        <img src="icon/social-media.png" alt="open nested menu">
                    </button>
                    <div class="nested-menu slide-in-left" id="nested-menu">
                        <img class="nested-menu-arrows" src="./icon/triple-arrows.png" alt="triple arrows image">
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
            <div class="card">
                <div class="card-content">
                    <div class="card-title card-welcome">
                        <p class="card-title card-welcome-p">
                            Ciao, <?php echo $_SESSION["nome_usr"] ?>
                        </p>
                        <img src="../icon/user.png" id="output" alt="profile image">
                    </div>
                    <p>
                        Non perdere altro tempo. Inizia subito ad allenarti!
                    </p>
                    <div class="container-button">
                        <button type="button" class="card-button" id="allenamento-btn">
                            Inizia subito
                        </button>
                    </div>
                </div>
            </div>
            <div class="card">
                    <div class="card-content">
                        <img src="icon/barbell.png" class="card-icon" alt="barbell icon">
                        <p class="card-title">
                            Programmazione
                        </p>
                        <div class="grid-1row-2rows">
                            <div class="grid-1row">
                                <p>Piano di allenamento in corso:</p> 
                            </div>
                            <div class="grid-2rows">
                                <p>
                                    Coach: <?php 
                                        try{
                                            // connection to DB
                                            $connection_string = "mysql:host=" . DBHost . ";dbname=" . DBName;
                                            $pdo = new PDO($connection_string, DBUsername, DBPassword);
                                    
                                            $query = "SELECT users.cognome, users.nome
                                                        FROM clientela INNER JOIN users ON clientela.coach = users.username
                                                        WHERE clientela.cliente = :usr
                                                        AND EXISTS(SELECT 1 FROM scheda s WHERE s.user = clientela.cliente AND s.coach = clientela.coach)";
                                            $query = $pdo->prepare($query);
                                            $query->bindValue(":usr", $_SESSION["username"]);
                                            $query->execute();
                                            $r = $query->fetch();
                                            if(!$r){ echo "----"; }
                                            else{ echo $r["cognome"] . ' ' . $r["nome"]; }
                                        }
                                        catch(PDOException){
                                            http_response_code(503);
                                        }
                                    ?>
                                </p>
                                <p>
                                    Caricato il: <?php
                                       try{
                                        // connection to DB
                                        $connection_string = "mysql:host=" . DBHost . ";dbname=" . DBName;
                                        $pdo = new PDO($connection_string, DBUsername, DBPassword);
                                
                                        $query = "SELECT s.data_rilascio, s.id_scheda
                                                    FROM scheda s
                                                    WHERE s.user = :usr AND s.id_scheda = (SELECT MAX(s1.id_scheda)
                                                                                                FROM scheda s1
                                                                                                WHERE s1.user = s.user)";
                                        $query = $pdo->prepare($query);
                                        $query->bindValue(":usr", $_SESSION["username"]);
                                        $query->execute();
                                        $r = $query->fetch();
                                        if(!$r){ echo "----"; }
                                        else{
                                            $_SESSION["scheda_target"] = $r["id_scheda"];
                                            echo $r["data_rilascio"];
                                        }
                                        }
                                        catch(PDOException){
                                            http_response_code(503);
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="container-button">
                            <button type="button" class="card-button" id="visualizza-scheda-btn">
                                Controlla la programmazione
                            </button>
                        </div>
                    </div>
            </div>
            <div class="card">
                <div class="card-content" id="card-stats">
                    <img src="./icon/trend.png" class="card-icon" alt="trend icon">
                    <p class="card-title" style="padding-bottom: 5px;">
                        Risultati
                    </p>
                    <p id="settimana">
                        <span id="dataInizio"></span> - <span id="dataFine"></span>
                    </p>
                    <div class="statistiche">
                        <div id="statistiche"></div>
                        <div id="dettagli">
                            <div id="dettagli-top">
                            <img src="./icon/exercise.png" alt="exercise icon">
                            </div>
                            <div id="dettagli-bot">
                                <div id="n-activities"></div>
                                Attività
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    </body>

</html>


