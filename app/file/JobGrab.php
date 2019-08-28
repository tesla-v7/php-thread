<?php

include_once './Parse.php';

class JobGrab extends Threaded implements Collectable {
    public $url;
    public $body;
    private $result;
    private $garbage = false;
    private $graber = null;
    public function __construct($url, Parser $graber){
        $this->url = $url;
        $this->graber = $graber;
    }
    public function run(){
        $this->result = $this->graber->getResult(file_get_contents($this->url, null, null));
        $next = $this->graber->getNextUrl();
        if($next){

        };
//        $this->body = $this->url;
        $this->setGarbage();
    }
    public function getResult(){
        return $this->result;
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