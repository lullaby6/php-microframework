<?php

$_BODY = json_decode(file_get_contents('php://input'), true);
$_METHOD = $_SERVER['REQUEST_METHOD'];
$_HEADERS = getallheaders();
$_URL = $_SERVER['REQUEST_URI'];
$_PATH = (str_contains($_SERVER['REQUEST_URI'], '?')) ? $explode('?', $_SERVER['REQUEST_URI'])[0] : $_SERVER['REQUEST_URI'];
$_QUERY_SRING = (str_contains($_SERVER['REQUEST_URI'], '?')) ? $explode('?', $_SERVER['REQUEST_URI'])[1] : "";

$_ENV = getenv();

$env_file_path = $_SERVER["DOCUMENT_ROOT"] . "/.env";

if (file_exists($env_file_path)) {
    $env_content = file_get_contents($env_file_path);

    $env_lines = explode("\n", $env_content);

    foreach ($env_lines as $line) {
        list($name, $value) = explode('=', $line, 2);

        if ($name && $value) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}