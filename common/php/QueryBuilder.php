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
class QueryBuilder {

    var $select;
    var $from;
    var $where;
    var $order;

    function __construct() {
        $this->select = [];
        $this->from = [];
        $this->where = [];
        $this->order = [];
    }

    function select(...$attrs) {
        foreach ($attrs as $attr) {
            $this->select[] = $attr;
        }
        return $this;
    }

    function from(...$tables) {
        foreach ($tables as $table) {
            $this->from[] = $table;
        }
        return $this;
    }

    function where(...$exprs) {
        foreach ($exprs as $expr) {
            $this->where[] = $expr;
        }
        return $this;
    }

    function order(...$exprs) {
        foreach ($exprs as $expr) {
            $this->order[] = $expr;
        }
        return $this;
    }

    function getQuery() {
        $query = "";
        if ($this->select) {
            $query = $query . "SELECT " . join(',', $this->select);
        }
        if ($this->from) {
            $query = $query . " FROM " . join(',', $this->from);
        }
        if ($this->where) {
            $query = $query . " WHERE " . join(' AND ', $this->where);
        }
        if ($this->order) {
            $query = $query . " ORDER BY " . join(',', $this->order);
        }
        return $query;
    }

}