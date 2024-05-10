<?php

function is_ip_blocked($file_path, $ip, $time_seconds): bool {
    $file_content = file_get_contents($file_path);

    if (!is_valid_json($file_content)) {
        unlink($file_path);

        return false;
    }

    $blocked_ips_json = json_decode($file_content, true);

    if (!isset($blocked_ips_json['blocked_ips'])) {
        unlink($file_path);

        return false;
    }

    $blocked_ips = $blocked_ips_json['blocked_ips'];

    if (count($blocked_ips) == 0) {
        unlink($file_path);

        return false;
    }

    if (!isset($blocked_ips[$ip])) {
        return false;
    }

    $blocked_ip_date_time = $blocked_ips[$ip];

    $time_diff = strtotime(date("Y-m-d H:i:s")) - strtotime($blocked_ip_date_time);

    if ($time_diff > $time_seconds) {
        unset($blocked_ips[$ip]);

        if (count($blocked_ips) == 0) {
            unlink($file_path);
        } else {
            file_put_contents($file_path, json_encode([
                'blocked_ips' => $blocked_ips
            ]));
        }

        return false;
    }

    return true;
}

function rate_limit($config = []): bool {
    $LAST_REQUEST_PATH = $config['last_request_path'] ?? __DIR__ . "/last_request.txt";
    $CLEAR_REQUESTS_TIME_SECONDS = $config['clear_requests_time_seconds'] ?? 3600;

    $BLOCKED_IPS_PATH = $config['blocked_ips_path'] ?? __DIR__ . "/blocked_ips.json";
    $BLOCK_IP_TIME_SECONDS = $config['block_ip_time_seconds'] ?? 3600;

    $REQUESTS_PATH = $config['requests_path'] ?? __DIR__ . "/requests.json";
    $REQUESTS_LIMIT = $config['requests_limit'] ?? 25;
    $REQUESTS_TIME_SECONDS = $config['requests_time_seconds'] ?? 1;

    $IP = isset($config['ip']) ? $config['ip'] : $_SERVER['REMOTE_ADDR'];

    $CURRENT_DATE_TIME = date("Y-m-d H:i:s");

    if (file_exists($BLOCKED_IPS_PATH) && is_ip_blocked($BLOCKED_IPS_PATH, $IP, $BLOCK_IP_TIME_SECONDS)) {
        return true;
    }

    if (!file_exists($LAST_REQUEST_PATH)) {
        file_put_contents($LAST_REQUEST_PATH, $CURRENT_DATE_TIME);

        return false;
    }

    $last_request_content = file_get_contents($LAST_REQUEST_PATH);

    if (!is_valid_format($last_request_content)) {
        file_put_contents($LAST_REQUEST_PATH, $CURRENT_DATE_TIME);

        return false;
    }

    file_put_contents($LAST_REQUEST_PATH, $CURRENT_DATE_TIME);

    $time_diff = strtotime($CURRENT_DATE_TIME) - strtotime($last_request_content);

    if ($time_diff > $CLEAR_REQUESTS_TIME_SECONDS) {
        file_put_contents($REQUESTS_PATH, json_encode([
            'requests' => [
                $IP => [
                    'last_request' => $CURRENT_DATE_TIME,
                    'requests_count' => 1
                ]
            ]
        ]));

        return false;
    }

    if (!file_exists($REQUESTS_PATH)) {
        file_put_contents($REQUESTS_PATH, json_encode([
            'requests' => [
                $IP => [
                    'last_request' => $CURRENT_DATE_TIME,
                    'requests_count' => 1
                ]
            ]
        ]));

        return false;
    }

    $requests_content = file_get_contents($REQUESTS_PATH);

    if (!is_valid_json($requests_content)) {
        file_put_contents($REQUESTS_PATH, json_encode([
            'requests' => [
                $IP => [
                    'last_request' => $CURRENT_DATE_TIME,
                    'requests_count' => 1
                ]
            ]
        ]));

        return false;
    }

    $requests = json_decode($requests_content, true);

    if (!isset($requests['requests'][$IP])) {
        $requests['requests'][$IP] = [
            'last_request' => $CURRENT_DATE_TIME,
            'requests_count' => 1
        ];

        file_put_contents($REQUESTS_PATH, json_encode($requests));

        return false;
    }

    if (!isset($requests['requests'][$IP]['last_request'])) {
        $requests['requests'][$IP]['last_request'] = $CURRENT_DATE_TIME;
    }

    if (!isset($requests['requests'][$IP]['requests_count'])) {
        $requests['requests'][$IP]['requests_count'] = 0;
    }

    if (!is_valid_format($requests['requests'][$IP]['last_request'])) {
        $requests['requests'][$IP]['last_request'] = $CURRENT_DATE_TIME;
    }

    $request_count = $requests['requests'][$IP]['requests_count'] + 1;
    $last_request_date_time = $requests['requests'][$IP]['last_request'];

    $requests['requests'][$IP] = [
        'last_request' => $CURRENT_DATE_TIME,
        'requests_count' => $request_count
    ];

    file_put_contents($REQUESTS_PATH, json_encode($requests));

    $time_diff = strtotime($CURRENT_DATE_TIME) - strtotime($last_request_date_time);

    if ($time_diff > $REQUESTS_TIME_SECONDS) {
        $requests['requests'][$IP]['requests_count'] = 0;

        file_put_contents($REQUESTS_PATH, json_encode($requests));

        return false;
    }

    if ($request_count >= $REQUESTS_LIMIT) {
        $blocked_date_time = date("Y-m-d H:i:s", strtotime($last_request_date_time) + $BLOCK_IP_TIME_SECONDS);

        if (!file_exists($BLOCKED_IPS_PATH)) {
            file_put_contents($BLOCKED_IPS_PATH, json_encode([
                'blocked_ips' => [
                    $IP => $blocked_date_time
                ]
            ]));

            return true;
        }

        $blocked_ips_content = file_get_contents($BLOCKED_IPS_PATH);

        if (!is_valid_json($blocked_ips_content)) {
            file_put_contents($BLOCKED_IPS_PATH, json_encode([
                'blocked_ips' => [
                    $IP => $blocked_date_time
                ]
            ]));

            return true;
        }

        $blocked_ips = json_decode($blocked_ips_content, true);

        $blocked_ips['blocked_ips'][$IP] = $blocked_date_time;

        file_put_contents($BLOCKED_IPS_PATH, json_encode($blocked_ips));

        return true;
    }

    return false;
}