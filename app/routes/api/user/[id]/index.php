<?php

content_type_json();

if ($_METHOD == 'GET') {
    status_code(200);

    return json([
        "user" => $_PATH_VALUE
    ]);
}

status_code(404);
return json(["error" => "Not found"]);