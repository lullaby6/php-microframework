<?php

function middleware(string $middleware, array $data = []) {
    extract($GLOBALS);

    extract($data);

    require MIDDLEWARES_PATH . "{$middleware}.php";
}