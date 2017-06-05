<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';
require_once __DIR__ . '/../../common/php/TransactionManager.php';
require_once __DIR__ . '/../../common/php/dao/ScheduleDao.php';
require_once __DIR__ . '/../../common/php/dao/ScheduleElementDao.php';

$id = null;
if (isset($request->id)) {
    $id = $request->id;
}

$sDao = new ScheduleDao();

$toDelete = $sDao->findById($id, true);
if (isAuthorized($toDelete->uniqueContent())) {
    $result = $sDao->deleteSchedule($id);
    if ($result->wasSuccessful()) {
        exit_with_data("OK");
    } else {
        exit_with_error($result->getMessage());
    }
} else {
    exit_with_error("Unauthorized");
}
