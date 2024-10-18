<?php
require_once(dirname(__FILE__) . "/config.php");

try{
    // connection to DB
    $connection_string = "mysql:host=" . DBHost . ";dbname=" . DBName;
    $pdo = new PDO($connection_string, DBUsername, DBPassword);

    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $body = json_decode(file_get_contents("php://input"), true);
        if ($body === null && json_last_error() !== JSON_ERROR_NONE) {
            echo 'Errore di parsing JSON: ' . json_last_error_msg();
        } 
        else {
            if($body["messageId"] == "invioScheda"){

                $pdo->beginTransaction();
                // informazioni scheda
                $coach = $body["data"]["coach"];
                $cliente = $body["data"]["cliente"];
                $data_rilascio = date("Y/m/d");
                $annotazioni = $body["data"]["annotazioni"];
                $giorni = $body["data"]["giorni"];
                //print_r($giorni);

                // input validation
                $annotazioni = trim($annotazioni);
                $annotazioni = htmlspecialchars($annotazioni);

                // check info
                if(!isset($body["data"]["esercizi"]) || empty($body["data"]["esercizi"])){
                    $result = ["esito" => "ERROR", "dettagli" => "Non sono stati inseriti esercizi per nessun giorno."];
                    echo json_encode($result, JSON_PRETTY_PRINT);
                    http_response_code(400);
                    exit();
                }

                if($cliente == "" || !isset($cliente) || is_null($cliente) || $cliente == "null"){ 
                    $result = ["esito" => "ERROR", "dettagli" => "Inserire il cliente a cui inviare la scheda e riprovare."];
                    echo json_encode($result, JSON_PRETTY_PRINT);
                    http_response_code(400);
                    exit();
                }

                for($i=0; $i < $giorni - 1; $i++){
                    if (!isset($body["data"]["esercizi"][$i]) || !is_array($body["data"]["esercizi"][$i])) {
                        $result = ["esito" => "ERROR", "dettagli" => "Dati degli esercizi mancanti o non validi per il giorno $i."];
                        echo json_encode($result, JSON_PRETTY_PRINT);
                        http_response_code(400);
                        exit();
                    }
                }

                for($i=0; $i < $giorni - 1; $i++){
                    foreach ($body["data"]["esercizi"][$i] as $e) {
                        if(is_null($e["n-serie"]) || $e["n-serie"] == 0
                             || is_null($e["n-rep"]) || $e["n-rep"] == 0
                             || is_null($e["id_esercizio"]) || $e["id_esercizio"] == "--")
                        {
                            $result = ["esito" => "ERROR", "dettagli" => "Dati degli esercizi mancanti."];
                            echo json_encode($result, JSON_PRETTY_PRINT);
                            http_response_code(400);
                            exit();
                        }
                    }
                }

                
                $query = "INSERT INTO scheda(coach, user, data_rilascio, annotazioni)
                        VALUES(:coach, :cliente, :data_rilascio, :annotazioni)";
                $query = $pdo->prepare($query);
                $query->bindParam(':coach', $coach);
                $query->bindParam(':cliente', $cliente);
                $query->bindParam(':data_rilascio', $data_rilascio);
                $query->bindParam(':annotazioni', $annotazioni);
                $query->execute();

                // recupero id scheda
                $id_scheda = $pdo->lastInsertId();  
                

                // dettagli scheda
                $query = "INSERT INTO scheda_dettaglio (id_scheda, id_esercizio, n_serie, n_reps, giorno) VALUES (:id_scheda, :id_esercizio, :n_serie, :n_reps, :g)";
                $stmt = $pdo->prepare($query);
                for($i=0; $i < $giorni - 1; $i++){
                    foreach ($body["data"]["esercizi"][$i] as $e) {
                        $stmt->bindParam(':id_scheda', $id_scheda);
                        $stmt->bindParam(':id_esercizio', $e["id_esercizio"]);
                        $stmt->bindParam(':n_serie', $e["n-serie"]);
                        $stmt->bindParam(':n_reps', $e["n-rep"]);
                        $stmt->bindParam(':g', $i);
                        $stmt->execute();
                    }
                }
                $pdo->commit();

                $result = ["esito" => "OK"];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
                exit();
            }
            elseif($body["messageId"] == "aggiornaDatiPersonali"){
                
                $username = $body["data"]["username"];
                $nuovoNome = trim($body["data"]["nuovo-nome"]);
                $nuovoNome = htmlspecialchars($nuovoNome);
                $nuovoCognome = trim($body["data"]["nuovo-cognome"]);
                $nuovoCognome = htmlspecialchars($nuovoCognome);
                $nuovaDataNascita = trim($body["data"]["nuova-data-nascita"]);
                $nuovaDataNascita = preg_replace("([^0-9/])", "", $nuovaDataNascita);
                $nuovaDataNascita = htmlspecialchars($nuovaDataNascita);
                $nuovoTelefono = trim($body["data"]["nuovo-telefono"]);
                $nuovoTelefono = htmlspecialchars($nuovoTelefono);

                $vecchioNome = $body["data"]["vecchio-nome"];
                $vecchioCognome = $body["data"]["vecchio-cognome"];
                $vecchiaDataNascita = $body["data"]["vecchia-data-nascita"];
                $vecchioTelefono = $body["data"]["vecchio-telefono"];

                if($nuovoNome == "") $nuovoNome = $vecchioNome;
                if($nuovoCognome == "") $nuovoCognome = $vecchioCognome;
                if($nuovaDataNascita == "") $nuovaDataNascita = $vecchiaDataNascita;
                if($nuovoTelefono == "") $nuovoTelefono = $vecchioTelefono;

                $query = "UPDATE users
                          SET nome = :nn, cognome = :nc, data_nascita = :ndn, telefono = :nt
                          WHERE username = :usr";
                $stmt = $pdo->prepare($query);

                $stmt->bindParam(':nn', $nuovoNome);
                $stmt->bindParam(':nc', $nuovoCognome);
                $stmt->bindParam(':ndn', $nuovaDataNascita);
                $stmt->bindParam(':nt', $nuovoTelefono);
                $stmt->bindParam(':usr', $username);
                
                $stmt->execute();
                $result = ["esito" => "OK"];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
                exit();
            }
            elseif ($body["messageId"] == "aggiornaDatiAccesso") {
                $username = $body["data"]["username"];
                $vecchiaPassword = htmlspecialchars(trim($body["data"]["vecchia-password"]));
                $nuovaPassword = htmlspecialchars(trim($body["data"]["nuova-password"]));

                // controllo vecchia password
                $query = "SELECT password FROM users WHERE username = :usr";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':usr', $username);
                $stmt->execute();

                $r = $stmt->fetch();
                
                if(!password_verify($vecchiaPassword, $r['password'])){
                    $result = ["esito" => "ERROR"];
                    echo json_encode($result, JSON_PRETTY_PRINT);
                    http_response_code(400);
                    exit();
                }
                else{
                    $query = "UPDATE users
                              SET password = :np
                              WHERE username = :usr";
                    $stmt = $pdo->prepare($query);
                    $nuovaPassword = password_hash($nuovaPassword, PASSWORD_DEFAULT);
                    $stmt->bindParam(':np', $nuovaPassword);
                    $stmt->bindParam(':usr', $username);
                    $stmt->execute();

                    $result = ["esito" => "OK"];
                    echo json_encode($result, JSON_PRETTY_PRINT);
                    http_response_code(200);
                    exit();
                }
            }
            elseif($body["messageId"] == "nuovoTask"){
                $username = trim($body["user"]);
                $username = htmlspecialchars($username);
                $task = trim($body["data"]);
                $task = htmlspecialchars($task);

                $query = "INSERT INTO to_do_list(user, task) VALUES(:usr, :tsk)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':usr', $username);
                $stmt->bindParam(':tsk', $task);
                $stmt->execute();
                $result = ["esito" => "OK"];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
                exit();
            }
            elseif ($body["messageId"] == "checkTask") {
                $task = trim($body["task"]);
                $task = htmlspecialchars($task);

                $query = "DELETE FROM to_do_list WHERE id = :id_";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':id_', $task);
                $stmt->execute();
                $result = ["esito" => "OK"];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
                exit();
            }
            elseif ($body["messageId"] == "visualizzaTask") {
                $username = $body["user"];
                $query = "SELECT *
                        FROM to_do_list
                        WHERE user = :usr";
                $query = $pdo->prepare($query);
                $query->bindValue(":usr", $username);
                $query->execute();
                
                $string ="";
                while($r = $query->fetch()){
                    $string.= '<div class="task">
                        <li>'. $r["task"] . '</li>
                        <button class="check-task" data-task-id="'. $r["id"] . '"><i class="material-icons">&#xe5ca;</i></button>
                        </div>';
                }
                $result = ["esito" => "OK", "tasks" => $string];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
                exit();


            }
            elseif ($body["messageId"] == "richiestaScheda") {
                $username = $body["user"];

                $query = "SELECT sd.giorno, e.nome, sd.n_serie, sd.n_reps, s.annotazioni
                          FROM scheda_dettaglio sd INNER JOIN scheda s ON sd.id_scheda = s.id_scheda
                            INNER JOIN esercizi e ON sd.id_esercizio = e.id_esercizio
                          WHERE s.user = :usr AND s.id_scheda = (SELECT MAX(s1.id_scheda)
                                                                 FROM scheda s1
                                                                 WHERE s1.user = s.user)
                          ORDER BY sd.giorno";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':usr', $username);
                $stmt->execute();


                $data = [];
                $annotazioni = "";
                while ($row = $stmt->fetch()) {
                    $giorno = $row['giorno'];
                    if (!isset($data[$giorno])) {
                        $data[$giorno] = [];
                    }
                    $annotazioni = $row["annotazioni"];
                    $data[$giorno][] = [
                        "nome" => $row['nome'],
                        "serie" => $row['n_serie'],
                        "ripetizioni" => $row['n_reps']
                    ];
                }

                $result = [
                    "esito" => "OK",
                    "annotazioni" => $annotazioni,
                    "scheda" => $data
                ];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
                exit();
            } 
            elseif($body["messageId"] == "richiestaEsercizioAllenamento"){
                $username = $body["user"];
                
                $query = "SELECT a.giorno
                          FROM allenamento a
                          WHERE a.user = :usr AND a.scheda = (SELECT MAX(s.id_scheda)
                                                              FROM scheda s
                                                              WHERE a.user = s.user)
                              AND a.timestamp = (SELECT MAX(a1.timestamp)
                                                 FROM allenamento a1
                                                 WHERE a.user = a1.user AND a.scheda = a1.scheda);";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':usr', $username);
                $stmt->execute();
                $r = $stmt->fetch();
                $ultimoGiornoAllenato = $r ? intval($r["giorno"]) : -1;
                
                $query = "SELECT MAX(sd.giorno) AS giorno
                          FROM scheda_dettaglio sd
                          INNER JOIN scheda s ON sd.id_scheda = s.id_scheda
                          WHERE s.user = :usr AND s.id_scheda = (SELECT MAX(s1.id_scheda)
                                                                  FROM scheda s1
                                                                  WHERE s.user = s1.user);";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':usr', $username);
                $stmt->execute();
                $ultimoGiornoScheda = intval($stmt->fetch()["giorno"]);
                
                $giorno = ($ultimoGiornoScheda > $ultimoGiornoAllenato) ? $ultimoGiornoAllenato + 1 : 0;
                
                $query = "SELECT sd.giorno, e.nome, sd.n_serie, sd.n_reps, sd.id_esercizio
                          FROM scheda_dettaglio sd
                          INNER JOIN scheda s ON sd.id_scheda = s.id_scheda
                          INNER JOIN esercizi e ON sd.id_esercizio = e.id_esercizio
                          WHERE s.user = :usr AND s.id_scheda = (SELECT MAX(s1.id_scheda)
                                                                 FROM scheda s1
                                                                 WHERE s1.user = s.user)
                          AND sd.giorno = :giorno_";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':usr', $username);
                $stmt->bindParam(':giorno_', $giorno);
                $stmt->execute();

                $data = [];
                while ($row = $stmt->fetch()) {
                    $data[] = [
                        "nome" => $row['nome'],
                        "serie" => $row['n_serie'],
                        "ripetizioni" => $row['n_reps'],
                        "id_esercizio" => $row["id_esercizio"]
                    ];
                }

                $result = [
                    "esito" => "OK",
                    "scheda" => $data, 
                    "giorno" => $giorno
                ];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
                exit();
            } elseif ($body["messageId"] == "inserimentoAllenamentoEsercizio") {
                $username = $body["user"];
                $id_esercizio = $body["esercizio"];
                $giorno = $body["giorno"];
                $carichi = $body["carichi"];

                $pdo->beginTransaction();

                $query = "SELECT s.id_scheda
                          FROM scheda s 
                          WHERE s.user = :usr AND s.id_scheda = (SELECT MAX(s1.id_scheda)
                                                                  FROM scheda s1
                                                                  WHERE s1.user = s.user);";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':usr', $username);
                $stmt->execute();
                $row = $stmt->fetch();
                if (!$row) {
                    throw new Exception("Errore nel recupero delle informazioni dal DB.");
                }
                $scheda = $row["id_scheda"];

                $query = "INSERT INTO allenamento (user, scheda, giorno, esercizio, serie, carico, timestamp)
                          VALUES (:usr, :scheda, :giorno, :esercizio, :serie, :carico, :timestamp_);";
                $stmt = $pdo->prepare($query);
                for ($i = 0; $i < count($carichi); $i++) {
                    $stmt->bindValue(':usr', $username);
                    $stmt->bindValue(':scheda', $scheda);
                    $stmt->bindValue(':esercizio', $id_esercizio);
                    $stmt->bindValue(':giorno', $giorno);
                    $stmt->bindValue(':serie', $i + 1);
                    $stmt->bindValue(':carico', intval(htmlspecialchars(trim($carichi[$i]))));
                    $stmt->bindValue(':timestamp_', date('Y-m-d H:i:s'));
                    $stmt->execute();
                }

                $pdo->commit();

                $result = ["esito" => "OK"];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
            } 
            elseif($body["messageId"] == "invioCommentiCoach"){
                $username = $body["user"];
                $commenti = htmlspecialchars(trim($body["commenti"]));

                $query = "SELECT coach FROM clientela WHERE cliente = :usr";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':usr', $username);
                $stmt->execute();

                $coach = $stmt->fetch()["coach"];
                
                $query = "INSERT INTO post_allenamento VALUES(:coach, :timestamp_, :user, :commenti, :visto)";
                $stmt = $pdo->prepare($query);
                $currentTimestamp = date('Y-m-d H:i:s');
                $stmt->bindParam(':timestamp_', $currentTimestamp);
                $stmt->bindParam(':coach', $coach);
                $stmt->bindParam(':user', $username);
                $stmt->bindParam(':commenti', $commenti);
                $temp = 0;
                $stmt->bindParam(':visto', $temp);

                $stmt->execute();
                $result = ["esito" => "OK"];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            elseif($body["messageId"] == "setVistoMessaggi"){
                $messaggio = $body["messaggio"];
                $query_string = "UPDATE post_allenamento SET visto = 1 WHERE post_allenamento.timestamp = :timestamp_";
                $query = $pdo->prepare($query_string);
                $query->bindParam(':timestamp_', $messaggio);
                $query->execute();
                $result = ["esito" => "OK"];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            elseif($body["messageId"] == "richiestaEsercizi"){
                $query = "SELECT nome, id_esercizio FROM esercizi";
                $query = $pdo->prepare($query);
                $query->execute();
                $string = "";
                while (!empty($r = $query->fetch())) {
                    $string .= '<option value="' . $r["id_esercizio"] . '">' . $r["nome"] . "</option>";    
                }
                $result = [
                            "esito" => "OK",
                            "text" => $string
                            ];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            elseif($body["messageId"] == "getStats"){
                $username = $body["user"];
                
                $query = "SELECT DATE(allenamento.timestamp) AS data_, SUM(carico) AS carico_totale_giornaliero,  DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) AS lunedi,
                DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY) AS domenica
                            FROM allenamento
                            WHERE user = :user
                            AND YEARWEEK(allenamento.timestamp, 1) = YEARWEEK(CURDATE(), 1)
                            GROUP BY DATE(allenamento.timestamp)
                            ORDER BY DATE(allenamento.timestamp);";

                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':user', $username);
                $stmt->execute();
                $results = $stmt->fetchAll();
                if(empty($results)){
                    $data = null;
                    $result = [
                        "esito" => "OK",
                        "results" => -1,
                        "attivita" => 0
                    ];
                    echo json_encode($result, JSON_PRETTY_PRINT);
                    http_response_code(200);
                    exit();
                }
                else{
                    $data = [];
                    foreach ($results as $row) {
                        $data[] = [
                            'data_' => $row['data_'],
                            'carico_totale_giornaliero' => $row['carico_totale_giornaliero'],
                            'data_inizio' => $row['lunedi'],
                            'data_fine' => $row['domenica']
                        ];
                    }
                }

                $result = [
                            "esito" => "OK",
                            "results" => $data,
                            "attivita" => count($results)
                        ];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
            }
            elseif($body["messageId"] == "updateProfilePic") {
                if(!isset($body["pic"])){
                    $result = ["esito" => "Errore: immagine non inviata"];
                    echo json_encode($result, JSON_PRETTY_PRINT);
                    http_response_code(400);
                    exit();
                }
            
                $username = $body["user"];
                $path = "../uploads/" . $username;
            
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }

                $files = scandir($path);
                foreach ($files as $file) {
                    if (strpos('profile-pic', $file) !== false) {
                        unlink($file);
                    }
                }
                $picData = $body["pic"];
                
                list($type, $picData) = explode(';', $picData);
                list(, $picData) = explode(',', $picData);
                
                $picData = base64_decode($picData);
                $mime_type = str_replace('data:image/', '', $type);
                $extension = "";
                switch ($mime_type) {
                    case 'jpeg':
                        $extension = '.jpg';
                        break;
                    case 'png':
                        $extension = '.png';
                        break;
                    case 'gif':
                        $extension = '.gif';
                        break;
                    default:
                        $result = ["esito" => "Errore: tipo di immagine non supportato"];
                        echo json_encode($result, JSON_PRETTY_PRINT);
                        http_response_code(400);
                        exit();
                }
                $filePath = $path . "/profile-pic" . $extension;
                file_put_contents($filePath, $picData);
            
                $result = ["esito" => "Immagine salvata con successo", "path" => $filePath];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
                exit();
            }            
            elseif($body["messageId"] == "retrieveProfilePic") {
                $username = $body["user"];
                $dirPath = "../uploads/" . $username; 
            
                if (!is_dir($dirPath)) {
                    $result = ["esito" => "ERROR", "message" => "Directory non trovata"];
                    echo json_encode($result, JSON_PRETTY_PRINT);
                    http_response_code(404); 
                    exit();
                }
            
                $files = scandir($dirPath);
                $imagePath = null;
                foreach ($files as $file) {
                    if (strpos($file, 'profile-pic') !== false) {
                        $extension = pathinfo($file, PATHINFO_EXTENSION);
                        $imagePath = $dirPath . '/' . $file;
                        error_log("Found image file: " . $imagePath);
                        break;
                    }
                }
            
                if ($imagePath && file_exists($imagePath)) {
                    $picData = file_get_contents($imagePath);
            
                    $mime_type = "";
                    switch ($extension) {
                        case 'jpg':
                        case 'jpeg':
                            $mime_type = 'image/jpeg';
                            break;
                        case 'png':
                            $mime_type = 'image/png';
                            break;
                        case 'gif':
                            $mime_type = 'image/gif';
                            break;
                        default:
                            $result = ["esito" => "ERROR", "message" => "Tipo di immagine non supportato"];
                            echo json_encode($result, JSON_PRETTY_PRINT);
                            http_response_code(415); 
                            exit();
                    }
            
                    // Prepara la stringa Base64
                    $base64Pic = 'data:' . $mime_type . ';base64,' . base64_encode($picData);
                    $result = [
                        "esito" => "OK",
                        "pic" => $base64Pic
                    ];
                    echo json_encode($result, JSON_PRETTY_PRINT);
                    http_response_code(200);
                    exit();
                } else {
                    $result = ["esito" => "ERROR", "message" => "File immagine non trovato"];
                    echo json_encode($result, JSON_PRETTY_PRINT);
                    http_response_code(404); 
                    exit();
                }
            }
            elseif($body["messageId"] == "helpRequest") {
                $content = $body["content"];
                $user = $body["user"];

                $query = "INSERT INTO help_requests
                VALUES(:usr, :tms, :con)";
                $stmt = $pdo->prepare($query);

                $stmt->bindParam(':usr', $user);
                $timestamp_ = date('Y-m-d H:i:s');
                $stmt->bindParam(':tms', $timestamp_);
                $stmt->bindParam(':con', $content);
                
                $stmt->execute();

                $result = ["esito" => "OK", "message" => "Inserimento completato"];
                echo json_encode($result, JSON_PRETTY_PRINT);
                http_response_code(200);
                exit();
                
            }
            else {
                http_response_code(503);
            }
        }
    }
} catch (PDOException $e) {
    error_log("Errore PDO: " . $e->getMessage());
    echo json_encode(["esito" => "ERROR", "details" => $e->getMessage()]);
    http_response_code(504);
}