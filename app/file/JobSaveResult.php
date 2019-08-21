<?php

class JobSaveResult extends Threaded implements Countable{
    private $garbage = false;
    private $data;
    public $body = '_OH_';
    public function __construct($data){
        $this->data = $data;
    }
    public function run(){
        file_put_contents('/home/app/scripts/tmp/result_.txt', "\n". $this->data, FILE_APPEND);
        $this->data = "test_$this->data";
        $this->setGarbage();
    }
    public function getResult(){
        return $this->body;
    }
    public function setGarbage(){
        $this->garbage = true;
    }
    public function isGarbage():bool{
        return $this->garbage;
    }
    public function getName(){
        return 'save';
    }
}