<?php

function get_env_or_default(string $key, string $default): string {
    $value = getenv($key);

    if (!isset($value)) {
        return $default;
    }

    return $value;
}