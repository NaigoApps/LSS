<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Classroom
 *
 * @author root
 */
class Classroom implements JsonSerializable{

    private $id;
    private $section;
    private $year;

    function __construct() {
        $this->id = null;
        $this->section = null;
        $this->year = null;
    }

    function getId() {
        return $this->id;
    }

    function getSection() {
        return $this->section;
    }

    function getYear() {
        return $this->year;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setSection($section) {
        $this->section = $section;
    }

    function setYear($year) {
        $this->year = $year;
    }

    public function jsonSerialize() {
        return [
            "id" => $this->id,
            "section" => $this->section,
            "year" => $this->year
        ];
    }

}
