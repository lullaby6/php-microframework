<?php

function get_all_folder_paths($folder_path) {
    $folder_paths = [];

    foreach (scandir($folder_path) as $file_path) {
        if ($file_path == "." || $file_path == "..") continue;

        $file_full_path = $folder_path . "/" . $file_path;
        if (is_dir($file_full_path)) {
            $folder_paths[] = $file_full_path;

            $sub_folder_paths = get_all_folder_paths($file_full_path);

            $folder_paths = array_merge($folder_paths, $sub_folder_paths);
        }
    }

    return $folder_paths;
}