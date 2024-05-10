<?php

include_once UTILS_PATH . "rate_limit/rate_limit.php";

if (rate_limit([
    'ip' => $_IP,
    'requests_limit' => MAX_REQUESTS,
    'requests_time_seconds' => MAX_REQUEST_TIME_SECONDS,
    'clear_requests_time_seconds' => CLEAR_REQUESTS_TIME_SECONDS,
    'block_ip_time_seconds' => BLOCK_IP_TIME_SECONDS,
])) {
    http_response_code(429);

    echo json_encode([
        'error' => 'Too many requests'
    ]);

    die();
}