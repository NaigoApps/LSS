<?php

require_once __DIR__ . '/../ajax-header.php';
require_once __DIR__ . '/../dao/SubjectDao.php';

$dao = new SubjectDao();
$result = $dao->findSubjects();
if ($result->wasSuccessful()) {
    exit_with_data($result->getContent());
} else {
    exit_with_error($result->getMessage());
}