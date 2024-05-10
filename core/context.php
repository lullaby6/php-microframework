<?php

$_BODY = json_decode(file_get_contents('php://input'), true);

$_METHOD = $_SERVER['REQUEST_METHOD'];

$_HEADERS = getallheaders();

$_FULL_URL = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$_URL = $_SERVER['REQUEST_URI'];

$_PATH = parse_url($_FULL_URL)['path'];

$_QUERY_STRING = (str_contains($_URL, '?')) ? explode('?', $_URL)[1] : '';

$_PATH_VALUE = array();