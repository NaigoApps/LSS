<?php

require_once __DIR__ . '/../ajax-header.php';
require_once __DIR__ . '/../dao/ElementDao.php';

$type = null;
$parent = null;
if (isset($request->type)) {
    $type = $request->type;
}
if (isset($request->parent)) {
    $parent = $request->parent;
}
if (isset($request->match)) {
    $match = $request->match;
}

$dao = new ElementDao();
$result = $dao->findElements(["type" => $type, "parent" => $parent]);
if ($result->wasSuccessful()) {
    if (isset($match)) {
        if (strlen($match) >= 3) {
            $match = strtolower($match);
            $realResult = array();
            foreach ($result->getContent() as $element) {
                if (strpos(strtolower($element->getName()), $match) !== false) {
                    array_push($realResult, $element);
                }
            }
            exit_with_data($realResult);
        }
        exit_with_data(array());
    } else {
        exit_with_data($result->getContent());
    }
} else {
    exit_with_error($result->getMessage());
}