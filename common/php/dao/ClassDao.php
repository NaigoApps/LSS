<?php

require_once __DIR__ . '/../QueryBuilder.php';
require_once __DIR__ . '/Dao.php';
require_once __DIR__ . '/../model/Classroom.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubjectDao
 *
 * @author root
 */
class ClassDao extends Dao {

    public function findById($id) {
        $builder = new QueryBuilder();
        $builder->select("*")->from("classi c")->where("c.id = $id");
        $select_result = parent::find($builder->getQuery());
        if ($select_result->wasSuccessful()) {
            return new QueryResult(QueryResult::SUCCESS, "", $this->toClasses($select_result));
        }
        return $select_result;
    }

    public function findClasses() {
        $builder = new QueryBuilder();
        $builder->select("*")->from("classi c");
        $select_result = $this->find($builder->getQuery());

        if ($select_result->wasSuccessful()) {
            return new QueryResult(QueryResult::SUCCESS, "", $this->toClasses($select_result));
        }

        return $select_result;
    }

    private function toClasses($res) {
        $classes = array();
        foreach ($res->getContent() as $class) {
            $result = new Classroom();
            $result->setId($class['id']);
            $result->setSection($class['sezione']);
            $result->setYear($class['anno']);
            $classes[] = $result;
        }
        return $classes;
    }

}
