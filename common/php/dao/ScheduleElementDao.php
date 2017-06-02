<?php

require_once __DIR__ . '/../QueryBuilder.php';
require_once __DIR__ . '/../QueryResult.php';
require_once __DIR__ . '/Dao.php';
require_once __DIR__ . '/ElementDao.php';
require_once __DIR__ . '/ScheduleDao.php';
require_once __DIR__ . '/../model/ScheduleElement.php';
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
class ScheduleElementDao extends Dao {

    public function findBySchedule($schedule) {
        return $this->findElements(["schedule" => $schedule]);
    }

    public function findRelated($element_id, $schedule_id) {
        $schedule = (new ScheduleDao())->findById($schedule_id, true)->uniqueContent();
        return $this->findElements(["element" => $element_id], $schedule);
    }

    public function findElements($filter = [], $related_to = null) {
        if ($related_to != null) {
            $query = $this->buildRelatedElementsQuery($filter, $related_to);
        } else {
            $query = $this->buildElementsQuery($filter);
        }
        $select_result = $this->find($query);
        if ($select_result->wasSuccessful()) {
            if ($related_to != null) {
                return new QueryResult(QueryResult::SUCCESS, "", $this->toElements($select_result, $related_to));
            } else {
                return new QueryResult(QueryResult::SUCCESS, "", $this->toElements($select_result));
            }
        }
        return $select_result;
    }

    private function toElements($res, $schedule = null) {
        $elementsDao = new ElementDao($this->getTransactionManager());
        $elements = array();
        foreach ($res->getContent() as $element) {
            $result = new ScheduleElement();
            $result->setId($element['id']);
            $result->setElement($elementsDao->findById($element['idelemento'])->uniqueContent());
            $result->setSchedule($element['idtimeline']);
            $result->setDate($element['data']);
            $result->setStatus($element['status']);
            if ($schedule != null) {
                $result->setFullStatus($this->computeFullStatus($element['idelemento'], $schedule));
            }
            $elements[] = $result;
        }
        return $elements;
    }

    public function computeFullStatus($element_id, $schedule_id) {
        $schedule = (new ScheduleDao())->findById($schedule_id, true)->uniqueContent();
        $query = "SELECT te.status as status, ti.idmateria as subject, UNIX_TIMESTAMP(te.data) as date FROM timeline_element te, timeline ti WHERE "
                . "te.idtimeline=ti.id AND "
                . "ti.idclasse = " . $schedule->getClass()->getId() . " AND "
                . "te.idelemento = " . $element_id . " AND "
                . "ti.anno = " . $schedule->getYear();
        $raw_status = (new Dao())->find($query);
        if ($raw_status->wasSuccessful()) {
            return $raw_status->getContent();
        } else {
            return $raw_status->getMessage();
        }
    }

    private function buildElementsQuery($filters) {
        $builder = new QueryBuilder();
        $builder->select("id, idelemento, UNIX_TIMESTAMP(data) as data, idtimeline, status")->from("timeline_element te");
        if (isset($filters['id'])) {
            $builder->where("te.id = " . $filters['id']);
        }
        if (isset($filters['element'])) {
            $builder->where("te.idelemento = " . $filters['element']);
        }
        if (isset($filters['status'])) {
            $builder->where("te.status = " . $filters['status']);
        }
        if (isset($filters['schedule'])) {
            $builder->where("te.idtimeline = " . $filters['schedule']);
        }
        return $builder->getQuery();
    }

    private function buildRelatedElementsQuery($filters, $schedule) {
        $builder = new QueryBuilder();
        $builder->select("te.id, te.idelemento, UNIX_TIMESTAMP(te.data) as data, te.idtimeline, te.status")->from("timeline_element te, timeline ti");
        $builder->where("te.idtimeline = ti.id")
                ->where("ti.anno = " . $schedule->getYear())
                ->where("ti.idclasse = " . $schedule->getClass()->getId());
        if (isset($filters['id'])) {
            $builder->where("te.id = " . $filters['id']);
        }
        if (isset($filters['element'])) {
            $builder->where("te.idelemento = " . $filters['element']);
        }
        if (isset($filters['status'])) {
            $builder->where("te.status = " . $filters['status']);
        }
        if (isset($filters['schedule'])) {
            $builder->where("te.idtimeline = " . $filters['schedule']);
        }
        return $builder->getQuery();
    }

    public function deleteBySchedule($schedule) {
        $delete_query = "DELETE FROM timeline_element WHERE idtimeline=$schedule";
        return $this->delete($delete_query);
    }

    public function insertElements($elements) {
        $update_query = "INSERT INTO timeline_element(idtimeline, idelemento, data, status) VALUES ";
        $at_least_one = false;
        $inserts = [];
        foreach ($elements as $element) {
            $inserts[] = "($element->schedule, " . $element->element->id . ", FROM_UNIXTIME('" . strtotime($element->date) . "'), '" . $element->status . "')";
            $at_least_one = true;
        }
        if ($at_least_one) {
            $update_query = $update_query . join(",", $inserts);
            return $this->multiInsert($update_query);
        }else{
            return new QueryResult(QueryResult::SUCCESS, null, null);
        }
    }

}
