<?php

class JobGrab extends Threaded implements Collectable {
    public $url;
    public $body;
    private $garbage = false;
    public function __construct($url){
        $this->url = $url;
    }
    public function run(){
        $this->body = file_get_contents($this->url, null, null);
//        $this->body = $this->url;
        $this->setGarbage();
    }
    public function getResult(){
        return $this->body;
    }
    public function setGarbage()
    {
        $this->garbage = true;
    }
    public function isGarbage():bool
    {
        return $this->garbage;
    }
    public function getName(){
        return 'grab';
    }
}