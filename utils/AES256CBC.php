<?php

function AES256CBC_encryptString($string, $key) {
    $cipher = "aes-256-cbc";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    $encrypted = openssl_encrypt($string, $cipher, $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function AES256CBC_decryptString($encryptedString, $key) {
    $cipher = "aes-256-cbc";
    list($encrypted_data, $iv) = explode('::', base64_decode($encryptedString), 2);
    return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);
}
