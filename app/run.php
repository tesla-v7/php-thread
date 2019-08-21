<?php

include_once __DIR__.'/file/JobGrab.php';
//include_once __DIR__ .'/file/JobSaveResult.php';

const POOL_SIZE = 4;
const CHUNK_SIZE = 30;

function main(){
    $start = microtime(true);
    file_put_contents('/home/app/scripts/tmp/result_.txt', '');
    $poolThread = new Pool(POOL_SIZE);

    $allUrls = getAllUrls();

    foreach(array_chunk($allUrls, CHUNK_SIZE) as $key=>$urlsChunk){
        echo "chunk $key\n";
        $chunkResult = [];
        foreach($urlsChunk as $keyCunk=>$url){
            $poolThread->submit(new JobGrab($url));
        };
        while($poolThread->collect(function($checkingTask)use(&$chunkResult){
            if($checkingTask->getName() === 'grab'){
                $chunkResult[] = $checkingTask->body ."_". $checkingTask->isGarbage();
            }
            return $checkingTask->isGarbage();
        }));
        saveResult(implode("\n", $chunkResult));
    }
    $poolThread->shutdown();

    echo sprintf('%.4F sec.', microtime(true) - $start);

}

function getAllUrls(){
    return explode("\n", file_get_contents(__DIR__ .'/tmp/urls.txt'));
}


function saveResult($data){
    file_put_contents('/home/app/scripts/tmp/result_.txt', "\n". $data, FILE_APPEND);

}

main();