<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * i do this with security reasons
 * 
 * 
 */

class SQL {

    public $sql;
    public $config;

    public function timeStamp() {
        return round(microtime(true));
    }

    public function insert() {
        #("INSERT INTO test(id) VALUES (1), (2), (3)")) 
    }

    public function update() {
        
    }

    public function select() {
        
    }

    /**
     * 
     * @param string $query
     * @return array
     */
    public function query($query) {
        ####dS####

        ####dE####
        $res = $this->sql->query($query);
        if ($res == false)
            return [""];
        while ($row = $res->fetch_array()) {
            $rows[] = $row;
        }
//        $res->close();
        if (isset($rows))
            return $rows;
        return [];
    }

    /**
     * 
     * @param string $query
     * @return boolean
     */
    public function justquery($query) {
        if ($res = $this->sql->multi_query($query))
            return true;
        error_log($this->sql->error);
        error_log($query);
        return false;
    }

    /**
     * 
     * @param string $string
     * @return string
     */
    public function security($string) {
        return htmlspecialchars($string, ENT_QUOTES);
    }

    protected function connect() {
        $this->sql = new mysqli('127.0.0.1', $this->config['user'], $this->config['password'], $this->config['db']);
        if ($this->sql->connect_errno) {
            die("Failed to connect to database!");
        }
    }

    /**
     * 
     * @param string $name
     * @return int
     */
    public function getSerial($name) { // inside serializer. Better if we do with care
        $name = $this->security($name);
        $result = $this->query(" SELECT `" . $this->config['db'] . "`.`getSerial`('".$name."') AS `getSerial`");
        $serial = $result[0]['getSerial']+1;
        return $serial;
    }

    /**
     * 
     * @param strong $size
     * @param int $pieces
     * @param string $type
     * @param string $name
     * @return string
     */
    public function createFile($size, $pieces, $type, $name) { // inside serializer. Better if we do with care
        $fileId = $this->getSerial("file");
        $piecesId = $this->getSerial("pieces");
        $this->justquery("INSERT INTO `" . $this->config['db'] . "`.`fileList` (`id`, `status`, `pieceStatus`, `creationTimestamp`, `size`, `type`, `filePieceNumber`, `filePieceNumberStatus`, `uploadFinishedStamp`, `access`, `fileName`) VALUES ('" . $fileId . "', 'new', '0', '" . $this->timeStamp() . "', '" . $size . "', '" . $type . "', '" . $pieces . "', 'new', '0', 'public', '" . $name . "')");
//                return false;        
        return $fileId;
    }

    /**
     * 
     * @param string $fileId
     * @return boolean
     */
    public function getFileType($fileId) { // inside serializer. Better if we do with care
        $result = $this->query("SELECT * FROM `" . $this->config['db'] . "`.`fileList` WHERE id = '" . $this->security($fileId) . "' ");
        if (isset($result[0]['type'])) {
            return $result[0]['type'];
        } else {
            return false;
        }
    }

    /**
     * 
     * @param string $fileId
     * @return boolean
     */
    public function getFilePieceStatus($fileId) { // inside serializer. Better if we do with care
        $result = $this->query("SELECT * FROM `" . $this->config['db'] . "`.`fileList` WHERE id = '" . $this->security($fileId) . "' ");
        if (isset($result[0]['pieceStatus'])) {
            error_log($result[0]['pieceStatus'] + 1);
            return $result[0]['pieceStatus'] + 1;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $fileId
     * @return boolean
     */
    public function getFilePieceNumber($fileId) { // inside serializer. Better if we do with care
        $result = $this->query("SELECT * FROM `" . $this->config['db'] . "`.`fileList` WHERE id = '" . $this->security($fileId) . "' ");
        if (isset($result[0]['filePieceNumber'])) {
            return $result[0]['filePieceNumber'];
        } else {
            return false;
        }
    }

    /**
     * 
     * @param string $fileId
     * @return boolean
     */
    public function checkFileProcess($fileId) { // inside serializer. Better if we do with care
        $result = $this->query("SELECT * FROM `" . $this->config['db'] . "`.`fileList` WHERE id = '" . $this->security($fileId) . "' ");
        if (isset($result[0]['status'])) {
            if (($result[0]['status'] == "process") || ($result[0]['status'] == "try")) {
                return true;
            } elseif ($result[0]['status'] == "new") {
                $this->justquery("UPDATE `" . $this->config['db'] . "`.`fileList` SET `status` = 'process' WHERE `id` = '" . $this->security($fileId) . "'");
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 
     * @param string $fileId
     * @return integer
     */
    public function checkFileFinnished($fileId) { // inside serializer. Better if we do with care
        $resultFile = $this->query("SELECT * FROM `" . $this->config['db'] . "`.`fileList` WHERE id = '" . $this->security($fileId) . "' ");
        if ($resultFile[0]['filePieceNumber'] >= $resultFile[0]['pieceStatus'])
            return false;
        return $resultFile[0]['pieceStatus'];
    }

    /**
     * 
     * @param string $fileId
     */
    public function setFileOk($fileId) { // inside serializer. Better if we do with care
        $this->justquery("UPDATE `" . $this->config['db'] . "`.`fileList` SET `status` = 'ok', `uploadFinishedStamp` = '" . $this->timeStamp() . "' WHERE `id` = '" . $this->security($fileId) . "'");
    }

    /**
     * 
     * @param string $fileId
     * @param string $piece
     */
    public function setFilePieceTry($fileId, $piece) { // inside serializer. Better if we do with care
        $this->justquery("UPDATE `" . $this->config['db'] . "`.`fileList` SET `status` = 'try' WHERE `id` = '" . $this->security($fileId) . "'");
    }

    /**
     * 
     * @param string $fileId
     * @param string $piece
     */
    public function setFilePieceOk($fileId) { // inside serializer. Better if we do with care
        $this->justquery("UPDATE `" . $this->config['db'] . "`.`fileList` SET `pieceStatus` = '" . ($this->getFilePieceStatus($fileId)) . "' WHERE `id` = '" . $this->security($fileId) . "'");
    }

    public function __construct() {
        global $SUPERGLOBAL;
        $this->config = $SUPERGLOBAL['config']['sql'];
        $this->connect();
    }

    public function __destruct() {
        
    }

}
