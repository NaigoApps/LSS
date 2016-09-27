<?php

require_once '../../../common/auth-header.php';

require_once("../../../consts.php");
$connection = mysqli_connect(HOST, USER, PASS, DB);

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if ($request != null && isset($request->command)) {
    if ($request->command === "view_timeline") {
        $timeline = $request->timeline;
        $myFile = "../../../timelines/" . $user_data->getId();
        if (!file_exists($myFile)) {
            mkdir($myFile);
        }
        $myFile = $myFile . "/$timeline.json";
        if (file_exists($myFile)) {
            $vars = explode("-", $timeline);
            $_SESSION['timeline-year'] = $vars[0];
            $_SESSION['timeline-class-year'] = $vars[1];
            $_SESSION['timeline-class-section'] = $vars[2];
            $_SESSION['timeline-class-id'] = $vars[3];
            $_SESSION['timeline-subject'] = $vars[4];
            $_SESSION['timeline-subject-id'] = $vars[5];
            $_SESSION['timeline-folder'] = $user_data->getId();
            exit_with_data("");
        } else {
            exit_with_error("Timeline inesistente!");
        }
        //$stringData = $_POST["data"];
        //fwrite($fh, $stringData);
    } else if ($request->command === "delete_timeline") {
        $timeline = $request->timeline;
        $myFile = "../../timelines/" . $user_data->getId();
        if (!file_exists($myFile)) {
            mkdir($myFile);
        }
        $myFile = $myFile . "/$timeline.json";

        if (file_exists($myFile)) {
            $descr = explode("-", $timeline);
            $delete_query = "DELETE FROM performed WHERE idclasse=$descr[3] AND idmateria=$descr[5] AND anno=$descr[0]";
            var_dump($delete_query);
            $connection = mysqli_connect(HOST, USER, PASS, DB);

            if (mysqli_autocommit($connection, false)) {
                if (mysqli_real_query($connection, $delete_query)) { 
                    mysqli_commit($connection);
                    unlink($myFile);
                    exit_with_data("Programmazione eliminata correttamente");
                } else {
                    mysqli_rollback($connection);
                    exit_with_error($delete_query);
                }
            }
        } else {
            exit_with_error("Timeline inesistente!");
        }
        //$stringData = $_POST["data"];
        //fwrite($fh, $stringData);
    }
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
