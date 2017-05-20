<?php

function load_user($conn, $id) {
    $builder = new QueryBuilder();
    $builder->select("u.*")->from("utenti u");
    if ($id !== null) {
        $builder->where("u.id = $id");
    }
    $builder->order("u.nome");
    $select_result = db_select($conn, $builder->getQuery());
    if ($select_result->getOutcome() == QueryResult::SUCCESS) {
        if ($select_result->getCount() === 1) {
            return $select_result;
        } else if ($select_result->getCount() < 1) {
            return new QueryResult(QueryResult::FAILURE, "No user with id", null);
        } else {
            return new QueryResult(QueryResult::FAILURE, "Multiple users with same id", null);
        }
    }
    return $select_result;
}
