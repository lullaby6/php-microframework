<?php

function render(string $file_path) {
    global $_LAYOUT, $_LAYOUT_DATA;

    extract($GLOBALS);

    $_LAYOUT = null;

    $_LAYOUT_DATA = null;

    ob_start();

    include_once $file_path;

    $_CONTENT = ob_get_clean();

    $content_type = get_response_header('Content-Type');

    if (isset($content_type) && str_contains($content_type, 'text/html')) {

        ob_start();

        echo minify_html($_CONTENT);

        echo get_css();

        echo get_js();

        $_CONTENT = ob_get_clean();

        if (isset($_LAYOUT)) {
            $layout_path = LAYOUTS_PATH . $_LAYOUT . ".php";

            if (file_exists($layout_path)) {
                ob_start();

                if (isset($_LAYOUT_DATA)) extract($_LAYOUT_DATA);

                include_once $layout_path;
            }
        }

        ob_end_flush();
    } else {
        echo $_CONTENT;
    }

    if (ob_get_level() > 0) ob_end_flush();
}