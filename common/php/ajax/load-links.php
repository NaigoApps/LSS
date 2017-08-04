<?php

require_once __DIR__ . '/../ajax-header.php';
require_once __DIR__ . '/../dao/LinkDao.php';

$dao = new LinkDao();
$result = $dao->findLinks([]);
if ($result->wasSuccessful()) {
    exit_with_data($result->getContent());
} else {
    exit_with_error($result->getMessage());
}