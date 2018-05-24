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
    global $bye, $SUPERGLOBAL;
    $post = json_decode($json);
    $sql = new SQL();
    $fileupload = new fileUpload();
    if (isset($post->admin)) {
        if (!isset($post->signature)) {
            return $bye->error('missing signature');
        }
        if (hash('sha512', $post->admin . $SUPERGLOBAL['config']['rights']['key']) !== $post->sigature) {
            return $bye->error('incorrect signature');
        }
        $adminJson = json_decode(base64_encode($post->admin));
        if (!isset($adminJson->size)) {
            return $bye->error('size missing');
        }
        if ((!is_int($adminJson->size)) && ( $adminJson->size < 0) && ($adminJson->size > 99999999)) {
            return $bye->error('size is irreal');
        }
        if (!$fileupload->checkFileType($adminJson->type)) {
            return $bye->error('File type error!');
        }
        if (!isset($adminJson->name)) {
            return $bye->error('File name missing!');
        }
        $pieces = ceil($adminJson->size / $fileupload->pieceSize);
        if ($pieces * $fileupload->pieceSize < $adminJson->size)
            $pieces + 1;

        if ($fileId = $sql->createFile($post->size, $pieces, $post->type, $post->name)) {
            $bye->out['fileId'] = $fileId;
            $bye->out['pieces'] = $pieces;
            $bye->out['pieceSize'] = $fileupload->pieceSize;
        } else {
            return $bye->error("error");
        }
    } else {
        if (!isset($post->command)) {
            return $bye->error('missing command');
        }
        if ($SUPERGLOBAL['config']['rights']['auth'] === true) {
            
        }
        $bye->out['command'] = $post->command;
        if ($post->command == "request") {
            if ($SUPERGLOBAL['config']['rights']['selfstart'] === true) {
                return $bye->error('self request not enabled');
            }
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
            if (!($sql->checkFileProcess($post->id))) {
                return $bye->error('file not processing 1');
            }
            /*        if (!($sql->checkFilePieces($post->id, $post->piece))) {
              return $bye->error('file piece not processing 2');
              } */
            $fileupload->writePiece($post->id, $post->piece, $post->data);
            $sql->setFilePieceOk($post->id, $post->piece);
            $bye->out['fileId'] = $post->id;
            $bye->out['piece'] = $post->piece;
            $pieces = $sql->checkFileFinnished($post->id);
            if ($pieces != false) {
                $sql->setFileOk($post->id);
                error_log($pieces);
                $fileupload->write($post->id, $sql->getFileType($post->id), $pieces);
                $bye->out['command'] = "finnished";
            }else{
                $bye->out['command'] = "upload";                
            }
        } else {
            return $bye->error('error unknown command');
        }
    }
    $bye->bye();
}

if (count($_GET) > 0) {
    mainServe($_GET);
} else {
    mainUpload(file_get_contents("php://input"));
}







