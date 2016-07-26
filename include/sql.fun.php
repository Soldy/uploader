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
    public $sql ;
    public $config ;
    
    public function timeStamp(){
        return round(microtime(true));
    }
    public function insert(){
        #("INSERT INTO test(id) VALUES (1), (2), (3)")) 
    } 

    public function update(){
        
    } 
    
    public function select(){
    }
    
    public function query ($query) {
        $res = $this->sql->query($query);
        if($res == false) return [""];
        while($row = $res->fetch_array()){
            $rows[] = $row;
        }
//        $res->close();
        return $rows;    
    }  

    public function justquery ($query) {       
        $res = $this->sql->multi_query($query);   
    }  
    
    public function security($string){
        return htmlspecialchars($string, ENT_QUOTES);
    }
    
    protected function connect(){
        global $SUPERGLOBAL;
        $this->sql = new mysqli('127.0.0.1', $this->config['user'], $this->config['password'], $this->config['db']);
        if ($this->sql->connect_errno){
            error_log("mysql connect error ",1,"ld@bookluk.com");
            die ("die die die fucking process just died!");
        }
    }

    public function getSerial($name){ // inside serializer. Better if we do with care
        $name=$this->security($name);
        $result = $this->query("SELECT value FROM `". $this->config['db']. "`.`serial` WHERE name = '". $name. "' ");
        if(isset($result[0]['value'])){ // future securit check
            $serial = $result[0]['value']+1;
            $this->justquery("UPDATE `". $this->config['db']. "`.`serial` SET `value` = '". $serial ."' WHERE `name` = '". $name. "'");            
        }else{
            $serial = 0;
            $this->justquery("INSERT INTO `". $this->config['db']. "`.`serial` (`name`, `value`) VALUES ('". $name. "', '0')");
        }
        return $serial;
    }    

   
    public function createFile($size, $pieces, $type, $name){ // inside serializer. Better if we do with care
        $fileId=$this->getSerial("file");
        $this->justquery("INSERT INTO `". $this->config['db']. "`.`fileList` (`id`, `status`, `creationTimestamp`, `size`, `type`, `filePieceNumber`, `filePieceNumberStatus`, `uploadFinishedStamp`, `access`, `fileName`) VALUES ('". $fileId. "', 'new', '". $this->timeStamp(). "', '". $size. "', '".$type."', '". $pieces."', 'new', '0', 'public', '". $name. "')");    
        $sql="INSERT INTO `". $this->config['db']. "`.`filePieces` (`id`, `fileId`, `status`) VALUES ";
        for ($i=0;$i<$pieces;$i++){
            if ($i>0) $sql.=", ";
            $sql.="('". $i. "', '". $fileId. "', 'new')";
        }
        error_log($sql);
        $this->justquery($sql);
        error_log($this->sql->error);
        return $fileId;
    }    

    public function getFileType($fileId){ // inside serializer. Better if we do with care
        $result = $this->query("SELECT * FROM `". $this->config['db']. "`.`fileList` WHERE id = '". $this->security($fileId). "' ");      
        if(isset($result[0]['type'])){
                return $result[0]['type'];
        }else{
            return false;
        }
    }    

    public function getFilePieceNumber($fileId){ // inside serializer. Better if we do with care
        $result = $this->query("SELECT * FROM `". $this->config['db']. "`.`fileList` WHERE id = '". $this->security($fileId). "' ");      
        if(isset($result[0]['type'])){
                return $result[0]['type'];
        }else{
            return false;
        }
    }  
    
    
    public function checkFileProcess($fileId){ // inside serializer. Better if we do with care
            error_log("-1.6");
        $result = $this->query("SELECT * FROM `". $this->config['db']. "`.`fileList` WHERE id = '". $this->security($fileId). "' ");      
            error_log("-1.4");
        if(isset($result[0]['status'])){
            if($result[0]['status'] == "process"){
                return true;
            }elseif($result[0]['status']=="new"){
                $this->justquery("UPDATE `". $this->config['db']. "`.`fileList` SET `status` = 'process' WHERE `id` = '".  $this->security($fileId). "'" );          
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    public function checkFileFinnished($fileId){ // inside serializer. Better if we do with care
        $resultFile = $this->query("SELECT * FROM `".  $this->config['db']. "`.`fileList` WHERE id = '". $this->security($fileId). "' ");    
        $result = $this->query("SELECT * FROM `". $this->config['db']. "`.`filePieces` WHERE `fileId` = '".  $this->security($fileId). "' AND `status` = 'ok' ");
        if($resultFile[0]['filePieceNumber'] == count($result)) return count($result);
        return false;
    }  
    
    
    public function setFileOk($fileId){ // inside serializer. Better if we do with care
        $this->justquery("UPDATE `".  $this->config['db']. "`.`fileList` SET `status` = 'ok',`uploadFinishedStamp` = '". $this->timeStamp(). "', WHERE `id` = '".  $this->security($fileId). "'" );  
    }  
    
    
    public function checkFilePieces($fileId, $piece){ // inside serializer. Better if we do with care
        $result = $this->query("SELECT * FROM `". $this->config['db']. "`.`filePieces`  WHERE `fileId` = '".  $this->security($fileId). "' AND `id` = '".  $this->security($piece) . "'");      
        if(isset($result[0]['status'])){
            if($result[0]['status'] == "try"){
                return true;
            }elseif($result[0]['status']=="new"){
                $this->setFilePieceTry($fileId, $piece);
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    } 
    
    public function setFilePieceTry($fileId, $piece){ // inside serializer. Better if we do with care
        $this->justquery("UPDATE `". $this->config['db']. "`.`filePieces` SET `status` = 'try' WHERE `fileId` = '".  $this->security($fileId). "' AND `id` = '".  $this->security($piece) . "'");
    }   
    
    public function setFilePieceOk($fileId, $piece){ // inside serializer. Better if we do with care
        $this->justquery("UPDATE `". $this->config['db']. "`.`filePieces` SET `status` = 'ok' WHERE `fileId` = '".  $this->security($fileId). "' AND `id` = '".  $this->security($piece) . "'"); 
    }        
    
    
    
    public function __construct (){
        global $SUPERGLOBAL;
        $this->config=$SUPERGLOBAL['config']['sql'];
        $this->connect();
    }
    
    public function __destruct() {
     
    }
    
}
