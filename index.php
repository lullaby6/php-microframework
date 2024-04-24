<?php

include_once __DIR__ . "/consts.php";
include_once ROOT_PATH . "/autoload.php";
include_once UTILS_PATH . "/schema.php";

// file_router(ROUTES_PATH);

// $int_schema = Schema::int()->max(5)->to_max(4);

// $valid = $int_schema->parse(5);

// var_dump($valid);

$user_schema = Schema::array([
    "first_name" => Schema::string()->min_length(3, "El nombre es demasiado corto")->required(),
    "last_name" => Schema::string()->min_length(3),
    "email" => Schema::string()->email("El e-mail no es valido")->required("El e-mail es requerido"),
]);

$valid = $user_schema->safe_parse([
    "first_name" => "John",
    "last_name" => "Doe",
    "email" => "john@doe.com",
]);

var_dump($valid);