<?php

require_once("../../../../../common/auth-header.php");
require_once("../../../../../common/functions.php");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if ($request != null && isset($request->command)) {

    if ($request->command === "save_timeline") {
        $timeline_id = $request->id;
        $timeline = $request->timeline;
        $delete_query = "DELETE FROM timeline_element WHERE idtimeline=$timeline_id";
        $update_query = "INSERT INTO timeline_element(idtimeline, idvoce, data, performed) VALUES ";
        $at_least_one = false;
        foreach ($timeline as $entry) {
            $performed = ($entry->performed)?'true':'false';
            $update_query = $update_query . "($timeline_id, " . $entry->id. ", FROM_UNIXTIME('" . strtotime($entry->data) . "'), " . $performed . "),";
            $at_least_one = true;
        }
        $conn = db_transaction_connect();
        $outcome = db_update($conn, $delete_query);
        if ($outcome->getOutcome() == QueryResult::SUCCESS) {
            if ($at_least_one) {
                $update_query = rtrim($update_query, ",");
                $outcome = db_update($conn, $update_query);
                if ($outcome->getOutcome() == QueryResult::SUCCESS) {
                    if (db_transaction_close($conn)) {
                        exit_with_data("Programmazione aggiornata");
                    } else {
                        db_transaction_abort($conn);
                        exit_with_error("Errore durante l'aggiornamento");
                    }
                } else {
                    db_transaction_abort($conn);
                    exit_with_error($outcome->getMessage());
                }
            } else {
                if (db_transaction_close($conn)) {
                    exit_with_data("Programmazione aggiornata");
                } else {
                    db_transaction_abort($conn);
                    exit_with_error("Errore durante l'aggiornamento");
                }
            }
        } else {
            db_transaction_abort($conn);
            exit_with_error($outcome->getMessage());
        }
    }
}

?>