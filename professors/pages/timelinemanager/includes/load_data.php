<?php

require_once("../../../../common/auth-header.php");
require_once("../../../../common/functions.php");
$connection = mysqli_connect(HOST, PROFESSOR, PASS, DB);

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if ($request != null && isset($request->command)) {

    if ($request->command === "load_timeline") {
        $timeline_id = $request->id;
        //Caricare tutti i dati, anche delle tabelle derivate es: annoclasse, sezione
        $query = "SELECT ti.id as id, "
                . "ti.idmateria as idmateria, "
                . "ti.idclasse as idclasse, "
                . "ti.anno as anno, "
                . "ti.iddocente as iddocente, "
                . "cl.anno as annoclasse, "
                . "cl.sezione as sezione, "
                . "ma.nome as nomemateria "
                . "FROM timeline ti, classi cl, materie ma "
                . "WHERE ti.id=$timeline_id AND "
                . "ti.idclasse = cl.id AND "
                . "ti.idmateria = ma.id";
        $conn = db_simple_connect();
        $outcome_1 = db_select($conn, $query);
        $query = "SELECT v.id as id, v.nome, v.descrizione, UNIX_TIMESTAMP(te.data) as data, te.performed FROM timeline_element te, voci v WHERE te.idtimeline=$timeline_id AND te.idvoce = v.id";
        $outcome_2 = db_select($conn, $query);
        db_simple_close($conn);
        if ($outcome_1->getOutcome() == QueryResult::SUCCESS) {
            if ($outcome_2->getOutcome() == QueryResult::SUCCESS) {
                $ret = array(
                    "timeline" => $outcome_1->getContent()[0],
                    "elements" => $outcome_2->getContent()
                );
                exit_with_data($ret);
            } else {
                exit_with_error($outcome_2->getMessage());
            }
        } else {
            exit_with_error($outcome_1->getMessage());
        }
    } else if ($request->command === "load_performances") {
        
        $timeline_year = $request->anno;
        $timeline_class = $request->classe;
        $conn = db_simple_connect();
        $query = "SELECT te.idvoce as id, ti.idmateria, UNIX_TIMESTAMP(te.data) as data FROM timeline_element te, timeline ti WHERE "
                . "te.idtimeline=ti.id AND "
                . "ti.idclasse = $timeline_class AND "
                . "ti.anno = $timeline_year AND "
                . "te.performed = 1";
        $outcome_2 = db_select($conn, $query);
        db_simple_close($conn);
        if ($outcome_2->getOutcome() == QueryResult::SUCCESS) {
            exit_with_data($outcome_2->getContent());
        } else {
            exit_with_error($outcome_2->getMessage());
        }
    } else if($request->command === "load_timeline_sameclass"){
        $timeline_id = $request->id;
        //Caricare tutti i dati, anche delle tabelle derivate es: annoclasse, sezione
        $query = "SELECT ti.id as id, "
                . "ti.idmateria as idmateria, "
                . "ti.idclasse as idclasse, "
                . "ti.anno as anno, "
                . "ti.iddocente as iddocente, "
                . "cl.anno as annoclasse, "
                . "cl.sezione as sezione, "
                . "ma.nome as nomemateria "
                . "FROM timeline ti, classi cl, materie ma "
                . "WHERE "
                . "ti.idclasse = cl.id AND "
                . "ti.idmateria = ma.id AND "
                . "ti.idclasse in (SELECT t2.idclasse from timeline t2 WHERE t2.id=$timeline_id) AND "
                . "ti.anno in (SELECT t2.anno from timeline t2 WHERE t2.id=$timeline_id)";
        $conn = db_simple_connect();
        $outcome_1 = db_select($conn, $query);
        db_simple_close($conn);
        if ($outcome_1->getOutcome() == QueryResult::SUCCESS) {  
            $ret = $outcome_1->getContent();
            exit_with_data($ret);
        } else {
            exit_with_error($outcome_1->getMessage());
        }
    }
}
?>