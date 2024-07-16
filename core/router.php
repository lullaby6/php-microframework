<?php

function router_generate_regex_pattern($url) {
    $pattern = preg_replace_callback('/\[(\w+)\]/', function($matches) {
        return "(?P<{$matches[1]}>\w+)";
    }, $url);

    return "/^" . str_replace("/", "\/", $pattern) . "$/";
}

function router() {
    global $_PATH_VALUE;

    extract($GLOBALS);

    $method = strtolower($_METHOD);

    if ($method === "get") header('Content-Type: text/html');
    http_response_code(200);

    // Route
    $file_names = ["{$method}.php", "/{$method}.php"];

    foreach ($file_names as $file_name) {
        $file_path = ROUTES_PATH . $_PATH . $file_name;

        if (file_exists($file_path) && is_file($file_path)) {
            return render($file_path);
        }
    }

    // Path Value

    $dirs = array_filter(get_all_sub_dirs(ROUTES_PATH), function($path) {
        return preg_match('/\[[^\]]+\]/', $path);
    });

    $dirs = array_map(function($path) {
        return str_replace(ROUTES_PATH, "", $path);
    }, $dirs);

    $path_deep = substr_count($_PATH, "/");

    foreach ($dirs as $dir) {
        if ($path_deep != substr_count($dir, "/")) {
            continue;
        }

        $pattern = router_generate_regex_pattern($dir);

        if (preg_match($pattern, $_PATH, $matches)) {
            foreach ($file_names as $file_name) {
                $file_path = ROUTES_PATH . $dir . $file_name;

                if (file_exists($file_path) && is_file($file_path)) {
                    $_PATH_VALUE = $matches;

                    return render($file_path);
                }
            }
        }
    }

    // Public

    $public_files_paths = [PUBLIC_PATH . $_PATH, PUBLIC_PATH . $_PATH . "/index.html"];

    foreach ($public_files_paths as $public_file_path) {
        if (file_exists($public_file_path) && is_file($public_file_path)) {
            $mime_type = get_mime_content_type($public_file_path);

            if (verify_mime_type($mime_type)) {
                header("Content-Type: $mime_type");

                return readfile($public_file_path);
            }
        }
    }

    // Not found
    $not_found_file_path = ROUTES_PATH . "/404.php";

    if (file_exists($not_found_file_path)) {
        http_response_code(404);

        return render($not_found_file_path);
    }
}