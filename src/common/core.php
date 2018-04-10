<?php

namespace Reactor\APIFuse;

date_default_timezone_set('Etc/UTC');

require __dir__."/../../vendor/autoload.php";
require __dir__."/../config.php";


$container = new \ArrayObject();

$container['db'] = new \Reactor\Database\PDO\Connection(DB_CONNECTION, DB_USER, DB_PASS);
$container['db']->sql("set time_zone='UTC'")->exec();
$container['http'] = new \Reactor\HttpClient\HttpClient("", $curl_defaults);

$container['app'] = new APIFuse($container['db'], $container['http'], $process_defaults, $curl_defaults);


