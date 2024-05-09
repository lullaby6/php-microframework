<?php

session_start();

ini_set('memory_limit', '-1');

include_once __DIR__ . "/consts.php";

foreach (new DirectoryIterator(UTILS_PATH) as $file) {
    if(!$file->isDot() && $file->isFile()) include_once $file->getPathname();
}

include_once CORE_PATH . "ip.php";

include_once CONFIG_PATH . "rate_limit.php";

include_once CORE_PATH . "env.php";
include_once CORE_PATH . "context.php";
include_once CORE_PATH . "errors.php";
include_once CORE_PATH . "layout.php";
include_once CORE_PATH . "template.php";
include_once CORE_PATH . "load.php";
include_once CORE_PATH . "middleware.php";

include_once ROOT_PATH . "/index.php";