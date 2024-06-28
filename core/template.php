<?php

function render_template(string $template, array $data = []) {
    extract($GLOBALS);

    extract($data);

    require TEMPLATES_PATH . "{$template}.php";
}

function render_array_template(string $templates, array $array) {
    foreach($array as $data) {
        render_template("todo", $data);
    }
}

function get_template(string $template, array $data = []) {
    ob_start();

    extract($GLOBALS);

    extract($data);

    require TEMPLATES_PATH . "{$template}.php";

    $content = ob_get_clean();

    return $content;
}