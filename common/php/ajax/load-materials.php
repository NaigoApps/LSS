<?php

require_once __DIR__ . '/../ajax-header.php';
require_once __DIR__ . '/../dao/MaterialDao.php';

$element = null;
$uploader = $_SESSION['user_data']->getId();
if (isset($request->element)) {
    $element = $request->element;
}

$dao = new MaterialDao();
$result = $dao->findMaterials(["element" => $element, "uploader" => $uploader]);
if ($result->wasSuccessful()) {
    exit_with_data($result->getContent());
} else {
    exit_with_error($result->getMessage());
}