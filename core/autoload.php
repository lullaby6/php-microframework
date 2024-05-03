<?php

include_once __DIR__ . "/consts.php";

include_once UTILS_PATH . "json.php";
include_once UTILS_PATH . "header.php";
include_once UTILS_PATH . "verify_mime_type.php";

include_once CORE_PATH . "errors.php";
include_once CORE_PATH . "env.php";
include_once CORE_PATH . "data.php";
include_once CORE_PATH . "template.php";
include_once CORE_PATH . "router.php";

session_start();

ini_set('memory_limit', '-1');