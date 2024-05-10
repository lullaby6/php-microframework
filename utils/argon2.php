<?php

function argon2_password_hash(string $password, string $salt = '', string $pepper = ''): string {
    if (empty($password)) {
        throw new Exception("Password cannot be empty");
    }

    $options = array_merge([
        'memory_cost' => 1 << 17, // 128 MB
        'time_cost' => 4,
        'threads' => 2,
    ]);

    $to_hash = $password;

    if (!empty($salt)) {
        $to_hash .= ".$salt";
    }

    if (!empty($pepper)) {
        $to_hash .= ".$pepper";
    }

    $hash = password_hash($to_hash, PASSWORD_ARGON2I, $options);

    if ($hash === false) {
        throw new Exception("Failed to hash password");
    }

    return $hash;
}

function argon2_password_verify(string $hash, string $password, string $salt = '', string $pepper = ''): bool {
    if (empty($hash)) {
        throw new Exception("Hash cannot be empty");
    }

    if (empty($password)) {
        throw new Exception("Password cannot be empty");
    }

    $to_hash = $password;

    if (!empty($salt)) {
        $to_hash .= ".$salt";
    }

    if (!empty($pepper)) {
        $to_hash .= ".$pepper";
    }

    $hash_to_verify = argon2_password_hash($to_hash, $salt, $pepper);

    return hash_equals($hash_to_verify, $hash);
}