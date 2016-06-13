<?php

$SUPERGLOBAL = [];
$SUPERGLOBAL['config'] = [];




require_once 'config.php';
require_once 'sql.fun.php';
require_once 'sql.php';
require_once 'post.php';
require_once 'json.php';
require_once 'admin.php'; # administration connect function
require_once 'file.server.php'; # file server connection function
require_once 'file.upload.fun.php'; # file upload connection function
require_once 'file.upload.php'; # file upload connection function
require_once 'cron.php'; # cron server function

function mainServe($_GET) {
    if (isset($_GET['f'])) {
        $fileId = $_GET['f'];
        $sql = new SQL();
        $fileupload = new fileUpload();
        if ($fileType = $sql->getFileType($fileId)) {
            $fileupload->read($fileId, $fileType);
        } else {
            echo("error");
        }
    } else {
        echo ('error');
    }
}

function mainUpload($json) {
    $post = json_decode($json);
    $sql = new SQL();
    $fileupload = new fileUpload();
    if (!isset($post['c'])) {
        echo ("missing command");
        return false;
    }
    if ($post['c'] == "r") {
        if (isset($post['s'])) {
            echo('size missing');
            return false;
        }
        if ((!is_int($post['s'])) && ($post['s'] < 0) && ($post['s'] > 99999999)) {
            echo('size is irreal');
            return false;
        }
        if (!isset($fileupload->fileTypes($post['t']))) {
            echo('File type error!');
            return false;
        }
        $pieces = floor($post['s'] / $fileupload->pieceSize);
        if ($pieces * $fileupload->pieceSize != $post['s'])
            $pieces + 1;

        if ($fileId = $sql->createFile($post['s'], $pieces, $post['t'])) {
            echo ('{"c":"r","t":"r","i":"' . $fileId . '","p":"' . $pieces . '","s":'. $fileupload->pieceSize.'}');
        } else {
            echo("error");
        }
    } elseif ($post['c'] == "u") {
        if (isset($post['i'])) {
            echo('fileId missing');
            return false;
        }
        if (isset($post['p'])) {
            echo('piece id missing');
            return false;
        }
        if (isset($post['d'])) {
            echo('data missing');
            return false;
        }        
        if (!($sql->checkFileProcess($post['i']))) {
            echo('file not processing');
            return false;            
        }
        if (!($sql->csetFilePiecesTry($post['i'], $post['p']))){
            echo('file piece not processing');
            return false;              
        }
        $fileupload->writePiece($post['i'], $post['p'],$post['d']);
        $sql->setFilePieceOk($post['i'], $post['p']);
        echo('{"c":"u","t":"r","i":"' . $post['i'] . '","p":"' . $post['p'] . '","s":"ok"}');
        if($pieces = $sql->checkFileFinnished($post['i'])){
            $sql-setFileOk($post['i']);
            $fileupload->write($post['i'],$sql->getFileType($post['i']), $pieces);
        }
        
    } else {
        echo('error unknown command');
    }
}

if (count($_GET) > 0) {
    mainServe($_GET);
} else {
    mainUpload(file_get_contents("php://input"));
}




    