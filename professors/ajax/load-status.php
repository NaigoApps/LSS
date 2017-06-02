<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';
require_once __DIR__ . '/../../common/php/dao/ScheduleElementDao.php';

$element = null;
$schedule = null;
if (isset($request->element)) {
    $element = $request->element;
}
if (isset($request->schedule)) {
    $schedule = $request->schedule;
}

$dao = new ScheduleElementDao();
exit_with_data($dao->computeFullStatus($element, $schedule));
