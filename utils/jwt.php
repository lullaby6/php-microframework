<?php

function base64url_encode($data) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
}

function base64url_decode($data) {
    $replaced = str_replace(['-', '_'], ['+', '/'], $data);
    return base64_decode($replaced);
}

function generate_jwt(array $payload = [], string $secret_key = "secret_key", int $exp = 0): string {
    $header = [
        'alg' => 'HS256',
        'typ' => 'JWT'
    ];

    if ($exp === 0) $exp = time() + 3600;

    $defaultPayload = [
        'exp' => $exp
    ];

    $payload = array_merge($defaultPayload, $payload);

    $base64Header = base64url_encode(json_encode($header));
    $base64Payload = base64url_encode(json_encode($payload));

    $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $secret_key, true);
    $base64Signature = base64url_encode($signature);

    return "$base64Header.$base64Payload.$base64Signature";
}

function verify_jwt($jwt, string $secret_key = "secret_key") {
    $tokenParts = explode('.', $jwt);

    if (count($tokenParts) !== 3) {
        throw new Exception('Invalid token format.');
    }

    [$base64Header, $base64Payload, $base64Signature] = $tokenParts;

    $header = json_decode(base64url_decode($base64Header), true);
    $payload = json_decode(base64url_decode($base64Payload), true);
    $signature = base64url_decode($base64Signature);

    if (!$header || !$payload) {
        throw new Exception('Invalid token format.');
    }

    if ($header['alg'] !== 'HS256') {
        throw new Exception('Invalid token algorithm.');
    }

    $expectedSignature = hash_hmac('sha256', "$base64Header.$base64Payload", $secret_key, true);

    if (!hash_equals($expectedSignature, $signature)) {
        throw new Exception('Invalid token signature.');
    }

    if (isset($payload['exp']) && $payload['exp'] < time()) {
        throw new Exception('Token expired.');
    }

    return $payload;
}
