<?php

middleware("cors");
middleware("rate-limit");

include_once PATHS['database'] . 'connection.php';

router();