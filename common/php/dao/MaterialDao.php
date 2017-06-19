<?php

require_once __DIR__ . '/Dao.php';
require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/ElementDao.php';

/**
 * Description of MaterialDao
 *
 * @author root
 */
class MaterialDao extends Dao {

    public function insertMaterial($data) {
        $query = $this->buildMaterialInsert($data);
        return $this->insert($query);
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
                $material->setFileName($material_info['file_name']);
                $material->setPrivate($material_info['private']);
                $material->setApproved($material_info['approved']);
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

    private function buildMaterialInsert($data) {
        $query = "INSERT INTO material(name, element, url, fileName, private, approved, uploader) VALUES ";
        $query = $query . "('" . $data['name'] . "'," . $data['element'] . ",'" . $data['url'] . ",'" . $data['fileName'] . "'," . $data['private'] . "'," . $data['approved'] . "," . $_SESSION['user_data']->getId() . ")";
        return $query;
    }

}
