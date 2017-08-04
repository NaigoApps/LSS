<?php


require_once __DIR__.'/../../common/php/ajax-header.php';
require_once __DIR__.'/../../common/php/dao/LinkDao.php';

$dao = new LinkDao();
$link = $dao->fromRequest($request);
$result = $dao->insertLink($link);
if($result->wasSuccessful()){
    exit_with_data($result->uniqueContent());
}else{
    exit_with_error($result->getMessage());
}