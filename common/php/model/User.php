<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author root
 */
class User implements JsonSerializable{

    const UNKNOWN = 0b0000;
    const PROFESSOR = 0b0001;
    const STUDENT = 0b0010;
    const ADMIN = 0b0100;
    const UNREGISTERED = 0b1000;
    
    private $id;
    private $email;
    private $type;
   
    
    function __construct() {
        $this->id = null;
        $this->email = null;
        $this->type = self::UNKNOWN;
    }

    function getId() {
        return $this->id;
    }

    function getEmail() {
        return $this->email;
    }

    function getType() {
        return $this->type;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setType($type) {
        $this->type = $type;
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

    public function jsonSerialize() {
        return [
            "id" => $this->id,
            "email" => $this->email,
            "type" => $this->type
        ];
    }

}
