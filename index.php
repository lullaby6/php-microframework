<?php

include_once "./config.php";
include_once "./utilities/data.php";
include_once "./utilities/env.php";
include_once "./utilities/router.php";
router("./pages");

include_once "./utilities/database.php";

// $db = new Database([
//     'driver' => 'mysql',
//     'database' => 'gym',
//     'host' => 'localhost',
//     'username' => 'root',
//     'password' => '',
// ]);

// $db->create('staff', [
//     'first_name' => 'Padre',
//     'last_name' => 'Madre'
// ]);

// $staff = $db->select('staff', [
//     'columns' => 'id, first_name, last_name',
// ]);

// foreach ($staff as $row) {
//     echo $row['id'] . " " . $row['first_name'] . " " . $row['last_name'] . "<br>";
// }

?>