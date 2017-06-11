<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';
require_once __DIR__ . '/../../common/php/dao/ElementDao.php';

$dao = new ElementDao();
$element = $dao->fromRequest($request->element);
if ($element->getType() == "module" || $element->getParent() != null) {
    $result = $dao->updateElement($element);
    if ($result->wasSuccessful()) {
        exit_with_data($dao->findById($element->getId())->uniqueContent());
    } else {
        exit_with_error($result->getMessage());
    }
} else {
    exit_with_error("Collegamento non valido");
}