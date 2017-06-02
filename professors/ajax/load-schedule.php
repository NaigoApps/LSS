<?php


require_once __DIR__.'/../../common/php/ajax-header.php';
require_once __DIR__.'/../../common/php/dao/ScheduleDao.php';

$id = null;
if (isset($request->id)) {
    $id = $request->id;
}

$dao = new ScheduleDao();
$result = $dao->findSchedules(["id" => $id]);
if($result->wasSuccessful()){
    exit_with_data($result->uniqueContent());
}else{
    exit_with_error($result->getMessage());
}