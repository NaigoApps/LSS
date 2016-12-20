<?php

require_once("../consts.php");
$connection = mysqli_connect(HOST, USER, PASS, DB);

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
if ($request != null && isset($request->command)) {
    if ($request->command == "load_table") {
        $table = mysqli_real_escape_string($connection, $request->table);
        $query = "SELECT * FROM $table LIMIT 50;";
        std_select($connection, $query);
        
    }else if ($request->command == "load_linked_table") {
        $main_table = mysqli_real_escape_string($connection, $request->target_table);
        $ref_table = mysqli_real_escape_string($connection, $request->source_table);
        $ref_id = $request->source_id;
        $query = "SELECT * FROM $main_table a WHERE EXISTS (SELECT * FROM $ref_table$main_table WHERE id1 = $ref_id AND id2 = a.id)";
        std_select($connection, $query);
        
    }else if ($request->command == "get-elements-to-link") {
        $table = mysqli_real_escape_string($connection, $request->table);
        $obj = mysqli_real_escape_string($connection, $request->obj->id);
        $linktable = mysqli_real_escape_string($connection, $request->link_table);
        if(isset($request->parent) && isset($request->parent_link_table)){
            $parent = mysqli_real_escape_string($connection, $request->parent->id);
            $parentlinktable = mysqli_real_escape_string($connection, $request->parent_link_table);
        }   
        
        if(!isset($parentlinktable)){
            $query = "SELECT * FROM $table t WHERE NOT EXISTS (SELECT * FROM $linktable WHERE id1 = t.id AND id2 = $obj)";
        }else{
            $query = "SELECT * FROM $table t WHERE EXISTS (SELECT * FROM $parentlinktable lt WHERE lt.id1 = $parent AND lt.id2 = t.id) AND NOT EXISTS (SELECT * FROM $linktable WHERE id1 = t.id AND id2 = $obj)";
        }
        std_select($connection, $query);
    }else if ($request->command == "get-elements-linked") {
        $table = mysqli_real_escape_string($connection, $request->table);
        $obj = mysqli_real_escape_string($connection, $request->obj->id);
        $linktable = mysqli_real_escape_string($connection, $request->link_table);
        
        $query = "SELECT * FROM $table t WHERE EXISTS (SELECT * FROM $linktable WHERE id1 = t.id AND id2 = $obj)";
        std_select($connection, $query);
    }else if ($request->command == "get-topics-to-link") {
        $item = mysqli_real_escape_string($connection, $request->obj->id);
        $module = mysqli_real_escape_string($connection, $request->module->id);
        $query = "SELECT * FROM argomenti a WHERE EXISTS (SELECT * FROM moduliargomenti ma WHERE ma.id1 = $module AND ma.id2 = a.id) AND NOT EXISTS (SELECT * FROM argomentivoci WHERE id1 = a.id AND id2 = $item)";
        std_select($connection, $query);
    }else if ($request->command == "find-topics-by-item") {
        $item = mysqli_real_escape_string($connection, $request->obj->id);
        $query = "SELECT * FROM argomenti a WHERE EXISTS (SELECT * FROM argomentivoci av WHERE av.id2 = $item AND av.id1 = a.id) LIMIT 1";
        std_select($connection, $query);
    }else if ($request->command == "find-modules-by-topic") {
        $topic = mysqli_real_escape_string($connection, $request->obj->id);
        $query = "SELECT * FROM moduli m WHERE EXISTS (SELECT * FROM moduliargomenti ma WHERE ma.id2 = $topic AND ma.id1 = m.id) LIMIT 1";
        std_select($connection, $query);
    }else if ($request->command == "search_object") {
        $table = mysqli_real_escape_string($connection, $request->table);
        $name = mysqli_real_escape_string($connection, $request->hint);
        $query = "SELECT * FROM $table a WHERE nome LIKE '%$name%' ORDER BY a.nome LIMIT 50";
        std_select($connection, $query);
    }
    
    else if ($request->command == "get-unassigned-topics") {
        $query = "SELECT * FROM argomenti a WHERE NOT EXISTS (SELECT * FROM moduloargomento WHERE id2 = a.id)";
        std_select($connection, $query);
    } else if ($request->command == "get-items-by-topic") {
        $module = mysqli_real_escape_string($connection, $request->topic->id);
        $query = "SELECT * FROM voci a WHERE EXISTS (SELECT * FROM argomentovoce WHERE id1 = $module AND id2 = a.id)";
        std_select($connection, $query);
    } else if ($request->command == "get-unassigned-items") {
        $query = "SELECT * FROM voci v WHERE NOT EXISTS (SELECT * FROM argomentovoce WHERE id2 = v.id)";
        std_select($connection, $query);
    }else if ($request->command == "add-user") {
        $email = $request->email;
        $query = "INSERT INTO utenti (email) VALUES ('$email')";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            exit_with_error("Impossibile aggiungere un nuovo utente");
        }else{
            exit_with_data("Utente aggiunto con successo. Attendere l'approvazione");
        }
    }
}
mysqli_close($connection);

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

?>