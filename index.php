<?php

include_once __DIR__ . "/config.php";
include_once BASE_PATH . "/utilities/data.php";
include_once BASE_PATH . "/utilities/env.php";
include_once BASE_PATH . "/utilities/router.php";
router(BASE_PATH . "/routes", "framework/");

include_once BASE_PATH . "/utilities/database.php";

$db = new Database('mysql', 'localhost', 'test', 'root', '');

// $users_table = [
//     'name' => 'users',
//     'columns' => [
//         'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
//         'name' => 'VARCHAR(50)',
//         'email' => 'VARCHAR(100)'
//     ]
// ];
// $db->delete_table($users_table['name']);
// $result = $db->create_table($users_table['name'], $users_table['columns']);
// if ($result) {
//     echo $users_table['name'] . " created successfully.";
// }

// $user = ['name' => 'Lullaby', 'email' => 'lucianobrumer5@gmail.com'];
// $new_user_id = $db->create('users', $user);

// if ($new_user_id) {
//     echo "new user inserted with ID: $new_user_id";
// }

// $conditions = ['id' => 1];
// $users = $db->select('users', $conditions);

// foreach ($users as $user) {
//     echo "name: {$user['name']}, email: {$user['email']}<br>";
// }

$data = ['email' => 'lucianobrumer5@gmail.com2'];
$conditions = ['id' => 1];
$user_updated = $db->update('users', $data, $conditions);

if ($user_updated) {
    echo "user updated successfully.";
}

// $user_deleted = $db->delete('users', ['id' => 2]);

// if ($user_deleted) {
//     echo "user deleted successfully.";
// }

?>