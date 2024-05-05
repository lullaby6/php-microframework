<?php

// Routes

$_LOWER_METHOD = strtolower($_METHOD);

if ($_LOWER_METHOD === "get") content_type_html();
status_code(200);

$_FILE_NAMES = ["{$_LOWER_METHOD}.php", "/{$_LOWER_METHOD}.php"];

foreach ($_FILE_NAMES as $_FILE_NAME) {
    $_FILE_PATH = ROUTES_PATH . $_PATH . $_FILE_NAME;

    if (file_exists($_FILE_PATH) && is_file($_FILE_PATH)) {
        include_once CORE_PATH . "render.php";

        return;
    }
}

// Path Value

$_ROUTES_FOLDERS = get_all_folder_paths(ROUTES_PATH);

$_PATH_VALUE_ROUTES = array_filter($_ROUTES_FOLDERS, function($path) {
    return str_contains($path, "[") && str_contains($path, "]");
});

foreach ($_PATH_VALUE_ROUTES as $_PATH_VALUE_ROUTE) {
    $_PATH_VALUE_ROUTE = str_replace(ROUTES_PATH, "", $_PATH_VALUE_ROUTE);
    $_PATH_VALUE_ROUTE_WITH_VALUES = $_PATH_VALUE_ROUTE;

    $_PATH_VALUE_ROUTE_PARTS = explode("/", $_PATH_VALUE_ROUTE);

    $_PATH_VALUE_COUNT = substr_count($_PATH_VALUE_ROUTE, "[");

    $_PATH_WITH_PATH_VALUES = $_PATH;

    for ($i = 0; $i < $_PATH_VALUE_COUNT; $i++) {
        $_PATH_VALUE_ROUTE_BASE = explode("[", $_PATH_VALUE_ROUTE_WITH_VALUES)[0];

        $_PATH_BASE = substr($_PATH, 0, strlen($_PATH_VALUE_ROUTE_BASE));

        if ($_PATH_BASE === $_PATH_VALUE_ROUTE_BASE) {
            $_PATH_VALUE_NAME = explode("]", explode("[", $_PATH_VALUE_ROUTE_WITH_VALUES)[1])[0];
            $_PATH_VALUE_FULL_NAME = "[{$_PATH_VALUE_NAME}]";

            $_PATH_WITH_PATH_VALUES_PARTS = explode("/", $_PATH_WITH_PATH_VALUES);

            for ($i2 = 0; $i2 < count($_PATH_VALUE_ROUTE_PARTS); $i2++) {
                if ($_PATH_VALUE_ROUTE_PARTS[$i2] === $_PATH_VALUE_FULL_NAME) {                    
                    $_PATH_VALUE[$_PATH_VALUE_NAME] = $_PATH_WITH_PATH_VALUES_PARTS[$i2];

                    $_PATH_VALUE_ROUTE_PARTS[$i2] = $_PATH_WITH_PATH_VALUES_PARTS[$i2];
    
                    $_PATH_WITH_PATH_VALUES_PARTS[$i2] = $_PATH_VALUE_FULL_NAME;
                }

                $_PATH_WITH_PATH_VALUES = implode("/", $_PATH_WITH_PATH_VALUES_PARTS);

                $_PATH_VALUE_ROUTE_WITH_VALUES = implode("/", $_PATH_VALUE_ROUTE_PARTS);
            }
        }
    }

    if ($_PATH_WITH_PATH_VALUES == $_PATH_VALUE_ROUTE) {
        foreach ($_FILE_NAMES as $_FILE_NAME) {
            $_FILE_PATH = ROUTES_PATH . $_PATH_WITH_PATH_VALUES . $_FILE_NAME;
        
            if (file_exists($_FILE_PATH) && is_file($_FILE_PATH)) {
                include_once CORE_PATH . "render.php";

                return;
            }
        }
    }
}

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

    $_FILE_PATH = NOT_FOUND_FILE_PATH;

    include_once CORE_PATH . "render.php";

    return;
}