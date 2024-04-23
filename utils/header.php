<?php

function header_json() {
    header('Content-Type: application/json');
}

function header_text() {
    header('Content-Type: text/plain');
}

function header_html() {
    header('Content-Type: text/html');
}