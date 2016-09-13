<?php
require_once("../../../../common/auth-header.php");
require_once("../../../../consts.php");
$connection = mysqli_connect(HOST, USER, PASS, DB);

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if ($request != null && isset($request->command)) {
    /*
      GESTIONE DI MODULI, ARGOMENTI E VOCI
     */

    if ($request->command === "addobj") {
        $nome = mysqli_real_escape_string($connection, $request->obj->nome);
        $descrizione = mysqli_real_escape_string($connection, $request->obj->descrizione);
        $table = mysqli_real_escape_string($connection, $request->table);
        $query = "INSERT INTO $table (nome,descrizione) VALUES ('$nome', '$descrizione')";
        std_insert($connection, $query);
        
    }  else if ($request->command === "updateobj") {
        $id = mysqli_real_escape_string($connection, $request->obj->id);
        $nome = mysqli_real_escape_string($connection, $request->obj->nome);
        $descrizione = mysqli_real_escape_string($connection, $request->obj->descrizione);
        $table = mysqli_real_escape_string($connection, $request->table);
        $query = "UPDATE $table SET nome = '$nome', descrizione = '$descrizione' WHERE id=$id";
        std_update($connection, $query);
        
    } else if ($request->command === "deleteobj") {
        $id = mysqli_real_escape_string($connection, $request->obj->id);
        $table = mysqli_real_escape_string($connection, $request->table);
        $query = "DELETE FROM $table WHERE id=$id";
        std_update($connection, $query);
        
    }else if ($request->command === "addlink") {
        $id1 = $request->src_element->id;
        $id2 = $request->dst_element->id;
        $table = $request->src_table . $request->dst_table;

        $query = "INSERT INTO $table (id1,id2) VALUES ($id1,$id2);";
        std_insert($connection, $query);
//Cancellazione
    } else if ($request->command === "removelink") {
        $id1 = $request->src_element->id;
        $id2 = $request->dst_element->id;
        $table = $request->src_table . $request->dst_table;

        $query = "DELETE FROM $table WHERE id1 = '$id1' AND id2 = '$id2';";
        std_update($connection, $query);
//Argomento-voce
    }
    /*
      GESTIONE UTENTI (Da controllare)
     */ else if ($request->command == "add-user") {
        parse_str($request->user, $user);
        $username = $user['username'];
        $password = $user['password'];
        $query = "INSERT INTO utenti (username, password) VALUES ($username, $password)";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Errore nella query $query: " . mysql_error());
        }
    } else if ($request->command == "add-admin") {
        parse_str($request->admin, $admin);
        $iddocente = $admin['iddocente'];
        $query = "INSERT INTO amministratori (iddocente) VALUES ($iddocente)";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Errore nella query $query: " . mysql_error());
        }
    } else if ($request->command == "add-class") {
        parse_str($request->class_, $class);
        $nome = $class['nome'];
        $query = "INSERT INTO classi (nome) VALUES ($nome)";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Errore nella query $query: " . mysql_error());
        }
    } else if ($request->command == "add-subject") {
        parse_str($request->subject, $subject);
        $nome = $subject['nome'];
        $descrizione = $subject['descrizione'];
        $query = "INSERT INTO materie (nome,descrizione) VALUES ($nome, $descrizione)";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Errore nella query $query: " . mysql_error());
        }
    } else if ($request->command == "add-developer") {
        parse_str($request->developer, $developer);
        $nome = $developer['nome'];
        $cognome = $developer['cognome'];
        $idutente = $developer['idutente'];
        $query = "INSERT INTO sviluppatori (nome,cognome,idutente) VALUES ($nome, $cognome, $idutente)";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Errore nella query $query: " . mysql_error());
        }
    } else if ($request->command == "add-professor") {
        parse_str($request->professor, $professor);
        $nome = $professor['nome'];
        $cognome = $professor['cognome'];
        $idmateria = $professor['idmateria'];
        $idutente = $professor['idutente'];
        $query = "INSERT INTO sviluppatori (nome,cognome,idmateria,idutente) VALUES ($nome, $cognome, $idmateria ,$idutente)";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            die("Errore nella query $query: " . mysql_error());
        }
    }
}
mysqli_close($connection);

function modules($connection) {
    return getter_query($connection, "SELECT * FROM moduli ORDER BY nome");
}

function topics($connection) {
    return getter_query($connection, "SELECT * FROM argomenti");
}

function items($connection) {
    return getter_query($connection, "SELECT * FROM voci");
}

function std_insert($connection, $query) {
    if (mysqli_autocommit($connection, false)) {
        if (mysqli_real_query($connection, $query) && ($last_id = mysqli_insert_id($connection)) != 0) {
            mysqli_commit($connection);
            exit_with_data($last_id);
        } else {
            mysqli_rollback($connection);
            exit_with_error($query);
        }
    }
}

function std_update($connection, $query) {
    if (mysqli_real_query($connection, $query)) {
        exit(200);
    } else {
        exit_with_error(mysqli_error($connection));
    }
}

function exit_with_error($msg) {
    $json_response = json_encode('{msg:' . $msg . '}');
    http_response_code(400);
    die($json_response);
}

function exit_with_data($data) {
    $json_response = json_encode($data);
    http_response_code(200);
    die($json_response);
}

?>