<?php


require_once __DIR__ . '/../ajax-header.php';
require_once __DIR__.'/../dao/ScheduleDao.php';

$sid = null;
if (isset($request->id)) {
    $sid = $request->id;
}

$dao = new ScheduleDao();
$result = $dao->findByStudent($sid);
if($result->wasSuccessful()){
    exit_with_data($result->getContent());
}else{
    exit_with_error($result->getMessage());
}