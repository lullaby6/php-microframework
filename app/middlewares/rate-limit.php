<?php

include_once UTILS_PATH . "rate_limit/rate_limit.php";

if (rate_limit([
    'ip' => $_IP,
    'requests_limit' => 60,
    'requests_time_seconds' => 1,
    'clear_requests_time_seconds' => 3600,
    'block_ip_time_seconds' => 3600,
])) {
    http_response_code(429);

    echo json_encode([
        'error' => 'Too many requests'
    ]);

    die();
}