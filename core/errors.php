<?php

if (SHOW_ERRORS) {
    ini_set('display_errors', 'On');
    ini_set('log_errors', 'On');
    ini_set('error_reporting', E_ALL);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'Off');
    ini_set('error_reporting', 0);
    error_reporting(0);
}