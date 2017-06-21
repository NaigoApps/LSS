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
class File implements JsonSerializable {

    private $id;
    private $name;
    private $uploader;

    function __construct() {
        $this->id = null;
        $this->name = null;
        $this->uploader = null;
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getUploader() {
        return $this->uploader;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setUploader($uploader) {
        $this->uploader = $uploader;
    }

    public function jsonSerialize() {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "uploader" => $this->uploader,
        ];
    }

}
