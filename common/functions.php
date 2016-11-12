<?php

include $_SERVER['DOCUMENT_ROOT'] . '/LSS/consts.php';

class QueryResult {
    
    const SUCCESS = 0;
    const FAILURE = 1;

    var $outcome;
    var $message;
    var $content;

    function __construct($outcome, $message, $content) {
        $this->outcome = $outcome;
        $this->message = $message;
        $this->content = $content;
    }

    function getOutcome(){
        return $this->outcome;
    }
    
    function wasSuccessful(){
        return $this->outcome == self::SUCCESS;
    }
    
    function wasUnsuccessful(){
        return $this->outcome == self::FAILURE;
    }

    function getMessage(){
        return $this->message;
    }

    function getContent(){
        return $this->content;
    }
}

/*
 * Unknown = 0
 * Professor = 1
 * Student = 2
 * Admin = 4
 * Admin-Professor = 5
 * Unregistered = 8
 */

class User {

    const UNKNOWN = 0b0000;
    const PROFESSOR = 0b0001;
    const STUDENT = 0b0010;
    const ADMIN = 0b0100;
    const UNREGISTERED = 0b1000;

    var $id;
    var $name;
    var $surname;
    var $email;
    var $google_id;
    var $google_image;
    var $type;

    function __construct($id, $email, $type) {
        $this->id = $id;
        $this->email = $email;
        $this->type = $type;
    }

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getEmail() {
        return $this->email;
    }

    function isAdmin() {
        return ($this->type & self::ADMIN) !== 0;
    }

    function isProfessor() {
        return ($this->type & self::PROFESSOR) !== 0;
    }

    function isStudent() {
        return ($this->type & self::STUDENT) !== 0;
    }

    function isUnknown() {
        return $this->type == 0;
    }

    function isUnregistered() {
        return ($this->type & self::UNREGISTERED) !== 0;
    }

}

function get_user_info($email) {
    $connection = mysqli_connect(HOST, USER, PASS, DB);
    $query = "SELECT * FROM utenti WHERE email = '$email'";
    $user = unique_select($connection, $query);

    if ($user) {
        return new User($user['id'], $user['email'], intval($user['type']));
    } else {
        return new User(-1, "", User::UNREGISTERED);
    }


    mysqli_close($connection);
    return false;
}

function is_professor_logged() {
    if (isset($_POST['user'])) {
        $user = $_POST['user'];
        return new User($user->email, $user->type) . isProfessor();
    }
}

function std_select($conn, $query) {
    $result = mysqli_query($conn, $query);
    if ($result !== false) {
        $rows = array();
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        return $rows;
    } else {
        return null;
    }
}

function db_select($mysqli, $query) {
    $result = $mysqli->query($query);
    if ($result !== false) {
        $rows = array();
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        return new QueryResult(QueryResult::SUCCESS, NULL, $rows);
    } else {
        return new QueryResult(QueryResult::FAILURE, $mysqli->error, NULL);
    }
}

function db_transaction_connect(){
    $mysqli = new mysqli(HOST, USER, PASS, DB);
    if(!mysqli_connect_errno()){
        if($mysqli->autocommit(false)){
            return $mysqli;
        }else{
            $mysqli->close();
            return false;
        }
    }else{
        return false;
    }
}

function db_transaction_close($mysqli){
    if($mysqli->commit()){
        $mysqli->close();
        return true;
    }
    return false;
}
function db_simple_connect(){
    $mysqli = new mysqli(HOST, USER, PASS, DB);
    if(!mysqli_connect_errno()){
        return $mysqli;
    }else{
        return false;
    }
}

function db_simple_close($mysqli){
    $mysqli->close();
}

function db_transaction_abort($mysqli){
    if($mysqli->rollback()){
        $mysqli->close();
        return true;
    }
    return false;
}

function db_insert($mysqli, $query) {
    $result = $mysqli->real_query($query);
    if ($result !== false) {
        $id = $mysqli->insert_id;
        if($id !== 0){
            return new QueryResult(QueryResult::SUCCESS, NULL, $id);
        }else{
            return new QueryResult(QueryResult::FAILURE, $mysqli->error, NULL);
        }
    } else {
        return new QueryResult(QueryResult::FAILURE, $mysqli->error, NULL);
    }
}

function db_multi_insert($mysqli, $query) {
    $result = $mysqli->real_query($query);
    if ($result !== false) {
        return new QueryResult(QueryResult::SUCCESS, NULL, NULL);
    } else {
        return new QueryResult(QueryResult::FAILURE, $mysqli->error, NULL);
    }
}

/**
 * 
 * @param type $mysqli
 * @param type $query
 * @return \QueryResult
 */
function db_update($mysqli, $query) {
    $result = $mysqli->real_query($query);
    if ($result !== false) {
        return new QueryResult(QueryResult::SUCCESS, NULL, NULL);
    } else {
        return new QueryResult(QueryResult::FAILURE, $mysqli->error, NULL);
    }
}

function unique_select($conn, $query) {
    $res = std_select($conn, $query);
    if ($res && count($res) == 1) {
        return $res[0];
    }
    return false;
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
