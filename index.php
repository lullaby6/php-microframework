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
    'columns' => 'id, first_name, last_name',
    'where' => [
        ['id', '<=', 5],
        ['first_name', '=', 'Luciano'],
    ],
    'order_by' => 'id DESC, first_name ASC',
    'group_by' => 'first_name',
    'join' => [
        [
            'table_name' => 'users',
            'type' => 'LEFT',
            'on' => 'staff.user_id = users.id'
        ],
        [
            'table_name' => 'products',
            'columns' => 'id, name',
            'on' => 'staff.user_id = products.user_id'
        ]
    ],
    'limit' => 5
]);

foreach ($staff as $row) {
    echo $row['id'] . " " . $row['first_name'] . " " . $row['last_name'] . "<br>";
}

?>