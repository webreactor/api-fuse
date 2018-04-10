<?php

include "../common/core.php";


// $id = $container['app']->addTask(date("Y-m-d H:i:s", time()), "http://total-test.cloud.private.srvcam.com/", "GET"); die();


// print_r($aq);
while (true) {
    $count = $container['app']->processAssigned();
    if ($count == 0) {
        usleep(500000);
    } else {
        echo "Processed $count\n";
    }
    $aq = $container['app']->assign();
}


