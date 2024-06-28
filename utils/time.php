<?php

function time_in_seconds(string $time): int {
    return strtotime($time) - time();
}