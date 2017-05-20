<?php

require_once __DIR__.'/../TransactionManager.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Dao
 *
 * @author root
 */
class Dao {

    private $transactionManager;
    
    public function __construct($tm = null) {
        if($tm == null){
            $tm = new TransactionManager();
        }
        $this->transactionManager = $tm;
    }

    public function find($query) {
        return $this->transactionManager->find($query);
    }

    public function delete($query){
        return $this->transactionManager->delete($query);
    }

    public function update($query){
        return $this->transactionManager->update($query);
    }

    public function insert($query){
        return $this->transactionManager->insert($query);
    }
    
    function getTransactionManager() {
        return $this->transactionManager;
    }


}
