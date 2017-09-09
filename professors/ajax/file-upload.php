<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';

require_once __DIR__ . '/../../common/php/TransactionManager.php';
require_once __DIR__ . '/../../common/php/dao/FileDao.php';
require_once __DIR__ . '/../../common/php/dao/ElementDao.php';
require_once __DIR__ . '/../../common/php/model/File.php';

$ok = true;
$message = "";

$element = $_POST['element'];

if (isset($_FILES['document'])) {
    if ($_FILES['document']['size'] > 5 * 1024 * 1024) {
        exit_with_error("Dimensione eccessiva");
    }
    $tm = new TransactionManager();
    $dao = new FileDao($tm);
    if ($tm->beginTransaction()) {
        if (uploadFile() && createFile($dao, $element)) {
            if ($tm->commit()) {
                exit_with_data("File caricato correttamente");
            } else {
                deleteFile();
                $tm->rollback();
                exit_with_error("Impossibile completare la transazione");
            }
        } else {
            $tm->rollback();
            exit_with_error("Impossibile caricare il file");
        }
    } else {
        exit_with_error("Impossibile iniziare la transazione");
    }
} else {
    exit_with_error("File non trovato");
}

function uploadFile() {
    $sourcePath = $_FILES['document']['tmp_name'];
    $targetPath = FILES_DIR . "/";
    $ok = true;
    if (!is_dir($targetPath)) {
        $ok = mkdir($targetPath, 0777, true);
    }
    if ($ok) {
        $targetPath = $targetPath . $_FILES['document']['name'];
        if (!is_file($targetPath)) {
            $ok = move_uploaded_file($sourcePath, $targetPath);    // Moving Uploaded file*/
        } else {
            return false;
        }
    } else {
        return false;
    }
    return $ok;
}

function deleteFile() {
    $targetPath = ROOT . "/files/" . ($_SESSION['user_data']->getEmail()) . "/";
    $targetPath = $targetPath . $_FILES['document']['name'];
    if (is_file($targetPath)) {
        unlink($targetPath);    // Moving Uploaded file*/
    }
}

function createFile($dao, $element) {
    $userDao = new UserDao();
    $elementDao = new ElementDao();
    $file = new File();
    $file->setElement(($element) ? $elementDao->findById($element)->uniqueContent() : null);
    $file->setUploader($userDao->findById($_SESSION['user_data']->getId())->uniqueContent());
    $file->setName($_FILES['document']['name']);
    return $dao->insertFile($file)->wasSuccessful();
}
