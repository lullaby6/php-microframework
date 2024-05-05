<?php

define('SERVER_DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);

const ROOT_PATH = SERVER_DOCUMENT_ROOT;

const CORE_PATH = ROOT_PATH . "/core/";
const CONFIG_PATH = ROOT_PATH . "/config/";
const UTILS_PATH = ROOT_PATH . "/utils/";

const APP_PATH = ROOT_PATH . "/app/";
const ROUTES_PATH = APP_PATH . "/routes/";
const PUBLIC_PATH = APP_PATH . "/public/";
const LAYOUTS_PATH = APP_PATH . "/layouts/";
const TEMPLATES_PATH = APP_PATH . "/templates/";
const CSS_PATH = APP_PATH . "/css/";
const JS_PATH = APP_PATH . "/js/";

const NOT_FOUND_FILE_PATH = ROUTES_PATH . "/404.php";

const SHOW_ERRORS = true;