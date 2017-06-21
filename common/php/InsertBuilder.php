<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QueryBuilder
 *
 * @author root
 */
class InsertBuilder {

    var $table;
    var $attrs;
    var $values;
    var $newValues;

    function __construct() {
        $this->table = null;
        $this->attrs = [];
        $this->values = [];
        $this->newValues = [];
    }

    function insert($table) {
        $this->table = $table;
        return $this;
    }

    function attrs(...$attrs) {
        foreach ($attrs as $attr) {
            $this->attrs[] = $attr;
        }
        return $this;
    }

    function startValues() {
        $this->newValues = [];
        return $this;
    }

    function value($val, $str = false) {
        if ($val !== null) {
            if (is_bool($val)) {
                $this->newValues[] = ($val) ? "true" : "false";
            }else if ($str) {
                $this->newValues[] = "'" . $val . "'";
            } else {
                $this->newValues[] = $val;
            }
        } else {
            $this->newValues[] = "NULL";
        }
        return $this;
    }

    function endValues() {
        $this->values[] = join(',', $this->newValues);
        return $this;
    }

    function getQuery() {
        $query = "";
        if ($this->table) {
            $query = $query . "INSERT INTO " . $this->table;
        }
        if ($this->attrs) {
            $query = $query . " (" . join(',', $this->attrs) . ")";
        }
        if ($this->values) {
            $query = $query . " VALUES(" . join('),(', $this->values) . ")";
        }
        return $query;
    }

}
