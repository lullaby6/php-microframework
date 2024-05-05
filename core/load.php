<?php

function load_css() {
    $css_files = scandir(CSS_PATH);

    foreach ($css_files as $css_file) {

        if (str_ends_with($css_file, ".css")) {

            ob_start();

            echo "<style>";
            include_once CSS_PATH . "/" . $css_file;
            echo "</style>";

            $css_content = ob_get_clean();
            echo minify_css($css_content);
        }
    }
}

function load_js() {
    $js_files = scandir(JS_PATH);

    foreach ($js_files as $js_file) {

        if (str_ends_with($js_file, ".js")) {
            ob_start();

            echo "<script>";
            include_once JS_PATH . "/" . $js_file;
            echo "</script>";

            $js_content = ob_get_clean();
            echo minify_js($js_content);
        }
    }
}