<?php

define("DB_CONNECTION", "mysql:dbname=".getenv("MYSQL_DB").";host=".getenv("MYSQL_HOST")); // connection string for PDO
define("DB_USER", getenv("MYSQL_USER"));
define("DB_PASS", getenv("MYSQL_PASSWORD"));

define("LOGS_TTL", 14*24*60*60); // seconds

$curl_defaults = array(
    CURLOPT_MAXREDIRS => 5,
    CURLOPT_CONNECTTIMEOUT => 5,
);

$process_defaults = array(
    'max_retry' => 5,
    'retry_ttl' => 30,
    'accept_codes' => array('1xx', '2xx', '3xx', '4xx'),
);
