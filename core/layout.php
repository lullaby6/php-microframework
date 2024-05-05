<?php

function render_layout($layout, $data = []) {
    global $_LAYOUT, $_LAYOUT_DATA;

    $_LAYOUT = $layout;
    
    if (!empty($data)) $_LAYOUT_DATA = $data;
}