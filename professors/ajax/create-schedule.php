<?php


require_once __DIR__.'/../../common/php/ajax-header.php';
require_once __DIR__.'/../../common/php/dao/ScheduleDao.php';

$subject = null;
$class = null;
$year = null;
if (isset($request->subject)) {
    $subject = $request->subject;
}
if (isset($request->class)) {
    $class = $request->class;
}
if (isset($request->year)) {
    $year = $request->year;
}

$dao = new ScheduleDao();
$result = $dao->insertSchedule(["subject" => $subject, "class" => $class, "year" => $year]);
if($result->wasSuccessful()){
    exit_with_data("OK");
}else{
    exit_with_error($result->getMessage());
}