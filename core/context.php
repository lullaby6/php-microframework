<?php

$_CONTEXT = [
    "METHOD" => $_SERVER['REQUEST_METHOD'],
    "URL" => $_SERVER['REQUEST_URI'],
    "HEADERS" => getallheaders(),
    "BODY" => json_decode(file_get_contents('php://input'), true),
    "SESSION" => $_SESSION,
    "COOKIES" => $_COOKIE,
    "SERVER" => $_SERVER,
    "PATH" => (str_contains($_SERVER['REQUEST_URI'], '?')) ? $explode('?', $_SERVER['REQUEST_URI'])[0] : $_SERVER['REQUEST_URI'],
    "QUERYSTRING" => (str_contains($_SERVER['REQUEST_URI'], '?')) ? $explode('?', $_SERVER['REQUEST_URI'])[1] : "",
    "QUERY" => $_GET,
    "ENV" => getenv()
];