<?php

function middleware(string $middleware, array $data = []) {
    extract($data);
    require MIDDLEWARES_PATH . "{$middleware}.php";
}