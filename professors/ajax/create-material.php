<?php


require_once __DIR__.'/../../common/php/ajax-header.php';
require_once __DIR__.'/../../common/php/dao/MaterialDao.php';

$dao = new MaterialDao();
$material = $dao->fromRequest($request);
$result = $dao->insertMaterial($material);
if($result->wasSuccessful()){
    exit_with_data("OK");
}else{
    exit_with_error($result->getMessage());
}