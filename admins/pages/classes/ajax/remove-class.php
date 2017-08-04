<?php

require_once __DIR__.'/../../../../common/php/ajax-header.php';
require_once __DIR__.'/../../../../common/php/dao/ClassDao.php';

$id = null;
if (isset($request->id)) {
    $id = $request->id;
}

$dao = new ClassDao();
$result = $dao->removeClass($id);
if($result->wasSuccessful()){
    exit_with_data("OK");
}else{
    exit_with_error($result->getMessage());
}