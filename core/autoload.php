<?php

session_start();

ini_set('memory_limit', '-1');

include_once __DIR__ . "/consts.php";

include_once UTILS_PATH . "headers.php";
include_once UTILS_PATH . "minify.php";
include_once UTILS_PATH . "json.php";
include_once UTILS_PATH . "verify_mime_type.php";
include_once UTILS_PATH . "file.php";
include_once UTILS_PATH . "valid.php";

include_once CORE_PATH . "context.php";
include_once CORE_PATH . "ip.php";
include_once CORE_PATH . "env.php";

include_once CORE_PATH . "errors.php";
include_once CORE_PATH . "layout.php";
include_once CORE_PATH . "template.php";
include_once CORE_PATH . "render.php";
include_once CORE_PATH . "load.php";
include_once CORE_PATH . "middleware.php";
include_once CORE_PATH . "router.php";

include_once ROOT_PATH . "/index.php";