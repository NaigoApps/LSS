<?php

function load_subject($conn, $id) {
    $builder = new QueryBuilder();
    $builder->select("m.*")->from("materie m");
    if ($id !== null) {
        $builder->where("m.id = $id");
    }
    $builder->order("m.nome");
    $select_result = db_select($conn, $builder->getQuery());
    if ($select_result->getOutcome() == QueryResult::SUCCESS) {
        if ($select_result->getCount() === 1) {
            return $select_result;
        } else if ($select_result->getCount() < 1) {
            return new QueryResult(QueryResult::FAILURE, "No subject with id", null);
        } else {
            return new QueryResult(QueryResult::FAILURE, "Multiple subjects with same id", null);
        }
    }
    
    return $select_result;
}
