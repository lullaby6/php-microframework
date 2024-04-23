<?php

function render($template, $data = []) {
    extract($data);
    include_once $template;
}