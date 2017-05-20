<?php

require_once __DIR__.'/Dao.php';
require_once __DIR__.'/SubjectDao.php';
require_once __DIR__.'/ClassDao.php';
require_once __DIR__.'/UserDao.php';

require_once __DIR__.'/../model/Schedule.php';

/**
 * Description of ScheduleDao
 *
 * @author root
 */
class ScheduleDao extends Dao {

    public function findById($id) {
        $subjectDao = new SubjectDao($this->getTransactionManager());
        $classDao = new ClassDao($this->getTransactionManager());
        $userDao = new UserDao($this->getTransactionManager());
        $query = $this->build_schedules_query($id, null, null);
        $select_result = $this->find($query);
        if ($select_result->wasSuccessful()) {
            $schedules = array();
            foreach ($select_result->getContent() as $schedule_info) {
                $schedule = new Schedule();
                $schedule->setId($schedule_info['id']);
                $schedule->setFiled($schedule_info['archiviata']);
                $schedule->setYear($schedule_info['anno']);
                $schedule->setSubject($subjectDao->findById($conn, $schedule_info['idmateria'])->getContent());
                $schedule->setClass($classDao->findById($conn, $schedule_info['idclasse'])->getContent());
                $schedule->setProfessor($userDao->findById($conn, $schedule_info['iddocente'])->getContent());
                $schedules[] = $schedule;
            }
            return new QueryResult(QueryResult::SUCCESS, "", $schedules);
        }

        return $select_result;
    }
    
    public function findAll($filed = null, $year = null, $subject = null, $class = null, $professor = null) {
        $subjectDao = new SubjectDao($this->getTransactionManager());
        $classDao = new ClassDao($this->getTransactionManager());
        $userDao = new UserDao($this->getTransactionManager());
        $query = $this->build_schedules_query(null, $filed, $year, $subject, $class, $professor);
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
                $schedules[] = $schedule;
            }
            return new QueryResult(QueryResult::SUCCESS, "", $schedules);
        }

        return $select_result;
    }

    private function build_schedules_query($id = null, $filed = null, $year = null, $subject = null, $class = null, $professor = null) {
        $builder = new QueryBuilder();
        $builder->select("t.*")->from("timeline t");
        if ($id != null) {
            $builder->where("t.id = $id");
        }
        if ($professor != null) {
            $builder->where("t.iddocente = $professor");
        }
        if ($filed != null) {
            $builder->where("t.archiviata = $filed");
        }
        $builder->order("t.anno DESC");
        //    $query = $query . "ORDER BY timeline.anno DESC, materie.nome ASC, classi.anno ASC, classi.sezione ASC";
        return $builder->getQuery();
    }

}
