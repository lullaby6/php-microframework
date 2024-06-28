<?php

function url_set_param($url, $paramName, $paramValue) {
    $parsed_url = parse_url($url);
    $query_params = [];

    if (isset($parsed_url['query'])) {
        parse_str($parsed_url['query'], $query_params);
    }

    $query_params[$paramName] = $paramValue;

    $new_query_string = http_build_query($query_params);

    $new_url = $parsed_url['scheme'] . '://' . $parsed_url['host'];

    if (isset($parsed_url['path'])) {
        $new_url .= $parsed_url['path'];
    }

    $new_url .= '?' . $new_query_string;

    return $new_url;
}