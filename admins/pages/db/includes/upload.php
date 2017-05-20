<?php

require_once("../../../../consts.php");
/*
  $sourcePath = $_FILES['file']['tmp_name'];       // Storing source path of the file in a variable
  $targetPath = "upload/".$_FILES['file']['name']; // Target path where file is to be stored
  move_uploaded_file($sourcePath,$targetPath) ;    // Moving Uploaded file */

$ok = true;
$message = "";

if (isset($_POST['type'], $_POST['id'])) {
    if (isset($_FILES['document'])) {
        $base_dir = $_POST['type'];
        $sourcePath = $_FILES['document']['tmp_name'];       // Storing source path of the file in a variable
        $targetPath = "../" . $base_dir . "/" . $_POST['id'] . "/";
        $path = realpath($targetPath);
        if (!is_dir($targetPath)) {
            $ok = mkdir($targetPath, 0777, true);
        }
        if ($ok) {
            $targetPath = $targetPath . $_FILES['document']['name'];
            $ok = move_uploaded_file($sourcePath, $targetPath);    // Moving Uploaded file*/
            if($ok){
                $message = "File successfully uploaded";
            }else{
                $message = "File non valido";
            }
        } else {
            $message = "Couldn't make directory";
        }
    } else {
        $ok = false;
        $message = "File not found";
    }
} else {
    $ok = false;
    $message =  "File not received";
}

if ($ok) {
    exit_with_data($message);
} else {
    exit_with_error($message);
}

function exit_with_error($msg) {
    $json_response = '{' . $msg . '}';
    http_response_code(400);
    die($json_response);
}

function exit_with_data($data) {
    $json_response = json_encode($data);
    http_response_code(200);
    die($json_response);
}
?>