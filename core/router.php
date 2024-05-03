<?php

// Routes

$index_file_paths = ["index.php", "index.html", "/index.php", "/index.html"];

foreach ($index_file_paths as $index_file_path) {
    $path = ROUTES_PATH . $_CONTEXT["PATH"] . $index_file_path;

    if (file_exists($path) && is_file($path)) {
        content_type_html();

        include_once $path;

        $content_type = get_response_header('Content-Type');

        if ($content_type && str_contains($content_type, 'text/html') || str_ends_with($path, '.html')) include_once CORE_PATH . "global_css.php";

        return;
    }
}

// Public

$public_file_path = PUBLIC_PATH . $_CONTEXT["PATH"];

if (file_exists($public_file_path) && is_file($public_file_path)) {
    $mime_type = mime_content_type($public_file_path);

    if (verify_mime_type($mime_type)) {
        header("Content-Type: $mime_type");
        readfile($public_file_path);

        if (str_ends_with($public_file_path, '.html')) include_once CORE_PATH . "global_css.php";

        return;
    }
}

// Not found

$not_found_path = ROUTES_PATH . "/404.php";

if (file_exists($not_found_path)) {
    status_code(404);

    include_once $not_found_path;

    return;
}

