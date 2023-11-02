<?php

function router($dir_path, $request_prefix = "") {
    if (!empty($dir_path)) {
        $REQUEST_URL = str_replace($request_prefix, "", $_SERVER['REQUEST_URI']);

        if (str_contains($REQUEST_URL, '?')) $REQUEST_URL = explode('?', $REQUEST_URL)[0];

        $extensions = [".php", ".html", "/index.php", "/index.html", "index.php", "index.html"];

        $error_path = $dir_path . "/404.php";

        $file_path = $dir_path . $REQUEST_URL;

        if (!file_exists($file_path) || !is_file($file_path)) {
            foreach ($extensions as $extension) {
                $check_path = $dir_path . $REQUEST_URL . $extension;
                if(file_exists($check_path)) {
                    $file_path = $check_path;
                    break;
                }
            }
        }

        if (!file_exists($file_path) && file_exists($error_path)) $file_path = $error_path;

        include_once $file_path;
    }
}

?>