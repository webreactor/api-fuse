<?php

include "../common/core.php";

$time = time();


for ($i=1; $i < 11; $i++) { 
    echo date("Y-m-d H:i:s", $time+$i*60). " > ".($i*10)."\n";
    for ($j=0; $j < $i*10; $j++) {
        $id = $container['app']->addTask(gmdate("Y-m-d H:i:s", $time+$i*60), "http://total-test.cloud.private.srvcam.com/", "GET");
    }
}



