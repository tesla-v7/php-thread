<?php
$chromeRun = '/usr/bin/google-chrome-stable --remote-debugging-address=192.168.43.249 --remote-debugging-port=9222';
$chromWs = 'ws://127.0.0.1:9222/devtools/browser/5385c568-5bf1-4d4e-b554-b1d30b0ebf7c';
$tun = 'socat tcp-l:9223,fork,reuseaddr tcp:127.0.0.1:9222';
class job extends Threaded implements Collectable {
    private $url = 'http://diesel.elcat.kg/index.php?showforum=283&prune_day=100&sort_by=Z-A&sort_key=last_post&topicfilter=all&page=';
    public $val;
    public $body;
    private $garbage = false;
    public function __construct($val){
        $this->val = $val;
    }
    public function run(){
        $this->body = file_get_contents($this->url . $this->val, null, null);
        $this->setGarbage();
    }
    public function setGarbage()
    {
        $this->garbage = true;
    }
    public function isGarbage():bool
    {
        return $this->garbage;
    }
}
$p = new Pool(4);
array_walk(
    array_fill(1, 10, 0),
    function ($item, $key)use($p){$p->submit(new JobGrab($key));}
);
//$tasks = [
//    new job('0'),
//    new job('1'),
//    new job('2'),
//    new job('3'),
//    new job('4'),
//    new job('5'),
//    new job('6'),
//    new job('7'),
//    new job('8'),
//    new job('9'),
//    new job('10'),
//];
//
//
//foreach ($tasks as $task) {
//    $p->submit($task);
//}
// garbage collection check / read results
$r = [];
while($p->collect(function($checkingTask)use(&$r){
        file_put_contents("/home/app/scripts/tmp/f_$checkingTask->val.html", $checkingTask->body);
    $r[] = $checkingTask->body;
    return $checkingTask->isGarbage();
}));
$p->shutdown();
var_dump($r);