<?php

include_once ROOT_PATH . "/consts.php";
include_once ROOT_PATH . "/utils/show_errors.php";
include_once ROOT_PATH . "/utils/data.php";
include_once ROOT_PATH . "/utils/env.php";
include_once ROOT_PATH . "/utils/page_router.php";

show_errors(SHOW_ERRORS);

session_start();

ini_set('memory_limit', '-1');