<?php

function get_all_sub_dirs($path) {
    $output = [];

    $dirs = scandir($path);

    foreach ($dirs as $dir) {
        if ($dir == "." || $dir == "..") continue;

        $full_path = $path . "/" . $dir;

        if (is_dir($full_path)) {
            $output[] = $full_path;

            $sub_dirs = get_all_sub_dirs($full_path);

            if (isset($sub_dirs) && !empty($sub_dirs) && count($sub_dirs) > 0) {
                $output = array_merge($output, $sub_dirs);
            }
        }
    }

    return $output;
}
