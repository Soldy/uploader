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

class sql {
    private $sql ;
    
    private $config = [];
    
    
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
        $res->close();
        return $rows;    
    }  
    
    public function security($string){
        return htmlspecialchars($string, ENT_QUOTES);
    }
    
    public function connect(){
        $this->sql = new mysqli($this->config['host'], $this->config["user"], $this->config["password"], $this->config["database"]);
        if ($this->sql->connect_errno) die ("die die die fucking process just died!");
    }

    public function getSerial($name){ // inside serializer. Better if we do with care
        $name=$this->security($name);
        $result = $this->query("SELECT value FROM `serial` WHERE name = '". $name. "' ");
        $serial = 0;
        $this->query("INSERT INTO `serial` (`name`, `value`) VALUES ('". $name. "', '1')");
        $this->query("UPDATE `serial` SET `value` = '2' WHERE `name` = '". $name. "'");
        return $serial;
    }    
    
    protected function __construct (){
        global $SUPERGLOBAL;
        $this->config=$SUPERGLOBAL['config']['sql'];
        $this->connect();
    }
    
    protected function __destruct() {
     
    }
    
}
