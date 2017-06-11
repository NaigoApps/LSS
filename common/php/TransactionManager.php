<?php

require_once __DIR__ . '/consts.php';
require_once __DIR__ . '/QueryResult.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TransactionManager
 *
 * @author root
 */
class TransactionManager {

    const NONE = 0;
    const OPEN = 0;

    private $mysqli;
    private $transaction;

    public function __construct() {
        $this->mysqli = null;
    }

    private function connect() {
        $this->mysqli = new mysqli(HOST, USER, PASS, DB);
        if (!$this->mysqli->connect_errno) {
            return true;
        } else {
            throw new Exception($this->mysqli->connect_error);
        }
    }

    public function beginTransaction() {
        if ($this->transaction == TransactionManager::NONE) {
            if ($this->mysqli == null) {
                $this->connect();
            }
            if ($this->mysqli->autocommit(false)) {
                $this->transaction = TransactionManager::OPEN;
                return true;
            } else {
                throw new Exception("Cannot begin transaction");
            }
        } else {
            throw new Exception("Transaction is already alive");
        }
    }

    public function commit() {
        if ($this->transaction == TransactionManager::OPEN) {
            if ($this->mysqli->commit() && $this->mysqli->autocommit(true)) {
                $this->transaction = TransactionManager::NONE;
                $this->disconnect();
                return true;
            } else {
                throw new Exception("Cannot close transaction");
            }
        } else {
            throw new Exception("There is no transaction to commit");
        }
    }

    public function rollback() {
        if ($this->transaction == TransactionManager::OPEN) {
            if ($this->mysqli->rollback() && $this->mysqli->autocommit(false)) {
                $this->transaction = TransactionManager::NONE;
                $this->disconnect();
                return true;
            } else {
                throw new Exception("Cannot close transaction");
            }
        } else {
            throw new Exception("There is no transaction to rollback");
        }
    }

    public function disconnect() {
        $this->mysqli->close();
        $this->mysqli = null;
    }

    public function find($query) {
        $alone = false;
        if (!$this->mysqli) {
            $alone = true;
            $this->connect();
        }
        $result = $this->mysqli->query($query);
        if ($result !== false) {
            $rows = array();
            while ($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }
            return new QueryResult(QueryResult::SUCCESS, NULL, $rows);
        } else {
            return new QueryResult(QueryResult::FAILURE, $this->mysqli->error . " Your query was: " . $query, NULL);
        }
        if ($alone) {
            $this->disconnect();
        }
    }

    function insert($query) {
        $alone = false;
        if (!$this->mysqli) {
            $alone = true;
            $this->connect();
        }
        $result = $this->mysqli->real_query($query);
        if ($result !== false) {
            $id = $this->mysqli->insert_id;
            if ($id !== 0) {
                return new QueryResult(QueryResult::SUCCESS, NULL, [$id]);
            } else {
                return new QueryResult(QueryResult::FAILURE, $this->mysqli->error, NULL);
            }
        } else {
            return new QueryResult(QueryResult::FAILURE, $this->mysqli->error . " Your query was: " . $query, NULL);
        }
        if ($alone) {
            $this->disconnect();
        }
    }

    function multiInsert($query) {
        $result = $this->mysqli->real_query($query);
        if ($result !== false) {
            return new QueryResult(QueryResult::SUCCESS, NULL, NULL);
        } else {
            return new QueryResult(QueryResult::FAILURE, $this->mysqli->error . " Your query was: " . $query, NULL);
        }
    }

    function update($query) {
        $alone = false;
        if (!$this->mysqli) {
            $alone = true;
            $this->connect();
        }
        $result = $this->mysqli->real_query($query);
        if ($result !== false) {
            return new QueryResult(QueryResult::SUCCESS, NULL, NULL);
        } else {
            return new QueryResult(QueryResult::FAILURE, $this->mysqli->error . " Your query was: " . $query, NULL);
        }
        if ($alone) {
            $this->disconnect();
        }
    }

    function delete($query) {
        $alone = false;
        if (!$this->mysqli) {
            $alone = true;
            $this->connect();
        }
        $result = $this->mysqli->real_query($query);
        if ($result !== false) {
            return new QueryResult(QueryResult::SUCCESS, NULL, NULL);
        } else {
            return new QueryResult(QueryResult::FAILURE, $this->mysqli->error . " Your query was: " . $query, NULL);
        }
        if ($alone) {
            $this->disconnect();
        }
    }

}
