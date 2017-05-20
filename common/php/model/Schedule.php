<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Schedule
 *
 * @author root
 */
class Schedule implements JsonSerializable {

    private $id;
    private $subject;
    private $class;
    private $year;
    private $professor;
    private $filed;

    function __construct() {
        $this->id = null;
        $this->subject = null;
        $this->class = null;
        $this->year = null;
        $this->professor = null;
        $this->filed = null;
    }

    function getId() {
        return $this->id;
    }

    function getSubject() {
        return $this->subject;
    }

    function getClass() {
        return $this->class;
    }

    function getYear() {
        return $this->year;
    }

    function getProfessor() {
        return $this->professor;
    }

    function getFiled() {
        return $this->filed;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setSubject($subject) {
        $this->subject = $subject;
    }

    function setClass($class) {
        $this->class = $class;
    }

    function setYear($year) {
        $this->year = $year;
    }

    function setProfessor($professor) {
        $this->professor = $professor;
    }

    function setFiled($filed) {
        $this->filed = $filed;
    }

    public function jsonSerialize() {
        return [
            "id" => $this->id,
            "subject" => $this->subject,
            "class" => $this->class,
            "year" => $this->year,
            "professor" => $this->professor,
            "filed" => $this->filed,
        ];
    }

}
