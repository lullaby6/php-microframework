<?php

$_BODY = json_decode(file_get_contents('php://input'), true);

$_METHOD = $_SERVER['REQUEST_METHOD'];

$_HEADERS = getallheaders();

$_URL = $_SERVER['REQUEST_URI'];

$_PATH = (str_contains($_URL, '?')) ? explode('?', $_URL)[0] : $_URL;

$_QUERY_STRING = (str_contains($_URL, '?')) ? explode('?', $_URL)[1] : '';

$_PATH_VALUE = array();