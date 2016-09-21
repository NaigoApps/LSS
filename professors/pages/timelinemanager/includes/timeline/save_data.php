<?php

require_once("../../../../../common/auth-header.php");
require_once("../../../../../consts.php");

$year = $_SESSION['timeline-year'];
$subject = $_SESSION['timeline-subject'];
$class = $_SESSION['timeline-class'];
$subject_id = $_SESSION['timeline-subject-id'];
$class_id = $_SESSION['timeline-class-id'];
$folder = $_SESSION['timeline-folder'];
$file = "../../../../timelines/" . $folder . "/$year-$class-$class_id-$subject-$subject_id.json";

$fh = fopen($file, 'w') or die("can't open file");
$stringData = $_POST["data"];
//$stringData = iconv("UTF-8", "ASCII//TRANSLIT", $stringData);
fwrite($fh, $stringData);
fclose($fh);

$timeline = json_decode($stringData);

$delete_query = "DELETE FROM performed WHERE idclasse=$class_id AND idmateria=$subject_id AND anno=$year";
$update_query = "INSERT INTO performed(idvoce, idmateria, idclasse, anno, data) VALUES ";

$at_least_one = false;
foreach ($timeline as $entry) {
    if (isset($entry->performed) && $entry->performed) {
        $at_least_one = true;
        $date_time = new DateTime($entry->start);
        $update_query = $update_query . "(" . $entry->id . ",$subject_id,$class_id,$year,'".$date_time->format('Y')."-" . $date_time->format('m') . "-".$date_time->format('d')."'),";
    }
}

$update_query = rtrim($update_query, ",");
$connection = mysqli_connect(HOST, USER, PASS, DB);

if (mysqli_autocommit($connection, false)) {
    if (mysqli_real_query($connection, $delete_query)) {
        if (!$at_least_one || mysqli_real_query($connection, $update_query) && ($last_id = mysqli_insert_id($connection)) != 0) {
            mysqli_commit($connection);
            exit_with_data("Programmazione aggiornata correttamente");
        } else {
            mysqli_rollback($connection);
            exit_with_error($update_query);
        }
    } else {
        mysqli_rollback($connection);
        exit_with_error($delete_query);
    }
}

function exit_with_error($msg) {
    $json_response = json_encode('{msg:' . $msg . '}');
    http_response_code(400);
    die($json_response);
}

function exit_with_data($data) {
    $json_response = json_encode($data);
    http_response_code(200);
    die($json_response);
}

?>