<?php

define('SERVER_DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);

const ROOT_PATH = SERVER_DOCUMENT_ROOT;

const CORE_PATH = ROOT_PATH . "/core/";
const CONFIG_PATH = ROOT_PATH . "/config/";
const UTILS_PATH = ROOT_PATH . "/utils/";

const APP_PATH = ROOT_PATH . "/app/";
const ROUTES_PATH = APP_PATH . "/routes/";
const MIDDLEWARES_PATH = APP_PATH . "/middlewares/";
const PUBLIC_PATH = APP_PATH . "/public/";
const LAYOUTS_PATH = APP_PATH . "/layouts/";
const TEMPLATES_PATH = APP_PATH . "/templates/";
const CSS_PATH = APP_PATH . "/css/";
const JS_PATH = APP_PATH . "/js/";

const SHOW_ERRORS = true;

// const MAX_REQUESTS = 25;
// const MAX_REQUEST_TIME_SECONDS = 1;
// const CLEAR_REQUESTS_TIME_SECONDS = 3600;
// const BLOCK_IP_TIME_SECONDS = 3600;