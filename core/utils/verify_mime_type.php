<?php

function verify_mime_type(string $mime_type): bool {
    $blocked_file_extensions = ['.php'];

    foreach ($blocked_file_extensions as $file_extension) {
        if (str_ends_with($mime_type, $file_extension)) {
            return false;
        }
    }

    return true;
}