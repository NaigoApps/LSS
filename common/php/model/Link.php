<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Material
 *
 * @author root
 */
class Link implements JsonSerializable {

    private $id;
    private $element1;
    private $element2;

    function __construct() {
        $this->id = null;
        $this->element1 = null;
        $this->element2 = null;
    }
    
    function getId() {
        return $this->id;
    }

    function getElement1() {
        return $this->element1;
    }

    function getElement2() {
        return $this->element2;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setElement1($element) {
        $this->element1 = $element;
    }

    function setElement2($element) {
        $this->element2 = $element;
    }

    public function jsonSerialize() {
        return [
            "id" => $this->id,
            "element1" => $this->element1,
            "element2" => $this->element2
        ];
    }

}
