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
class Material implements JsonSerializable {

    private $id;
    private $name;
    private $element;
    private $uploader;
    private $private;
    private $approved;
    private $url;
    private $file;

    function __construct() {
        $this->id = null;
        $this->name = null;
        $this->element = null;
        $this->uploader = null;
        $this->private = null;
        $this->approved = null;

        $this->url = null;
        $this->file = null;
    }
    
    function getPrivate() {
        return $this->private;
    }

    function getApproved() {
        return $this->approved;
    }

    
    function getFile() {
        return $this->file;
    }

    function setFile($file) {
        $this->file = $file;
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getElement() {
        return $this->element;
    }

    function getUrl() {
        return $this->url;
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

    function setElement($element) {
        $this->element = $element;
    }

    function setUrl($url) {
        $this->url = $url;
    }

    function setUploader($uploader) {
        $this->uploader = $uploader;
    }

    function setPrivate($private) {
        $this->private = $private;
    }

    function setApproved($approved) {
        $this->approved = $approved;
    }

    public function jsonSerialize() {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "element" => $this->element,
            "uploader" => $this->uploader,
            "private" => $this->private,
            "approved" => $this->approved,
            "url" => $this->url,
            "file" => $this->file
        ];
    }

}
