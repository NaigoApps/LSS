<?php

require_once __DIR__ . '/../model/File.php';
require_once __DIR__ . '/Dao.php';
require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/ElementDao.php';
require_once __DIR__ . '/../InsertBuilder.php';
require_once __DIR__ . '/../QueryBuilder.php';

/**
 * Description of MaterialDao
 *
 * @author root
 */
class FileDao extends Dao {

    function fromRequest($request) {
        $element = new File();
        foreach ($request as $property => $value) {
            $element->{"set" . $property}($value);
        }
        if ($element->getUploader() != null) {
            $dao = new UserDao();
            $element->setUploader($dao->findById($element->getUploader())->uniqueContent());
        }
        if ($element->getElement() != null) {
            $dao = new ElementDao();
            $element->setElement($dao->findById($element->getElement())->uniqueContent());
        }
        return $element;
    }

    public function insertFile($file) {
        $builder = new InsertBuilder();
        $builder->insert("file")
                ->attrs("uploader", "element", "name");
        $builder->startValues();
        $builder->value($file->getUploader()->getId())
                ->value(($file->getElement()) ? $file->getElement()->getId() : NULL)
                ->value($file->getName(), true);
        $builder->endValues();
        return $this->insert($builder->getQuery());
    }

    public function deleteFile($id) {
        $query = "DELETE FROM file WHERE id = $id";
        return $this->delete($query);
    }

    public function findById($id) {
        return $this->findFiles(["id" => $id]);
    }

    public function findByUser($id) {
        return $this->findFiles(["uploader" => $id]);
    }

    public function findFiles($filters) {
        $userDao = new UserDao($this->getTransactionManager());
        $elementDao = new ElementDao($this->getTransactionManager());
        $query = $this->buildFilesQuery($filters);
        $select_result = $this->find($query);
        if ($select_result->wasSuccessful()) {
            $files = array();
            foreach ($select_result->getContent() as $file_info) {
                $file = new File();
                $file->setId($file_info['id']);
                $file->setName($file_info['name']);
                $file->setUploader($userDao->findById($file_info['uploader'])->uniqueContent());
                $file->setElement($elementDao->findById($file_info['element'])->uniqueContent());
                $files[] = $file;
            }
            return new QueryResult(QueryResult::SUCCESS, "", $files);
        }

        return $select_result;
    }

    private function buildFilesQuery($filters) {
        $builder = new QueryBuilder();
        $builder->select("f.*")->from("file f");
        if (isset($filters['id'])) {
            $builder->where("f.id = " . $filters['id']);
        }
        if (isset($filters['uploader'])) {
            $builder->where("f.uploader = " . $filters['uploader']);
        }
        $builder->order("f.name");
        return $builder->getQuery();
    }

}
