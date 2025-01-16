<?php

define('PATHS', [
    'root' => $_SERVER['DOCUMENT_ROOT'],
    'core' => $_SERVER['DOCUMENT_ROOT'] . '/core/',
    'core_utils' => $_SERVER['DOCUMENT_ROOT'] . '/core/utils/',
    'app' => $_SERVER['DOCUMENT_ROOT'] . '/app/',
    'routes' => $_SERVER['DOCUMENT_ROOT'] . '/app/routes/',
    'middlewares' => $_SERVER['DOCUMENT_ROOT'] . '/app/middlewares/',
    'utils' => $_SERVER['DOCUMENT_ROOT'] . '/utils/',
    'public' => $_SERVER['DOCUMENT_ROOT'] . '/app/public/',
    'layouts' => $_SERVER['DOCUMENT_ROOT'] . '/app/layouts/',
    'templates' => $_SERVER['DOCUMENT_ROOT'] . '/app/templates/',
    'css' => $_SERVER['DOCUMENT_ROOT'] . '/app/css/',
    'js' => $_SERVER['DOCUMENT_ROOT'] . '/app/js/',
    'database' => $_SERVER['DOCUMENT_ROOT'] . '/database/',
    'config' => $_SERVER['DOCUMENT_ROOT'] . '/config/',
]);

define('SHOW_ERRORS', false);

include_once PATHS['utils'] . 'env.php';

define('SECRET_KEY', get_env_or_default('SECRET_KEY', 'secret_key'));