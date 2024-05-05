<?php

function render_route($_FILE_PATH) {
    global $_LAYOUT, $_LAYOUT_DATA;

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
}

// Routes

$_LAYOUT = null;
$_LAYOUT_DATA = null;

$_LOWER_METHOD = strtolower($_METHOD);

if ($_LOWER_METHOD === "get") content_type_html();
status_code(200);

$_FILE_NAMES = ["{$_LOWER_METHOD}.php", "/{$_LOWER_METHOD}.php"];

foreach ($_FILE_NAMES as $_FILE_NAME) {
    $_FILE_PATH = ROUTES_PATH . $_PATH . $_FILE_NAME;

    if (file_exists($_FILE_PATH) && is_file($_FILE_PATH)) {
        return render_route($_FILE_PATH);
    }
}

// Path Value

// $path_parts = explode("/", $_PATH);
// $path_value = $path_parts[count($path_parts)-1];

// unset($path_parts[count($path_parts)-1]);

// $base_path = ROUTES_PATH . implode("/", $path_parts);

// if (file_exists($base_path) && is_dir($base_path)) {
//     $base_path_files = scandir( $base_path);

//     foreach ($base_path_files as $base_path_file) {
//         if (str_starts_with($base_path_file, "[") && str_ends_with($base_path_file, "]")) {
//             $_PATH_VALUE = $path_value;

//             foreach ($file_paths as $file_path) {
//                 $path = $base_path . "/" . $base_path_file . "/"  . $file_path;

//                 if (file_exists($path) && is_file($path)) {
//                     content_type_html();

//                     include_once $path;

//                     // $content_type = get_response_header('Content-Type');

//                     // if ($content_type && str_contains($content_type, 'text/html') || str_ends_with($path, '.html')) include_once CORE_PATH . "global_css.php";

//                     return;
//                 }
//             }
//         }
//     }
// }

// Public

$_PUBLIC_FILE_PATH = PUBLIC_PATH . $_PATH;

if (file_exists($_PUBLIC_FILE_PATH) && is_file($_PUBLIC_FILE_PATH)) {
    $mime_type = mime_content_type($_PUBLIC_FILE_PATH);

    if (verify_mime_type($mime_type)) {
        header("Content-Type: $mime_type");

        readfile($_PUBLIC_FILE_PATH);

        return;
    }
}

// Not found

if (file_exists(NOT_FOUND_FILE_PATH)) {
    status_code(404);

    return render_route(NOT_FOUND_FILE_PATH);
}