<?php

function get_html_input_type_from_sql_type(string $type): string {
    $type = strtolower($type);

    if (
        str_contains($type, 'int') ||
        str_contains($type, 'bit') ||
        str_contains($type, 'float') ||
        str_contains($type, 'double') ||
        str_contains($type, 'decimal')
    ) {
        return 'number';
    } else if (str_contains($type, 'date')) return 'date';
    else if (str_contains($type, 'time')) return 'time';
    else if (str_contains($type, 'timestamp')) return 'datetime-local';
    else if (str_contains($type, 'text')) return 'textarea';

    return 'text';
}

function format_date_arg($date) {
    if ($date === '0000-00-00' || empty($date)) return '00/00/0000';

    return date('d/m/Y', timestamp: strtotime($date));
}

function format_short_time_arg($time) {
    if ($time === '00:00:00' || empty($time)) return '00:00';

    return date('H:i', timestamp: strtotime($time));
}

function format_time_arg($time) {
    if ($time === '00:00:00' || empty($time)) return '00:00:00';

    return date('H:i:s', timestamp: strtotime($time));
}

function format_currency_arg($value, $decimals = 2) {
    $formated_value = number_format($value, $decimals, ',', '.');
    return "$ $formated_value";
}