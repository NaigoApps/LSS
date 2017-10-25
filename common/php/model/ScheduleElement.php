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
class ScheduleElement implements JsonSerializable {

    private $id;
    private $schedule;
    private $status;
    private $fullStatus;
    private $date;
    private $message;
    private $element;

    function __construct() {
        $this->id = null;
        $this->schedule = null;
        $this->status = null;
        $this->message = null;
        $this->fullStatus = [];
        $this->date = null;
        $this->element = null;
    }
    function getId() {
        return $this->id;
    }

    function getSchedule() {
        return $this->schedule;
    }

    function getMessage() {
        return $this->message;
    }

    function getStatus() {
        return $this->status;
    }
    
    function getFullStatus() {
        return $this->fullStatus;
    }

    function getDate() {
        return $this->date;
    }

    function getElement() {
        return $this->element;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setSchedule($schedule) {
        $this->schedule = $schedule;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setFullStatus($status) {
        $this->fullStatus = $status;
    }

    function setDate($date) {
        $this->date = $date;
    }

    function setMessage($message) {
        $this->message = $message;
    }

    function setElement($element) {
        $this->element = $element;
    }

        public function jsonSerialize() {
        return [
            "id" => $this->id,
            "element" => $this->element,
            "schedule" => $this->schedule,
            "status" => $this->status,
            "message" => $this->message,
            "fullStatus" => $this->fullStatus,
            "date" => $this->date
        ];
    }

}
