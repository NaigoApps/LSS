<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';
require_once __DIR__ . '/../../common/php/TransactionManager.php';
require_once __DIR__ . '/../../common/php/dao/ElementDao.php';

$id = null;
if (isset($request->id)) {
    $id = $request->id;
}

$dao = new ElementDao();

if ($_SESSION['user_data']->isAdmin()) {
    $result = $dao->deleteElement($id);
    if ($result->wasSuccessful()) {
        exit_with_data("OK");
    } else {
        exit_with_error($result->getMessage());
    }
} else {
    exit_with_error("Unauthorized");
}
