<?php

include_once UTILS_PATH . "cors.php";

cors([
    'origins' => ["*"],
    'methods' => ["*"],
    'headers' => ["*"],
    'credentials' => true,
    'max_age' => 86400
]);