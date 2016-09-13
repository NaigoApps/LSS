<?php

require_once("../../../../consts.php");
$connection = mysqli_connect(HOST, USER, PASS, DB);

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if ($request != null && isset($request->command)) {
    /*
      GESTIONE DEL MATERIALE
     */
//Aggiunta di links
    if ($request->command === "addurl") {
        $id = mysqli_real_escape_string($connection, $request->obj->id);
        $link_name = mysqli_real_escape_string($connection, $request->url->nome);
        $link_url = mysqli_real_escape_string($connection, $request->url->link);
        $table = "link".mysqli_real_escape_string($connection, $request->table);
        $query = "INSERT INTO $table(idoggetto,nome,link) VALUES($id,'$link_name','$link_url');";
        std_insert($connection, $query);
    }
//Modifica di links
    else if ($request->command === "updateurl") {
        $link_id = mysqli_real_escape_string($connection, $request->url->id);
        $link_name = mysqli_real_escape_string($connection, $request->url->nome);
        $link_url = mysqli_real_escape_string($connection, $request->url->link);
        $table = "link".mysqli_real_escape_string($connection,$request->table);
        $query = "UPDATE $table SET nome='$link_name', link='$link_url' WHERE id = '$link_id'";
        std_update($connection, $query);
    }
//Cancellazione di links
    else if ($request->command === "deleteurl") {
        $link = mysqli_real_escape_string($connection, $request->url->id);
        $table = "link".mysqli_real_escape_string($connection,$request->table);
        $query = "DELETE FROM $table WHERE id='$link';";
        std_update($connection, $query);
    }
//Recupero di links
    else if ($request->command === "geturl") {
        $id = mysqli_real_escape_string($connection, $request->obj->id);
        $table = "link" . mysqli_real_escape_string($connection, $request->table);
        $query = "SELECT * FROM $table WHERE idoggetto = '$id'";
        $result = mysqli_query($connection, $query);
        std_select($connection, $query);
    }
//Recupero lista documenti
    else if ($request->command === "getdocs") {
        $id = mysqli_real_escape_string($connection, $request->obj->id);
        $table = mysqli_real_escape_string($connection, $request->table);
        $dir = "../" . $table . "/" . $id;
        if (is_dir($dir) && ($files = scandir($dir)) !== false) {
            exit_with_data(array_values(array_diff($files, array('..', '.'))));
        } else {
            echo "[]";
        }
    }
//Cancellazione di documenti
    else if ($request->command === "deletedoc") {
        $id = mysqli_real_escape_string($connection, $request->obj->id);
        $doc = mysqli_real_escape_string($connection, $request->document);
        $table = mysqli_real_escape_string($connection, $request->table);
        if (unlink("../" . $table . "/" . $id . "/" . $doc)) {
            exit(200);
        } else {
            exit_with_error("Impossibile cancellare il documento...");
        }
    }
} else {
    
}

function std_insert($connection, $query) {
    if (mysqli_autocommit($connection, false)) {
        if (mysqli_real_query($connection, $query) && ($last_id = mysqli_insert_id($connection)) != 0) {
            mysqli_commit($connection);
            exit_with_data($last_id);
        } else {
            mysqli_rollback($connection);
            exit_with_error(mysqli_error($connection));
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

function std_select($conn, $query) {
    $result = mysqli_query($conn, $query);
    if ($result !== false) {
        $rows = array();
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        exit_with_data($rows);
    } else {
        exit_with_error("Impossibile recuperare i dati...");
    }
}

function exit_with_error($msg) {
    $json_response = '{' . $msg . '}';
    http_response_code(400);
    die($json_response);
}

function exit_with_data($data) {
    $json_response = json_encode($data);
    http_response_code(200);
    die($json_response);
}
