<?php

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
            $_SERVER[$name] = $value;
            $_CONTEXT["ENV"][$name] = $value;
        }
    }
}