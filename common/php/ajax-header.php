<?php

require_once __DIR__ . '/consts.php';
require_once __DIR__ . '/../auth-header.php';

function exception_handler($exception) {
    $traces = "";
    foreach ($exception->getTrace() as $trace) {
        $traces = $traces . "<p>" . $trace["file"] . $trace["line"] . $trace["function"] . "</p>";
    }
    exit_with_error("Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " line " . $exception->getLine() . " TRACE: " . $traces);
}

function error_handler($errno, $errstr, $errfile, $errline) {
    exit_with_error("Error " . $errno . ":" . $errstr . " in " . $errfile . " line " . $errline);
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

set_error_handler('error_handler');
set_exception_handler('exception_handler');

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
