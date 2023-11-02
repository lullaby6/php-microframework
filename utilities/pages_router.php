<?php

function pages_router($dir_path, $request_prefix = "") {
    if (!empty($dir_path)) {
        $REQUEST_URL = str_replace($request_prefix, "", $_SERVER['REQUEST_URI']);

        if (str_contains($REQUEST_URL, '?')) $REQUEST_URL = explode('?', $REQUEST_URL)[0];

        $error_path = $dir_path . "/404.php";

        $file_types = ["php", "html"];
        $file_path;

        foreach ($file_types as $file_type) {
            $file_path = $dir_path . $REQUEST_URL . "/index." . $file_type;
            if (file_exists($file_path)) break;
        }

        if (!file_exists($file_path) && file_exists($error_path)) $file_path = $error_path;

        include_once $file_path;
    }
}

?>