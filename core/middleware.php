<?php

function middleware(string $middleware, array $data = []) {
    extract($GLOBALS);

    extract($data);

    require PATHS['middlewares'] . "{$middleware}.php";
}