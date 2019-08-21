<?php
$url = 'https://www.catalog.update.microsoft.com/Search.aspx?q=windows';
$result = [];
$startYear = 2000;
$endYear = 2005;
$mounts = 12;
$days = 31;
for($year = $startYear; $year <= $endYear; $year++){
    for($mount =1; $mount <= $mounts; $mount++){
        for($day =1; $day <= $days; $day++){
            $result[] = $url . "%20$mount%2F$day%2F$year";
        }
    }
    echo 'Y '. $year;
}
file_put_contents(__DIR__ .'/tmp/urls.txt', implode("\n", $result));