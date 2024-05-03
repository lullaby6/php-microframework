<?php

function status_code($code) {
    http_response_code($code);
}

function content_type_json() {
    header('Content-Type: application/json');
}

function content_type_text() {
    header('Content-Type: text/plain');
}

function content_type_html() {
    header('Content-Type: text/html');
}

function content_type_css() {
    header('Content-Type: text/css');
}

function content_type_js() {
    header('Content-Type: text/javascript');
}

function get_response_header($header_name) {
    $header_name = strtolower("{$header_name}:");
    foreach (headers_list() as $header) {
        $header = strtolower($header);
        if (str_starts_with($header, $header_name)) {
            return trim(str_replace($header_name, '', $header));
        }
    }
}