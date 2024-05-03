<?php

content_type_json();
status_code(200);

$response = [
    "ping" => "pong"
];

json($response);