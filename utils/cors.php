<?php

function cors(array $config = []) {
    $config = array_merge([
        'origin' => "*",
        'methods' => "*",
        'headers' => "*",
        'credentials' => "true"
    ], $config);

    header("Access-Control-Allow-Origin: " . $config['origin']);
    header("Access-Control-Allow-Methods: " . $config['methods']);
    header("Access-Control-Allow-Headers: " . $config['headers']);
    header("Access-Control-Allow-Credentials: " . $config['credentials']);
}
