<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Subject
 *
 * @author root
 */
class Subject implements JsonSerializable{
    private $id;
    private $name;
    private $description;
    private $color;
    
    function __construct() {
        $this->id = null;
        $this->name = null;
        $this->description = null;
        $this->color = null;
    }
    
    function getId() {
        return $this->id;
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

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function getColor() {
        return $this->color;
    }

    function setColor($color) {
        $this->color = $color;
    }

        
    public function jsonSerialize() {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "color" => $this->color
        ];
    }

}