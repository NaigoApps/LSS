<?php

require_once __DIR__ . '/Dao.php';
require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/ElementDao.php';
require_once __DIR__ . '/FileDao.php';
require_once __DIR__ . '/../model/Material.php';

/**
 * Description of MaterialDao
 *
 * @author root
 */
class MaterialDao extends Dao {

    function fromRequest($request) {
        $element = new Material();
        foreach ($request->material as $property => $value) {
            $element->{"set" . ucfirst($property)}($value);
        }
        if ($element->getElement() != null) {
            $dao = new ElementDao();
            $element->setElement($dao->findById($element->getElement())->uniqueContent());
        }
        if ($element->getFile() != null) {
            $dao = new FileDao();
            $element->setFile($dao->findById($element->getFile())->uniqueContent());
        }
        $element->setUploader($_SESSION['user_data']);
        return $element;
    }

    public function insertMaterial($material) {
        $builder = new InsertBuilder();
        $builder->insert("material")
                ->attrs("url", "uploader", "file", "name", "element", "private", "approved");
        $builder->startValues();
        $builder->value(($material->getUrl() != null) ? $material->getUrl() : null, true)
                ->value($material->getUploader()->getId())
                ->value(($material->getFile() != null) ? $material->getFile()->getId() : null)
                ->value($material->getName(), true)
                ->value($material->getElement()->getId())
                ->value(($material->getPrivate() != null) ? $material->getPrivate() : false)
                ->value(($material->getApproved() != null) ? $material->getApproved() : false);
        $builder->endValues();
        return $this->insert($builder->getQuery());
    }

    public function deleteMaterial($id) {
        $query = "DELETE FROM material WHERE id = $id";
        return $this->delete($query);
    }

    public function updateMaterial($material) {
        $query = "UPDATE material SET name = '" . $material->getName() . "', "
                . "approved = " . $material->getApproved() . ", "
                . "private = " . $material->getPrivate() . " WHERE id = " . $material->getId();
        return $this->update($query);
    }

    public function findById($id) {
        return $this->findMaterials(["id" => $id]);
    }

    public function findMaterials($filters) {
        $userDao = new UserDao($this->getTransactionManager());
        $elementsDao = new ElementDao($this->getTransactionManager());
        $filesDao = new FileDao($this->getTransactionManager());
        $query = $this->buildMaterialsQuery($filters);
        $select_result = $this->find($query);
        if ($select_result->wasSuccessful()) {
            $materials = array();
            foreach ($select_result->getContent() as $material_info) {
                $material = new Material();
                $material->setId($material_info['id']);
                $material->setName($material_info['name']);
                $material->setElement($elementsDao->findById($material_info['element'])->uniqueContent());
                $material->setUrl($material_info['url']);
                if ($material_info['file'] != null) {
                    $material->setFile($filesDao->findById($material_info['file'])->uniqueContent());
                }
                $material->setPrivate(($material_info['private']) ? true : false);
                $material->setApproved(($material_info['approved']) ? true : false);
                $material->setUploader($userDao->findById($material_info['uploader'])->uniqueContent());
                $materials[] = $material;
            }
            return new QueryResult(QueryResult::SUCCESS, "", $materials);
        }

        return $select_result;
    }

    private function buildMaterialsQuery($filters) {
        $builder = new QueryBuilder();
        $builder->select("m.*")->from("material m");
        if (isset($filters['id'])) {
            $builder->where("m.id = " . $filters['id']);
        }
        if (isset($filters['element'])) {
            $builder->where("m.element = " . $filters['element']);
        }
        if (isset($filters['private'])) {
            $builder->where("m.private = " . $filters['private']);
        }
        if (isset($filters['approved'])) {
            $builder->where("m.approved = " . $filters['approved']);
        }
        $builder->order("m.name DESC");
        return $builder->getQuery();
    }

}
