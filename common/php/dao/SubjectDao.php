<?php

require_once __DIR__ . '/../QueryBuilder.php';
require_once __DIR__ . '/../QueryResult.php';
require_once __DIR__ . '/Dao.php';
require_once __DIR__ . '/../model/Subject.php';
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
class SubjectDao extends Dao {

    public function findById($id) {
        return $this->findSubjects(["id" => $id]);
    }
    
    public function findAll($id) {
        return $this->findSubjects(["id" => $id]);
    }

    public function findSubjects($filter = []) {
        $query = $this->buildSubjectsQuery($filter);
        $select_result = $this->find($query);
        if ($select_result->wasSuccessful()) {
            return new QueryResult(QueryResult::SUCCESS, "", $this->toSubjects($select_result));
        }
        return $select_result;
    }

    private function toSubjects($res) {
        $subjects = array();
        foreach ($res->getContent() as $subject) {
            $result = new Subject();
            $result->setId($subject['id']);
            $result->setName($subject['nome']);
            $result->setDescription($subject['descrizione']);
            $result->setColor($subject['color']);
            $subjects[] = $result;
        }
        return $subjects;
    }

    private function buildSubjectsQuery($filters) {
        $builder = new QueryBuilder();
        $builder->select("id, nome, descrizione, LPAD(HEX(color),6,0) as color")->from("materie m");
        if (isset($filters['id'])) {
            $builder->where("m.id = " . $filters['id']);
        }
        return $builder->getQuery();
    }

}
