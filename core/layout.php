<?php

$_LAYOUT = null;
$_LAYOUT_DATA = null;

function render_layout(string $layout, array $data = []): void {
    global $_LAYOUT, $_LAYOUT_DATA;

    extract($GLOBALS);

    $_LAYOUT = $layout;

    if (!empty($data)) $_LAYOUT_DATA = $data;
}