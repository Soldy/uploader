<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class fileUpload {

    public $pieceSize = 512000;
    public $fileDir = "/tmp/upload/files";
    public $pieceDir = "/tmp/upload/pieces";
    public $dir = "/tmp/upload";
    public $fileTypesList = array("image/bmp", "image/g3fax", "image/gif", "image/jpeg", "image/png", "image/svg", "image/tiff", "image/x-icon", "image/x-pcx", "image/x-pict", "application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.openxmlformats-officedocument.wordprocessingml.template", "application/vnd.oasis.opendocument.text", "application/xhtml+xml", "application/xcap-diff+xml", "application/xml", "application/xml-dtd", "application/xslt+xml", "audio/adpcm", "audio/basic", "audio/midi", "audio/mp4", "audio/mpeg", "audio/ogg", "audio/webm", "audio/xwav", "video/3gpp", "video/3gpp2", "video/h261", "video/h263", "video/h264", "video/jpeg", "video/jpm", "video/mp4", "video/mpeg", "video/ogg", "video/quicktime", "video/webm", "video/x-f4v", "video/x-fli", "video/x-flv", "video/x-m4v", "video/x-ms-asf", "video/x-ms-wm", "video/x-ms-wmv", "video/x-ms-wmx", "video/x-ms-wvx", "video/x-msvideo", "video/x-sgi-movie", "video/x-matroska", "text/css", "text/cvs", "text/html", "text/plain", "text/richtext", "application/x-gzip", "application/tar+gzip", "application/tar", 'application/x-bzip2', 'application/tar+bzip2', 'application/zip', "application/x-7z-compressed", "application/x-rar-compressed");
    public $fileTypes = array(
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
        global $SUPERGLOBAL;
        $this->dir = $SUPERGLOBAL['config']['file']['www'];
        $this->pieceDir = $SUPERGLOBAL['config']['file']['tmp'];
        $this->fileDir = $SUPERGLOBAL['config']['file']['files'];
    }

    public function __destruct() {
        
    }

    /**
     * 
     * @param string $type
     * @return boolean
     */
    public function checkFileType($type) {
        if (in_array($type, $this->fileTypesList))
            return true;
        if (isset($this->fileTypes[$type])) {
            return true;
        }
        return false;
    }

    /**
     * 
     * @param string $filename
     * @return string
     */
    protected function security($filename) {
        $path_parts = pathinfo($filename);
        return $path_parts['basename'];
    }

    /**
     * 
     * @param string $fileId
     * @param string $type
     * @param string $pieces
     */
    public function write($fileId, $type, $pieces) {
        for ($piece = 0; $piece <= $pieces; $piece++){
            file_put_contents($this->fileDir . "/" . $this->security($fileId . ".store"), file_get_contents($this->pieceDir . "/" . $this->security($fileId . ".piece." . $piece . ".split")), FILE_APPEND);
        }            
    }

    /**
     * 
     * @param string $fileId
     * @param string $type
     */
    public function read($fileId, $type) {
//        header("Content-type: " . $type);
        echo readfile($this->fileDir . "/" . $this->security($fileId . ".store"));
    }

    /**
     * 
     * @param string $fileId
     * @param string $piece
     * @param string $data
     */
    public function writePiece($fileId, $piece, $data) {
        file_put_contents($this->pieceDir . "/" . $this->security($fileId . ".piece." . $piece . ".split"), base64_decode($data));
    }

}
