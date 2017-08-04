<?php

require_once __DIR__ . '/../ajax-header.php';
require_once __DIR__ . '/../dao/UserDao.php';

$dao = new UserDao();
$result = $dao->findUsers();
if ($result->wasSuccessful()) {
    exit_with_data($result->getContent());
} else {
    exit_with_error($result->getMessage());
}