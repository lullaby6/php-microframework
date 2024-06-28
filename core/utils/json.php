<?php

function json(array $data) {
    header('Content-Type: application/json');
    echo json_encode($data);
}