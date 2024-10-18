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
        <title>TODO-LIST</title>
        <meta name="description" content="to do page for user">
        <link rel="stylesheet" type="text/css" href="../style/todo_list.css">
        <script src="../js/todo_list.js"></script>
        <script src="../js/common.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=PT+Sans+Narrow:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>

    <body>
        <main>
            <div class="card">
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
                        <ul class="todo-list">
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
        </main>


    </body>
</html>