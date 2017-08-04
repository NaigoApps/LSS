<?php


require_once __DIR__.'/../../common/php/ajax-header.php';
require_once __DIR__.'/../../common/php/dao/UserDao.php';

$name = null;
$surname = null;
$classroom = null;
if (isset($request->name)) {
    $name = $request->name;
}
if (isset($request->surname)) {
    $surname = $request->surname;
}
if (isset($request->classroom)) {
    $classroom = $request->classroom;
}

$dao = new UserDao();
$result = $dao->insertUser(["email" => $_SESSION["user_data"]->getEmail(),"name" => $name, "surname" => $surname, "classroom" => $classroom]);
if($result->wasSuccessful()){
    exit_with_data("OK");
}else{
    exit_with_error($result->getMessage());
}