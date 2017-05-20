<?php


require_once __DIR__.'/../../common/php/ajax-header.php';
require_once __DIR__.'/../../common/php/dao/ScheduleDao.php';

$dao = new ScheduleDao();
$result = $dao->findAll(false, null, null, null, $_SESSION['user_data']->getId());
if($result->wasSuccessful()){
    exit_with_data($result->getContent());
}else{
    exit_with_error($result->getMessage());
}