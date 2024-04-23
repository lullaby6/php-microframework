<?php

include_once ROOT_PATH . "/utils/header.php";
header_json();
http_response_code(200);

$response = [
    "msg" => "pong"
];

echo json_encode($response);