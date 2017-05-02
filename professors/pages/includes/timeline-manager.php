<?php

require_once '../../../common/auth-header.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if ($request != null && isset($request->command)) {
    if ($request->command === "create_timeline") {
        if (isset($request->year) && isset($request->class) && isset($request->subject)) {
            $year = $request->year;
            $class_a = $request->class->anno;
            $class_s = $request->class->sezione;
            $class_id = $request->class->id;
            $subject = $request->subject->nome;
            $subject_id = $request->subject->id;
            $user_id = $user_data->getId();

            $conn = db_transaction_connect();
            if ($conn) {
                $query = "INSERT INTO timeline(idmateria,iddocente,idclasse,anno) VALUES ($subject_id,$user_id,$class_id,$year)";
                $insert_result = db_insert($conn, $query);
                if ($insert_result->getOutcome() == QueryResult::SUCCESS) {
                    if (db_transaction_close($conn)) {
                        exit_with_data($insert_result->getContent());
                    } else {
                        exit_with_error("Cannot complete transaction");
                    }
                } else {
                    exit_with_error($insert_result->message);
                }
            }
        } else {
            exit_with_error("Invalid input");
        }
    } else if ($request->command === "copy_timeline") {
        if (isset($request->year) && isset($request->class) && isset($request->subject)) {
            $year = $request->year;
            $class_a = $request->class->anno;
            $class_s = $request->class->sezione;
            $class_id = $request->class->id;
            $subject = $request->subject->nome;
            $subject_id = $request->subject->id;
            $source_timeline = $request->timeline;
            $user_id = $user_data->getId();

            $conn = db_transaction_connect();
            if ($conn) {
                $query = "INSERT INTO timeline(idmateria,iddocente,idclasse,anno) VALUES ($subject_id,$user_id,$class_id,$year)";
                $insert_result = db_insert($conn, $query);
                if ($insert_result->getOutcome() == QueryResult::SUCCESS) {
                    $destination_timeline = $insert_result->getContent();
                    $query = "INSERT INTO timeline_element(idvoce,data,performed,idtimeline) "
                            . "SELECT idvoce, data, false, $destination_timeline FROM timeline_element WHERE idtimeline = $source_timeline";
                    $insert_result = db_multi_insert($conn, $query);
                    if ($insert_result->getOutcome() == QueryResult::SUCCESS) {
                        if (db_transaction_close($conn)) {
                            exit_with_data($insert_result->getContent());
                        } else {
                            exit_with_error("Cannot complete transaction");
                        }
                    } else {
                        db_transaction_abort($conn);
                        exit_with_error($insert_result->message);
                    }
                } else {
                    db_transaction_abort($conn);
                    exit_with_error($insert_result->message);
                }
            }
        } else {
            exit_with_error("Invalid input");
        }
    } else if ($request->command === "store_timeline") {
        $timeline = $request->timeline;
        $delete_query = "UPDATE timeline SET archiviata=1 WHERE id=$timeline";
        $conn = db_simple_connect();
        $outcome = db_update($conn, $delete_query);
        db_simple_close($conn);
        if ($outcome->wasSuccessful()) {
            exit_with_data("");
        } else {
            exit_with_error($outcome->getMessage());
        }
    } else if ($request->command === "unstore_timeline") {
        $timeline = $request->timeline;
        $delete_query = "UPDATE timeline SET archiviata=0 WHERE id=$timeline";
        $conn = db_simple_connect();
        $outcome = db_update($conn, $delete_query);
        db_simple_close($conn);
        if ($outcome->wasSuccessful()) {
            exit_with_data("");
        } else {
            exit_with_error($outcome->getMessage());
        }
    }else if ($request->command === "delete_timeline") {
        $timeline = $request->timeline;
        $delete_query = "DELETE FROM timeline WHERE id=$timeline";
        $conn = db_simple_connect();
        $outcome = db_update($conn, $delete_query);
        db_simple_close($conn);
        if ($outcome->wasSuccessful()) {
            exit_with_data("");
        } else {
            exit_with_error($outcome->getMessage());
        }
    } else if ($request->command === "list_timelines") {
        $user_id = $user_data->getId();
        $conn = db_simple_connect();
        if ($conn) {
            //id, classe, sezione, materia, anno
            $query = "SELECT timeline.id as 'id', classi.anno as 'classe', classi.sezione as 'sezione', materie.nome as 'materia', timeline.anno as 'anno' "
                    . "FROM classi, materie, timeline "
                    . "WHERE classi.id = timeline.idclasse AND materie.id = timeline.idmateria AND "
                    . "timeline.iddocente = $user_id "
                    . "ORDER BY timeline.anno DESC, materie.nome ASC, classi.anno ASC, classi.sezione ASC";
            $select_result = db_select($conn, $query);
            if ($select_result->getOutcome() == QueryResult::SUCCESS) {
                db_simple_close($conn);
                exit_with_data($select_result->getContent());
            } else {
                exit_with_error($select_result->message);
            }
        }
    }else if ($request->command === "list_not_stored_timelines") {
        $user_id = $user_data->getId();
        $conn = db_simple_connect();
        if ($conn) {
            //id, classe, sezione, materia, anno
            $query = "SELECT timeline.id as 'id', classi.anno as 'classe', classi.sezione as 'sezione', materie.nome as 'materia', timeline.anno as 'anno' "
                    . "FROM classi, materie, timeline "
                    . "WHERE classi.id = timeline.idclasse AND "
                    . "materie.id = timeline.idmateria AND "
                    . "archiviata = 0 AND "
                    . "timeline.iddocente = $user_id "
                    . "ORDER BY timeline.anno DESC, materie.nome ASC, classi.anno ASC, classi.sezione ASC";
            $select_result = db_select($conn, $query);
            if ($select_result->getOutcome() == QueryResult::SUCCESS) {
                db_simple_close($conn);
                exit_with_data($select_result->getContent());
            } else {
                exit_with_error($select_result->message);
            }
        }
    }else if ($request->command === "list_stored_timelines") {
        $user_id = $user_data->getId();
        $conn = db_simple_connect();
        if ($conn) {
            //id, classe, sezione, materia, anno
            $query = "SELECT timeline.id as 'id', classi.anno as 'classe', classi.sezione as 'sezione', materie.nome as 'materia', timeline.anno as 'anno' "
                    . "FROM classi, materie, timeline "
                    . "WHERE classi.id = timeline.idclasse AND "
                    . "materie.id = timeline.idmateria AND "
                    . "archiviata = 1 AND "
                    . "timeline.iddocente = $user_id "
                    . "ORDER BY timeline.anno DESC, materie.nome ASC, classi.anno ASC, classi.sezione ASC";
            $select_result = db_select($conn, $query);
            if ($select_result->getOutcome() == QueryResult::SUCCESS) {
                db_simple_close($conn);
                exit_with_data($select_result->getContent());
            } else {
                exit_with_error($select_result->message);
            }
        }
    }
}
