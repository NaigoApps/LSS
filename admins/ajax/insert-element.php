<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';
require_once __DIR__ . '/../../common/php/dao/ElementDao.php';

$name = null;
$description = null;
$type = null;
$parent = null;
if (isset($request->name)) {
    $name = $request->name;
}
if (isset($request->description)) {
    $description = $request->description;
}
if (isset($request->type)) {
    $type = $request->type;
}
if (isset($request->parent) && $request->parent != null) {
    $parent = $request->parent->id;
}

if (isset($name) && !empty($name)) {
    $dao = new ElementDao();
    if ($parent != null && $type != "module") {
        $result = $dao->insertElement(["name" => $name, "description" => $description, "parent" => $parent, "type" => $type]);
    } else if ($type == "module") {
        $result = $dao->insertElement(["name" => $name, "description" => $description, "type" => $type]);
    } else {
        exit_with_error("Invalid data");
    }
    if ($result->wasSuccessful()) {
        exit_with_data($result->uniqueContent());
    } else {
        exit_with_error($result->getMessage());
    }
} else {
    exit_with_error("Nome non valido");
}