<?php

include_once __DIR__ . "/config.php";
include_once BASE_PATH . "/utilities/data.php";
include_once BASE_PATH . "/utilities/env.php";
include_once BASE_PATH . "/utilities/router.php";
router(BASE_PATH . "/routes", "framework/");

?>