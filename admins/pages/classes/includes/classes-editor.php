<?php

require_once("../../../../common/auth-header.php");
require_once("../../../../consts.php");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if ($request != null && isset($request->command)) {
    /*
      GESTIONE DI CLASSI
     */
    if ($request->command === "updateclass") {
        $id = $request->obj->id;
        $sezione = $request->obj->sezione;
        $anno = $request->obj->anno;
        $query = "UPDATE classi SET sezione = '$sezione', anno = $anno WHERE id = '$id'";
        $conn = db_simple_connect();
        $outcome = db_update($conn, $query);
        if ($outcome->getOutcome() == QueryResult::SUCCESS) {
            if (db_simple_close($conn)) {
                exit_with_data("Classe aggiornata");
            } else {
                exit_with_error("Errore durante l'aggiornamento");
            }
        }
    } else if ($request->command === "addclass") {
        $sezione = $request->obj->sezione;
        $anno = $request->obj->anno;
        $query = "INSERT INTO classi(sezione,anno) VALUES ('$sezione',$anno)";
        $conn = db_simple_connect();
        $outcome = db_insert($conn, $query);
        if ($outcome->getOutcome() == QueryResult::SUCCESS) {
            if (db_simple_close($conn)) {
                exit_with_data($outcome->content);
            } else {
                exit_with_error("Errore durante l'inserimento");
            }
        }
    } else if ($request->command === "deleteclass") {
        $id = $request->obj->id;
        $query = "DELETE FROM classi WHERE id=$id";
        $conn = db_simple_connect();
        $outcome = db_update($conn, $query);
        if ($outcome->getOutcome() == QueryResult::SUCCESS) {
            if (db_simple_close($conn)) {
                exit_with_data("Classe eliminata");
            } else {
                exit_with_error("Errore durante l'eliminazione");
            }
        }
    }
}

?>