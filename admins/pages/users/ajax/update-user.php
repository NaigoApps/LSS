<?php

require_once __DIR__.'/../../common/php/ajax-header.php';
require_once __DIR__.'/../../common/php/dao/UserDao.php';

$id = null;
$name = null;
$email = null;
$surname = null;
$classroom = null;
$type = null;
if (isset($request->id)) {
    $id = $request->id;
}
if (isset($request->name)) {
    $name = $request->name;
}
if (isset($request->email)) {
    $email = $request->email;
}
if (isset($request->surname)) {
    $surname = $request->surname;
}
if (isset($request->classroom)) {
    $classroom = $request->classroom;
}
if (isset($request->type)) {
    $type = $request->type;
}

$dao = new UserDao();
$result = $dao->updateUser([
    "id" => $id,
    "type" => $type, 
    "email" => $email,
    "name" => $name, 
    "surname" => $surname,
    "classroom" => $classroom]);
if($result->wasSuccessful()){
    exit_with_data("OK");
}else{
    exit_with_error($result->getMessage());
}