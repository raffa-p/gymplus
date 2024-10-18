<?php
require_once(dirname(__FILE__) . "/config.php");

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
$error_flag = false;

if(isset($_POST["usr-email"]) && isset($_POST["usr-psw"])){
    try{
        // connection to DB
        $connection_string = "mysql:host=" . DBHost . ";dbname=" . DBName;
        $pdo = new PDO($connection_string, DBUsername, DBPassword);
        // check information
        $usr = trim($_POST["usr-email"]);
        $usr = htmlspecialchars($usr);
        $query_string = "SELECT 1 FROM users WHERE username = :usr";
        $query_string = $pdo->prepare($query_string);
        $query_string->bindValue(":usr", $usr);
        $query_string->execute();
        $result_check = $query_string->fetch();
        if(empty($result_check)){
            echo '<script type+"text/javascript>
            document.addEventListener("DOMContentLoaded", () => {
                console.log("dentro");
                const container = document.getElementById("message-error");
                let subContainer = document.createElement("div");
                subContainer.className = "alert";
                subContainer.innerText = "Utente non trovato. Si prega di riprovare oppure ";
                let link = document.createElement("a");
                link.innerText = "registrati";
                link.href = "sign-up.php";
                subContainer.appendChild(link);
                let button = document.createElement("button");
                button.type = "button";
                button.id = "close-alert";
                let icona = document.createElement("i");
                icona.className = "material-icons";
                icona.innerText = "&#xe872;";
                button.appendChild(icona);
                subContainer.appendChild(button);
                container.appendChild(subContainer);
            });
            </script>';
        }
        else{
            $query_string = "SELECT password FROM users WHERE username = :usr";
            $query_string = $pdo->prepare($query_string);
            $query_string->bindValue(":usr", $usr);
            $query_string->execute();
            $result_check = $query_string->fetch();
            $password = trim($_POST["usr-psw"]);
            $password = htmlspecialchars($password);
            if(password_verify($password, $result_check["password"])){
                $query_string = "SELECT nome FROM users WHERE username = :usr";
                $query_string = $pdo->prepare($query_string);
                $query_string->bindValue(":usr", $usr);
                $query_string->execute();
                $result_check = $query_string->fetch();
                $_SESSION["nome_usr"] = $result_check["nome"];
                $_SESSION["username"] = $_POST["usr-email"];
                
                // controllo se e' un coach
                $query_string = "SELECT coach FROM users WHERE username = :usr";
                $query_string = $pdo->prepare($query_string);
                $query_string->bindValue(":usr", $usr);
                $query_string->execute();
                $result_check = $query_string->fetch();
                $_SESSION["coach"] = ($result_check["coach"] == 0)? false : true;
                header("Location: ./redirect.php");
            }
            else{
                $error_flag = true;
            }
        }
    }
    catch(PDOException){
        http_response_code(503);
    }
}

?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>LOG-IN</title>
        <meta name="description" content="log-in page">
        <link rel="stylesheet" type="text/css" href="../style/log-in.css">
        <meta charset="UTF-8">
        <script src="../js/log-in.js"></script>
        <script src="../js/common.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=PT+Sans+Narrow:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>
    <body>
        <div id="message-error"></div>
        <form method="POST" action="log-in.php" id='log-in-form'>
            <div class="card-log-in">
                <div class="card-content">
                    <img class="card-logo" src="../images/gympluslogo.svg" alt="Logo">
                    <input type="email" name="usr-email" id="usr-email" placeholder="E-mail" required>
                    <input type="password" name="usr-psw" id="usr-psw" placeholder="Password" required>
                    <span id="psw-error"><?php if($error_flag){ echo "Password errata"; } ?></span>
                    <input type="submit" value="Accedi" class="button">
                    <div class="sign-up-and-help">
                        <p><a href="./sign-up.php">Non hai un account? Registrati</a></p>
                        <p><a href="./help-page.php">Hai bisogno di aiuto? Contattaci</a></p>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>