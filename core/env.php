<?php

$_ENV = getenv();

$env_file_path = $_SERVER["DOCUMENT_ROOT"] . "/.env";

if (file_exists($env_file_path)) {
    $env_content = file_get_contents($env_file_path);
    $env_lines = explode("\n", $env_content);

    foreach ($env_lines as $line) {
        $line = trim($line);

        if (!empty($line) && strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}