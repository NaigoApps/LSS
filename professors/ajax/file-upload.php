<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';

require_once __DIR__ . '/../../common/php/TransactionManager.php';

$ok = true;
$message = "";
if (isset($_FILES['document'])) {
    $tm = new TransactionManager();
    $dao = new MaterialDao($tm);
    if ($tm->beginTransaction()) {
        if (uploadFile() && createMaterial($dao)) {
            if ($dao->commit()) {
                exit_with_data("File caricato correttamente");
            } else {
                $dao->rollback();
                exit_with_error("Impossibile caricare il file");
            }
        } else {
            $dao->rollback();
            exit_with_error("Impossibile caricare il file");
        }
    } else {
        exit_with_error("Impossibile caricare il file");
    }
} else {
    exit_with_error("File non trovato");
}

function uploadFile() {
    $sourcePath = $_FILES['document']['tmp_name'];
    $targetPath = ROOT . "/files/";
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
}

function createMaterial($dao){
    $dao->insertMaterial();
}