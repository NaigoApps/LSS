<?php


require_once __DIR__.'/../../common/php/ajax-header.php';
require_once __DIR__.'/../../common/php/dao/UserDao.php';

exit_with_data($_SESSION['user_data']);