<?php

require_once './ajax-header.php';

require_once './load-schedules.php';

define('ID', 'id');
define('ARCHIVED', 'archived');

$parameters = init_parameters($request);
$conn = db_simple_connect();
if ($conn) {
    //id, classe, sezione, materia, anno
    if ($parameters[ID] != null) {
        $schedule = load_schedules($conn, $parameters[ID], null, null);
        if ($schedule->getContent()[0]['docente']['id'] == $_SESSION['user_data']->getId()) {
            if ($parameters[ARCHIVED]) {
                $result = store_schedule($conn, $parameters[ID]);
            } else {
                $result = unstore_schedule($conn, $parameters[ID]);
            }
            if ($result->wasSuccessful()) {
                db_simple_close($conn);
                exit_with_data("OK");
            } else {
                db_simple_close($conn);
                exit_with_error($result->getMessage());
            }
        } else {
            db_simple_close($conn);
            exit_with_error("Utente non autorizzato");
        }
    } else {
        db_simple_close($conn);
        exit_with_error("Programmazione non valida");
    }
    db_simple_close($conn);
    if ($schedules->getOutcome() == QueryResult::SUCCESS) {
        exit_with_data($schedules->getContent());
    } else {
        exit_with_error($schedules->getMessage());
    }
} else {
    exit_with_error("Impossibile stabilire la connessione");
}

function init_parameters($request) {
    $parameters = array();
    $parameters[ARCHIVED] = null;
    if (isset($request->archived)) {
        $parameters[ARCHIVED] = ($request->archived) ? true : false;
    }
    if (isset($request->id)) {
        $parameters[ID] = $request->archived = $request->id;
    } else {
        $parameters[ID] = null;
    }
    return $parameters;
}

function store_schedule($conn, $id) {
    $query = "UPDATE timeline SET archiviata=true WHERE id=$id;";
    $result = db_update($conn, $query);
    return $result;
}

function unstore_schedule($conn, $id) {
    $query = "UPDATE timeline SET archiviata=false WHERE id=$id;";
    $result = db_update($conn, $query);
    return $result;
}
