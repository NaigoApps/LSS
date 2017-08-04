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
class UpdateBuilder {

    var $table;
    var $values;
    var $where;

    function __construct() {
        $this->table = null;
        $this->values = [];
        $this->where = [];
    }

    function update($table) {
        $this->table = $table;
        return $this;
    }

    function set($attr, $val, $str = false) {
        $str = $attr . " = ";
        if ($val !== null) {
            if (is_bool($val)) {
                $str .= ($val) ? "true" : "false";
            }else if ($str) {
                $str .= "'" . $val . "'";
            } else {
                $str .= $val;
            }
        } else {
            $str .= "NULL";
        }
        $this->values[] = $str;
        return $this;
    }
    
    function where(...$exprs) {
        foreach ($exprs as $expr) {
            $this->where[] = $expr;
        }
        return $this;
    }

    function getQuery() {
        $query = "";
        if ($this->table) {
            $query = $query . "UPDATE " . $this->table . " SET";
        }
        if ($this->values) {
            $query = $query . " " . join(',', $this->values);
        }
        if ($this->where) {
            $query = $query . " WHERE " . join(' AND ', $this->where);
        }
        return $query;
    }

}
