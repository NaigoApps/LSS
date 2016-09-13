<?php
require_once '../../../common/auth-header.php';

require_once("../../../consts.php");
$connection = mysqli_connect(HOST, USER, PASS, DB);

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if ($request != null && isset($request->command)) {
    if($request->command === "create_timeline"){
        $year = $request->year;
        $class = $request->class->nome;
        $subject = $request->subject->nome;
        $myFile = "../../".$user_data->getId();
        if(!file_exists($myFile)){
            mkdir($myFile);
        }
        $myFile = $myFile."/$year-$class-$subject.json";
        if(!file_exists($myFile)){
            $fh = fopen($myFile, 'w') or die("can't open file");
            fwrite($fh, "[]");
            fclose($fh);
            $_SESSION['timeline-year'] = $year;
            $_SESSION['timeline-subject'] = $subject;
            $_SESSION['timeline-class'] = $class;
            $_SESSION['timeline-folder'] = $user_data->getId();
            exit_with_data("");
        }else{
            exit_with_error("Timeline esistente!");
        }
        //$stringData = $_POST["data"];
        //fwrite($fh, $stringData);
    }else if($request->command === "edit_timeline"){
        $timeline = $request->timeline;
        $myFile = "../../".$user_data->getId();
        if(!file_exists($myFile)){
            mkdir($myFile);
        }
        $myFile = $myFile."/$timeline.json";
        if(file_exists($myFile)){
            $vars = explode("-", $timeline);
            $_SESSION['timeline-year'] = $vars[0];
            $_SESSION['timeline-class'] = $vars[1];
            $_SESSION['timeline-subject'] = $vars[2];
            $_SESSION['timeline-folder'] = $user_data->getId();
            exit_with_data("");
        }else{
            exit_with_error("Timeline inesistente!");
        }
        //$stringData = $_POST["data"];
        //fwrite($fh, $stringData);
    }else if($request->command === "delete_timeline"){
        $timeline = $request->timeline;
        $myFile = "../../".$user_data->getId();
        if(!file_exists($myFile)){
            mkdir($myFile);
        }
        $myFile = $myFile."/$timeline.json";
        if(file_exists($myFile)){
            unlink($myFile);
            exit_with_data("");
        }else{
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