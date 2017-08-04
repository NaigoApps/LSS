<?php

require_once __DIR__.'/../../../../common/php/ajax-header.php';
require_once __DIR__.'/../../../../common/php/dao/ClassDao.php';

$id = null;
$section = null;
$year = null;
if (isset($request->id)) {
    $id = $request->id;
}
if (isset($request->section)) {
    $section = $request->section;
}
if (isset($request->year)) {
    $year = $request->year;
}

$dao = new ClassDao();
$result = $dao->updateClass([
    "id" => $id,
    "section" => $section, 
    "year" => $year]);
if($result->wasSuccessful()){
    exit_with_data("OK");
}else{
    exit_with_error($result->getMessage());
}