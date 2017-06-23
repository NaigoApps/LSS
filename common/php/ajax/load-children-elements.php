<?php

require_once __DIR__ . '/../ajax-header.php';
require_once __DIR__ . '/../dao/ElementDao.php';

$parent = null;
if (isset($request->parent)) {
    $parent = $request->parent;
}

$dao = new ElementDao();
$result = $dao->findElements(["parent" => $parent]);
if ($result->wasSuccessful()) {
    exit_with_data($result->getContent());
} else {
    exit_with_error($result->getMessage());
}