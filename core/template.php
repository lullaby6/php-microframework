<?php

function render_template($template, $data = []) {
    extract($data);
    require TEMPLATES_PATH . "{$template}.php";
}

function render_array_template($templates, $array) {
    foreach($array as $data) {
        render_template("todo", $data);
    }
}