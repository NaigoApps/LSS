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
class Element implements JsonSerializable {

    private $id;
    private $parent;
    private $name;
    private $description;
    private $type;

    function __construct() {
        $this->id = null;
        $this->parent = null;
        $this->name = null;
        $this->description = null;
        $this->type = null;
    }

    function getId() {
        return $this->id;
    }

    function getParent() {
        return $this->parent;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setParent($parent) {
        $this->parent = $parent;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function getType() {
        return $this->type;
    }

    function setType($type) {
        $this->type = $type;
    }

    public function jsonSerialize() {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "parent" => $this->parent,
            "type" => $this->type
        ];
    }

}
