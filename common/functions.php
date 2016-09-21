<?php

include $_SERVER['DOCUMENT_ROOT'] . '/LSS/consts.php';
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
    
    function __construct($id,$email, $type) {
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

    function isAdmin(){
        return ($this->type & self::ADMIN) !== 0;
    }

    function isProfessor(){
        return ($this->type & self::PROFESSOR) !== 0;
    }

    function isStudent(){
        return ($this->type & self::STUDENT) !== 0;
    }

    function isUnknown(){
        return $this->type == 0;
    }
    
    function isUnregistered(){
        return ($this->type & self::UNREGISTERED) !== 0;
    }
}

function get_user_info($email) {
    $connection = mysqli_connect(HOST, USER, PASS, DB);
    $query = "SELECT * FROM utenti WHERE email = '$email'";
    $user = unique_select($connection, $query);
    
    if($user){
        return new User($user['id'],$user['email'],intval($user['type']));
    }else{
        return new User(-1,"",User::UNREGISTERED);
    }
    
    
    mysqli_close($connection);
    return false;
}

function is_professor_logged() {
    if (isset($_POST['user'])) {
        $user = $_POST['user'];
        return new User($user->email, $user->type).isProfessor();
    }
}

function not_empty_result($query) {
    $connection = mysqli_connect($HOST, $USER, $PASS, $DB);
    $res = mysqli_real_query($connection, $query);
    if (mysqli_num_rows($res) > 0) {
        mysqli_close($connection);
        return true;
    }
    mysqli_close($connection);
    return false;
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

function unique_select($conn, $query) {
    $res = std_select($conn, $query);
    if($res && count($res) == 1){
        return $res[0];
    }
    return false;
}
