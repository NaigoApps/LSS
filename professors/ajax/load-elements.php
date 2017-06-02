<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';
require_once __DIR__ . '/../../common/php/dao/ElementDao.php';

$type = null;
$parent = null;
if (isset($request->type)) {
    $type = $request->type;
}
if (isset($request->parent)) {
    $parent = $request->parent;
}

$dao = new ElementDao();
$result = $dao->findElements(["type" => $type, "parent" => $parent]);
if ($result->wasSuccessful()) {
    exit_with_data($result->getContent());
} else {
    exit_with_error($result->getMessage());
}