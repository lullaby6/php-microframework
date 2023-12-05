<?php

include_once "./config.php";
include_once "./utilities/data.php";
include_once "./utilities/env.php";
include_once "./utilities/router.php";
router("./pages");

include_once "./utilities/database.php";

$db = new Database([
    'driver' => 'mysql',
    'database' => 'gym',
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
]);

$db->create([
    'table_name' => 'staff',
    'data' => [
        'first_name' => 'Amber',
        'last_name' => 'Jesus'
    ]
]);

$staff = $db->select([
    'table_name' => 'staff',
    'columns' => '*',
    'conditions' => [
        ['id', '<=', 5]
    ],
    'order' => [
        'id DESC',
        'first_name ASC'
    ],
    'group' => [
        'first_name'
    ],
    'limit' => 5
]);

foreach ($staff as $row) {
    echo $row['id'] . " " . $row['first_name'] . " " . $row['last_name'] . "<br>";
}

?>