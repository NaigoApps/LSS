<?php

require_once __DIR__ . '/../ajax-header.php';
require_once __DIR__ . '/../dao/MaterialDao.php';
require_once __DIR__ . '/../dao/ElementDao.php';

function findRelatedMaterial($id) {
    $eDao = new ElementDao();
    $mDao = new MaterialDao();
    $result = $mDao->findMaterials(["element" => $id])->getContent();
    $element = $eDao->findById($id)->uniqueContent();
    if ($element->getParent() != null) {
        $other = findRelatedMaterial($element->getParent()->getId());
        foreach($other as $m){
            $result[] = $m;
        }
    }
    return $result;
}

$element_id = null;
if (isset($request->element)) {
    $element_id = $request->element;
}

exit_with_data(findRelatedMaterial($element_id));
