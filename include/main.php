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
    error_log($json);
    $post = json_decode($json);
    $sql = new SQL();
    $fileupload = new fileUpload();
    if (!isset($post->command)) {
        return $bye->error('missing command');
    }
    $bye->out['command'] = $post->command;
    if ($post->command == "request") {
        if (!isset($post->size)) {
            return $bye->error('size missing');
        }
        if ((!is_int($post->size)) && ($post->size < 0) && ($post->size > 99999999)) {
            return $bye->error('size is irreal');
        }
        if (!$fileupload->checkFileType($post->type)) {
            return $bye->error('File type error!');
        }
        if (!isset($post->name)) {
            return $bye->error('File name missing!');
        }
        $pieces = ceil($post->size / $fileupload->pieceSize);
        if ($pieces * $fileupload->pieceSize < $post->size)
            $pieces + 1;

        if ($fileId = $sql->createFile($post->size, $pieces, $post->type, $post->name)) {
            $bye->out['fileId'] = $fileId;
            $bye->out['pieces'] = $pieces;
            $bye->out['pieceSize'] = $fileupload->pieceSize;
        } else {
            return $bye->error("error");
        }
    } elseif ($post->command == "upload") {
        if (!isset($post->id)) {
            return $bye->error('fileId missing');
        }
        if (!isset($post->piece)) {
            return $bye->error('piece missing');
        }
        if (!isset($post->data)) {
            return $bye->error('data missing');
        }
        error_log("-2");
        if (!($sql->checkFileProcess($post->id))) {
            return $bye->error('file not processing');
        }
        error_log("-1");
        if (!($sql->checkFilePieces($post->id, $post->piece))) {
            return $bye->error('file piece not processing');
        }
        error_log("0");
        $fileupload->writePiece($post->id, $post->piece, $post->data);
        error_log("1");
        $sql->setFilePieceOk($post->id, $post->piece);
        error_log("2");
        $bye->out['fileId'] = $post->id;
        $bye->out['piece'] = $post->piece;
        $pieces = $sql->checkFileFinnished($post->id);
        error_log($pieces);
        if ($pieces != false) {
                    error_log("2.5");
            $sql->setFileOk($post->id);
            $fileupload->write($post->id, $sql->getFileType($post->id), $pieces);
            $bye->out['command'] = "finnished";
        }
    } else {
        return $bye->error('error unknown command');
    }
    $bye->bye();
}

if (count($_GET) > 0) {
    mainServe($_GET);
} else {
    mainUpload(file_get_contents("php://input"));
}
