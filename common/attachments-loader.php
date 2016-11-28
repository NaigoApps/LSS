<?php

require_once("../consts.php");
$connection = mysqli_connect(HOST, USER, PASS, DB);

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if ($request != null && isset($request->command)) {
    /*
      GESTIONE DEL MATERIALE
     */
//Aggiunta di links
    if ($request->command === "geturl") {
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
        $dir = "../admins/pages/db/" . $table . "/" . $id;
        if (is_dir($dir) && ($files = scandir($dir)) !== false) {
            exit_with_data(array_values(array_diff($files, array('..', '.'))));
        } else {
            echo "[]";
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
