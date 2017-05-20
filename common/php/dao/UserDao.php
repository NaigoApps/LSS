<?php

require_once __DIR__.'/../model/User.php';

require_once __DIR__.'/../QueryBuilder.php';
require_once __DIR__.'/Dao.php';
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

    private function toUsers($res) {
        $users = array();
        foreach ($res->getContent() as $user) {
            $result = new User();
            $result->setId($user['id']);
            $result->setEmail($user['email']);
            $result->setType($user['type']);
            $users[] = $result;
        }
        return $users;
    }

}
