<?php

function cors(string $origin = "*", string $methods = "*", string $headers = "*", string $credentials = "true") {
    header("Access-Control-Allow-Origin: " . $origin);
    header("Access-Control-Allow-Methods: " . $methods);
    header("Access-Control-Allow-Headers: " . $headers);
    header("Access-Control-Allow-Credentials: " . $credentials);
}