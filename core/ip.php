<?php

$_IP = null;

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $_IP = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $_IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $_IP = $_SERVER['REMOTE_ADDR'];
}