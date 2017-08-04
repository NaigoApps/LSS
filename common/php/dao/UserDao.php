<?php

require_once __DIR__ . '/../model/User.php';

require_once __DIR__ . '/../QueryBuilder.php';
require_once __DIR__ . '/../UpdateBuilder.php';
require_once __DIR__ . '/Dao.php';
require_once __DIR__ . '/ClassDao.php';
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
class UserDao extends Dao {

    public function insertUser($data) {
        $query = $this->buildUserInsert($data);
        return $this->insert($query);
    }
    
    public function removeUser($id) {
        $query = "DELETE FROM utenti WHERE id = ".$id;
        return $this->delete($query);
    }
    
    

    public function updateUser($data) {
        $query = $this->buildUserUpdate($data);
        return $this->update($query);
    }

    private function buildUserInsert($data) {
        $query = "INSERT INTO utenti(name, surname, type, email, classroom) VALUES ";
        $query = $query . "('" . $data['name'] . "','" . $data['surname'] . "'," . User::UNKNOWN . ",'" . $data['email'] . "'," . $data['classroom'] . ")";
        return $query;
    }

    private function buildUserUpdate($data) {
        $builder = new UpdateBuilder();
        $builder->update("utenti");
        if (isset($data['name'])) {
            $builder->set("name", $data['name'], true);
        }
        if (isset($data['surname'])) {
            $builder->set("surname", $data['surname'], true);
        }
        if (isset($data['email'])) {
            $builder->set("email", $data['email'], true);
        }
        if (isset($data['type'])) {
            $builder->set("type", $data['type']);
        }
        if (isset($data['classroom'])) {
            $builder->set("classroom", $data['classroom']);
        }
        $builder->where("id = " . $data['id']);
        return $builder->getQuery();
    }

    public function findById($id) {
        $builder = new QueryBuilder();
        $builder->select("*")->from("utenti u")->where("u.id = $id");
        $select_result = $this->find($builder->getQuery());
        if ($select_result->wasSuccessful()) {
            return new QueryResult(QueryResult::SUCCESS, "", $this->toUsers($select_result));
        }
        return $select_result;
    }

    public function findByEmail($email) {
        $builder = new QueryBuilder();
        $builder->select("*")->from("utenti u")->where("u.email = '$email'");
        $select_result = $this->find($builder->getQuery());
        if ($select_result->wasSuccessful()) {
            return new QueryResult(QueryResult::SUCCESS, "", $this->toUsers($select_result));
        }
        return $select_result;
    }

    public function findUsers() {
        $builder = new QueryBuilder();
        $builder->select("*")->from("utenti u");
        $select_result = $this->find($builder->getQuery());

        if ($select_result->wasSuccessful()) {
            return new QueryResult(QueryResult::SUCCESS, "", $this->toUsers($select_result));
        }

        return $select_result;
    }

    private function toUsers($res) {
        $users = array();
        $classDao = new ClassDao($this->getTransactionManager());
        foreach ($res->getContent() as $user) {
            $result = new User();
            $result->setId($user['id']);
            $result->setEmail($user['email']);
            $result->setType($user['type']);
            $result->setName($user['name']);
            $result->setSurname($user['surname']);
            $result->setClassroom(($user['classroom'] != NULL) ? $classDao->findById($user['classroom'])->uniqueContent() : NULL);
            $users[] = $result;
        }
        return $users;
    }

}
