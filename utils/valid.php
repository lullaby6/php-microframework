<?php

function is_valid_json($string): string {
    $decoded = json_decode($string);

    return $decoded !== null && json_last_error() === JSON_ERROR_NONE;
}

function is_valid_format($string, $format = 'Y-m-d H:i:s'): bool {
    $datetime = DateTime::createFromFormat($format, $string);

    return $datetime && $datetime->format($format) === $string;
}