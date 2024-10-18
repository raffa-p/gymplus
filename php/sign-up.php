<?php
require_once(dirname(__FILE__) . "/config.php");
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

$error_msg = "";
$ok_flag = false;

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["usr-email"]) && isset($_POST["usr-psw"])){
    
    $usr = trim($_POST["usr-email"]);
    $usr = htmlspecialchars($usr);
    $_SESSION["username"] = $usr;
    
    try {
        // Test connessione al database
        $connection_string = "mysql:host=" . DBHost . ";dbname=" . DBName;
        $pdo = new PDO($connection_string, DBUsername, DBPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $error_msg = "<p>Connessione al database fallita: " . $e->getMessage() . "</p>";
        echo '<div class="alert" role="alert" id="alert">' . $error_msg . 
             '<button type="button" id="close-alert"><i class="material-icons">&#xe872;</i></button></div>';
        http_response_code(503);
        exit();
    }
    try{
        // insert new user in DB
        $query_check = "SELECT 1 FROM users WHERE username= :usr";
        $query_check = $pdo->prepare($query_check);
        $query_check->bindValue(":usr", $usr);
        $query_check->execute();
        $result_check = $query_check->fetch();
    }
    catch(PDOException $e){
        $error_msg = "<p>Impossibile accedere alle informazioni.<br>Riprovare tra qualche minuto</p>";
        echo '<div class="alert" role="alert" id="alert">' . $error_msg . 
             '<button type="button" id="close-alert"><i class="material-icons">&#xe872;</i></button></div>';
        http_response_code(503);
        exit();
    }
    if(!empty($result_check)){
        // existing username
        $error_msg = "<p>Credenziali gia' in uso.</p>
                    Si prega di riprovare oppure <a href='log-in.php'>accedi</a>";
        echo '<div class="alert" role="alert" id="alert">' . $error_msg . 
        '<button type="button" id="close-alert">  
                <i class="material-icons">&#xe872;</i> 
            </button>  
        </div>';
    }
    else{
        try{
            // inserting new user
            $psw = trim($_POST["usr-psw"]);
            $psw = htmlspecialchars($psw);
            $psw = password_hash($psw, PASSWORD_DEFAULT);
            $c_f = isset($_POST["usr-coach"]);
            $nome = trim($_POST["usr-name"]);
            $nome = htmlspecialchars($nome);
            $cognome = trim($_POST["usr-surname"]);
            $cognome = htmlspecialchars($cognome);
            $query_insert = "INSERT INTO users(username, password, coach, nome, cognome) VALUES(:usr, :psw, :c_f, :n, :c)";
            $query_insert = $pdo->prepare($query_insert);
            $query_insert->bindValue(":usr", $usr);
            $query_insert->bindValue(":psw", $psw);
            $query_insert->bindValue(":c_f", $c_f);
            $query_insert->bindValue(":n", $nome);
            $query_insert->bindValue(":c", $cognome);
            $query_insert->execute();

            if($c_f){
                // inserting coach personal code
                function randomCode() {
                    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                    $pass = array();
                    $alphaLength = strlen($alphabet) - 1;
                    for ($i = 0; $i < 10; $i++) {
                        $n = rand(0, $alphaLength);
                        $pass[] = $alphabet[$n];
                    }
                    return implode($pass);
                }
                do{
                    $query_insert = "INSERT INTO dettagli_coach VALUES(:usr, :cod)";
                    $query_insert = $pdo->prepare($query_insert);
                    $query_insert->bindValue(":usr", $usr);
                    $query_insert->bindValue(":cod", randomCode());
                    $query_insert->execute();
                    echo "errorCode = " . $query_insert->errorCode();
                }
                while($query_insert->errorCode() != 0000);

                // open modal
                $ok_flag = true;
                echo '<div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close" id="close-modal">&times;</span>
                            <p class="modal-title">Registrazione avvenuta con successo</p>
                            <p>Puoi trovare il tuo codice da condividere al cliente nella tua area personale</p>
                            <button type="button" class="button" id="send-to-log-in">Accedi</button>
                        </div>
                    </div>';
            }
            else{
                echo'<div id="myModal" class="modal">
                <div class="modal-content">
                    <p class="modal-title">Hai quasi finito</p>
                    <label for="coach-code">Inserisci il codice del tuo personal trainer</label>
                    <form method="POST" action="sign-up.php" id="inserimento-codice">
                        <input type="text" id="coach-code" name="coach-code" pattern="[a-zA-Z0-9-]+{10}" required></input>
                        <input type="submit" class="button" id="send-to-log-in" value="Conferma">
                    </form>
                </div>
            </div>';
            }
        }
        catch(PDOException){
            $error_msg = "<p>Servizio momentaneamente non disponibile.</p>
                            Si prega di riprovare tra qualche minuto.";
                echo '<div class="alert" role="alert" id="alert">' . $error_msg . 
                '<button type="button" id="close-alert">  
                        <i class="material-icons">&#xe872;</i> 
                    </button>  
                </div>';
            http_response_code(503);
        }
    }
}
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["coach-code"])){
    try{
        // connection to DB
        $connection_string = "mysql:host=" . DBHost . ";dbname=" . DBName;
        $pdo = new PDO($connection_string, DBUsername, DBPassword);

        //finding code-related coach
        $query_search = "SELECT coach FROM dettagli_coach WHERE codice = :cod";
        $query_search = $pdo->prepare($query_search);
        $query_search->bindValue(":cod", $_POST["coach-code"]);
        $query_search->execute();
        $r = $query_search->fetch();
        if(empty($r)){
            $error_msg = "<p>Codice personal trainer errato.</p>
                        Si prega di riprovare.";
            echo '<div class="alert" role="alert" id="alert">' . $error_msg . 
            '<button type="button" id="close-alert">  
                    <i class="material-icons">&#xe872;</i> 
                </button>  
            </div>';
            echo'<div id="myModal" class="modal">
                <div class="modal-content">
                    <p class="modal-title">Hai quasi finito</p>
                    <label for="coach-code">Inserisci il codice del tuo personal trainer</label>
                    <form method="POST" action="sign-up.php" id="inserimento-codice">
                        <input type="text" id="coach-code" name="coach-code" pattern="[a-zA-Z0-9-]+{10}" required></input>
                        <input type="submit" class="button" id="confirm-coach-code" value="Conferma">
                    </form>
                </div>
            </div>';
        }
        else{
            //inserting relationship client-coach
            $query = "INSERT IGNORE INTO clientela VALUES(:coach, :client)";
            $query = $pdo->prepare($query);
            $query->bindValue(":coach", $r["coach"]);
            $query->bindValue(":client", $_SESSION["username"]);
            $query->execute();
            echo '<div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close" id="close-modal">&times;</span>
                            <p class="modal-title">Registrazione avvenuta con successo</p>
                            <button type="button" class="button" id="send-to-log-in">Accedi</button>
                        </div>
                    </div>';
        }
    }
    catch(PDOException $e){
        $error_msg = "<p>Servizio momentaneamente non disponibile.</p>
                        Si prega di riprovare tra qualche minuto.";
            echo '<div class="alert" role="alert" id="alert">' . $error_msg . 
            '<button type="button" id="close-alert">  
                    <i class="material-icons">&#xe872;</i> 
                </button>  
            </div>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>SIGN-UP</title>
        <meta name="description" content="sign-up page">
        <link rel="stylesheet" type="text/css" href="../style/sign-up.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="../js/sign-up.js"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=PT+Sans+Narrow:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>
    <body>
        <form method="POST" action="sign-up.php">
            <div class="card-log-in">
                <div class="card-content">
                    <img class="card-logo" src="../images/gympluslogo.svg" alt="Logo">
                    <div class="container-info">
                        <div class="info-details">
                            <label for="usr-name">Nome</label>
                            <input type="text" name="usr-name" id="usr-name" placeholder="Nome">
                        </div>
                        <div class="info-details">
                            <label for="usr-surname">Cognome</label>
                            <input type="text" name="usr-surname" id="usr-surname" placeholder="Cognome">
                        </div>
                    </div>
                    <div class="container-input">
                        <label for="usr-email">E-mail</label>
                        <input type="email" name="usr-email" id="usr-email" placeholder="E-mail" required>
                    </div>
                    <div class="container-input">
                        <label for="usr-psw">Password</label>   
                        <input type="password" name="usr-psw" id="usr-psw" placeholder="Password" required onkeyup="validate()">
                    </div>
                    <div class="container-input">
                        <label for="usr-psw-check">Conferma password</label>   
                        <input type="password" name="usr-psw-check" id="usr-psw-check" placeholder="Conferma password" required onkeyup="validate()">
                    </div>
                    <span id='message'></span>
                    <div>
                        <input type="checkbox" name="usr-coach" id="usr-coach">
                        <label for="usr-coach">Sei un trainer?</label>
                    </div>
                    <input type="submit" value="Registrati" class="button">
                    <div class="need-help">
                        <p><a href="./log-in.php">Sei gia' registrato? Accedi</a></p>
                        <p><a href="./help-page.php">Hai bisogno di aiuto? Contattaci</a></p>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>