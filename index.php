<?php

include_once __DIR__ . "/config.php";
include_once BASE_PATH . "/utilities/data.php";
include_once BASE_PATH . "/utilities/env.php";
include_once BASE_PATH . "/utilities/pages_router.php";
pages_router(BASE_PATH . "/pages", "framework/");

?>