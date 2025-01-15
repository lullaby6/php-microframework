<?php

session_start();

ini_set('memory_limit', '-1');

include_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";

include_once "./utils/headers.php";
include_once "./utils/minify.php";
include_once "./utils/json.php";
include_once "./utils/mime_type.php";
include_once "./utils/file.php";
include_once "./utils/valid.php";
include_once "./utils/url.php";

include_once "./context.php";
include_once "./ip.php";
include_once "./env.php";

include_once "./errors.php";
include_once "./layout.php";
include_once "./template.php";
include_once "./render.php";
include_once "./get.php";
include_once "./middleware.php";
include_once "./router.php";

include_once $_SERVER['DOCUMENT_ROOT'] . "/index.php";