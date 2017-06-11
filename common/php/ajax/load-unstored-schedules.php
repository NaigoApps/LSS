<?php


require_once __DIR__ . '/../ajax-header.php';
require_once __DIR__.'/../dao/ScheduleDao.php';

$dao = new ScheduleDao();
$result = $dao->findSchedules(["user" => $_SESSION['user_data']->getId(), "filed" => "false"], true);
if($result->wasSuccessful()){
    exit_with_data($result->getContent());
}else{
    exit_with_error($result->getMessage());
}