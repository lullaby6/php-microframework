<?php

include_once UTILS_PATH . "cors.php";

cors([
    'origin' => "*",
    'methods' => "*",
    'headers' => "*",
    'credentials' => true
]);

if ($_METHOD == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}