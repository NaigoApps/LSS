<?php

class QueryResult {

    const SUCCESS = 0;
    const FAILURE = 1;

    var $outcome;
    var $message;
    var $content;
    var $count;

    function __construct($outcome, $message, $content) {
        $this->outcome = $outcome;
        $this->message = $message;
        $this->content = $content;
        $this->count = count($content);
    }

    function getOutcome() {
        return $this->outcome;
    }

    function getCount() {
        return $this->count;
    }

    function wasSuccessful() {
        return $this->outcome == self::SUCCESS;
    }

    function wasUnsuccessful() {
        return $this->outcome == self::FAILURE;
    }

    function getMessage() {
        return $this->message;
    }

    function getContent() {
        return $this->content;
    }
    
    function uniqueContent() {
        return $this->content[0];
    }

}