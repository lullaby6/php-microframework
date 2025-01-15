<?php

function get_css($file_name, $minify = true) {
    if (!str_ends_with($file_name, ".css")) $file_name .= ".css";

    $file_path = PATHS['css'] . $file_name;
    if (!file_exists($file_path)) return;

    $content = file_get_contents($file_path);

    if ($minify) $content = minify_css($content);

    return "<style>$content</style>";
}

function get_js($file_name, $minify = true) {
    if (!str_ends_with($file_name, ".js")) $file_name .= ".js";

    $file_path = PATHS['js'] . $file_name;
    if (!file_exists($file_path)) return;

    $content = file_get_contents($file_path);

    if ($minify) $content = minify_js($content);

    return "<script>$content</script>";
}

function get_all_css() {
    if (!file_exists(PATHS['css'])) return;

    $output = "";

    $files = scandir(PATHS['css']);

    foreach ($files as $file) {
        if (str_ends_with($file, ".css")) {
            $output .= get_css($file);
        }
    }

    return $output;
}

function get_all_js() {
    if (!file_exists(PATHS['js'])) return;

    $output = "";

    $files = scandir(PATHS['js']);

    foreach ($files as $file) {
        if (str_ends_with($file, ".js")) {
            $output .= get_js($file);
        }
    }

    return $output;
}