<?php

require_once("../../../../../common/auth-header.php");
require_once("../../../../../consts.php");

$year = $_SESSION['timeline-year'];
$subject = $_SESSION['timeline-subject'];
$class = $_SESSION['timeline-class'];
$subject_id = $_SESSION['timeline-subject-id'];
$class_id = $_SESSION['timeline-class-id'];

$query = "SELECT * FROM performed WHERE idclasse=$class_id AND anno=$year";

$conn = mysqli_connect(HOST, USER, PASS, DB);
std_select($conn, $query);

$rows = std_select($conn, $query);
if($rows !== null){
    exit_with_data($rows);
}else{
    exit_with_error("Impossibile recuperare i dati");
}

function exit_with_error($msg) {
    $json_response = json_encode($msg);
    http_response_code(400);
    die($json_response);
}

function exit_with_data($data) {
    $json_response = json_encode($data);
    http_response_code(200);
    die($json_response);
}


?>