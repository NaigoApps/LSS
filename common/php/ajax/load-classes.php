<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';
require_once __DIR__ . '/../../common/php/dao/ClassDao.php';

$dao = new ClassDao();
$result = $dao->findClasses();
if ($result->wasSuccessful()) {
    exit_with_data($result->getContent());
} else {
    exit_with_error($result->getMessage());
}