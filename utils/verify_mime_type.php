<?php

function verify_mime_type($mime_type) {
    $blocked_file_extensions = ['.php'];

    foreach ($blocked_file_extensions as $file_extension) {
        if (str_ends_with($mime_type, $file_extension)) {
            return false;
        }
    }

    return true;
}