<?php

function cors($origin = "*", $methods = "*", $headers = "*", $credentials = "true") {
    header("Access-Control-Allow-Origin: " . $origin);
    header("Access-Control-Allow-Methods: " . $methods);
    header("Access-Control-Allow-Headers: " . $headers);
    header("Access-Control-Allow-Credentials: " . $credentials);
}

?>