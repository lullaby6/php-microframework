<?php

function middleware($middleware, $data = []) {
    extract($data);
    require MIDDLEWARES_PATH . "{$middleware}.php";
}