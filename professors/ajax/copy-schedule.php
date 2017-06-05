<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';
require_once __DIR__ . '/../../common/php/TransactionManager.php';
require_once __DIR__ . '/../../common/php/dao/ScheduleDao.php';
require_once __DIR__ . '/../../common/php/dao/ScheduleElementDao.php';

$id = null;
$subject = null;
$class = null;
$year = null;
if (isset($request->id)) {
    $id = $request->id;
}
if (isset($request->subject)) {
    $subject = $request->subject;
}
if (isset($request->class)) {
    $class = $request->class;
}
if (isset($request->year)) {
    $year = $request->year;
}

$tm = new TransactionManager();
$sDao = new ScheduleDao($tm);
$seDao = new ScheduleElementDao($tm);
$ok = true;
$error = "";
if ($tm->beginTransaction()) {
    $schedule = $sDao->findById($id);
    if ($schedule->wasSuccessful()) {
        $schedule = $schedule->uniqueContent();
        $newId = $sDao->insertSchedule(["subject" => $subject, "class" => $class, "year" => $year]);
        if ($newId->wasSuccessful()) {
            $newId = $newId->uniqueContent();
            adjustSchedule($schedule, $newId);
            $result = $seDao->insertElements($schedule->getElements());
            if ($result->wasSuccessful()) {
                $ok = true;
            } else {
                $error = $result->getMessage();
                $ok = false;
            }
        } else {
            $error = $newId->getMessage();
            $ok = false;
        }
    } else {
        $error = $schedule->getMessage();
        $ok = false;
    }

    if ($ok && $tm->commit()) {
        exit_with_data("OK");
    } else {
        $tm->rollback();
        exit_with_error("Impossibile duplicare la programmazione: ".$error);
    }
} else {
    exit_with_error("Impossibile iniziare la transazione");
}

function adjustSchedule($schedule, $id) {
    foreach ($schedule->getElements() as $element) {
        $element->setSchedule($id);
        $element->setStatus('todo');
    }
}
