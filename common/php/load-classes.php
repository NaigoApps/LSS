<?php

function load_class($conn, $id) {
    $builder = new QueryBuilder();
    $builder->select("c.*")->from("classi c");
    if ($id !== null) {
        $builder->where("c.id = $id");
    }
    $builder->order("c.anno", "c.sezione");
    $select_result = db_select($conn, $builder->getQuery());
    if ($select_result->getOutcome() == QueryResult::SUCCESS) {
        if ($select_result->getCount() === 1) {
            return $select_result;
        } else if ($select_result->getCount() < 1) {
            return new QueryResult(QueryResult::FAILURE, "No classes with id", null);
        } else {
            return new QueryResult(QueryResult::FAILURE, "Multiple classes with same id", null);
        }
    }
    
    return $select_result;
}
