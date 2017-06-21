<?php

require_once __DIR__ . '/../ajax-header.php';
require_once __DIR__ . '/../dao/FileDao.php';

$uploader = $_SESSION['user_data']->getId();

$dao = new FileDao();
$result = $dao->findFiles(["uploader" => $uploader]);
if ($result->wasSuccessful()) {
    exit_with_data($result->getContent());
} else {
    exit_with_error($result->getMessage());
}