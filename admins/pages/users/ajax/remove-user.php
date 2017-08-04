<?php

require_once __DIR__.'/../../../../common/php/ajax-header.php';
require_once __DIR__.'/../../../../common/php/dao/UserDao.php';

$id = null;
if (isset($request->id)) {
    $id = $request->id;
}

$dao = new UserDao();
$result = $dao->removeUser($id);
if($result->wasSuccessful()){
    exit_with_data("OK");
}else{
    exit_with_error($result->getMessage());
}