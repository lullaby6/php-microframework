<?php

$_LAYOUT = null;

$_LAYOUT_DATA = null;

ob_start();

include_once $_FILE_PATH;

$_CURRENT_CONTENT_TYPE = get_response_header('Content-Type');

if (isset($_CURRENT_CONTENT_TYPE) && str_contains($_CURRENT_CONTENT_TYPE, 'text/html')) {
    $_CONTENT = ob_get_clean();

    if (isset($_LAYOUT)) {
        ob_start();

        if (isset($_LAYOUT_DATA)) extract($_LAYOUT_DATA);

        include_once LAYOUTS_PATH . $_LAYOUT . ".php";
        
        $_CONTENT = ob_get_clean();
    }

    echo minify_html($_CONTENT);

    load_css();

    load_js();
}

if (ob_get_level() > 0) ob_end_flush();