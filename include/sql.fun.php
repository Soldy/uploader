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
    protected $sql ;
    
    public function insert(){
        #("INSERT INTO test(id) VALUES (1), (2), (3)")) 
    } 

    public function update(){
        
    } 
    
    public function select(){
    }
    
    public function query ($query) {
        $res = $this->sql->query($query);
        while($row = $res->fetch_array()){
            $rows[] = $row;
        }
//        $res->close();
        return $rows;    
    }  
    
    public function security($string){
        return htmlspecialchars($string, ENT_QUOTES);
    }
    
    protected function connect(){
        global $SUPERGLOBAL;
        $this->sql = new mysqli('127.0.0.1', $SUPERGLOBAL['config']['sql']['user'], $SUPERGLOBAL['config']['sql']['password'], $SUPERGLOBAL['config']['sql']['db']);
        if ($this->sql->connect_errno){
            error_log("mysql connect error ",1,"ld@bookluk.com");
            die ("die die die fucking process just died!");
        }
    }

    public function getSerial($name){ // inside serializer. Better if we do with care
        $name=$this->security($name);
        $result = $this->query("SELECT value FROM `serial` WHERE name = '". $name. "' ");
        if(isset($result[0]['value'])){ // future securit check
            $serial = $result[0]['value']+1;
            $this->query("UPDATE `serial` SET `value` = '". $serial ."' WHERE `name` = '". $name. "'");            
        }else{
            $serial = 0;
            $this->query("INSERT INTO `serial` (`name`, `value`) VALUES ('". $name. "', '0')");
        }
        return $serial;
    }    

   
    public function createFile($size, $pieces, $type){ // inside serializer. Better if we do with care
        $fileId=$this->getSerial("file");
        $this->query("INSERT INTO `". $this->database. "`.`fileList` (`id`, `status`, `creationTimestamp`, `size`, `type`, `filePieceNumber`, `filePieceNumberStatus`, `uploadFinishedStamp`, `access`) VALUES ('". $fileId. "', 'new', '". microtime()."', '". $size. "', '".$type."', '". $pieces."', 'new', '0', 'public')");    
        $sql="INSERT INTO `". $this->database. "`.`filePieces` (`id`, `fileId`, `status`) VALUES";
        for ($i=0;$i<$pieces;$i++){
            if ($i>0) $sql.=", ";
            $sql.="('". $i. "', '". $fileId. "', 'new')";
        }
        $this->query($sql);
        return $fileId;
    }    

    public function getFileType($fileId){ // inside serializer. Better if we do with care
        $result = $this->query("SELECT `". $this->database. "`.`fileList` FROM `serial` WHERE id = '". $this->security($fileId). "' ");      
        if(isset($result[0]['type'])){
                return $result[0]['type'];
        }else{
            return false;
        }
    }    

    public function getFilePieceNumber($fileId){ // inside serializer. Better if we do with care
        $result = $this->query("SELECT `". $this->database. "`.`fileList` FROM `serial` WHERE id = '". $this->security($fileId). "' ");      
        if(isset($result[0]['type'])){
                return $result[0]['type'];
        }else{
            return false;
        }
    }  
    
    
    public function checkFileProcess($fileId){ // inside serializer. Better if we do with care
        $result = $this->query("SELECT `". $this->database. "`.`fileList` FROM `serial` WHERE id = '". $this->security($fileId). "' ");      
        if(isset($result[0]['status'])){
            if($result[0]['status'] == "process"){
                return true;
            }elseif($result[0]['status']=="new"){
                $this->query("UPDATE `". $this->database. "`.`fileList` SET `status` = 'process' WHERE `id` = '".  $this->security($fileId). "'" );          
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    public function checkFileFinnished($fileId){ // inside serializer. Better if we do with care
        $resultFile = $this->query("SELECT `". $this->database. "`.`fileList` FROM `serial` WHERE id = '". $this->security($fileId). "' ");    
        $result = $this->query("SELECT`". $this->database. "`.`filePieces`  FROM `serial` WHERE `fileId` = '".  $this->security($fileId). "' AND `status` = 'ok' ");
        if($resultFile[0]['filePieceNumber'] == count($result)) return $resultFile[0]['filePieceNumber'];
        return false;
    }  
    
    
    public function setFileOk($fileId){ // inside serializer. Better if we do with care
        $this->query("UPDATE `". $this->database. "`.`fileList` SET `status` = 'ok',`uploadFinishedStamp` = '". microtime(). "', WHERE `id` = '".  $this->security($fileId). "'" );  
    }  
    
    
    public function setFilePiecesTry($fileId, $piece){ // inside serializer. Better if we do with care
        $result = $this->query("SELECT`". $this->database. "`.`filePieces`  FROM `serial` WHERE `fileId` = '".  $this->security($fileId). "' AND `id` = '".  $this->security($piece) . "'");      
        if(isset($result[0]['status'])){
            if($result[0]['status'] == "try"){
                return true;
            }elseif($result[0]['status']=="new"){
                $this->setFilePiecesTry($fileId, $piece);
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    } 
    
    public function setFilePieceTry($fileId, $piece){ // inside serializer. Better if we do with care
        $this->query("UPDATE `". $this->database. "`.`filePieces` SET `status` = 'try' WHERE `fileId` = '".  $this->security($fileId). "' AND `id` = '".  $this->security($piece) . "'");
    }   
    
    public function setFilePieceOk($fileId, $piece){ // inside serializer. Better if we do with care
        $this->query("UPDATE `". $this->database. "`.`filePieces` SET `status` = 'ok' WHERE `fileId` = '".  $this->security($fileId). "' AND `id` = '".  $this->security($piece) . "'"); 
    }        
    
    
    
    public function __construct (){
        $this->connect();
    }
    
    public function __destruct() {
     
    }
    
}
