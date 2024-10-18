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

        $query = "SELECT cognome, nome, data_nascita, telefono
                    FROM users
                    WHERE username = :usr";
        $query = $pdo->prepare($query);
        $query->bindValue(":usr", $_SESSION["username"]);
        $query->execute();
        while(!empty($r = $query->fetch())){
            $_SESSION["cognome"] = $r["cognome"];
            $_SESSION["nome"] = $r["nome"];
            $_SESSION["data_nascita"] = $r["data_nascita"];
            $_SESSION["telefono"] = $r["telefono"];
        }
    }
    catch(PDOException){
        http_response_code(503);
    }
                    
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>IMPOSTAZIONI</title>
        <meta name="description" content="settings page for user">
        <link rel="stylesheet" type="text/css" href="../style/impostazioni.css">
        <script src="../js/impostazioni.js"></script>
        <script src="../js/common.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=PT+Sans+Narrow:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script>
            // set technical cookies
            document.cookie = "username=<?php echo $_SESSION["username"]; ?>"
            document.cookie = "vecchio-nome=<?php echo $_SESSION["nome"]; ?>"
            document.cookie = "vecchio-cognome=<?php echo $_SESSION["cognome"]; ?>"
            document.cookie = "vecchia-data-nascita=<?php echo $_SESSION["data_nascita"]; ?>"
            document.cookie = "vecchio-telefono=<?php echo $_SESSION["telefono"]; ?>"
        </script>
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
                    <button type="submit" id="profile-redirect">
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
                    <div class="card-content card-dati">
                        <div class="card-title">
                            <p class="card-title">
                                Dati personali
                            </p>
                        </div>
                        <label for="modifica-nome">
                            Nome
                        </label>
                        <input type="text" placeholder="<?php if($_SESSION["nome"] == ""){echo "--------";}else{echo $_SESSION["nome"];}; ?>" id="modifica-nome" name="modifica-nome" class="modifica-dati">
                        <label for="modifica-cognome">
                            Cognome
                        </label>
                        <input type="text" id="modifica-cognome" name="modifica-cognome" class="modifica-dati">
                        <label for="modifica-data-nascita">
                            Data di nascita
                        </label>
                        <input type="date" id="modifica-data-nascita" name="modifica-data-nascita" class="modifica-dati">
                        <label for="modifica-telefono">
                            Telefono
                        </label>
                        <input type="tel" placeholder="<?php if($_SESSION["telefono"] == ""){echo "--------";}else{echo $_SESSION["telefono"];}; ?>" id="modifica-telefono" name="modifica-telefono" class="modifica-dati">
                        <div class="container-button">
                            <button class="card-button" id="personal-info-btn">
                                Salva le modifiche
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-column">
                <div class="card">
                    <div class="card-content card-dati">
                        <p class="card-title">
                            Opzioni di accesso
                        </p>
                        <label for="modifica-email">
                            E-mail
                        </label>
                        <input type="email" placeholder="<?php echo $_SESSION["username"]; ?>" id="modifica-email" name="modifica-email" class="modifica-dati" readonly>
                        <label for="modifica-password-vecchia">
                            Password attuale
                        </label>
                        <input type="password" placeholder="************" id="modifica-password-vecchia" name="modifica-password-vecchia" class="modifica-dati">
                        <label for="modifica-password-nuova">
                            Nuova password
                        </label>
                        <input type="password" placeholder="************" id="modifica-password-nuova" name="modifica-password-nuova" class="modifica-dati">
                        <label for="modifica-password-nuova-conferma">
                            Conferma nuova password
                        </label>
                        <input type="password" placeholder="************" id="modifica-password-nuova-conferma" name="modifica-password-nuova-conferma" class="modifica-dati">
                        <span id='message'></span>

                            <div class="container-button">
                                <button class="card-button" id="access-info-btn">
                                    Salva le modifiche
                                </button>
                            </div>
                    </div>
                </div>
            </div>
            
        </div>
        
    </main>


    </body>

</html>


