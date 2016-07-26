<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class outBye {

    public $out = [];
    public $echo = "";
    public $header = 'Content-Type: application/json';

    public function __construct() {
        $this->out['status'] = 'ok';
    }

    public function error($error) {
        $this->out['error'] = $error;
        $this->out['status'] = 'error';
        $this->bye();
        return false;
    }

    public function bye() {
        $this->echo = json_encode($this->out);
    }

    public function __destruct() {
        
        header($this->header);        
        print $this->echo;
    }
    
}
