<?php

$SUPERGLOBAL = [];
$SUPERGLOBAL['config'] = [];



require_once 'bye.php';
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

$bye = new outBye();

function mainServe() {
    global $bye;
    if (isset($_GET['f'])) {
        $fileId = $_GET['f'];
        $sql = new SQL();
        $fileupload = new fileUpload();
        if ($fileType = $sql->getFileType($fileId)) {
            $fileupload->read($fileId, $fileType);
        } else {
            return $bye->error("error");
        }
    } else {
        return $bye->error("error");
    }
}

function mainUpload($json) {
    global $bye;
    $post = json_decode($json);
    $sql = new SQL();
    $fileupload = new fileUpload();
    if (!isset($post['c'])) {
        return $bye->error('missing command');
    }
    $bye->out['command'] = $post['c'];
    if ($post['c'] == "r") {
        if (isset($post['s'])) {
            return $bye->error('size missing');
        }
        if ((!is_int($post['s'])) && ($post['s'] < 0) && ($post['s'] > 99999999)) {
            return $bye->error('size is irreal');
        }
        if (!isset($fileupload->fileTypes[$post['t']])) {
            return $bye->error('File type error!');
        }
        $pieces = floor($post['s'] / $fileupload->pieceSize);
        if ($pieces * $fileupload->pieceSize != $post['s'])
            $pieces + 1;

        if ($fileId = $sql->createFile($post['s'], $pieces, $post['t'])) {
            $bye->out['fileId'] = $fileId;
            $bye->out['pieces'] = $pieces;
            $bye->out['pieceSize'] = $fileupload->pieceSize;
        } else {
            return $bye->error("error");
        }
    } elseif ($post['c'] == "u") {
        if (isset($post['i'])) {
            return $bye->error('fileId missing');
        }
        if (isset($post['p'])) {
            return $bye->error('piece id missing');
        }
        if (isset($post['d'])) {
            return $bye->error('data missing');
        }
        if (!($sql->checkFileProcess($post['i']))) {
            return $bye->error('file not processing');
        }
        if (!($sql->csetFilePiecesTry($post['i'], $post['p']))) {
            return $bye->error('file piece not processing');
        }
        $fileupload->writePiece($post['i'], $post['p'], $post['d']);
        $sql->setFilePieceOk($post['i'], $post['p']);
        $bye->out['fileId'] = $fileId;
        $bye->out['pieces'] = $pieces;
        if ($pieces = $sql->checkFileFinnished($post['i'])) {
            $sql->setFileOk($post['i']);
            $fileupload->write($post['i'], $sql->getFileType($post['i']), $pieces);
        }
    } else {
        return $bye->error('error unknown command');
    }
}

if (count($_GET) > 0) {
    mainServe($_GET);
} else {
    mainUpload(file_get_contents("php://input"));
}
