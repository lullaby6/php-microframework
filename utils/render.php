<?php

function render($template, $data = []) {
    extract($data);
    require $template;
}