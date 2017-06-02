<?php

require_once __DIR__ . '/../model/Element.php';

require_once __DIR__ . '/../QueryBuilder.php';
require_once __DIR__ . '/Dao.php';
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
class ElementDao extends Dao {

    public function findById($id) {
        return $this->findElements(["id" => $id]);
    }

    public function findElements($filters) {
        $query = $this->buildElementQuery($filters);
        $select_result = $this->find($query);
        if ($select_result->wasSuccessful()) {
            return new QueryResult(QueryResult::SUCCESS, "", $this->toElements($select_result));
        }
        return $select_result;
    }

    private function buildElementQuery($filters) {
        $builder = new QueryBuilder();
        $builder->select("e.*")->from("element e");
        if (isset($filters['id'])) {
            $builder->where("e.id = " . $filters['id']);
        }
        if (isset($filters['parent'])) {
            $builder->where("e.parent = " . $filters['parent']);
        }
        if (isset($filters['type'])) {
            $builder->where("e.tipo = '" . $filters['type'] . "'");
        }
        if (isset($filters['like'])) {
            $builder->where("(e.nome LIKE '%" . $filters['like'] . "%' OR e.descrizione LIKE '%$like%')");
        }
        $builder->order("e.nome");
        return $builder->getQuery();
    }

    private function toElements($res) {
        $elements = array();
        foreach ($res->getContent() as $element) {
            $result = new Element();
            $result->setId($element['id']);
            $result->setName($element['nome']);
            $result->setDescription($element['descrizione']);
            $result->setType($element['tipo']);
            if (!is_null($element['parent'])) {
                $result->setParent($this->findById($element['parent'])->uniqueContent());
            }
            $elements[] = $result;
        }
        return $elements;
    }

}
