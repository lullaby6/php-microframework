<?php

ini_set('error_reporting', E_ALL);

function show_errors($show = true) {
    if ($show) {
        ini_set('display_errors', 'On');
        ini_set('log_errors', 'On');

        error_reporting(1);
    } else {
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'Off');

        error_reporting(0);
    }
}