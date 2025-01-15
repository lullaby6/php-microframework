<?php

function template(string|array $template, array $data = [], array $extra_data = []) {
    extract($GLOBALS);
    extract($data);
    extract($extra_data);

    if (!is_array($template)) {
        require PATHS['templates'] . "{$template}.php";
        return;
    }

    foreach($template as $t) {
        $template_path = PATHS['templates'] . "{$t}.php";

        if (file_exists($template_path)) {
            require $template_path;
            return;
        }
    }
}

function template_array(string $template, array $array = [], array $extra_data = []) {
    foreach($array as $data) {
        template($template, $data, $extra_data);
    }
}

function get_template(string|array $template, array $data = [], array $extra_data = []): string {
    if (!file_exists(PATHS['templates'] . "{$template}.php")) {
        throw new Exception("Template {$template} does not exist");
    }

    extract($GLOBALS);
    extract($data);
    extract($extra_data);

    ob_start();

    if (!is_array($template)) {
        require PATHS['templates'] . "{$template}.php";
    } else {
        foreach($template as $t) {
            $template_path = PATHS['templates'] . "{$t}.php";

            if (file_exists($template_path)) {
                require $template_path;
            }
        }
    }

    $content = ob_get_clean();

    return $content;
}

function get_template_array(string $templates, array $array = [], array $extra_data = []): string {
    $output = "";

    foreach ($array as $data) {
        $output .= get_template($templates, $data, $extra_data);
    }

    return $output;
}

function template_render(string $template, array $data = []) {
    extract($GLOBALS);

    extract($data);

    eval('?>' . $template);
}

function template_render_array(string $template, array $array) {
    foreach($array as $data) {
        template_render($template, $data);
    }
}