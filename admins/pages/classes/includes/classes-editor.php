<?php
require_once("../../../../common/auth-header.php");
require_once("../../../../consts.php");
$connection = mysqli_connect(HOST, USER, PASS, DB);

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if ($request != null && isset($request->command)) {
    /*
      GESTIONE DI CLASSI
     */

    if ($request->command === "updateclass") {
        $id = mysqli_real_escape_string($connection, $request->obj->id);
        $nome = mysqli_real_escape_string($connection, $request->obj->nome);
        $query = "UPDATE classi SET nome = '$nome' WHERE id=$id";
        std_update($connection, $query);
        
    }else if ($request->command === "addclass") {
        $nome = mysqli_real_escape_string($connection, $request->obj->nome);
        $query = "INSERT INTO classi (nome) VALUES ('$nome')";
        std_insert($connection, $query);
        
    }else if ($request->command === "deleteclass") {
        $id = mysqli_real_escape_string($connection, $request->obj->id);
        $query = "DELETE FROM classi WHERE id=$id";
        std_update($connection, $query);
        
    }
}
mysqli_close($connection);

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