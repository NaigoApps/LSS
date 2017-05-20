<?php

require_once './load-users.php';
require_once './load-subjects.php';
require_once './load-classes.php';

function load_schedules($conn, $id, $professor, $archived) {
    $query = build_schedules_query(null, $professor, $archived);
    $select_result = db_select($conn, $query);
    if ($select_result->wasSuccessful()) {
        $schedules = array();
        foreach ($select_result->getContent() as $schedule_info) {
            $schedule = array();
            $schedule['id'] = $schedule_info['id'];
            $schedule['archiviata'] = $schedule_info['archiviata'];
            $schedule['anno'] = $schedule_info['anno'];
            $schedule['materia'] = load_subject($conn, $schedule_info['idmateria'])->getContent();
            $schedule['classe'] = load_class($conn, $schedule_info['idclasse'])->getContent();
            $schedule['docente'] = load_user($conn, $schedule_info['iddocente'])->getContent();
            $schedules[] = $schedule;
        }
        return new QueryResult(QueryResult::SUCCESS, "", $schedules);
    }

    return $select_result;
}
