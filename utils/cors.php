<?php

function cors(array $config = []) {
    $config = array_merge([
        'origins' => ["*"],
        'methods' => ["*"],
        'headers' => ["*"],
        'credentials' => true,
        'max_age' => 86400
    ], $config);

    cors_allow_origins(...$config['origins']);
    cors_allow_methods(...$config['methods']);
    cors_allow_headers(...$config['headers']);
    cors_allow_credentials($config['credentials']);
    cors_max_age($config['max_age']);
}

function cors_allow_credentials(bool $allow = true) {
    header("Access-Control-Allow-Credentials: $allow");
}

function cors_max_age(int $age = 86400) {
    header("Access-Control-Max-Age: $age");
}

function cors_allow_origins(...$origins) {
    if (in_array('*', $origins) || empty($origins)) {
        return header("Access-Control-Allow-Origin: *");
    }

    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    if (in_array($origin, $origins)) {
        header("Access-Control-Allow-Origin: $origin");
    }
}

function cors_allow_methods(...$allowed_methods) {
    $request_method = $_SERVER['REQUEST_METHOD'] ?? '';

    if (in_array('*', $allowed_methods) || empty($allowed_methods)) {
        $methods = '*';
    } else {
        $methods = implode(', ', $allowed_methods);
    }

    header("Access-Control-Allow-Methods: $methods");

    if ($request_method === 'OPTIONS') {
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Request-Headers, Authorization");
        header("HTTP/1.1 200 OK");
        exit;
    }

    if (!in_array('*', $allowed_methods) && !in_array($request_method, $allowed_methods)) {
        header("HTTP/1.1 405 Method Not Allowed");
        exit;
    }
}

function cors_allow_headers(...$allowed_headers) {
    if (in_array('*', $allowed_headers) || empty($allowed_headers)) {
        return header("Access-Control-Allow-Headers: *");
    }

    $request_headers = getallheaders();
    $headers = implode(', ', $allowed_headers);
    header("Access-Control-Allow-Headers: $headers");

    if (isset($request_headers['Access-Control-Request-Headers'])) {
        $requested_headers = explode(',', $request_headers['Access-Control-Request-Headers']);
        foreach ($requested_headers as $header) {
            if (!in_array(trim($header), $allowed_headers)) {
                header("HTTP/1.1 400 Bad Request");
                exit;
            }
        }
    }

}