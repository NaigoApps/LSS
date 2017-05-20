<?php

require_once '../../TransactionManager.php';
require_once '../../dao/ClassDao.php';

$tm = new TransactionManager();
$dao = new ClassDao($tm);
$result = $dao->findAll()->getContent();

foreach ($result as $class) {
    echo "<p>";
    echo $class->getId()." - ".$class->getYear()." - ".$class->getSection();
    echo "</p>";
}