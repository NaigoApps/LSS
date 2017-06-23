<?php


require_once __DIR__ . '/../ajax-header.php';
require_once __DIR__.'/../dao/ScheduleDao.php';

$id = null;
if (isset($request->id)) {
    $id = $request->id;
}

$dao = new ScheduleDao();
$result = $dao->findRelatedSchedules($id);
if($result->wasSuccessful()){
    exit_with_data($result->getContent());
}else{
    exit_with_error($result->getMessage());
}