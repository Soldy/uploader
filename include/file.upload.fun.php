<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class fileUpload {
    protected static $pieceSize = 2048;
    protected static $fileDir = "/tmp/upload/file";
    protected static $pieceDir = "/tmp/upload/pieces";
    protected static $dir = "/tmp/upload";
    protected static $fileTypes = array(
        "bmp" => "image/bmp",
        "g3" => "image/g3fax",
        "gif" => "image/gif",
        "jpg" => "image/jpeg",
        "png" => "image/png",
        "svg" => "image/svg",
        "tiff" => "image/tiff",
        "ico" => "image/x-icon",
        "pcx" => "image/x-pcx",
        "pic" => "image/x-pict",
        "pdf" => "application/pdf",
        "doc" => "application/msword",
        "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "dotx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.template",
        "odt" => "application/vnd.oasis.opendocument.text",
        "xhtml" => "application/xhtml+xml",
        "xpdf" => "application/xcap-diff+xml",
        "xml" => "application/xml",
        "dtd" => "application/xml-dtd",
        "xslt" => "application/xslt+xml",
        "adp" => "audio/adpcm",
        "au" => "audio/basic",
        "mid" => "audio/midi",
        "mp4a" => "audio/mp4",
        "mp3" => "audio/mpeg",
        "oga" => "audio/ogg",
        "weba" => "audio/webm",
        "wav" => "audio/xwav",
        "3gp" => "video/3gpp",
        "3g2" => "video/3gpp2",
        "h261" => "video/h261",
        "h263" => "video/h263",
        "h264" => "video/h264",
        "jpgv" => "video/jpeg",
        "jpm" => "video/jpm",
        "mp4" => "video/mp4",
        "mpeg" => "video/mpeg",
        "ogv" => "video/ogg",
        "qt" => "video/quicktime",
        "webm" => "video/webm",
        "f4v" => "video/x-f4v",
        "fli" => "video/x-fli",
        "flv" => "video/x-flv",
        "m4v" => "video/x-m4v",
        "asf" => "video/x-ms-asf",
        "wm" => "video/x-ms-wm",
        "wmv" => "video/x-ms-wmv",
        "wmx" => "video/x-ms-wmx",
        "wvx" => "video/x-ms-wvx",
        "avi" => "video/x-msvideo",
        "movie" => "video/x-sgi-movie",
        "mkv" => "video/x-matroska",
        "css" => "text/css",
        "cvs" => "text/cvs",
        "html" => "text/html",
        "txt" => "text/plain",
        "rtx" => "text/richtext",
        "gz" => "application/x-gzip",
        "tgz" => "application/tar+gzip",
        "tar" => "application/tar",
        "bz2" => 'application/x-bzip2',
        "tbz2" => 'application/tar+bzip2',
        "zip" => 'application/zip',
        "7z" => "application/x-7z-compressed",
        "rar" => "application/x-rar-compressed"
    );

    public function __construct() {
        
    }

    public function __destruct() {
        
    }

    public function checkFileType($type){
        if (isset($this->fileTypes[$type])) {
            return true;
        }
        return false;
    }

        protected function security($filename) {
        $path_parts = pathinfo($filename);
        return $path_parts['basename'];
    }

    protected function write($fileId, $type, $pieces) {
        for($i = 0;$i<$pieces; $i++) 
                file_put_contents($this->fileDir . $this->security($fileId . "." . $type), readfile($this->fileDir . $this->security($fileId . ".piece." . $piece . ".split")), FILE_APPEND);
    }


    protected function read($fileId, $type) {
        header("Content-type: ". $this->fileTypes[$type]);
        echo readfile($this->fileDir . $this->security($fileId . "." . $type));
    }

    protected function writePiece($fileId, $piece, $data) {
        file_put_contents($this->pieceDir . $this->security($fileId . ".piece." . $piece . ".split"), base64_decode($data));
    }

}
