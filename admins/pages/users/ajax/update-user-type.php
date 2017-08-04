<?php

require_once __DIR__ . '/../../../../common/php/ajax-header.php';
require_once __DIR__ . '/../../../../common/php/dao/UserDao.php';

$id = null;
$grant = null;
$revoke = null;
if (isset($request->id)) {
    $id = $request->id;
}
if (isset($request->grant)) {
    $grant = $request->grant;
}
if (isset($request->revoke)) {
    $revoke = $request->revoke;
}

$dao = new UserDao();
$cur_user = $dao->findById($id)->uniqueContent();
$type = $cur_user->getType();
if ($grant != null) {
    $type |= $grant;
}
if($revoke != null){
    $type &= ~$revoke;
}

$result = $dao->updateUser([
    "id" => $id,
    "type" => $type]);
if ($result->wasSuccessful()) {
    exit_with_data("OK");
} else {
    exit_with_error($result->getMessage());
}