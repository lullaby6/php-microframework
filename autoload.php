<?php

include_once ROOT_PATH . "/utils/show_errors.php";

include_once ROOT_PATH . "/utils/env.php";

include_once ROOT_PATH . "/utils/file_router.php";

include_once ROOT_PATH . "/utils/render.php";

include_once ROOT_PATH . "/utils/data.php";
include_once ROOT_PATH . "/utils/header.php";
include_once ROOT_PATH . "/utils/cors.php";

show_errors(SHOW_ERRORS);

session_start();

ini_set('memory_limit', '-1');