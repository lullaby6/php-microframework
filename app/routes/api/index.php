<?php

content_type_json();

if ($_CONTEXT['METHOD'] == 'GET') {
    status_code(200);

    return json([
        "ping" => "pong"
    ]);
}

status_code(404);
return json(["error" => "Not found"]);