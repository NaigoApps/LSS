<?php

require_once __DIR__ . '/Dao.php';
require_once __DIR__ . '/SubjectDao.php';
require_once __DIR__ . '/ClassDao.php';
require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/ScheduleElementDao.php';

require_once __DIR__ . '/../model/Schedule.php';

/**
 * Description of ScheduleDao
 *
 * @author root
 */
class ScheduleDao extends Dao {

    public function insertSchedule($data) {
        $query = $this->buildSchedulesInsert($data);
        return $this->insert($query);
    }

    public function deleteSchedule($id) {
        $query = "DELETE FROM timeline WHERE id = $id";
        return $this->delete($query);
    }

    public function updateSchedule($schedule) {
        $query = "UPDATE timeline SET archiviata = " . $schedule->getFiled() . " WHERE id = " . $schedule->getId();
        return $this->update($query);
    }

    public function findById($id, $lazy = false) {
        return $this->findSchedules(["id" => $id], $lazy);
    }

    public function findSchedules($filters, $lazy = false) {
        $subjectDao = new SubjectDao($this->getTransactionManager());
        $classDao = new ClassDao($this->getTransactionManager());
        $userDao = new UserDao($this->getTransactionManager());
        $elementsDao = new ScheduleElementDao($this->getTransactionManager());
        $query = $this->buildSchedulesQuery($filters);
        $select_result = $this->find($query);
        if ($select_result->wasSuccessful()) {
            $schedules = array();
            foreach ($select_result->getContent() as $schedule_info) {
                $schedule = new Schedule();
                $schedule->setId($schedule_info['id']);
                $schedule->setFiled($schedule_info['archiviata']);
                $schedule->setYear($schedule_info['anno']);
                $schedule->setSubject($subjectDao->findById($schedule_info['idmateria'])->uniqueContent());
                $schedule->setClass($classDao->findById($schedule_info['idclasse'])->uniqueContent());
                $schedule->setProfessor($userDao->findById($schedule_info['iddocente'])->uniqueContent());
                if ($lazy) {
                    $schedule->setElements([]);
                } else {
                    $schedule->setElements($elementsDao->findBySchedule($schedule_info['id'])->getContent());
                }
                $schedules[] = $schedule;
            }
            return new QueryResult(QueryResult::SUCCESS, "", $schedules);
        }

        return $select_result;
    }

    private function buildSchedulesQuery($filters) {
        $builder = new QueryBuilder();
        $builder->select("t.*")->from("timeline t");
        if (isset($filters['id'])) {
            $builder->where("t.id = " . $filters['id']);
        }
        if (isset($filters['user'])) {
            $builder->where("t.iddocente = " . $filters['user']);
        }
        if (isset($filters['filed'])) {
            $builder->where("t.archiviata = " . $filters['filed']);
        }
        $builder->order("t.anno DESC");
        //    $query = $query . "ORDER BY timeline.anno DESC, materie.nome ASC, classi.anno ASC, classi.sezione ASC";
        return $builder->getQuery();
    }

    private function buildSchedulesInsert($data) {
        $query = "INSERT INTO timeline(idmateria, idclasse, anno, iddocente, archiviata) VALUES ";
        $query = $query . "(" . $data['subject'] . "," . $data['class'] . "," . $data['year'] . "," . $_SESSION['user_data']->getId() . ",false)";
        return $query;
    }

}
