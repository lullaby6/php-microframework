<?php

function router($dir_path, $only_index_files = true, $default_page = "404.php") {
    if (!empty($dir_path)) {
        $REQUEST_URL = $_SERVER['REQUEST_URI'];

        if (str_contains($REQUEST_URL, '?')) $REQUEST_URL = explode('?', $REQUEST_URL)[0];
        if (str_contains($REQUEST_URL, '.')) {
            $request_url_parts = explode('.', $REQUEST_URL);
            print_r($request_url_parts);
            $request_url_file_type = $request_url_parts[count($request_url_parts) - 1];
            echo "<br>$request_url_file_type<br>";
        }

        $default_page = $dir_path . "/" . $default_page;

        $file_extensions = ["index.php", "index.html", "/index.php", "/index.html"];
        if(!$only_index_files) $file_extensions = array_merge($file_extensions, ["", "/", ".php", ".html"]);

        $file_path;

        foreach ($file_extensions as $file_extension) {
            $file_path = $dir_path . $REQUEST_URL . $file_extension;
            if (file_exists($file_path) && is_file($file_path)) break;
        }

        if (!file_exists($file_path) && file_exists($default_page)) $file_path = $default_page;

        include_once $file_path;
    }else{
        trigger_error("dir path is required", E_USER_WARNING);
    }
}

?>