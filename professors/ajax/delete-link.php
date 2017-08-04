<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';

require_once __DIR__ . '/../../common/php/dao/LinkDao.php';

$ok = true;
$message = "";
if (isset($request->id)) {
    $dao = new LinkDao();
    $result = $dao->deleteLink($request->id);
    if($result->wasSuccessful()){
        exit_with_data("OK");
    }else{
        exit_with_error($result->getMessage());
    }
}
