<?php

require_once __DIR__ . '/../QueryBuilder.php';
require_once __DIR__ . '/../InsertBuilder.php';
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

    public function insertClass($data) {
        $query = $this->buildClassInsert($data);
        return $this->insert($query);
    }
    
    public function updateClass($data) {
        $query = $this->buildClassUpdate($data);
        return $this->update($query);
    }
    
    private function buildClassInsert($data){
        $builder = new InsertBuilder();
        return $builder
                ->insert("classi")
                    ->attrs("sezione", "anno")
                ->startValues()
                    ->value($data['section'], true)
                    ->value($data['year'])
                ->endValues()
                ->getQuery();
    }
    
    private function buildClassUpdate($data) {
        $builder = new UpdateBuilder();
        $builder->update("classi");
        if (isset($data['year'])) {
            $builder->set("anno", $data['year']);
        }
        if (isset($data['section'])) {
            $builder->set("sezione", $data['section'], true);
        }
        $builder->where("id = " . $data['id']);
        return $builder->getQuery();
    }

    public function removeClass($id) {
        $query = "DELETE FROM classi WHERE id = " . $id;
        return parent::delete($query);
    }

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
