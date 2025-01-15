<?php

require_once PATHS['utils'] . 'database.php';

$_DB = null;

try {
    $_DB = new Database([
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'db',
        'username' => 'root',
        'password' => ''
    ]);
} catch (\Throwable $th) {
    exit('Database connection failed: ' . $th->getMessage());
}

if (is_null($_DB)) {
    exit('Database connection failed');
}