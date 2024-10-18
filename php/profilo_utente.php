<?php
require_once(dirname(__FILE__) . "/config.php");
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    if(!isset($_SESSION["username"])){
        header("Location: ./log-in.php");
    }

  
    try{
        // connection to DB
        $connection_string = "mysql:host=" . DBHost . ";dbname=" . DBName;
        $pdo = new PDO($connection_string, DBUsername, DBPassword);

        $query = "SELECT cognome, nome, data_nascita, telefono, coach
                    FROM users
                    WHERE username = :usr";
        $query = $pdo->prepare($query);
        $query->bindValue(":usr", $_SESSION["username"]);
        $query->execute();
        while(!empty($r = $query->fetch())){
            $_SESSION["cognome"] = $r["cognome"];
            $_SESSION["nome"] = $r["nome"];
            $_SESSION["coach"] = ($r["coach"] == 0? false: true);
        }
    }
    catch(PDOException){
        http_response_code(503);
    }
                    
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>PROFILO</title>
        <meta name="description" content="profile page for user">
        <link rel="stylesheet" type="text/css" href="../style/profilo_utente.css">
        <script src="../js/common.js"></script>
        <script src="../js/profilo_utente.js"></script>
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
                    <button type="button" id="<?php if($_SESSION["coach"]){ echo "clients-list-redirect"; } else{ echo "visualizza-scheda-redirect"; } ?>">
                        <img src="../icon/sort.png" alt="rubrica button">
                    </button>
                </div>
                <div class="sidebar-link">
                    <button type="button" id="<?php if($_SESSION["coach"]){ echo "new-routine-redirect"; } else{ echo "allenamento-redirect"; } ?>">
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
            <div class="left-column">
                <div class="card">
                    <div class="card-content">
                        <div class="card-title card-welcome">
                            
                        <div class="profile-pic">
                            <label class="-label" for="file">
                                <span><i class="material-icons">&#xe3b0;</i></span>
                                <span>Change Image</span>
                            </label>
                            <input id="file" type="file" accept="image/png, image/jpeg" onchange="loadPic(event)">
                            <img src="../icon/user.png" id="output" alt="profile image">
                        </div>
                        
                            <p class="card-title card-welcome-p">
                                <?php echo $_SESSION["nome_usr"] . " " . $_SESSION["cognome"] ?>
                            </p>
                      



                        </div>
                    </div>
                </div>
                <?php
                    if($_SESSION["coach"]){
                        try{
                            // connection to DB
                            $connection_string = "mysql:host=" . DBHost . ";dbname=" . DBName;
                            $pdo = new PDO($connection_string, DBUsername, DBPassword);
                    
                            $query = "SELECT codice
                                        FROM dettagli_coach
                                        WHERE coach = :usr";
                            $query = $pdo->prepare($query);
                            $query->bindValue(":usr", $_SESSION["username"]);
                            $query->execute();
                            
                            echo '<div class="card">
                                    <div class="card-content" id="card-stats">
                                        <p class="card-title" style="padding-bottom: 5px;">Codice Coach</p>
                                        <p>Codice personale da far inserire al cliente:</p>
                                        <div id="codice-coach">
                                            <p id="codice-coach-text">' . $query->fetch()["codice"] . '</p>
                                            <button id="copy-button"><i class="material-icons">&#xe14d;</i></button>
                                        </div>
                                    </div>
                                </div>';
                        }
                        catch(PDOException){
                            http_response_code(503);
                        }
                    }  
                ?>
            </div>
            <div class="right-column">
            <div class="card" id="to-do">
                <div class="card-content">
                    <p class="card-title">
                        TO-DO List
                    </p>
                    <div class="insert-to-do">
                        <form>
                            <input class="todo-input" id="new-task" type="text" placeholder="Aggiungi un task">
                            <button class="card-button" type="submit" id="add-task">Conferma</button>
                        </form>
                    </div>
                    <div class="container-todo-list">
                        <ul class="todo-list" id="task-list">
                            <?php
                                try{
                                    // connection to DB
                                    $connection_string = "mysql:host=" . DBHost . ";dbname=" . DBName;
                                    $pdo = new PDO($connection_string, DBUsername, DBPassword);
                            
                                    $query = "SELECT *
                                                FROM to_do_list
                                                WHERE user = :usr";
                                    $query = $pdo->prepare($query);
                                    $query->bindValue(":usr", $_SESSION["username"]);
                                    $query->execute();
                                    
                                    while(!empty($r = $query->fetch())){
                                        echo '<div class="task">
                                                <li>'. $r["task"] . '</li>
                                                <button class="check-task" data-task-id="'. $r["id"] . '"><i class="material-icons">&#xe5ca;</i></button>
                                                </div>';
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
        </div>
        
    </main>


    </body>

</html>