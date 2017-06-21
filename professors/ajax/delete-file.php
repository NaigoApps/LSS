<?php

require_once __DIR__ . '/../../common/php/ajax-header.php';

require_once __DIR__ . '/../../common/php/TransactionManager.php';
require_once __DIR__ . '/../../common/php/dao/FileDao.php';
require_once __DIR__ . '/../../common/php/model/File.php';

$ok = true;
$message = "";
if (isset($request->file)) {
    $tm = new TransactionManager();
    $dao = new FileDao($tm);
    if ($tm->beginTransaction()) {
        if (deleteDatabaseFile($dao, $request->file) && deleteFilesystemFile($request->file)) {
            if ($tm->commit()) {
                exit_with_data("File eliminato correttamente");
            } else {
                $tm->rollback();
                exit_with_error("Impossibile completare la transazione");
            }
        } else {
            $tm->rollback();
            exit_with_error("Impossibile eliminare il file");
        }
    } else {
        exit_with_error("Impossibile iniziare la transazione");
    }
} else {
    exit_with_error("File non trovato");
}

function deleteFilesystemFile($file) {
    $targetPath = FILES_DIR . "/";
    $targetPath = $targetPath . $file->name;
    if (is_file($targetPath)) {
        unlink($targetPath);
        return true;
    }
    return false;
}

function deleteDatabaseFile($dao, $file) {
    return $dao->deleteFile($file->id)->wasSuccessful();
}
