<?php


require_once __DIR__.'/../../common/php/ajax-header.php';
require_once __DIR__.'/../../common/php/dao/ScheduleDao.php';

$id = null;
if (isset($request->id)) {
    $id = $request->id;
}

$dao = new ScheduleDao();
$toUpdate = $dao->findById($id);
if ($toUpdate->wasSuccessful()) {
    $toUpdate = $toUpdate->uniqueContent();
    if (isAuthorized($toUpdate)) {
        $toUpdate->setFiled("false");
        $result = $dao->updateSchedule($toUpdate);
        if ($result->wasSuccessful()) {
            exit_with_data("OK");
        } else {
            exit_with_error($result->getMessage());
        }
    } else {
        exit_with_error("Unauthorized");
    }
} else {
    exit_with_error($toUpdate->getMessage());
}