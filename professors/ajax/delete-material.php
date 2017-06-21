<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';

require_once __DIR__ . '/../../common/php/dao/MaterialDao.php';

$ok = true;
$message = "";
if (isset($request->material)) {
    $dao = new MaterialDao();
    $result = $dao->deleteMaterial($request->material->id);
    if($result->wasSuccessful()){
        exit_with_data("OK");
    }else{
        exit_with_error($result->getMessage());
    }
}
