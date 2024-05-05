<?php

function minify_html($html) {
    $search = array(
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
    );

    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );

    $html = preg_replace($search, $replace, $html);

    return $html;
}

function minify_css($css) {
    // Remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    // Remove spaces before and after selectors, braces, and colons
    $css = preg_replace('/\s*([{}|:;,])\s+/', '$1', $css);
    // Remove remaining spaces and line breaks
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '',$css);
    
    return $css;
}

function minify_js($javascript){
    return preg_replace(array("/\s+\n/", "/\n\s+/", "/ +/"), array("\n", "\n ", " "), $javascript);
}