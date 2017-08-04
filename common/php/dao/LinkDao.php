<?php

require_once __DIR__ . '/Dao.php';
require_once __DIR__ . '/ElementDao.php';
require_once __DIR__ . '/../model/Link.php';

/**
 * Description of MaterialDao
 *
 * @author root
 */
class LinkDao extends Dao {

    function fromRequest($request) {
        $element = new Link();
        foreach ($request->link as $property => $value) {
            $element->{"set" . ucfirst($property)}($value);
        }
        if ($element->getElement1() != null) {
            $dao = new ElementDao();
            $element->setElement1($dao->findById($element->getElement1())->uniqueContent());
        }
        if ($element->getElement2() != null) {
            $dao = new ElementDao();
            $element->setElement2($dao->findById($element->getElement2())->uniqueContent());
        }
        return $element;
    }

    public function insertLink($link) {
        $builder = new InsertBuilder();
        $builder->insert("collegamenti")
                ->attrs("id1", "id2");
        $builder->startValues();
        $builder->value($link->getElement1()->getId())
                ->value($link->getElement2()->getId());
        $builder->endValues();
        return $this->insert($builder->getQuery());
    }

    public function deleteLink($id) {
        $query = "DELETE FROM collegamenti WHERE id = $id";
        return $this->delete($query);
    }

    public function findById($id) {
        return $this->findLinks(["id" => $id]);
    }

    public function findLinks($filters) {
        $elementsDao = new ElementDao($this->getTransactionManager());
        $query = $this->buildLinksQuery($filters);
        $select_result = $this->find($query);
        if ($select_result->wasSuccessful()) {
            $links = array();
            foreach ($select_result->getContent() as $link_info) {
                $link = new Link();
                $link->setId($link_info['id']);
                $link->setElement1($elementsDao->findById($link_info['id1'])->uniqueContent());
                $link->setElement2($elementsDao->findById($link_info['id2'])->uniqueContent());
                $links[] = $link;
            }
            return new QueryResult(QueryResult::SUCCESS, "", $links);
        }

        return $select_result;
    }

    private function buildLinksQuery($filters) {
        $builder = new QueryBuilder();
        $builder->select("c.*")->from("collegamenti c");
        if (isset($filters['id'])) {
            $builder->where("c.id = " . $filters['id']);
        }
        if (isset($filters['element1']) && isset($filters['element2'])) {
            $builder->where(
                    "c.id1 = " . $filters['element1'] . " AND " . "c.id2 = " . $filters['element2'] . " OR "
                    . "c.id1 = " . $filters['element2'] . " AND " . "c.id2 = " . $filters['element1']);
        }
        return $builder->getQuery();
    }

}
