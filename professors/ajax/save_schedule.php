<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';
require_once __DIR__ . '/../../common/php/dao/ScheduleElementDao.php';

$schedule = null;
if (isset($request->schedule)) {
    $schedule = $request->schedule;
}

$tm = new TransactionManager();
if ($tm->beginTransaction()) {
    $sed = new ScheduleElementDao($tm);
    $res = $sed->deleteBySchedule($schedule->id);
    if ($res->wasSuccessful()) {
        $res = $sed->insertRawElements($schedule->elements);
        if ($res->wasSuccessful()) {
            if ($tm->commit()) {
                exit_with_data("OK");
            } else {
                $tm->rollback();
                exit_with_error("Error while committing...");
            }
        }else{
            exit_with_error($res->getMessage());
        }
    }else{
        exit_with_error($res->getMessage());
    }
}