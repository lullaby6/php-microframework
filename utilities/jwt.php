<?php

function generate_jwt($payload = array(), $secret_key = "secret_key", $exp = 3600) {
    $header = [
        'alg' => 'HS256',
        'typ' => 'JWT'
    ];

    $defaultPayload = [
        'exp' => time() + $exp
    ];
    $payload = array_merge($defaultPayload, $payload);

    $base64Header = base64_encode(json_encode($header));
    $base64Payload = base64_encode(json_encode($payload));

    $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $secret_key);

    $jwt = "$base64Header.$base64Payload.$signature";

    return $jwt;
}


function verify_jwt($jwt, $secret_key = "secret_key"){
    $tokenParts = explode('.', $jwt);
    $header = base64_decode($tokenParts[0]);
    $payload = base64_decode($tokenParts[1]);
}
