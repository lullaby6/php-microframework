<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
include_once ROOT_PATH. "/utilities/data.php";
include_once ROOT_PATH . "/utilities/env.php";
include_once ROOT_PATH . "/utilities/router.php";
router(ROOT_PATH . "/pages");

?>