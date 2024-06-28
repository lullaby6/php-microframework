<?php

function get_env_or_default($key, $default) {
    $value = getenv($key);

    if (!isset($value)) {
        return $default;
    }

    return $value;
}