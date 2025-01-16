<?php

$_BODY = json_decode(file_get_contents('php://input'), true);

$_METHOD = $_SERVER['REQUEST_METHOD'];

$_HEADERS = apache_request_headers();

$_PROTOCOL = empty($_SERVER['HTTPS']) ? 'http' : 'https';

$_HOST = $_SERVER['HTTP_HOST'];

$_URI = $_SERVER['REQUEST_URI'];

$_BASE_URL = "{$_PROTOCOL}://{$_HOST}";

$_FULL_URL = "{$_PROTOCOL}://{$_HOST}{$_URI}";

$_PATH = parse_url($_FULL_URL)['path'];

$_QUERY_STRING = (str_contains($_URI, '?')) ? explode('?', $_URI)[1] : '';

$_PATH_VALUE = array();